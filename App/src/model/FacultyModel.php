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
            $sql = "SELECT * FROM faculty";
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
            $faculties = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($isPagination) {
                $sqlCount = 'SELECT COUNT(*) AS all_faculty FROM faculty';
                $stmtCount = $this->Conn->prepare($sqlCount);
                $stmtCount->execute();
                $total = $stmtCount->fetch(PDO::FETCH_ASSOC)['all_faculty'];
            } else {
                $total = count($faculties);
            }

            return [$faculties, $total];
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage(), 500);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function GetFacultyByMajor($mid): array
    {
        try {
            $sql = "SELECT 
                        f.* FROM 
                        faculty f
                    WHERE 
                        m.major_id = :major_id";

            $stmt = $this->Conn->prepare($sql);
            $stmt->bindValue(':major_id', $mid, PDO::PARAM_INT);
            $stmt->execute();

            // fetch (ไม่ใช่ fetchAll) เพราะ major หนึ่งสังกัดได้แค่ 1 faculty ตาม logic ปกติ
            $faculty = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$faculty) {
                return []; // หรือ throw Exception ถ้าต้องการ
            }

            return $faculty;

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
            if (empty($data) && !is_array($data) || empty($fid)) {
                throw new Exception('Bad Request =(', 400);
            }

            // $encodedPassword = password_hash(
            //     $data['password'],
            //     PASSWORD_DEFAULT,
            //     ['cost' => self::$SaltRound]
            // );

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

    public function DeleteFacultyById($id): int
    {
        try {
            if (empty($id)) {
                throw new Exception('ID is required for deletion', 400);
            }

            $sql = "DELETE FROM faculty WHERE faculty_id = :faculty_id";
            $stmt = $this->Conn->prepare($sql);
            $stmt->execute(['faculty_id' => $id]);

            return $stmt->rowCount();
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
            $sql = "DELETE FROM faculty WHERE faculty_id IN ($placeholders)";

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
