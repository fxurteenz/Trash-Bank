<?php
namespace App\Model;
use App\Utils\Database;
use Exception;
use PDO;
use PDOException;

class FacultyModel
{
    private static $Database;
    private $Conn;

    public function __construct()
    {
        self::$Database = new Database();
        $this->Conn = self::$Database->connect();
    }

    public function GetAllFaculty($query): array
    {
        try {
            $whereClauses = [];
            $params = [];

            if (!empty($query['search'])) {
                $whereClauses[] = "faculty_name LIKE :search";
                $params[':search'] = "%" . $query['search'] . "%";
            }

            $whereSql = !empty($whereClauses) ? " WHERE " . implode(" AND ", $whereClauses) : "";

            $sql = "SELECT * FROM faculty {$whereSql}";
            $isPagination = isset($query['page']) && isset($query['limit']);

            if ($isPagination) {
                $page = (int) $query['page'];
                $limit = (int) $query['limit'];
                $offset = ($page - 1) * $limit;
                $sql .= " LIMIT :limit OFFSET :offset";
            }

            $stmt = $this->Conn->prepare($sql);

            foreach ($params as $key => $val) {
                $stmt->bindValue($key, $val);
            }

            if ($isPagination) {
                $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
                $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            }

            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($isPagination) {
                $sqlCount = "SELECT COUNT(*) AS all_faculty FROM faculty{$whereSql}";
                $stmtCount = $this->Conn->prepare($sqlCount);
                foreach ($params as $key => $val) {
                    $stmtCount->bindValue($key, $val);
                }
                $stmtCount->execute();
                $total = $stmtCount->fetch(PDO::FETCH_ASSOC)['all_faculty'];
            } else {
                $total = count($data);
            }

            return [$data, $total];
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage(), 500);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function GetFacultyById($id): array
    {
        try {

            $sql = "SELECT * FROM faculty WHERE faculty_id = :faculty_id";
            $stmt = $this->Conn->prepare($sql);
            $stmt->bindValue(':faculty_id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            return $data;
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage(), 500);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function CreateFaculty(array $data): int
    {
        try {
            if (!is_array($data)) {
                throw new Exception('Invalid data format', 400);
            }

            if (empty($data['faculty_name'])) {
                throw new Exception('faculty name not provided', 400);
            }

            $data["created_at"] = date('Y-m-d H:i:s');

            $setClauses = [];
            $updateData = [];
            foreach ($data as $column => $value) {
                if (!empty($value)) {
                    $setClauses[] = "`{$column}` = :{$column}";
                    $updateData[$column] = $value;
                }
            }
            $setClauseString = implode(', ', $setClauses);

            $sql =
                "INSERT INTO 
                    faculty
                SET
                    {$setClauseString}
                ";
            $stmt = $this->Conn->prepare($sql);
            $stmt->execute($updateData);

            $updated_row = $stmt->rowCount();
            return $updated_row;
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage(), 500);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function UpdateFaculty($fid, $data): mixed
    {
        try {
            if (empty($data) || !is_array($data) || empty($fid)) {
                throw new Exception('Bad Request =(', 400);
            }

            $data["updated_at"] = date('Y-m-d H:i:s');
            $setClauses = [];
            $updateData = [];
            foreach ($data as $column => $value) {
                if (!empty($value)) {
                    $setClauses[] = "`{$column}` = :{$column}";
                    $updateData[$column] = $value;
                }
            }
            $setClauseString = implode(', ', $setClauses);

            $sql =
                "UPDATE faculty
                SET 
                    {$setClauseString}
                WHERE
                    faculty_id = :faculty_id
                ";

            $stmt = $this->Conn->prepare($sql);
            $stmt->execute(array_merge($updateData, ['faculty_id' => $fid]));

            $result = $stmt->rowCount();
            return $result;
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage(), 500);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function DeleteFaculty(array $data): int
    {
        if (empty($data['faculty_ids'] ?? []) || !is_array($data['faculty_ids'])) {
            throw new Exception('Bad Request: faculty_ids is required and must be an array', 400);
        }

        $ids = array_filter($data['faculty_ids']);

        if (empty($ids)) {
            return 0;
        }

        try {

            $this->Conn->beginTransaction();

            $placeholders = str_repeat('?,', count($ids) - 1) . '?';
            $sql = "DELETE FROM 
                        faculty 
                    WHERE 
                        faculty_id 
                    IN ($placeholders)";

            $stmt = $this->Conn->prepare($sql);

            foreach ($ids as $index => $id) {
                $stmt->bindValue($index + 1, $id, PDO::PARAM_INT);
            }

            $stmt->execute();
            $rowCount = $stmt->rowCount();

            $this->Conn->commit();

            return $rowCount;
        } catch (PDOException $e) {
            if ($this->Conn->inTransaction()) {
                $this->Conn->rollBack();
            }
            throw new Exception("Database error: " . $e->getMessage(), 500);
        } catch (Exception $e) {
            if ($this->Conn->inTransaction()) {
                $this->Conn->rollBack();
            }
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }
}
