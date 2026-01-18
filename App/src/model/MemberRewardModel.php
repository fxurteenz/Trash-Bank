<?php
namespace App\Model;

use App\Utils\Database;
use App\Utils\DatabaseException;
use Exception;
use PDO;
use PDOException;

class MemberRewardModel
{
    private static $Database;
    private $Conn;

    public function __construct()
    {
        self::$Database = new Database();
        $this->Conn = self::$Database->connect();
    }

    public function GetAll(array $query): array
    {
        try {
            $whereClauses = [];
            $params = [];

            if (!empty($query['search'])) {
                $whereClauses[] = "(m.member_name LIKE :search OR r.reward_name LIKE :search)";
                $params[':search'] = '%' . $query['search'] . '%';
            }

            if (!empty($query['status'])) {
                $whereClauses[] = "mr.member_reward_status = :status";
                $params[':status'] = $query['status'];
            }

            if (!empty($query['date'])) {
                $whereClauses[] = "DATE(mr.member_reward_date) = :date";
                $params[':date'] = $query['date'];
            }

            $whereSql = !empty($whereClauses) ? ' WHERE ' . implode(' AND ', $whereClauses) : '';

            $sql = "SELECT
                        mr.*,
                        m.member_name,
                        r.reward_name,
                        r.reward_point_required,
                        r.reward_stock
                    FROM member_reward mr
                    LEFT JOIN member m ON mr.member_id = m.member_id
                    LEFT JOIN reward r ON mr.reward_id = r.reward_id" .
                    $whereSql .
                    " ORDER BY mr.member_reward_date DESC, mr.member_reward_id DESC";

            $limit = isset($query['limit']) ? (int) $query['limit'] : null;
            $page = isset($query['page']) ? (int) $query['page'] : null;
            if ($limit && $page) {
                $offset = ($page - 1) * $limit;
                $sql .= " LIMIT :limit OFFSET :offset";
            }

            $stmt = $this->Conn->prepare($sql);
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            if ($limit && $page) {
                $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
                $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            }
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $countSql = "SELECT COUNT(*) as total
                        FROM member_reward mr
                        LEFT JOIN member m ON mr.member_id = m.member_id
                        LEFT JOIN reward r ON mr.reward_id = r.reward_id" . $whereSql;
            $countStmt = $this->Conn->prepare($countSql);
            foreach ($params as $key => $value) {
                $countStmt->bindValue($key, $value);
            }
            $countStmt->execute();
            $total = (int) ($countStmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0);

            return ['data' => $data, 'total' => $total];
        } catch (PDOException $e) {
            throw new DatabaseException($e->getMessage(), (int) $e->getCode());
        }
    }

