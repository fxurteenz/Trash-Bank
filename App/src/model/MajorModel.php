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
            $sql = "SELECT 
                        m.*, 
                        f.faculty_name
                    FROM 
                        major m
                    LEFT JOIN 
                        faculty f ON m.faculty_id = f.faculty_id";

            $isPagination = isset($query['page']) && isset($query['limit']);

            if ($isPagination) {
                $page = (int) $query['page'];
                $limit = (int) $query['limit'];
                $offset = ($page - 1) * $limit;

                $sql .= " LIMIT :limit OFFSET :offset";
            }

            $stmt = $this->Conn->prepare($sql);

            if ($isPagination) {
                $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
                $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            }

            $stmt->execute();
            $majors = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($isPagination) {
                $sqlCount = 'SELECT COUNT(*) AS allMajor FROM major';
                $stmtCount = $this->Conn->prepare($sqlCount);
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
                    m.major_name,
                    SUM(CASE WHEN a.account_role = 'user' THEN 1 ELSE 0 END) AS count_user,
                    SUM(CASE WHEN a.account_role = 'faculty_staff' THEN 1 ELSE 0 END) AS count_staff,
                    SUM(CASE WHEN a.account_role = 'operater' THEN 1 ELSE 0 END) AS count_operater,
                    COUNT(a.account_id) AS total_all
                FROM 
                    major m
                LEFT JOIN 
                    account a ON m.major_id = a.major_id
                WHERE 
                    m.faculty_id = :faculty_id
                GROUP BY 
                    m.major_id";

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
            // Validate แบบเดียวกับ WasteTypeModel
            if (!is_array($data)) {
                throw new Exception('Invalid data format', 400);
            }
            if (empty($data['faculty_id'])) {
                throw new Exception('Faculty ID is not provided', 400);
            }
            if (empty($data['major_name'])) {
                throw new Exception('Major name is not provided', 400);
            }

            // ใช้การ Insert แบบระบุ Field ชัดเจน (หรือจะใช้แบบ dynamic set เหมือน WasteType ก็ได้ แต่แบบนี้อ่านง่ายสำหรับ Create)
            $sql = "INSERT INTO major (faculty_id, major_name) VALUES (:faculty_id, :major_name)";

            $stmt = $this->Conn->prepare($sql);
            $stmt->execute([
                'faculty_id' => $data['faculty_id'],
                'major_name' => $data['major_name']
            ]);

            return $stmt->rowCount();

        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage(), 500);
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