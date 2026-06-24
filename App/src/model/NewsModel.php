<?php
namespace App\Model;

use App\Utils\Database;
use App\Utils\DatabaseException;
use Exception;
use PDO;
use PDOException;

class NewsModel
{
    private $Conn;

    public function __construct()
    {
        $this->Conn = (new Database())->connect();
    }

    public function GetAllNews(array $query): array
    {
        try {
            $where = [];
            $params = [];

            if (!empty($query['search'])) {
                $where[] = '(news_title LIKE :q OR news_content LIKE :q)';
                $params[':q'] = '%' . $query['search'] . '%';
            }

            if (isset($query['is_published'])) {
                $where[] = 'is_published = :is_published';
                $params[':is_published'] = $query['is_published'];
            }

            $whereSql = !empty($where) ? ' WHERE ' . implode(' AND ', $where) : '';

            $sqlCount = "SELECT COUNT(*) AS total FROM news $whereSql";
            $stmtCount = $this->Conn->prepare($sqlCount);
            $stmtCount->execute($params);
            $total = (int) $stmtCount->fetch(PDO::FETCH_ASSOC)['total'];

            $sql = "SELECT news_id, news_title, news_excerpt, news_image, is_published, created_at, updated_at FROM news $whereSql ORDER BY created_at DESC";

            if (isset($query['page']) && isset($query['limit'])) {
                $limit = (int) $query['limit'];
                $offset = ((int) $query['page'] - 1) * $limit;
                $sql .= ' LIMIT :limit OFFSET :offset';
                $params[':limit'] = $limit;
                $params[':offset'] = $offset;
            }

            $stmt = $this->Conn->prepare($sql);
            foreach ($params as $key => &$val) {
                $stmt->bindParam($key, $val, is_int($val) ? PDO::PARAM_INT : PDO::PARAM_STR);
            }
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return ['data' => $rows, 'total' => $total];
        } catch (PDOException $e) {
            throw new DatabaseException($e->getMessage(), (int) $e->getCode());
        }
    }

    public function GetNewsById(int $id): ?array
    {
        try {
            $stmt = $this->Conn->prepare("SELECT * FROM news WHERE news_id = :id");
            $stmt->execute([':id' => $id]);
            $news = $stmt->fetch(PDO::FETCH_ASSOC);
            return $news ?: null;
        } catch (PDOException $e) {
            throw new DatabaseException($e->getMessage(), (int) $e->getCode());
        }
    }

    public function CreateNews(array $data, ?array $file): int
    {
        try {
            $imageName = $this->handleImageUpload($file);

            $sql = "INSERT INTO news (news_title, news_content, news_excerpt, news_image, is_published, created_at, updated_at) 
                    VALUES (:title, :content, :excerpt, :image, :is_published, NOW(), NOW())";
            $stmt = $this->Conn->prepare($sql);
            $stmt->execute([
                ':title' => $data['news_title'],
                ':content' => $data['news_content'],
                ':excerpt' => $data['news_excerpt'] ?? null,
                ':image' => $imageName,
                ':is_published' => $data['is_published'] ?? 0
            ]);
            return (int) $this->Conn->lastInsertId();
        } catch (PDOException $e) {
            throw new DatabaseException($e->getMessage(), (int) $e->getCode());
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function Update(int $id, array $data, ?array $file): bool
    {
        try {
            $imageName = $this->handleImageUpload($file, $id);
            if ($imageName) {
                $data['news_image'] = $imageName;
            }

            $data['updated_at'] = date('Y-m-d H:i:s');

            $setClauses = [];
            foreach ($data as $key => $value) {
                $setClauses[] = "`{$key}` = :{$key}";
            }
            $setClauseString = implode(', ', $setClauses);

            $sql = "UPDATE news SET {$setClauseString} WHERE news_id = :news_id";
            $stmt = $this->Conn->prepare($sql);

            $data['news_id'] = $id;
            $stmt->execute($data);

            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            throw new DatabaseException($e->getMessage(), (int) $e->getCode());
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function Delete(int $id): bool
    {
        try {
            // First, get the image filename to delete it from the server
            $stmt = $this->Conn->prepare("SELECT news_image FROM news WHERE news_id = :id");
            $stmt->execute([':id' => $id]);
            $image = $stmt->fetchColumn();

            if ($image) {
                $path = dirname(__DIR__, 2) . '/public/assets/images/news/' . $image;
                if (file_exists($path)) {
                    unlink($path);
                }
            }

            $stmt = $this->Conn->prepare("DELETE FROM news WHERE news_id = :id");
            $stmt->execute([':id' => $id]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            throw new DatabaseException($e->getMessage(), (int) $e->getCode());
        }
    }

    private function handleImageUpload(?array $file, ?int $existingId = null): ?string
    {
        $uploadDir = dirname(__DIR__, 2) . '/public/assets/images/news/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        if ($file && $file['error'] === UPLOAD_ERR_OK) {
            // If updating, delete the old image
            if ($existingId) {
                $stmt = $this->Conn->prepare("SELECT news_image FROM news WHERE news_id = :id");
                $stmt->execute([':id' => $existingId]);
                $oldImage = $stmt->fetchColumn();
                if ($oldImage && file_exists($uploadDir . $oldImage)) {
                    unlink($uploadDir . $oldImage);
                }
            }

            $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

            if (!in_array($fileExtension, $allowedExtensions)) {
                throw new Exception('ประเภทไฟล์รูปภาพไม่รองรับ', 400);
            }

            $imageName = uniqid('news_') . '.' . $fileExtension;
            $destPath = $uploadDir . $imageName;

            if (!move_uploaded_file($file['tmp_name'], $destPath)) {
                throw new Exception('ไม่สามารถบันทึกไฟล์รูปภาพได้', 500);
            }

            return $imageName;
        }

        return null;
    }
}