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

            if (!empty($query['faculty'])) {
                $whereClauses[] = "m.faculty_id = :faculty_id";
                $params[':faculty_id'] = $query['faculty'];
            }

            if (!empty($query['role'])) {
                $whereClauses[] = "m.role_id = :role_id";
                $params[':role_id'] = $query['role'];
            }

            if (!empty($query['major_id'])) {
                $whereClauses[] = "m.major_id = :major_id";
                $params[':major_id'] = $query['major_id'];
            }

            if (!empty($query['search'])) {
                $whereClauses[] = "(m.member_name LIKE :search OR m.member_phone LIKE :search OR m.member_email LIKE :search OR m.member_personal_id LIKE :search)";
                $params[':search'] = "%" . $query['search'] . "%";
            }

            $whereSql = !empty($whereClauses) ? " WHERE " . implode(" AND ", $whereClauses) : "";
            $sortDirection = 'DESC';
            if (isset($query['order']) && strtolower($query['order']) === 'asc') {
                $sortDirection = 'ASC';
            }

            $orderBySql = " ORDER BY m.member_waste_point " . $sortDirection;

            if (!empty($query['sort_by'])) {
                switch ($query['sort_by']) {
                    case 'waste_point':
                        $orderBySql = " ORDER BY m.member_waste_point " . $sortDirection;
                        break;
                    case 'goodness_point':
                        $orderBySql = " ORDER BY m.member_goodness_point " . $sortDirection;
                        break;
                    case 'name':
                        $orderBySql = " ORDER BY m.member_name " . $sortDirection;
                        break;
                }
            }

            $sql = "SELECT 
                    m.member_id, 
                    m.member_name, 
                    m.member_phone, 
                    m.member_email, 
                    m.member_personal_id, 
                    m.member_waste_point, 
                    m.member_goodness_point, 
                    m.role_id,
                    m.faculty_id,
                    m.major_id,
                    f.faculty_name,
                    f.faculty_point as member_faculty_point,
                    maj.major_name,
                    r.role_name,
                    r.role_name_th
                FROM 
                    member m
                LEFT JOIN 
                    faculty f ON m.faculty_id = f.faculty_id
                LEFT JOIN 
                    major maj ON m.major_id = maj.major_id
                LEFT JOIN 
                    role r ON m.role_id = r.role_id
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

    public function CreateMember($data): array
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
            // Add initial points of 10 for new members
            if ((int) $data["role_id"] == 2) {
                $data['member_waste_point'] = 10;
            } else {
                $data['member_waste_point'] = 0;
            }

            $setClauses = [];
            $updateData = [];
            foreach ($data as $column => $value) {
                if ($value !== '' && $value !== null) {
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
            // error_log($e->getMessage());
            $error = DatabaseException::handle($e);
            throw new Exception($error['message'], $error['code']);
            // throw new Exception($e->getMessage(), $e->getCode() ?: 500);
        } catch (Exception $e) {
            // error_log($e->getMessage());
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
                if ($value !== '' && $value !== null) {
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
            $error = DatabaseException::handle($e);
            throw new Exception($error['message'], $error['code']);
            // throw new Exception("Database error: " . $e->getMessage(), 500);
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

    public function GetMemberProfile($member_id): array
    {
        try {
            $sql = "SELECT 
                        m.member_id,
                        m.member_personal_id,
                        m.member_name,
                        m.member_phone,
                        m.member_email,
                        m.member_waste_point,
                        m.member_goodness_point,
                        m.role_id,
                        m.faculty_id,
                        m.major_id,
                        m.created_at,
                        m.updated_at,
                        f.faculty_name,
                        f.faculty_point as member_faculty_point,
                        maj.major_name,
                        r.role_name,
                        r.role_name_th
                    FROM 
                        member m
                    LEFT JOIN 
                        faculty f ON m.faculty_id = f.faculty_id
                    LEFT JOIN 
                        major maj ON m.major_id = maj.major_id
                    LEFT JOIN 
                        role r ON m.role_id = r.role_id
                    WHERE 
                        m.member_id = :member_id";

            $stmt = $this->Conn->prepare($sql);
            $stmt->bindValue(':member_id', $member_id, PDO::PARAM_INT);
            $stmt->execute();

            $member = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$member) {
                throw new Exception("ไม่พบข้อมูลสมาชิก", 404);
            }

            // // Get member badges
            // $badgeSql = "SELECT 
            //                 b.*,
            //                 mb.member_badge_date
            //             FROM 
            //                 member_badge mb
            //             JOIN 
            //                 badge b ON mb.badge_id = b.badge_id
            //             WHERE 
            //                 mb.member_id = :member_id
            //             ORDER BY 
            //                 mb.member_badge_date DESC";

            // $badgeStmt = $this->Conn->prepare($badgeSql);
            // $badgeStmt->bindValue(':member_id', $member_id, PDO::PARAM_INT);
            // $badgeStmt->execute();
            // $badges = $badgeStmt->fetchAll(PDO::FETCH_ASSOC);

            // $member['badges'] = $badges;

            // Get member easte transaction history
            $wasteSql = "SELECT 
                            wt.*
                        FROM 
                            waste_transaction wt
                        WHERE 
                            wt.member_id = :member_id
                        ORDER BY 
                            wt.created_at DESC";

            $wasteStmt = $this->Conn->prepare($wasteSql);
            $wasteStmt->bindValue(':member_id', $member_id, PDO::PARAM_INT);
            $wasteStmt->execute();
            $wasteTransactions = $wasteStmt->fetchAll(PDO::FETCH_ASSOC);

            $member['waste_transactions'] = $wasteTransactions;

            // member donation history
            $donationSql = "SELECT 
                            d.*
                            FROM 
                            donation d
                            WHERE 
                            d.member_id = :member_id
                        ORDER BY 
                            d.created_at DESC";

            $donationStmt = $this->Conn->prepare($donationSql);
            $donationStmt->bindValue(':member_id', $member_id, PDO::PARAM_INT);
            $donationStmt->execute();
            $donations = $donationStmt->fetchAll(PDO::FETCH_ASSOC);

            $member['donations'] = $donations;

            // member item history (redemption history)
            $memberItemSql = "SELECT 
                            mi.*,
                            di.donation_item_name,
                            di.donation_item_image
                            FROM 
                            member_item mi
                            LEFT JOIN donation_item di ON mi.donation_item_id = di.donation_item_id
                            WHERE 
                            mi.member_id = :member_id
                        ORDER BY 
                            mi.created_at DESC";
            $memberItemStmt = $this->Conn->prepare($memberItemSql);
            $memberItemStmt->bindValue(':member_id', $member_id, PDO::PARAM_INT);
            $memberItemStmt->execute();
            $memberItems = $memberItemStmt->fetchAll(PDO::FETCH_ASSOC);

            $member['member_items'] = $memberItems;
            
            return $member;
        } catch (PDOException $e) {
            throw new Exception($e->getMessage(), (int) $e->getCode());
        } catch (Exception $e) {
            throw $e;
        }
    }
    
    public function GetMemberRoleCount($query = [])
    {
        try {
            $filterClauses = [];
            $params = [];

            if (!empty($query['faculty'])) {
                $filterClauses[] = "m.faculty_id = :faculty_id";
                $params[':faculty_id'] = $query['faculty'];
            }

            if (!empty($query['major'])) {
                $filterClauses[] = "m.major_id = :major_id";
                $params[':major_id'] = $query['major'];
            }

            $whereSql = !empty($filterClauses) ? " WHERE " . implode(" AND ", $filterClauses) : "";

            // Get total members count
            $totalSql = "SELECT COUNT(*) as total_members FROM member m" . $whereSql;
            $totalStmt = $this->Conn->prepare($totalSql);
            $totalStmt->execute($params);
            $totalMembers = $totalStmt->fetch(PDO::FETCH_ASSOC)['total_members'];

            // Get count per role
            $joinSql = "";
            if (!empty($filterClauses)) {
                $joinSql = " AND " . implode(" AND ", $filterClauses);
            }

            $roleSql = "SELECT 
                            r.role_id, 
                            r.role_name, 
                            r.role_name_th,
                            COUNT(m.member_id) as member_count
                        FROM 
                            role r
                        LEFT JOIN 
                            member m ON r.role_id = m.role_id {$joinSql}
                        GROUP BY 
                            r.role_id, r.role_name, r.role_name_th
                        ORDER BY 
                            r.role_id";

            $roleStmt = $this->Conn->prepare($roleSql);
            $roleStmt->execute($params);
            $roleCounts = $roleStmt->fetchAll(PDO::FETCH_ASSOC);

            return [
                'total_members' => (int) $totalMembers,
                'roles' => $roleCounts
            ];

        } catch (PDOException $e) {
            throw new DatabaseException($e->getMessage(), (int) $e->getCode());
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }
}
