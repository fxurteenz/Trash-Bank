<?php
namespace App\Model;
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
                    account_tb 
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

    public function GetAllUsers($query): array
    {
        try {
            $page = isset($query['page']) ? (int) $query['page'] : 1;
            $limit = isset($query['limit']) ? (int) $query['limit'] : 0;
            $offset = ($page - 1) * $limit;
            $sql =
                'SELECT 
                    * 
                FROM 
                    account_tb
                LIMIT :limit OFFSET :offset;
                ';

            $stmt = $this->Conn->prepare($sql);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $sqlCount = 'SELECT COUNT(*) AS allUser FROM account_tb';
            $stmt = $this->Conn->prepare($sqlCount);
            $stmt->execute();
            $total = $stmt->fetch(PDO::FETCH_ASSOC);

            $result = [$users, $total['allUser']];
            return $result;
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage() . $sql, 500);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function CreateUser(array $data)
    {
        try {
            if (empty($data['account_email']) || empty($data['account_password'])) {
                throw new Exception('Email or password not provided', 500);
            }

            $encodedPassword = password_hash(
                $data['account_password'],
                PASSWORD_DEFAULT,
                ['cost' => self::$SaltRound]
            );

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
                "INSERT INTO account_tb 
                SET
                    {$setClauseString}
                ";

            $stmt = $this->Conn->prepare($sql);
            $stmt->execute($updateData);

            $acc_id = $stmt->rowCount();
            return $acc_id;
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
                "UPDATE account_tb
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
            $sql = "DELETE FROM account_tb WHERE account_id IN ($placeholders)";

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
