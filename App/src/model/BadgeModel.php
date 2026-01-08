<?php
namespace App\Model;
use App\Utils\Database;
use App\Utils\DatabaseException;
use Exception;
use PDO;
use PDOException;

class BadgeModel
{
    private static $Database;
    private $Conn;
    
    public function __construct()
    {
        self::$Database = new Database();
        $this->Conn = self::$Database->connect();
    }

    public function GetAllBadges($query): array
    {
        try {
            $whereClauses = [];
            $params = [];

            if (!empty($query['badge_type'])) {
                $whereClauses[] = "badge_type = :badge_type";
                $params[':badge_type'] = $query['badge_type'];
            }

            if (!empty($query['search'])) {
                $whereClauses[] = "(badge_name LIKE :search OR badge_description LIKE :search)";
                $params[':search'] = "%" . $query['search'] . "%";
            }

            $whereSql = !empty($whereClauses) ? " WHERE " . implode(" AND ", $whereClauses) : "";

            $sql = "SELECT * FROM badge" . $whereSql . " ORDER BY badge_id DESC";

            $limit = isset($query['limit']) ? (int) $query['limit'] : null;
            $page = isset($query['page']) ? (int) $query['page'] : null;

            if ($limit && $page) {
                $offset = ($page - 1) * $limit;
                $sql .= " LIMIT :limit OFFSET :offset";
            }

            $stmt = $this->Conn->prepare($sql);

            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }

            if ($limit && $page) {
                $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
                $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            }

            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $countSql = "SELECT COUNT(*) as total FROM badge" . $whereSql;
            $countStmt = $this->Conn->prepare($countSql);
            foreach ($params as $key => $value) {
                $countStmt->bindValue($key, $value);
            }
            $countStmt->execute();
            $total = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];

            return [
                'data' => $data,
                'total' => (int) $total
            ];
        } catch (PDOException $e) {
            throw new DatabaseException($e->getMessage(), (int) $e->getCode());
        }
    }

    public function GetBadgeById($id): array
    {
        try {
            $sql = "SELECT * FROM badge WHERE badge_id = :id";
            $stmt = $this->Conn->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            $badge = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$badge) {
                throw new Exception("Badge not found", 404);
            }
            
            return $badge;
        } catch (PDOException $e) {
            throw new DatabaseException($e->getMessage(), (int) $e->getCode());
        }
    }

    public function CreateBadge($data): array
    {
        try {
            $sql = "INSERT INTO badge (
                        badge_name, 
                        badge_description, 
                        badge_condition, 
                        badge_image, 
                        badge_type
                    ) VALUES (
                        :badge_name, 
                        :badge_description, 
                        :badge_condition, 
                        :badge_image, 
                        :badge_type
                    )";
            
            $stmt = $this->Conn->prepare($sql);
            $stmt->bindValue(':badge_name', $data['badge_name']);
            $stmt->bindValue(':badge_description', $data['badge_description'] ?? null);
            $stmt->bindValue(':badge_condition', $data['badge_condition'] ?? null);
            $stmt->bindValue(':badge_image', $data['badge_image'] ?? null);
            $stmt->bindValue(':badge_type', $data['badge_type'] ?? null);
            
            $stmt->execute();
            $id = $this->Conn->lastInsertId();
            
            return $this->GetBadgeById($id);
        } catch (PDOException $e) {
            throw new DatabaseException($e->getMessage(), (int) $e->getCode());
        }
    }

    public function UpdateBadge($id, $data): array
    {
        try {
            $fields = [];
            $params = [':id' => $id];

            if (isset($data['badge_name'])) {
                $fields[] = "badge_name = :badge_name";
                $params[':badge_name'] = $data['badge_name'];
            }
            if (isset($data['badge_description'])) {
                $fields[] = "badge_description = :badge_description";
                $params[':badge_description'] = $data['badge_description'];
            }
            if (isset($data['badge_condition'])) {
                $fields[] = "badge_condition = :badge_condition";
                $params[':badge_condition'] = $data['badge_condition'];
            }
            if (isset($data['badge_image'])) {
                $fields[] = "badge_image = :badge_image";
                $params[':badge_image'] = $data['badge_image'];
            }
            if (isset($data['badge_type'])) {
                $fields[] = "badge_type = :badge_type";
                $params[':badge_type'] = $data['badge_type'];
            }

            if (empty($fields)) {
                throw new Exception("No fields to update", 400);
            }

            $sql = "UPDATE badge SET " . implode(", ", $fields) . " WHERE badge_id = :id";
            $stmt = $this->Conn->prepare($sql);
            
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            
            $stmt->execute();
            
            if ($stmt->rowCount() === 0) {
                throw new Exception("Badge not found or no changes made", 404);
            }
            
            return $this->GetBadgeById($id);
        } catch (PDOException $e) {
            throw new DatabaseException($e->getMessage(), (int) $e->getCode());
        }
    }

    public function DeleteBadge($data): int
    {
        try {
            $ids = is_array($data['badge_id']) ? $data['badge_id'] : [$data['badge_id']];
            
            $placeholders = implode(',', array_fill(0, count($ids), '?'));
            $sql = "DELETE FROM badge WHERE badge_id IN ($placeholders)";
            
            $stmt = $this->Conn->prepare($sql);
            $stmt->execute($ids);
            
            return $stmt->rowCount();
        } catch (PDOException $e) {
            throw new DatabaseException($e->getMessage(), (int) $e->getCode());
        }
    }
}
