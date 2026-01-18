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

    public function GetAllMemberRewards(array $query): array
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

            $whereSql = !empty($whereClauses) ? ' WHERE ' . implode(' AND ', $whereClauses) : '';

            $sql = "SELECT
                        mr.*,
                        m.member_name,
                        r.reward_name,
                        r.reward_required_point
                    FROM member_reward mr
                    LEFT JOIN member m ON mr.member_id = m.member_id
                    LEFT JOIN reward r ON mr.reward_id = r.reward_id" .
                    $whereSql .
                    " ORDER BY mr.created_at DESC, mr.member_reward_id DESC";

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

    public function GetMemberRewardById(int $id): array
    {
        try {
            $sql = "SELECT
                        mr.*,
                        m.member_name,
                        r.reward_name,
                        r.reward_required_point
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

    public function CreateMemberReward(array $data): array
    {
        try {
            $memberId = isset($data['member_id']) && $data['member_id'] !== '' ? (int) $data['member_id'] : null;
            $rewardId = isset($data['reward_id']) && $data['reward_id'] !== '' ? (int) $data['reward_id'] : null;
            $quantity = isset($data['member_reward_quantity']) ? (int) $data['member_reward_quantity'] : 1;
            $status = $data['member_reward_status'] ?? 'pending';

            if (!$memberId) {
                throw new Exception('Member ID is required', 400);
            }
            if (!$rewardId) {
                throw new Exception('Reward ID is required', 400);
            }

            // Get member's points and reward's required points
            $memberSql = "SELECT member_goodness_point FROM member WHERE member_id = :mid FOR UPDATE";
            $memberStmt = $this->Conn->prepare($memberSql);
            $memberStmt->bindValue(':mid', $memberId, PDO::PARAM_INT);
            $memberStmt->execute();
            $member = $memberStmt->fetch(PDO::FETCH_ASSOC);

            if (!$member) {
                throw new Exception('Member not found', 404);
            }

            $rewardSql = "SELECT reward_required_point, reward_stock FROM reward WHERE reward_id = :rid FOR UPDATE";
            $rewardStmt = $this->Conn->prepare($rewardSql);
            $rewardStmt->bindValue(':rid', $rewardId, PDO::PARAM_INT);
            $rewardStmt->execute();
            $reward = $rewardStmt->fetch(PDO::FETCH_ASSOC);

            if (!$reward) {
                throw new Exception('Reward not found', 404);
            }

            $totalPointsNeeded = $reward['reward_required_point'] * $quantity;
            $memberPoints = (int) $member['member_goodness_point'];

            if ($memberPoints < $totalPointsNeeded) {
                throw new Exception('Member does not have enough points', 400);
            }

            if ($reward['reward_stock'] < $quantity) {
                throw new Exception('Not enough reward stock', 400);
            }

            // Start transaction
            $this->Conn->beginTransaction();

            try {
                // Create member_reward record
                $sql = "INSERT INTO member_reward (
                            member_id,
                            reward_id,
                            member_reward_quantity,
                            member_reward_status,
                            created_at
                        ) VALUES (
                            :member_id,
                            :reward_id,
                            :quantity,
                            :status,
                            NOW()
                        )";
                
                $stmt = $this->Conn->prepare($sql);
                $stmt->bindValue(':member_id', $memberId, PDO::PARAM_INT);
                $stmt->bindValue(':reward_id', $rewardId, PDO::PARAM_INT);
                $stmt->bindValue(':quantity', $quantity, PDO::PARAM_INT);
                $stmt->bindValue(':status', $status);
                $stmt->execute();

                $id = (int) $this->Conn->lastInsertId();

                // Deduct member points if status is completed
                if ($status === 'completed') {
                    $updateMemberSql = "UPDATE member SET member_goodness_point = member_goodness_point - :points WHERE member_id = :mid";
                    $updateMemberStmt = $this->Conn->prepare($updateMemberSql);
                    $updateMemberStmt->bindValue(':points', $totalPointsNeeded, PDO::PARAM_INT);
                    $updateMemberStmt->bindValue(':mid', $memberId, PDO::PARAM_INT);
                    $updateMemberStmt->execute();

                    // Deduct reward stock
                    $updateRewardSql = "UPDATE reward SET reward_stock = reward_stock - :qty WHERE reward_id = :rid";
                    $updateRewardStmt = $this->Conn->prepare($updateRewardSql);
                    $updateRewardStmt->bindValue(':qty', $quantity, PDO::PARAM_INT);
                    $updateRewardStmt->bindValue(':rid', $rewardId, PDO::PARAM_INT);
                    $updateRewardStmt->execute();
                }

                $this->Conn->commit();

                return [
                    'member_reward_id' => $id,
                    'success' => true
                ];
            } catch (Exception $e) {
                $this->Conn->rollBack();
                throw $e;
            }
        } catch (PDOException $e) {
            throw new DatabaseException($e->getMessage(), (int) $e->getCode());
        }
    }

    public function UpdateMemberReward(int $id, array $data): array
    {
        try {
            $status = $data['member_reward_status'] ?? null;

            if (!$status) {
                throw new Exception('Status is required', 400);
            }

            // Get current redemption record
            $currentSql = "SELECT * FROM member_reward WHERE member_reward_id = :id";
            $currentStmt = $this->Conn->prepare($currentSql);
            $currentStmt->bindValue(':id', $id, PDO::PARAM_INT);
            $currentStmt->execute();
            $current = $currentStmt->fetch(PDO::FETCH_ASSOC);

            if (!$current) {
                throw new Exception('Member reward not found', 404);
            }

            $this->Conn->beginTransaction();

            try {
                // Update status
                $sql = "UPDATE member_reward SET member_reward_status = :status WHERE member_reward_id = :id";
                $stmt = $this->Conn->prepare($sql);
                $stmt->bindValue(':status', $status);
                $stmt->bindValue(':id', $id, PDO::PARAM_INT);
                $stmt->execute();

                // If changing to completed, deduct points and stock
                if ($status === 'completed' && $current['member_reward_status'] !== 'completed') {
                    $memberId = $current['member_id'];
                    $rewardId = $current['reward_id'];
                    $quantity = $current['member_reward_quantity'];

                    // Get reward points needed
                    $rewardSql = "SELECT reward_required_point FROM reward WHERE reward_id = :rid";
                    $rewardStmt = $this->Conn->prepare($rewardSql);
                    $rewardStmt->bindValue(':rid', $rewardId, PDO::PARAM_INT);
                    $rewardStmt->execute();
                    $reward = $rewardStmt->fetch(PDO::FETCH_ASSOC);

                    $totalPoints = $reward['reward_required_point'] * $quantity;

                    // Deduct member points
                    $updateMemberSql = "UPDATE member SET member_goodness_point = member_goodness_point - :points WHERE member_id = :mid";
                    $updateMemberStmt = $this->Conn->prepare($updateMemberSql);
                    $updateMemberStmt->bindValue(':points', $totalPoints, PDO::PARAM_INT);
                    $updateMemberStmt->bindValue(':mid', $memberId, PDO::PARAM_INT);
                    $updateMemberStmt->execute();

                    // Deduct reward stock
                    $updateRewardSql = "UPDATE reward SET reward_stock = reward_stock - :qty WHERE reward_id = :rid";
                    $updateRewardStmt = $this->Conn->prepare($updateRewardSql);
                    $updateRewardStmt->bindValue(':qty', $quantity, PDO::PARAM_INT);
                    $updateRewardStmt->bindValue(':rid', $rewardId, PDO::PARAM_INT);
                    $updateRewardStmt->execute();
                }
                // If changing from completed to cancelled, restore points and stock
                elseif ($status === 'cancelled' && $current['member_reward_status'] === 'completed') {
                    $memberId = $current['member_id'];
                    $rewardId = $current['reward_id'];
                    $quantity = $current['member_reward_quantity'];

                    // Get reward points needed
                    $rewardSql = "SELECT reward_required_point FROM reward WHERE reward_id = :rid";
                    $rewardStmt = $this->Conn->prepare($rewardSql);
                    $rewardStmt->bindValue(':rid', $rewardId, PDO::PARAM_INT);
                    $rewardStmt->execute();
                    $reward = $rewardStmt->fetch(PDO::FETCH_ASSOC);

                    $totalPoints = $reward['reward_required_point'] * $quantity;

                    // Restore member points
                    $updateMemberSql = "UPDATE member SET member_goodness_point = member_goodness_point + :points WHERE member_id = :mid";
                    $updateMemberStmt = $this->Conn->prepare($updateMemberSql);
                    $updateMemberStmt->bindValue(':points', $totalPoints, PDO::PARAM_INT);
                    $updateMemberStmt->bindValue(':mid', $memberId, PDO::PARAM_INT);
                    $updateMemberStmt->execute();

                    // Restore reward stock
                    $updateRewardSql = "UPDATE reward SET reward_stock = reward_stock + :qty WHERE reward_id = :rid";
                    $updateRewardStmt = $this->Conn->prepare($updateRewardSql);
                    $updateRewardStmt->bindValue(':qty', $quantity, PDO::PARAM_INT);
                    $updateRewardStmt->bindValue(':rid', $rewardId, PDO::PARAM_INT);
                    $updateRewardStmt->execute();
                }

                $this->Conn->commit();

                return ['success' => true];
            } catch (Exception $e) {
                $this->Conn->rollBack();
                throw $e;
            }
        } catch (PDOException $e) {
            throw new DatabaseException($e->getMessage(), (int) $e->getCode());
        }
    }

    public function DeleteMemberReward(int $id): array
    {
        try {
            // Get the record first
            $getSql = "SELECT * FROM member_reward WHERE member_reward_id = :id";
            $getStmt = $this->Conn->prepare($getSql);
            $getStmt->bindValue(':id', $id, PDO::PARAM_INT);
            $getStmt->execute();
            $record = $getStmt->fetch(PDO::FETCH_ASSOC);

            if (!$record) {
                throw new Exception('Member reward not found', 404);
            }

            // If completed, restore points and stock
            if ($record['member_reward_status'] === 'completed') {
                $rewardSql = "SELECT reward_required_point FROM reward WHERE reward_id = :rid";
                $rewardStmt = $this->Conn->prepare($rewardSql);
                $rewardStmt->bindValue(':rid', $record['reward_id'], PDO::PARAM_INT);
                $rewardStmt->execute();
                $reward = $rewardStmt->fetch(PDO::FETCH_ASSOC);

                $totalPoints = $reward['reward_required_point'] * $record['member_reward_quantity'];

                // Restore member points
                $restoreMemberSql = "UPDATE member SET member_goodness_point = member_goodness_point + :points WHERE member_id = :mid";
                $restoreMemberStmt = $this->Conn->prepare($restoreMemberSql);
                $restoreMemberStmt->bindValue(':points', $totalPoints, PDO::PARAM_INT);
                $restoreMemberStmt->bindValue(':mid', $record['member_id'], PDO::PARAM_INT);
                $restoreMemberStmt->execute();

                // Restore reward stock
                $restoreRewardSql = "UPDATE reward SET reward_stock = reward_stock + :qty WHERE reward_id = :rid";
                $restoreRewardStmt = $this->Conn->prepare($restoreRewardSql);
                $restoreRewardStmt->bindValue(':qty', $record['member_reward_quantity'], PDO::PARAM_INT);
                $restoreRewardStmt->bindValue(':rid', $record['reward_id'], PDO::PARAM_INT);
                $restoreRewardStmt->execute();
            }

            // Delete the record
            $deleteSql = "DELETE FROM member_reward WHERE member_reward_id = :id";
            $deleteStmt = $this->Conn->prepare($deleteSql);
            $deleteStmt->bindValue(':id', $id, PDO::PARAM_INT);
            $deleteStmt->execute();

            return ['success' => true];
        } catch (PDOException $e) {
            throw new DatabaseException($e->getMessage(), (int) $e->getCode());
        }
    }
}