    public function GetById(int $id): array
    {
        try {
            $sql = "SELECT
                        mr.*,
                        m.member_name,
                        r.reward_name
                    FROM member_reward mr
                    LEFT JOIN member m ON mr.member_id = m.member_id
                    LEFT JOIN reward r ON mr.reward_id = r.reward_id
                    WHERE mr.member_reward_id = :id";
            $stmt = $this->Conn->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$row) {
                throw new Exception('Member reward not found', 404);
            }
            return $row;
        } catch (PDOException $e) {
            throw new DatabaseException($e->getMessage(), (int) $e->getCode());
        }
    }

    public function Create(array $data): array
    {
        try {
            $this->Conn->beginTransaction();

            $memberId = (int) $data['member_id'];
            $rewardId = (int) $data['reward_id'];
            $qty = isset($data['member_reward_qty']) && $data['member_reward_qty'] !== '' ? (int) $data['member_reward_qty'] : 1;

            // Lock and fetch member
            $memberSql = "SELECT (member_waste_point + member_goodness_point) as total_points FROM member WHERE member_id = :member_id FOR UPDATE";
            $memberStmt = $this->Conn->prepare($memberSql);
            $memberStmt->bindValue(':member_id', $memberId, PDO::PARAM_INT);
            $memberStmt->execute();
            $member = $memberStmt->fetch(PDO::FETCH_ASSOC);

            if (!$member) {
                throw new Exception("Member not found", 404);
            }

            // Lock and fetch reward
            $rewardSql = "SELECT * FROM reward WHERE reward_id = :reward_id FOR UPDATE";
            $rewardStmt = $this->Conn->prepare($rewardSql);
            $rewardStmt->bindValue(':reward_id', $rewardId, PDO::PARAM_INT);
            $rewardStmt->execute();
            $reward = $rewardStmt->fetch(PDO::FETCH_ASSOC);

            if (!$reward) {
                throw new Exception("Reward not found", 404);
            }

            if (!$reward['reward_active']) {
                throw new Exception("Reward is not active", 400);
            }

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
            $insertStmt->bindValue(':member_id', $memberId, PDO::PARAM_INT);
            $insertStmt->bindValue(':reward_id', $rewardId, PDO::PARAM_INT);
            $insertStmt->bindValue(':qty', $qty, PDO::PARAM_INT);
            $insertStmt->bindValue(':points', $totalPoints, PDO::PARAM_INT);
            $insertStmt->execute();

            $insertId = (int) $this->Conn->lastInsertId();

            // Update member points
            $updateMemberSql = "UPDATE member 
                               SET member_waste_point = GREATEST(0, member_waste_point - :points),
                                   member_goodness_point = GREATEST(0, member_goodness_point - GREATEST(0, :points - member_waste_point))
                               WHERE member_id = :member_id";
            $updateMemberStmt = $this->Conn->prepare($updateMemberSql);
            $updateMemberStmt->bindValue(':points', $totalPoints, PDO::PARAM_INT);
            $updateMemberStmt->bindValue(':member_id', $memberId, PDO::PARAM_INT);
            $updateMemberStmt->execute();

            // Update reward stock
            $updateRewardSql = "UPDATE reward SET reward_stock = reward_stock - :qty WHERE reward_id = :reward_id";
            $updateRewardStmt = $this->Conn->prepare($updateRewardSql);
            $updateRewardStmt->bindValue(':qty', $qty, PDO::PARAM_INT);
            $updateRewardStmt->bindValue(':reward_id', $rewardId, PDO::PARAM_INT);
            $updateRewardStmt->execute();

            $this->Conn->commit();

            return $this->GetById($insertId);
        } catch (PDOException $e) {
            $this->Conn->rollBack();
            throw new DatabaseException($e->getMessage(), (int) $e->getCode());
        } catch (Exception $e) {
            $this->Conn->rollBack();
            throw $e;
        }
    }

    public function Update(int $id, array $data): array
    {
        try {
            $fields = [];
            $params = [':id' => $id];

            if (array_key_exists('member_reward_status', $data)) {
                $fields[] = 'member_reward_status = :status';
                $params[':status'] = $data['member_reward_status'];
            }

            if (array_key_exists('member_reward_redeem_date', $data) && $data['member_reward_redeem_date'] !== '') {
                $fields[] = 'member_reward_redeem_date = :redeem_date';
                $params[':redeem_date'] = $data['member_reward_redeem_date'];
            }

            if (empty($fields)) {
                throw new Exception('No fields to update', 400);
            }

            $sql = 'UPDATE member_reward SET ' . implode(', ', $fields) . ' WHERE member_reward_id = :id';
            $stmt = $this->Conn->prepare($sql);

            foreach ($params as $key => $value) {
                if ($value === null) {
                    $stmt->bindValue($key, null, PDO::PARAM_NULL);
                    continue;
                }
                $stmt->bindValue($key, $value);
            }

            $stmt->execute();
            if ($stmt->rowCount() === 0) {
                throw new Exception('Member reward not found or no changes made', 404);
            }

            return $this->GetById($id);
        } catch (PDOException $e) {
            throw new DatabaseException($e->getMessage(), (int) $e->getCode());
        }
    }
}
