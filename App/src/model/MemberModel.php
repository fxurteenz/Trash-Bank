<?php
namespace App\Model;
use App\Utils\Database;
use App\Utils\DatabaseException;

use Exception;
use PDO;
use PDOException;

class MemberModel
{
    private static $Database;
    private static $SaltRound;
    private $Conn;
    public function __construct()
    {
        self::$SaltRound = $_ENV['SALT_ROUND'];
        self::$Database = new Database();
        $this->Conn = self::$Database->connect();
    }

    public function GetAllMembers($query): array
    {
        try {
            $whereClauses = [];
            $params = [];

            if (!empty($query['faculty_id'])) {
                $whereClauses[] = "m.faculty_id = :faculty_id";
                $params[':faculty_id'] = $query['faculty_id'];
            }

            if (!empty($query['role_id'])) {
                $whereClauses[] = "m.role_id = :role_id";
                $params[':role_id'] = $query['role_id'];
            }

            if (!empty($query['search'])) {
                $whereClauses[] = "(m.member_name LIKE :search OR m.member_phone LIKE :search OR m.member_email LIKE :search OR m.member_personal_id LIKE :search)";
                $params[':search'] = "%" . $query['search'] . "%";
            }

            $whereSql = !empty($whereClauses) ? " WHERE " . implode(" AND ", $whereClauses) : "";

            $sql = "SELECT 
                        m.*, 
                        f.faculty_name,
                        r.role_name,
                        r.role_name_th
                    FROM 
                        member m
                    LEFT JOIN 
                        faculty f ON m.faculty_id = f.faculty_id
                    LEFT JOIN 
                        role r ON m.role_id = r.role_id
                    {$whereSql}";

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
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($isPagination) {
                $sqlCount = "SELECT COUNT(*) AS total FROM member m {$whereSql}";
                $stmtCount = $this->Conn->prepare($sqlCount);
                foreach ($params as $key => $val) {
                    $stmtCount->bindValue($key, $val);
                }
                $stmtCount->execute();
                $total = $stmtCount->fetch(PDO::FETCH_ASSOC)['total'];
            } else {
                $total = count($users);
            }
            return ["data" => $users, "total" => $total];
        } catch (PDOException $e) {
            error_log($e->getMessage());
            throw new Exception("Database error: " . $e->getMessage(), 500);
        } catch (Exception $e) {
            error_log($e->getMessage());
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function CreateMember($data)
    {
        try {
            if (empty($data) && !is_array($data)) {
                throw new Exception('มีบางอย่างผิดพลาด,กรุณาลองใหม่อีกครั้ง', 400);
            }

            if (empty($data['member_password'])) {
                throw new Exception('ตรวจสอบข้อมูล, กรุณากรอกรหัสผ่าน', 422);
            }

            if (empty($data['member_phone'])) {
                throw new Exception('ตรวจสอบข้อมูล, กรุณากรอกเบอร์โทรศัพท์', 422);
            }

            $encodedPassword = password_hash(
                $data['member_password'],
                PASSWORD_DEFAULT,
                ['cost' => self::$SaltRound]
            );

            $data['member_password'] = $encodedPassword;
            $data['created_at'] = date('Y-m-d H:i:s');

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
                    member 
                SET
                    {$setClauseString}
                ";

            $stmt = $this->Conn->prepare($sql);
            $stmt->execute($updateData);

            $id = $this->Conn->lastInsertId();
            return ["member_phone" => $data["member_phone"], "member_id" => $id];
        } catch (PDOException $e) {
            $error = DatabaseException::handle($e);
            throw new Exception($error['message'], $error['code']);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function UpdateMember($uid, $data): mixed
    {
        try {
            if (empty($data) && !is_array($data) || empty($uid)) {
                throw new Exception('Bad Request =(', 400);
            }

            if (!empty($data['new_password']) && !empty($data['old_password'])) {
                // $encodedPassword = password_hash(
                //     $data['password'],
                //     PASSWORD_DEFAULT,
                //     ['cost' => self::$SaltRound]
                // );

                // $data["member_password"] = $encodedPassword;
            } else if (empty($data['old_password']) && !empty($data['new_password'])) {
                throw new Exception("กรุณาลองใหม่, ต้องใช้รหัสผ่านเก่า", 400);
            } else if (!empty($data['new_password']) && empty($data['old_password'])) {
                throw new Exception("กรุณาลองใหม่, อย่าลืมกรอกรหัสผ่านใหม่", 400);
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
                "UPDATE 
                    member
                SET 
                    {$setClauseString}
                WHERE
                    member_id = :member_id
                ";

            $stmt = $this->Conn->prepare($sql);
            $stmt->execute(array_merge($updateData, ['member_id' => $uid]));

            $result = $stmt->rowCount();
            return $result;
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage(), 500);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function DeleteMember(array $data): int
    {
        if (empty($data['member_ids'] ?? []) || !is_array($data['member_ids'])) {
            throw new Exception('Bad Request: member_ids is required and must be an array', 400);
        }

        $ids = $data['member_ids'];
        $ids = array_filter($ids);

        if (empty($ids)) {
            return 0;
        }

        try {
            $this->Conn->beginTransaction();
            $placeholders = str_repeat('?,', count($ids) - 1) . '?';
            $sql = "DELETE FROM member WHERE member_id IN ($placeholders)";

            $stmt = $this->Conn->prepare($sql);

            foreach ($ids as $index => $uuid) {
                $stmt->bindValue($index + 1, $uuid, PDO::PARAM_STR);
            }

            $stmt->execute();
            $this->Conn->commit();
            return $stmt->rowCount();
        } catch (PDOException $e) {
            $this->Conn->rollBack();
            throw new Exception("Database error: " . $e->getMessage() . $ids, 500);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }
}
