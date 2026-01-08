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

    public function GetMemberProfile($member_id): array
    {
        try {
            $sql = "SELECT 
                        m.*, 
                        f.faculty_name,
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
                throw new Exception("Member not found", 404);
            }
            
            // Get member badges
            $badgeSql = "SELECT 
                            b.*,
                            mb.member_badge_date
                        FROM 
                            member_badge mb
                        JOIN 
                            badge b ON mb.badge_id = b.badge_id
                        WHERE 
                            mb.member_id = :member_id
                        ORDER BY 
                            mb.member_badge_date DESC";
            
            $badgeStmt = $this->Conn->prepare($badgeSql);
            $badgeStmt->bindValue(':member_id', $member_id, PDO::PARAM_INT);
            $badgeStmt->execute();
            $badges = $badgeStmt->fetchAll(PDO::FETCH_ASSOC);
            
            $member['badges'] = $badges;
            
            return $member;
        } catch (PDOException $e) {
            throw new DatabaseException($e->getMessage(), (int) $e->getCode());
        }
    }

    public function RedeemReward($member_id, $data): array
    {
        try {
            $this->Conn->beginTransaction();
            
            // Get member's current points
            $memberSql = "SELECT (member_waste_point + member_goodness_point) as total_points FROM member WHERE member_id = :member_id FOR UPDATE";
            $memberStmt = $this->Conn->prepare($memberSql);
            $memberStmt->bindValue(':member_id', $member_id, PDO::PARAM_INT);
            $memberStmt->execute();
            $member = $memberStmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$member) {
                throw new Exception("Member not found", 404);
            }
            
            // Get reward details
            $rewardSql = "SELECT * FROM reward WHERE reward_id = :reward_id FOR UPDATE";
            $rewardStmt = $this->Conn->prepare($rewardSql);
            $rewardStmt->bindValue(':reward_id', $data['reward_id'], PDO::PARAM_INT);
            $rewardStmt->execute();
            $reward = $rewardStmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$reward) {
                throw new Exception("Reward not found", 404);
            }
            
            if (!$reward['reward_active']) {
                throw new Exception("Reward is not active", 400);
            }
            
            $qty = $data['quantity'] ?? 1;
            $totalPoints = $reward['reward_point_required'] * $qty;
            
            // Check if member has enough points
            if ($member['total_points'] < $totalPoints) {
                throw new Exception("Insufficient points", 400);
            }
            
            // Check if reward has enough stock
            if ($reward['reward_stock'] < $qty) {
                throw new Exception("Insufficient stock", 400);
            }
            
            // Create member_reward record
            $insertSql = "INSERT INTO member_reward (
                            member_id, 
                            reward_id, 
                            member_reward_date, 
                            member_reward_qty, 
                            member_reward_point_used, 
                            member_reward_status
                        ) VALUES (
                            :member_id, 
                            :reward_id, 
                            CURDATE(), 
                            :qty, 
                            :points, 
                            'pending'
                        )";
            
            $insertStmt = $this->Conn->prepare($insertSql);
            $insertStmt->bindValue(':member_id', $member_id, PDO::PARAM_INT);
            $insertStmt->bindValue(':reward_id', $data['reward_id'], PDO::PARAM_INT);
            $insertStmt->bindValue(':qty', $qty, PDO::PARAM_INT);
            $insertStmt->bindValue(':points', $totalPoints, PDO::PARAM_INT);
            $insertStmt->execute();
            
            // Update member points (deduct from waste_point first, then goodness_point if needed)
            $updateMemberSql = "UPDATE member 
                               SET member_waste_point = GREATEST(0, member_waste_point - :points),
                                   member_goodness_point = GREATEST(0, member_goodness_point - GREATEST(0, :points - member_waste_point))
                               WHERE member_id = :member_id";
            $updateMemberStmt = $this->Conn->prepare($updateMemberSql);
            $updateMemberStmt->bindValue(':points', $totalPoints, PDO::PARAM_INT);
            $updateMemberStmt->bindValue(':member_id', $member_id, PDO::PARAM_INT);
            $updateMemberStmt->execute();
            
            // Update reward stock
            $updateRewardSql = "UPDATE reward SET reward_stock = reward_stock - :qty WHERE reward_id = :reward_id";
            $updateRewardStmt = $this->Conn->prepare($updateRewardSql);
            $updateRewardStmt->bindValue(':qty', $qty, PDO::PARAM_INT);
            $updateRewardStmt->bindValue(':reward_id', $data['reward_id'], PDO::PARAM_INT);
            $updateRewardStmt->execute();
            
            $this->Conn->commit();
            
            return [
                'member_reward_id' => $this->Conn->lastInsertId(),
                'reward_name' => $reward['reward_name'],
                'quantity' => $qty,
                'points_used' => $totalPoints,
                'remaining_points' => $member['total_points'] - $totalPoints
            ];
        } catch (PDOException $e) {
            $this->Conn->rollBack();
            throw new DatabaseException($e->getMessage(), (int) $e->getCode());
        } catch (Exception $e) {
            $this->Conn->rollBack();
            throw $e;
        }
    }

    public function GetMemberDashboard($member_id): array
    {
        try {
            // Get member basic info
            $memberSql = "SELECT 
                            m.member_id,
                            m.member_name,
                            m.member_waste_point,
                            m.member_goodness_point,
                            (m.member_waste_point + m.member_goodness_point) as total_points,
                            m.member_email,
                            m.member_phone,
                            f.faculty_name,
                            maj.major_name,
                            r.role_name
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
            
            $memberStmt = $this->Conn->prepare($memberSql);
            $memberStmt->bindValue(':member_id', $member_id, PDO::PARAM_INT);
            $memberStmt->execute();
            $member = $memberStmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$member) {
                throw new Exception("Member not found", 404);
            }

            // Get waste statistics
            $statsSql = "SELECT 
                            COUNT(*) as total_transactions,
                            COALESCE(SUM(wt.waste_transaction_weight), 0) as total_weight,
                            COALESCE(SUM(wt.waste_transaction_weight * 2.5), 0) as carbon_saved
                        FROM 
                            waste_transaction wt
                        WHERE 
                            wt.member_id = :member_id";
            
            $statsStmt = $this->Conn->prepare($statsSql);
            $statsStmt->bindValue(':member_id', $member_id, PDO::PARAM_INT);
            $statsStmt->execute();
            $stats = $statsStmt->fetch(PDO::FETCH_ASSOC);

            // Get recent activities (waste transactions + rewards)
            $activitiesSql = "
                SELECT 
                    'waste' as activity_type,
                    wt.waste_transaction_id as id,
                    wt.waste_transaction_weight as weight,
                    wt.waste_transaction_member_point as points,
                    wt.waste_transaction_date as activity_date,
                    wtype.waste_type_name as description
                FROM 
                    waste_transaction wt
                LEFT JOIN
                    waste_type wtype ON wt.waste_transaction_waste_type = wtype.waste_type_id
                WHERE 
                    wt.member_id = :member_id
                
                UNION ALL
                
                SELECT 
                    'reward' as activity_type,
                    mr.member_reward_id as id,
                    mr.member_reward_qty as weight,
                    -mr.member_reward_point_used as points,
                    mr.member_reward_date as activity_date,
                    r.reward_name as description
                FROM 
                    member_reward mr
                LEFT JOIN
                    reward r ON mr.reward_id = r.reward_id
                WHERE 
                    mr.member_id = :member_id
                
                ORDER BY activity_date DESC
                LIMIT 10";
            
            $activitiesStmt = $this->Conn->prepare($activitiesSql);
            $activitiesStmt->bindValue(':member_id', $member_id, PDO::PARAM_INT);
            $activitiesStmt->execute();
            $activities = $activitiesStmt->fetchAll(PDO::FETCH_ASSOC);

            // Get member badges
            $badgesSql = "SELECT 
                            b.badge_id,
                            b.badge_name,
                            b.badge_description,
                            b.badge_image,
                            b.badge_type,
                            mb.member_badge_date
                        FROM 
                            member_badge mb
                        JOIN 
                            badge b ON mb.badge_id = b.badge_id
                        WHERE 
                            mb.member_id = :member_id
                        ORDER BY 
                            mb.member_badge_date DESC";
            
            $badgesStmt = $this->Conn->prepare($badgesSql);
            $badgesStmt->bindValue(':member_id', $member_id, PDO::PARAM_INT);
            $badgesStmt->execute();
            $badges = $badgesStmt->fetchAll(PDO::FETCH_ASSOC);

            // Get available rewards (active and in stock)
            $rewardsSql = "SELECT 
                            reward_id,
                            reward_name,
                            reward_description,
                            reward_point_required,
                            reward_stock,
                            reward_image
                        FROM 
                            reward
                        WHERE 
                            reward_active = 1 
                            AND reward_stock > 0
                        ORDER BY 
                            reward_point_required ASC
                        LIMIT 6";
            
            $rewardsStmt = $this->Conn->prepare($rewardsSql);
            $rewardsStmt->execute();
            $rewards = $rewardsStmt->fetchAll(PDO::FETCH_ASSOC);

            // Calculate level progress (assuming 1000 points per level)
            $pointsPerLevel = 1000;
            $totalPoints = $member['total_points'] ?? 0;
            $currentLevel = floor($totalPoints / $pointsPerLevel) + 1;
            $pointsInCurrentLevel = $totalPoints % $pointsPerLevel;
            $levelProgress = ($pointsInCurrentLevel / $pointsPerLevel) * 100;

            return [
                'member_info' => [
                    'id' => $member['member_id'],
                    'name' => $member['member_name'],
                    'email' => $member['member_email'],
                    'phone' => $member['member_phone'],
                    'waste_points' => (float) $member['member_waste_point'],
                    'goodness_points' => (float) $member['member_goodness_point'],
                    'total_points' => (float) $totalPoints,
                    'level' => (int) $currentLevel,
                    'level_progress' => round($levelProgress, 2),
                    'faculty' => $member['faculty_name'],
                    'major' => $member['major_name'],
                    'role' => $member['role_name']
                ],
                'statistics' => [
                    'total_transactions' => (int) $stats['total_transactions'],
                    'total_weight' => (float) $stats['total_weight'],
                    'carbon_saved' => (float) $stats['carbon_saved']
                ],
                'recent_activities' => $activities,
                'badges' => $badges,
                'rewards_available' => $rewards
            ];
        } catch (PDOException $e) {
            throw new DatabaseException($e->getMessage(), (int) $e->getCode());
        }
    }
}
