<?php
namespace App\Model;
use App\Utils\Database;
use App\Utils\DatabaseException;
use Exception;
use PDO;
use PDOException;

class CenterBranchModel
{
    private static $Database;
    private $Conn;

    public function __construct()
    {
        self::$Database = new Database();
        $this->Conn = self::$Database->connect();
    }

    public function GetAllBranch($query): array
    {
        try {
            $whereClauses = [];
            $params = [];

            if (!empty($query['search'])) {
                $whereClauses[] = "(cb.center_branch_name LIKE :search OR cb.center_branch_id LIKE :search)";
                $params[':search'] = "%" . $query['search'] . "%";
            }

            $whereSql = !empty($whereClauses) ? " WHERE " . implode(" AND ", $whereClauses) : "";

            $sortDirection = 'DESC';
            if (isset($query['order']) && strtoupper($query['order']) === 'ASC') {
                $sortDirection = 'ASC';
            }

            $orderBySql = " ORDER BY cb.center_branch_id " . $sortDirection;

            if (!empty($query['sort_by'])) {
                switch ($query['sort_by']) {
                    case 'name':
                        $orderBySql = " ORDER BY cb.center_branch_name " . $sortDirection;
                        break;
                    case 'point':
                        $orderBySql = " ORDER BY cb.center_branch_point " . $sortDirection;
                        break;
                    case 'member':
                        $orderBySql = " ORDER BY total_member " . $sortDirection;
                        break;
                }
            }

            // --- แก้ไข SQL ตรงนี้ ---
            $sql = "SELECT 
                    cb.*, 
                    COALESCE(mem_count.admin_count, 0) AS admin_count,
                    COALESCE(mem_count.operater_count, 0) AS operater_count
                FROM 
                    center_branch cb
                LEFT JOIN (
                    SELECT center_branch_id,
                           SUM(CASE WHEN role_id = 1 THEN 1 ELSE 0 END) as admin_count,
                           SUM(CASE WHEN role_id = 4 THEN 1 ELSE 0 END) as operater_count,
                           COUNT(member_id) as total_member
                    FROM member
                    GROUP BY center_branch_id
                ) AS mem_count ON cb.center_branch_id = mem_count.center_branch_id
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
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // ส่วนนับจำนวนทั้งหมดสำหรับ Pagination
            if ($isPagination) {
                $sqlCount = "SELECT COUNT(*) AS all_center_branch FROM center_branch cb {$whereSql}";
                $stmtCount = $this->Conn->prepare($sqlCount);
                foreach ($params as $key => $val) {
                    $stmtCount->bindValue($key, $val);
                }
                $stmtCount->execute();
                $total = $stmtCount->fetch(PDO::FETCH_ASSOC)['all_center_branch'];
            } else {
                $total = count($data);
            }

            return [$data, $total];
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage(), 500);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function GetBranchById($id): array
    {
        try {
            $sql = "SELECT 
                    cb.*, 
                    COALESCE(mem_count.admin_count, 0) AS admin_count,
                    COALESCE(mem_count.operater_count, 0) AS operater_count,
                    COALESCE(mem_count.total_member, 0) AS total_member
                FROM center_branch cb
                LEFT JOIN (
                    SELECT center_branch_id,
                           SUM(CASE WHEN role_id = 1 THEN 1 ELSE 0 END) as admin_count,
                           SUM(CASE WHEN role_id = 4 THEN 1 ELSE 0 END) as operater_count,
                           COUNT(member_id) as total_member
                    FROM member
                    GROUP BY center_branch_id
                ) AS mem_count ON cb.center_branch_id = mem_count.center_branch_id
                WHERE cb.center_branch_id = :center_branch_id";
            $stmt = $this->Conn->prepare($sql);
            $stmt->bindValue(':center_branch_id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            return $data ?: [];
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage(), 500);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function CreateBranch(array $data): int
    {
        try {
            if (!is_array($data)) {
                throw new Exception('Invalid data format', 400);
            }

            if (empty($data['center_branch_name'])) {
                throw new Exception('branch name not provided', 400);
            }

            $data["created_at"] = date('Y-m-d H:i:s');

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
                "INSERT INTO 
                    center_branch
                SET
                    {$setClauseString}
                ";
            $stmt = $this->Conn->prepare($sql);
            $stmt->execute($updateData);

            $updated_row = $stmt->rowCount();
            return $updated_row;
        } catch (PDOException $e) {
            $error = DatabaseException::handle($e);
            throw new Exception($error['message'], $error['code']);
            // throw new Exception("Database error: " . $e->getMessage(), 500);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function UpdateBranch($bid, $data): mixed
    {
        try {
            if (empty($data) || !is_array($data) || empty($bid)) {
                throw new Exception('Bad Request =(', 400);
            }

            $data["updated_at"] = date('Y-m-d H:i:s');
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
                "UPDATE 
                    center_branch
                SET 
                    {$setClauseString}
                WHERE
                    center_branch_id = :center_branch_id
                ";

            $stmt = $this->Conn->prepare($sql);
            $stmt->execute(array_merge($updateData, ['center_branch_id' => $bid]));

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

    public function DeleteBranch(array $data): int
    {
        if (empty($data['center_branch_ids'] ?? []) || !is_array($data['center_branch_ids'])) {
            throw new Exception('Bad Request: center_branch_ids is required and must be an array', 400);
        }

        $ids = array_filter($data['center_branch_ids']);

        if (empty($ids)) {
            return 0;
        }

        try {

            $this->Conn->beginTransaction();

            $placeholders = str_repeat('?,', count($ids) - 1) . '?';
            $sql = "DELETE FROM 
                        center_branch 
                    WHERE 
                        center_branch_id 
                    IN ($placeholders)";

            $stmt = $this->Conn->prepare($sql);

            foreach ($ids as $index => $id) {
                $stmt->bindValue($index + 1, $id, PDO::PARAM_INT);
            }

            $stmt->execute();
            $rowCount = $stmt->rowCount();

            $this->Conn->commit();

            return $rowCount;
        } catch (PDOException $e) {
            if ($this->Conn->inTransaction()) {
                $this->Conn->rollBack();
            }
            $error = DatabaseException::handle($e);
            throw new Exception($error['message'], $error['code']);
            // throw new Exception("Database error: " . $e->getMessage(), 500);
        } catch (Exception $e) {
            if ($this->Conn->inTransaction()) {
                $this->Conn->rollBack();
            }
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }
}
