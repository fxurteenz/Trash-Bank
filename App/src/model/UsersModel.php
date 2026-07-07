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
                throw new Exception('กรุณาลองใหม่อีกครั้ง', 400);
            }

            $sql =
                'SELECT 
                    m.*,
                    r.role_name,
                    r.role_name_th,
                    f.faculty_name
                FROM 
                    member m
                LEFT JOIN 
                    role r ON m.role_id = r.role_id
                LEFT JOIN 
                    faculty f ON m.faculty_id = f.faculty_id
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
                throw new Exception("กรุณาลองใหม่อีกครั้ง, รหัสผ่านไม่ถูกต้อง", 401);
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
            if (empty($data)) {
                throw new Exception('มีบางอย่างผิดพลาด, กรุณาลองใหม่อีกครั้ง', 400);
            }

            if (empty($data['member_password'])) {
                throw new Exception('ตรวจสอบข้อมูล, กรุณากรอกรหัสผ่าน', 422);
            }
            if (mb_strlen($data['member_password']) < 6) {
                throw new Exception('ตรวจสอบข้อมูล, รหัสผ่านต้องมีความยาวอย่างน้อย 6 ตัวอักษร', 422);
            }

            if (empty($data['member_phone'])) {
                throw new Exception('ตรวจสอบข้อมูล, กรุณากรอกเบอร์โทรศัพท์', 422);
            }
            if (!preg_match('/^\d{10}$/', $data['member_phone'])) {
                throw new Exception('เบอร์โทรศัพท์ต้องเป็นตัวเลข 10 หลัก', 422);
            }

            if (empty($data['member_type'])) {
                throw new Exception('ตรวจสอบข้อมูล, กรุณาระบุประเภทสมาชิก', 422);
            }

            if (empty($data['member_name'])) {
                throw new Exception('ตรวจสอบข้อมูล, กรุณากรอกชื่อ-สกุล', 422);
            }
            if (!preg_match('/^[a-zA-Zก-๏\s\.]+$/u', $data['member_name'])) {
                throw new Exception('ชื่อ-นามสกุลต้องเป็นตัวอักษรเท่านั้น', 422);
            }

            if (!empty($data['member_email']) && !filter_var($data['member_email'], FILTER_VALIDATE_EMAIL)) {
                throw new Exception('รูปแบบอีเมลไม่ถูกต้อง', 422);
            }

            if (in_array($data['member_type'], ['student', 'teacher'], true) && empty($data['faculty_id'])) {
                throw new Exception('ตรวจสอบข้อมูล, กรุณาระบุคณะ', 422);
            }

            if ($data['member_type'] === 'student' && !empty($data['member_personal_id']) && !preg_match('/^\d{12}$/', $data['member_personal_id'])) {
                throw new Exception('รหัสนักศึกษาต้องเป็นตัวเลข 12 หลัก', 422);
            }

            self::$Conn->beginTransaction();

            // จัดการ Inviter
            $inviterId = !empty($data['inviter_id']) ? $data['inviter_id'] : null;

            // แปลง Role ID
            $roleMap = [
                'student' => 1,
                'teacher' => 2,
                'staff' => 3
            ];

            if (!isset($roleMap[$data['member_type']])) {
                throw new Exception('ประเภทสมาชิกไม่ถูกต้อง', 400);
            }

            // เตรียมข้อมูลสำหรับบันทึก (Whitelist เพื่อป้องกัน Mass Assignment และ SQL Injection)
            $insertData = [
                'member_phone' => $data['member_phone'],
                'member_password' => password_hash($data['member_password'], PASSWORD_DEFAULT, ['cost' => self::$SaltRound]),
                'member_name' => $data['member_name'],
                'role_id' => $roleMap[$data['member_type']],
                'created_at' => date('Y-m-d H:i:s'),
            ];

            // คอลัมน์ทางเลือก (Optional fields)
            $optionalFields = ['member_email', 'faculty_id', 'member_personal_id'];
            foreach ($optionalFields as $field) {
                if (isset($data[$field]) && $data[$field] !== '') {
                    $insertData[$field] = $data[$field];
                }
            }

            // สร้าง SQL INSERT dynamically อย่างปลอดภัย
            $columns = array_keys($insertData);
            $setClauses = array_map(fn($col) => "`{$col}` = :{$col}", $columns);
            $sql = "INSERT INTO member SET " . implode(', ', $setClauses);

            $stmt = self::$Conn->prepare($sql);
            $stmt->execute($insertData);
            $newMemberId = self::$Conn->lastInsertId();

            $updateMemberPoint = "INSERT INTO member_point (member_id, member_point_event, member_point_event_sum)
                              VALUES (:member_id, 10, 10)
                              ON DUPLICATE KEY UPDATE
                                member_point_event = member_point_event + 10,
                                member_point_event_sum = member_point_event_sum + 10";
            self::$Conn->prepare($updateMemberPoint)->execute([':member_id' => $newMemberId]);

            // กรณีมีผู้แนะนำ (Inviter)
            if ($inviterId) {
                $inviteSql = "INSERT INTO member_invite (inviter_id, invitees_id, created_at) 
                          VALUES (:inviter_id, :invitees_id, NOW())";
                self::$Conn->prepare($inviteSql)->execute([
                    ':inviter_id' => $inviterId,
                    ':invitees_id' => $newMemberId
                ]);

                $updateRecruiterSql = "INSERT INTO member_point (member_id, social_point, total_social_point) 
                                   VALUES (:inviter_id, 1, 1)
                                   ON DUPLICATE KEY UPDATE
                                       social_point = social_point + 1,
                                       total_social_point = total_social_point + 1";
                self::$Conn->prepare($updateRecruiterSql)->execute([':inviter_id' => $inviterId]);
            }

            self::$Conn->commit();
            return ["member_phone" => $data["member_phone"], "member_id" => $newMemberId];

        } catch (PDOException $e) {
            if (self::$Conn->inTransaction()) {
                self::$Conn->rollBack();
            }
            $error = DatabaseException::handle($e);
            // error_log($e->getMessage());
            throw new Exception($error['message'], $error['code'] ?? 500);
        } catch (Exception $e) {
            if (self::$Conn->inTransaction()) {
                self::$Conn->rollBack();
            }
            error_log($e->getMessage());
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

}
