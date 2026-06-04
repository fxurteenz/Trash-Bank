<?php
namespace App\Model;
use App\Utils\Database;
use App\Utils\DatabaseException;
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

            // ตรวจสอบค่าพารามิเตอร์ boolean
            $showBranch = filter_var($query['show_branch'] ?? false, FILTER_VALIDATE_BOOLEAN);
            $onlyBranch = filter_var($query['only_branch'] ?? false, FILTER_VALIDATE_BOOLEAN);

            // ป้องกันการส่งพารามิเตอร์ที่ทำงานขัดแย้งกัน
            if ($showBranch && $onlyBranch) {
                throw new Exception("INVALID PARAMETER", 400);
            }

            if ($onlyBranch) {
                $whereClauses[] = "f.isCenterBranch = 1";
            } elseif (!$showBranch) {
                $whereClauses[] = "f.isCenterBranch = 0";
            }

            $whereSql = !empty($whereClauses) ? " WHERE " . implode(" AND ", $whereClauses) : "";

            $sortDirection = 'DESC';
            if (isset($query['order']) && strtoupper($query['order']) === 'ASC') {
                $sortDirection = 'ASC';
            }

            $orderBySql = " ORDER BY f.faculty_id " . $sortDirection;

            if (!empty($query['sort_by'])) {
                switch ($query['sort_by']) {
                    case 'name':
                        $orderBySql = " ORDER BY f.faculty_name " . $sortDirection;
                        break;
                    case 'point':
                        $orderBySql = " ORDER BY f.faculty_point " . $sortDirection;
                        break;
                    case 'major':
                        $orderBySql = " ORDER BY major_count_total " . $sortDirection;
                        break;
                    case 'member':
                        $orderBySql = " ORDER BY total_member " . $sortDirection;
                        break;
                }
            }

            // --- แก้ไข SQL ตรงนี้ ---
            $sql = "SELECT 
                    f.*, 
                    COALESCE(m_count.total_major, 0) AS major_count_total,
                    COALESCE(mem_count.user_count, 0) AS user_count,
                    COALESCE(mem_count.professor_count, 0) AS professor_count,
                    COALESCE(mem_count.employee_count, 0) AS employee_count,
                    COALESCE(mem_count.staff_count, 0) AS staff_count,
                    COALESCE(mem_count.total_member, 0) AS total_member
                FROM 
                    faculty f
                -- Subquery 1: นับจำนวน Major
                LEFT JOIN (
                    SELECT faculty_id, COUNT(major_id) AS total_major
                    FROM major
                    GROUP BY faculty_id
                ) AS m_count ON f.faculty_id = m_count.faculty_id
                -- Subquery 2: นับจำนวน Member แยกตาม Role
                LEFT JOIN (
                    SELECT faculty_id,
                           SUM(CASE WHEN role_id = 1 THEN 1 ELSE 0 END) as user_count,
                           SUM(CASE WHEN role_id = 2 THEN 1 ELSE 0 END) as professor_count,
                           SUM(CASE WHEN role_id = 3 THEN 1 ELSE 0 END) as employee_count,
                           SUM(CASE WHEN role_id = 5 THEN 1 ELSE 0 END) as staff_count,
                           COUNT(member_id) as total_member
                    FROM member
                    GROUP BY faculty_id
                ) AS mem_count ON f.faculty_id = mem_count.faculty_id
                
                {$whereSql}
                
                {$orderBySql}";

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
            $sql = "SELECT 
                    f.*, 
                    COALESCE(m_count.total_major, 0) AS major_count_total,
                    COALESCE(mem_count.user_count, 0) AS user_count,
                    COALESCE(mem_count.professor_count, 0) AS professor_count,
                    COALESCE(mem_count.employee_count, 0) AS employee_count,
                    COALESCE(mem_count.staff_count, 0) AS staff_count,
                    COALESCE(mem_count.total_member, 0) AS total_member
                FROM faculty f
                LEFT JOIN (
                    SELECT faculty_id, COUNT(major_id) AS total_major
                    FROM major
                    GROUP BY faculty_id
                ) AS m_count ON f.faculty_id = m_count.faculty_id
                LEFT JOIN (
                    SELECT faculty_id,
                        SUM(CASE WHEN role_id = 1 THEN 1 ELSE 0 END) as user_count,
                        SUM(CASE WHEN role_id = 2 THEN 1 ELSE 0 END) as professor_count,
                        SUM(CASE WHEN role_id = 3 THEN 1 ELSE 0 END) as employee_count,
                        SUM(CASE WHEN role_id = 4 THEN 1 ELSE 0 END) as staff_count,
                        COUNT(member_id) as total_member
                    FROM member
                    GROUP BY faculty_id
                ) AS mem_count ON f.faculty_id = mem_count.faculty_id
                WHERE f.faculty_id = :faculty_id";
            $stmt = $this->Conn->prepare($sql);
            $stmt->bindValue(':faculty_id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            // if ($data) {
            //     // ดึงรายการสาขาที่อยู่ในคณะนี้
            //     $majorSql = "SELECT * FROM major WHERE faculty_id = :faculty_id";
            //     $majorStmt = $this->Conn->prepare($majorSql);
            //     $majorStmt->bindValue(':faculty_id', $id, PDO::PARAM_INT);
            //     $majorStmt->execute();
            //     $data['majors'] = $majorStmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
            // }

            return $data ?: [];
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
            $error = DatabaseException::handle($e);
            throw new Exception($error['message'], $error['code']);
            // throw new Exception("Database error: " . $e->getMessage(), 500);
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
            $error = DatabaseException::handle($e);
            throw new Exception($error['message'], $error['code']);
            // throw new Exception("Database error: " . $e->getMessage(), 500);
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
            $error = DatabaseException::handle($e);
            throw new Exception($error['message'], $error['code']);
            // throw new Exception("Database error: " . $e->getMessage(), 500);
        } catch (Exception $e) {
            if ($this->Conn->inTransaction()) {
                $this->Conn->rollBack();
            }
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }
}
