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
                $whereClauses[] = "(f.faculty_name LIKE :search OR f.faculty_id LIKE :search OR f.faculty_code LIKE :search)";
                $params[':search'] = "%" . $query['search'] . "%";
            }

            $whereSql = !empty($whereClauses) ? " WHERE " . implode(" AND ", $whereClauses) : "";

            // --- แก้ไข SQL ตรงนี้ ---
            $sql = "SELECT 
                    f.*, 
                    COALESCE(m_count.total_major, 0) AS major_count_total, 
                    COALESCE(fp_sum.total_point, 0) AS faculty_point_total
                FROM 
                    faculty f
                -- Subquery 1: นับจำนวน Major
                LEFT JOIN (
                    SELECT faculty_id, COUNT(major_id) AS total_major
                    FROM major
                    GROUP BY faculty_id
                ) AS m_count ON f.faculty_id = m_count.faculty_id
                -- Subquery 2: รวมคะแนน Point
                LEFT JOIN (
                    SELECT faculty_id, SUM(faculty_point_amount) AS total_point
                    FROM faculty_point
                    GROUP BY faculty_id
                ) AS fp_sum ON f.faculty_id = fp_sum.faculty_id
                
                {$whereSql}
                
                ORDER BY f.faculty_id DESC";

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

            // ส่วนนับจำนวนทั้งหมดสำหรับ Pagination
            if ($isPagination) {
                // สังเกตว่า count ต้องนับจาก alias f (faculty)
                $sqlCount = "SELECT COUNT(*) AS all_faculty FROM faculty f {$whereSql}";
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
