<?php
namespace App\Model;
use Exception;
use PDO;
use PDOException;

class UserModel
{
    private $Conn;
    private $Jwt;
    private static $SaltRound;
    public function __construct($database)
    {
        $this->Conn = $database->connect();
        // $this->Jwt = new Jwt();
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

    public function GetAllUsers(): array
    {
        try {
            $sql =
                'SELECT 
                    * 
                FROM 
                    account_tb;';

            $stmt = $this->Conn->prepare($sql);
            $stmt->execute();
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $users;
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage(), 500);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function CreateUser(array $data)
    {
        try {
            if (empty($data['email']) || empty($data['password'])) {
                throw new Exception('Email or password not provided', 500);
            }

            $encodedPassword = password_hash(
                $data['password'],
                PASSWORD_DEFAULT,
                ['cost' => self::$SaltRound]
            );

            $sql =
                'INSERT INTO account_tb
                    (account_email,account_password,account_role)
                VALUES
                    (:email,:encodedPassword,:role)
                ';

            $stmt = $this->Conn->prepare($sql);
            $stmt->execute([
                'email' => $data['email'],
                'encodedPassword' => $encodedPassword,
                'role' => 2,
            ]);

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
            foreach ($data as $column => $value) {
                $setClauses[] = "`{$column}` = :{$column}";
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
            $stmt->execute(array_merge($data, ['account_id' => $uid]));

            $result = $stmt->rowCount();
            return $result;
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage() . $sql, 500);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function DeleteUser($uid)
    {
        try {
            if (empty($uid)) {
                throw new Exception('Bad Request =(', 400);
            }

            $sql = 'DELETE FROM 
                        account_tb
                    WHERE account_id = :account_id';

            $stmt = $this->Conn->prepare($sql);
            $stmt->execute([
                'account_id' => $uid
            ]);
            $affectedRows = $stmt->rowCount();
            return $affectedRows;
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage(), 500);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }
}
