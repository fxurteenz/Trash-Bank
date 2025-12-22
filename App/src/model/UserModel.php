<?php
namespace App\Model;
use App\Utils\Authentication;
use App\Utils\AuthenticationException;
use App\Utils\Database;
use Exception;
use PDO;
use PDOException;

class UserModel
{
    private static $Database;
    private static $SaltRound;
    private $Conn;
    public function __construct()
    {
        self::$Database = new Database();
        $this->Conn = self::$Database->connect();
        self::$SaltRound = $_ENV['SALT_ROUND'];
    }

    public function Login(array $data): mixed
    {
        try {
            if (empty($data['email']) || empty($data['password'])) {
                throw new Exception('Email or password not provided', 400);
            }

            $sql =
                'SELECT 
                    *
                FROM 
                    account 
                WHERE 
                    account_email = :email';

            $stmt = $this->Conn->prepare($sql);
            $stmt->execute(['email' => $data['email']]);

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user) {
                throw new Exception("Wrong Email" . $data['email'], 401);
            }

            if (password_verify($data['password'], $user['account_password'])) {
                unset($user['account_password']);
            } else {
                throw new Exception("Wrong Password", 401);
            }

            return $user;
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage(), 500);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function Logout()
    {
        try {
            $result = Authentication::UserLogout();
            return $result;
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function GetAllUsers($query): array
    {
        try {
            $whereClauses = [];
            $params = [];

            if (!empty($query['faculty_id'])) {
                $whereClauses[] = "a.faculty_id = :faculty_id";
                $params[':faculty_id'] = $query['faculty_id'];
            }

            if (!empty($query['major_id'])) {
                $whereClauses[] = "a.major_id = :major_id";
                $params[':major_id'] = $query['major_id'];
            }

            if (!empty($query['role'])) {
                $whereClauses[] = "a.account_role = :role";
                $params[':role'] = $query['role'];
            }

            if (!empty($query['search'])) {
                $whereClauses[] = "(a.account_personal_id LIKE :search OR a.account_tel LIKE :search OR a.account_email LIKE :search OR a.account_name LIKE :search)";
                $params[':search'] = "%" . $query['search'] . "%";
            }

            $whereSql = !empty($whereClauses) ? " WHERE " . implode(" AND ", $whereClauses) : "";

            $sql =
                'SELECT 
                	a.account_id, a.account_personal_id, a.account_tel, a.account_name,  a.account_email, a.account_role, a.account_point,
                    f.faculty_id, f.faculty_name, m.major_id, m.major_name
                FROM
                	account a
                LEFT JOIN
                	faculty f ON a.faculty_id = f.faculty_id
                LEFT JOIN
                	major m ON a.major_id = m.major_id' . $whereSql;

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

            $sqlCount = "SELECT COUNT(*) AS allUser FROM account a " . $whereSql;
            $stmt = $this->Conn->prepare($sqlCount);
            foreach ($params as $key => $val) {
                $stmt->bindValue($key, $val);
            }
            $stmt->execute();
            $total = $stmt->fetch(PDO::FETCH_ASSOC);

            $result = [$users, $total['allUser']];
            return $result;
        } catch (PDOException $e) {
            error_log("ERROR DB : " . $e->getMessage());
            throw new Exception("Database error: " . $e->getMessage() . $sql, 500);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function CreateUser(array $data)
    {
        try {
            if (empty($data) && !is_array($data)) {
                throw new Exception('Bad Request =(', 400);
            }

            if (empty($data['account_password'])) {
                throw new Exception('Password not provided', 422);
            }

            if (empty($data['account_personal_id'])) {
                throw new Exception('Personal ID not provided', 422);
            }

            if (empty($data['faculty_id']) || empty($data['major_id'])) {
                throw new Exception('Faculty or Major not provided', 422);
            }

            $encodedPassword = password_hash(
                $data['account_password'],
                PASSWORD_DEFAULT,
                ['cost' => self::$SaltRound]
            );

            $data['account_password'] = $encodedPassword;
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
                "INSERT INTO account 
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

    public function UpdateUser($uid, $data): mixed
    {
        try {
            if (empty($data) && !is_array($data) || empty($uid)) {
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
                "UPDATE account
                SET 
                    {$setClauseString}
                WHERE
                    account_id = :account_id
                ";

            $stmt = $this->Conn->prepare($sql);
            $stmt->execute(array_merge($updateData, ['account_id' => $uid]));

            $result = $stmt->rowCount();
            return $result;
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage(), 500);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function DeleteUser(array $data): int
    {
        if (empty($data['account_ids'] ?? []) || !is_array($data['account_ids'])) {
            throw new Exception('Bad Request: account_ids is required and must be an array', 400);
        }

        $ids = $data['account_ids'];
        $ids = array_filter($ids);

        if (empty($ids)) {
            return 0;
        }

        try {
            $this->Conn->beginTransaction();
            $placeholders = str_repeat('?,', count($ids) - 1) . '?';
            $sql = "DELETE FROM account WHERE account_id IN ($placeholders)";

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
