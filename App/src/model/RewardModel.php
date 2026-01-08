<?php
namespace App\Model;
use App\Utils\Database;
use App\Utils\DatabaseException;
use Exception;
use PDO;
use PDOException;

class RewardModel
{
    private static $Database;
    private $Conn;
    
    public function __construct()
    {
        self::$Database = new Database();
        $this->Conn = self::$Database->connect();
    }

    public function GetAllRewards($query): array
    {
        try {
            $whereClauses = [];
            $params = [];

            if (isset($query['active'])) {
                $whereClauses[] = "reward_active = :active";
                $params[':active'] = $query['active'];
            }

            if (!empty($query['search'])) {
                $whereClauses[] = "(reward_name LIKE :search OR reward_description LIKE :search)";
                $params[':search'] = "%" . $query['search'] . "%";
            }

            $whereSql = !empty($whereClauses) ? " WHERE " . implode(" AND ", $whereClauses) : "";

            $sql = "SELECT * FROM reward" . $whereSql . " ORDER BY reward_id DESC";

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

            $countSql = "SELECT COUNT(*) as total FROM reward" . $whereSql;
            $countStmt = $this->Conn->prepare($countSql);
            foreach ($params as $key => $value) {
                $countStmt->bindValue($key, $value);
            }
            $countStmt->execute();
            $total = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];

            return [
                'data' => $data,
                'total' => (int) $total
            ];
        } catch (PDOException $e) {
            throw new DatabaseException($e->getMessage(), (int) $e->getCode());
        }
    }

    public function GetRewardById($id): array
    {
        try {
            $sql = "SELECT * FROM reward WHERE reward_id = :id";
            $stmt = $this->Conn->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            $reward = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$reward) {
                throw new Exception("Reward not found", 404);
            }
            
            return $reward;
        } catch (PDOException $e) {
            throw new DatabaseException($e->getMessage(), (int) $e->getCode());
        }
    }

    public function CreateReward($data): array
    {
        try {
            $sql = "INSERT INTO reward (
                        reward_name, 
                        reward_description, 
                        reward_point_required, 
                        reward_stock, 
                        reward_image, 
                        reward_active
                    ) VALUES (
                        :reward_name, 
                        :reward_description, 
                        :reward_point_required, 
                        :reward_stock, 
                        :reward_image, 
                        :reward_active
                    )";
            
            $stmt = $this->Conn->prepare($sql);
            $stmt->bindValue(':reward_name', $data['reward_name']);
            $stmt->bindValue(':reward_description', $data['reward_description'] ?? null);
            $stmt->bindValue(':reward_point_required', $data['reward_point_required'] ?? 0, PDO::PARAM_INT);
            $stmt->bindValue(':reward_stock', $data['reward_stock'] ?? 0, PDO::PARAM_INT);
            $stmt->bindValue(':reward_image', $data['reward_image'] ?? null);
            $stmt->bindValue(':reward_active', $data['reward_active'] ?? 1, PDO::PARAM_INT);
            
            $stmt->execute();
            $id = $this->Conn->lastInsertId();
            
            return $this->GetRewardById($id);
        } catch (PDOException $e) {
            throw new DatabaseException($e->getMessage(), (int) $e->getCode());
        }
    }

    public function UpdateReward($id, $data): array
    {
        try {
            $fields = [];
            $params = [':id' => $id];

            if (isset($data['reward_name'])) {
                $fields[] = "reward_name = :reward_name";
                $params[':reward_name'] = $data['reward_name'];
            }
            if (isset($data['reward_description'])) {
                $fields[] = "reward_description = :reward_description";
                $params[':reward_description'] = $data['reward_description'];
            }
            if (isset($data['reward_point_required'])) {
                $fields[] = "reward_point_required = :reward_point_required";
                $params[':reward_point_required'] = $data['reward_point_required'];
            }
            if (isset($data['reward_stock'])) {
                $fields[] = "reward_stock = :reward_stock";
                $params[':reward_stock'] = $data['reward_stock'];
            }
            if (isset($data['reward_image'])) {
                $fields[] = "reward_image = :reward_image";
                $params[':reward_image'] = $data['reward_image'];
            }
            if (isset($data['reward_active'])) {
                $fields[] = "reward_active = :reward_active";
                $params[':reward_active'] = $data['reward_active'];
            }

            if (empty($fields)) {
                throw new Exception("No fields to update", 400);
            }

            $sql = "UPDATE reward SET " . implode(", ", $fields) . " WHERE reward_id = :id";
            $stmt = $this->Conn->prepare($sql);
            
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            
            $stmt->execute();
            
            if ($stmt->rowCount() === 0) {
                throw new Exception("Reward not found or no changes made", 404);
            }
            
            return $this->GetRewardById($id);
        } catch (PDOException $e) {
            throw new DatabaseException($e->getMessage(), (int) $e->getCode());
        }
    }

    public function DeleteReward($data): int
    {
        try {
            $ids = is_array($data['reward_id']) ? $data['reward_id'] : [$data['reward_id']];
            
            $placeholders = implode(',', array_fill(0, count($ids), '?'));
            $sql = "DELETE FROM reward WHERE reward_id IN ($placeholders)";
            
            $stmt = $this->Conn->prepare($sql);
            $stmt->execute($ids);
            
            return $stmt->rowCount();
        } catch (PDOException $e) {
            throw new DatabaseException($e->getMessage(), (int) $e->getCode());
        }
    }
}
