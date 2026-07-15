<?php
namespace App\Model;

use App\Utils\Database;
use Exception;
use PDO;
use PDOException;

class MajorModel
{
    private static $Database;
    private $Conn;

    public function __construct()
    {
        self::$Database = new Database();
        $this->Conn = self::$Database->connect();
    }

    public function GetMajorById($mid): array
    {
        try {
            $sql = "SELECT 
                        m.*, 
                        f.faculty_name
                    FROM 
                        major m
                    LEFT JOIN 
                        faculty f ON m.faculty_id = f.faculty_id
                    WHERE
                        m.major_id = :major_id";

            $stmt = $this->Conn->prepare($sql);
            $stmt->execute(['major_id' => $mid]);
            $majors = $stmt->fetch(PDO::FETCH_ASSOC);

            return $majors;
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage(), 500);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function GetAllMajor($query): array
    {
        try {
            $whereClauses = [];
            $params = [];

            if (!empty($query['name'])) {
                $whereClauses[] = "m.major_name LIKE :major_name";
                $params[':major_name'] = "%" . $query['name'] . "%";
            }

            if (!empty($query['faculty'])) {
                $whereClauses[] = "m.faculty_id = :faculty_id";
                $params[':faculty_id'] = $query['faculty'];
            }

            $whereSql = !empty($whereClauses) ? " WHERE " . implode(" AND ", $whereClauses) : "";

            $sortDirection = 'DESC';
            if (isset($query['order']) && strtoupper($query['order']) === 'ASC') {
                $sortDirection = 'ASC';
            }

            $orderBySql = " ORDER BY total_member " . $sortDirection;

            if (!empty($query['sort_by'])) {
                switch ($query['sort_by']) {
                    case 'name':
                        $orderBySql = " ORDER BY m.major_name " . $sortDirection;
                        break;
                    case 'member':
                        $orderBySql = " ORDER BY user_count " . $sortDirection;
                        break;
                    case 'professor':
                        $orderBySql = " ORDER BY professor_count " . $sortDirection;
                        break;
                    case 'employee':
                        $orderBySql = " ORDER BY employee_count " . $sortDirection;
                        break;
                    case 'total':
                        $orderBySql = " ORDER BY total_member" . $sortDirection;
                        break;
                }
            }

            $sql = "SELECT 
                        m.*,
                        f.faculty_name,
                        COALESCE(member_count.total_member, 0) AS total_member,
                        COALESCE(member_count.student_count, 0) AS user_count,
                        COALESCE(member_count.professor_count, 0) AS professor_count,
                        COALESCE(member_count.employee_count, 0) AS employee_count
                    FROM 
                        major m
                    LEFT JOIN faculty f ON m.faculty_id = f.faculty_id
                    LEFT JOIN (
                        SELECT 
                            major_id, 
                            COUNT(member_id) AS total_member,
                            SUM(CASE WHEN role_id = 1 THEN 1 ELSE 0 END) as student_count,
                            SUM(CASE WHEN role_id = 2 THEN 1 ELSE 0 END) as professor_count,
                            SUM(CASE WHEN role_id = 3 THEN 1 ELSE 0 END) as employee_count
                        FROM 
                            member
                        GROUP BY 
                            major_id
                    ) AS member_count ON m.major_id = member_count.major_id
                    {$whereSql} {$orderBySql}";

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
            $majors = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($isPagination) {
                $sqlCount = "SELECT COUNT(*) AS allMajor FROM major m {$whereSql}";
                $stmtCount = $this->Conn->prepare($sqlCount);
                foreach ($params as $key => $val) {
                    $stmtCount->bindValue($key, $val);
                }
                $stmtCount->execute();
                $total = $stmtCount->fetch(PDO::FETCH_ASSOC)['allMajor'];
            } else {
                $total = count($majors);
            }

            return [$majors, $total];

        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage(), 500);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function GetMajorByFaculty($fid, $query): array
    {
        try {
            $sql =
                "SELECT 
                    m.major_id, 
                    m.major_name
                FROM 
                    major m
                WHERE 
                    m.faculty_id = :faculty_id";

            $isPagination = isset($query['page']) && isset($query['limit']);

            if ($isPagination) {
                $page = (int) $query['page'];
                $limit = (int) $query['limit'];
                $offset = ($page - 1) * $limit;
                $sql .= " LIMIT :limit OFFSET :offset";
            }

            $stmt = $this->Conn->prepare($sql);
            $stmt->bindValue(':faculty_id', $fid, PDO::PARAM_INT);

            if ($isPagination) {
                $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
                $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            }

            $stmt->execute();
            $allMajor = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($isPagination) {
                $sqlCount = "SELECT COUNT(*) as total FROM major WHERE faculty_id = :faculty_id";
                $stmtCount = $this->Conn->prepare($sqlCount);
                $stmtCount->bindValue(':faculty_id', $fid, PDO::PARAM_INT);
                $stmtCount->execute();
                $total = $stmtCount->fetch(PDO::FETCH_ASSOC)['total'];
            } else {
                $total = count($allMajor);
            }

            return [$allMajor, $total];
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage(), 500);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function CreateMajor(array $data): int
    {
        try {
            if (!is_array($data) || empty($data['major_name']) || empty($data['faculty_id'])) {
                throw new Exception('Invalid or incomplete data provided', 400);
            }

            $data['created_at'] = date('Y-m-d H:i:s');

            $columns = [];
            $placeholders = [];
            $insertData = [];

            // Define allowed fields to prevent SQL injection from unexpected data keys
            $allowedFields = ['faculty_id', 'major_name', 'major_name_en', 'major_code', 'created_at'];

            foreach ($allowedFields as $field) {
                if (isset($data[$field]) && !is_null($data[$field])) {
                    $columns[] = "`{$field}`";
                    $placeholders[] = ":{$field}";
                    $insertData[$field] = $data[$field];
                }
            }

            if (empty($columns)) {
                throw new Exception('No valid data to insert', 400);
            }

            $sql = sprintf(
                "INSERT INTO major (%s) VALUES (%s)",
                implode(', ', $columns),
                implode(', ', $placeholders)
            );

            $stmt = $this->Conn->prepare($sql);
            $stmt->execute($insertData);

            return $stmt->rowCount();

        } catch (PDOException $e) {
            // Log the detailed PDO error for debugging, but return a generic message to the user
            error_log("Database error in CreateMajor: " . $e->getMessage());
            throw new Exception("Database error occurred while creating the major.", 500);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function UpdateMajor($id, $data): mixed
    {
        try {
            if ((empty($data) && !is_array($data)) || empty($id)) {
                throw new Exception('Bad Request', 400);
            }

            $data['updated_at'] = date('Y-m-d H:i:s');

            $setClauses = [];
            $updateData = [];
            foreach ($data as $column => $value) {
                // อัปเดตเฉพาะค่าที่ส่งมา
                if (isset($value) && $value !== '') {
                    $setClauses[] = "`{$column}` = :{$column}";
                    $updateData[$column] = $value;
                }
            }

            if (empty($setClauses)) {
                return 0; // ไม่มีข้อมูลให้เปลี่ยนแปลง
            }

            $setClauseString = implode(', ', $setClauses);

            $sql = "UPDATE major SET {$setClauseString} WHERE major_id = :major_id";

            $stmt = $this->Conn->prepare($sql);
            // รวม array ข้อมูลที่จะอัปเดตเข้ากับ ID สำหรับ WHERE clause
            $stmt->execute(array_merge($updateData, ['major_id' => $id]));

            return $stmt->rowCount();

        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage(), 500);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function DeleteMajorById($id): int
    {
        try {
            if (empty($id)) {
                throw new Exception('ID is required for deletion', 400);
            }

            $sql = "DELETE FROM major WHERE major_id = :major_id";
            $stmt = $this->Conn->prepare($sql);
            $stmt->execute(['major_id' => $id]);

            return $stmt->rowCount();
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage(), 500);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function DeleteMajor(array $data): int
    {
        if (empty($data['major_ids'] ?? []) || !is_array($data['major_ids'])) {
            throw new Exception('Bad Request: major_ids is required and must be an array', 400);
        }

        $ids = array_filter($data['major_ids']);

        if (empty($ids)) {
            throw new Exception('Bad Request:require value are empty', 400);
        }

        try {
            $this->Conn->beginTransaction();

            $placeholders = str_repeat('?,', count($ids) - 1) . '?';
            $sql = "DELETE FROM major WHERE major_id IN ($placeholders)";

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