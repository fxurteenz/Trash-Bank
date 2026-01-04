<?php
namespace App\Model;
use App\Utils\Authentication;
use App\Utils\AuthenticationException;
use App\Utils\Database;
use App\Utils\Jwt;
use App\Utils\CookieBaker;
use App\Utils\DatabaseException;
use Exception;
use PDO;
use PDOException;

class UsersModel
{
    private static $Database;
    private static $SaltRound;
    private static $Conn;
    public function __construct()
    {
        self::$Database = new Database();
        self::$Conn = self::$Database->connect();
        self::$SaltRound = $_ENV['SALT_ROUND'];
    }

    public function UsersLogin(array $data): mixed
    {
        try {
            $identifier = $data['identifier'] ?? null;
            if (empty($identifier) || empty($data['password'])) {
                throw new Exception('Identifier or password not provided', 400);
            }

            $sql =
                'SELECT 
                    *
                FROM 
                    member 
                WHERE 
                    member_email = :identifier OR
                    member_personal_id = :identifier OR
                    member_phone = :identifier';

            $stmt = self::$Conn->prepare($sql);
            $stmt->execute(['identifier' => $identifier]);

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user) {
                throw new Exception("User not found " . $identifier, 401);
            }

            if (password_verify($data['password'], $user['member_password'])) {
                unset($user['member_password']);
            } else {
                throw new Exception("Wrong Password", 401);
            }
            $token = Jwt::jwt_encode($user);
            $cookieToken = CookieBaker::BakeUserCookie($token);

            return [$user, $cookieToken];
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage(), 500);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function UsersLogout()
    {
        try {
            $result = Authentication::UserLogout();
            return $result;
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        } catch (AuthenticationException $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 401);
        }
    }

    public function UsersRegister(array $data)
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
            $data['role_id'] = 2;
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
                "INSERT INTO member 
                SET
                    {$setClauseString}
                ";

            $stmt = self::$Conn->prepare($sql);
            $stmt->execute($updateData);
            $id = self::$Conn->lastInsertId();
            return ["member_phone" => $data["member_phone"], "member_id" => $id];
        } catch (PDOException $e) {
            $error = DatabaseException::handle($e);
            throw new Exception($error['message'], $error['code']);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

}
