<?php
namespace App\Model;

use App\Utils\Database;
use App\Utils\DatabaseException;

use Exception;
use PDO;
use PDOException;

class WasteTypeModel
{
    private static $Database;
    private $Conn;

    public function __construct()
    {
        self::$Database = new Database();
        $this->Conn = self::$Database->connect();
    }

    public function GetAllWasteType($query): array
    {
        try {
            $whereClauses = [];
            $params = [];

            if (!empty($query['search'])) {
                $whereClauses[] = "wt.waste_type_name LIKE :search";
                $params[':search'] = "%" . $query['search'] . "%";
            }

            $whereSql = !empty($whereClauses) ? " WHERE " . implode(" AND ", $whereClauses) : "";

            $sql = "SELECT 
                    wt.*, 
                    wc.waste_category_name
                FROM 
                    waste_type wt
                LEFT JOIN 
                    waste_category wc ON wt.waste_category_id = wc.waste_category_id
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
            $wasteType = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($isPagination) {
                $sqlCount = "SELECT COUNT(*) AS allType FROM waste_type wt{$whereSql}";
                $stmtCount = $this->Conn->prepare($sqlCount);
                foreach ($params as $key => $val) {
                    $stmtCount->bindValue($key, $val);
                }
                $stmtCount->execute();
                $total = $stmtCount->fetch(PDO::FETCH_ASSOC)['allType'];
            } else {
                $total = count($wasteType);
            }

            return ["data" => $wasteType, "total" => $total];

        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage(), 500);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function GetWasteTypeByCategory($query, $cid): array
    {
        try {
            $isPagination = isset($query['page']) && isset($query['limit']);

            $sql = "SELECT 
                    wt.*, 
                    wc.waste_category_name
                FROM 
                    waste_type wt
                LEFT JOIN 
                    waste_category wc ON wt.waste_category_id = wc.waste_category_id
                WHERE
                    wt.waste_category_id = :waste_category_id";

            if ($isPagination) {
                $page = (int) $query['page'];
                $limit = (int) $query['limit'];
                $offset = ($page - 1) * $limit;
                $sql .= " LIMIT :limit OFFSET :offset";
            }

            $stmt = $this->Conn->prepare($sql);
            $stmt->bindValue(':waste_category_id', $cid, PDO::PARAM_INT);

            if ($isPagination) {
                $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
                $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            }

            $stmt->execute();
            $wasteType = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($isPagination) {
                $sqlCount = 'SELECT COUNT(*) AS allType FROM waste_type';
                $stmtCount = $this->Conn->prepare($sqlCount);
                $stmtCount->execute();
                $total = $stmtCount->fetch(PDO::FETCH_ASSOC)['allType'];
            } else {
                $total = count($wasteType);
            }

            return ["data" => $wasteType, "total" => $total];

        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage(), 500);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function CreateWasteType(array $data): array
    {
        try {

            if (!is_array($data)) {
                throw new Exception('Invalid data format', 400);
            }

            if (
                empty($data['waste_category_id']) || empty($data['waste_type_name']) ||
                empty($data['waste_type_price']) || empty($data['waste_type_co2'])
            ) {
                throw new Exception('ลองใหม่อีกครั้ง, กรุณากรอกข้อมูลให้ครบถ้วน', 400);
            }

            $data["created_at"] = date("Y-m-d H:i:s");
            $data["updated_at"] = date("Y-m-d H:i:s");

            $setClauses = [];
            $updateData = [];
            foreach ($data as $column => $value) {
                if (isset($value) && $value !== '') {
                    $setClauses[] = "`{$column}` = :{$column}";
                    $updateData[$column] = $value;
                }
            }
            $setClauseString = implode(', ', $setClauses);

            $sql =
                "INSERT INTO 
                    waste_type
                SET
                    {$setClauseString}
                ";
            $stmt = $this->Conn->prepare($sql);
            $stmt->execute($updateData);
            $id = $this->Conn->lastInsertId();

            return [
                "waste_type_name" => $data["waste_type_price"],
                "waste_type_id" => $id,
                "waste_type_price" => $data["waste_type_price"],
                "waste_type_co2" => $data["waste_type_co2"],
                "waste_category_id" => $data["waste_category_id"],
                "created_at" => $data["created_at"],
                "updated_at" => $data["updated_at"]
            ];
        } catch (PDOException $e) {
            $erresult = DatabaseException::handle($e);
            throw new Exception($erresult['message'], $erresult['code'] ?: 500);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function UpdateWasteType($id, $data): array
    {
        try {
            if ((empty($data) && !is_array($data)) || empty($id)) {
                throw new Exception('Bad Request =(', 400);
            }

            $data["updated_at"] = date("Y-m-d H:i:s");

            $setClauses = [];
            $updateData = [];
            foreach ($data as $column => $value) {
                if (isset($value)) {
                    $setClauses[] = "`{$column}` = :{$column}";
                    $updateData[$column] = $value;
                }
            }

            if (empty($setClauses)) {
                return ['data' => $data, 'total' => 0];
            }

            $setClauseString = implode(', ', $setClauses);

            $sql =
                "UPDATE 
                    waste_type
                SET 
                    {$setClauseString}
                WHERE
                    waste_type_id = :waste_type_id
                ";

            $stmt = $this->Conn->prepare($sql);
            $stmt->execute(array_merge($updateData, ['waste_type_id' => $id]));
            $result = $stmt->rowCount();

            return ['data' => $data, 'total' => $result];
        } catch (PDOException $e) {
            $erresult = DatabaseException::handle($e);
            throw new Exception($erresult['message'], $erresult['code'] ?: 500);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function ToggleActiveWasteType(array $data): array
    {
        if (empty($data['waste_type_ids'] ?? []) || !is_array($data['waste_type_ids'])) {
            throw new Exception('ผิดพลาด, ระบุข้อมูลที่ต้องการแก้ไข', 400);
        }

        $ids = array_filter($data['waste_type_ids']);

        if (empty($ids)) {
            return ['data' => $data, 'total' => 0];
        }

        try {
            // $this->Conn->beginTransaction();

            $placeholders = str_repeat('?,', count($ids) - 1) . '?';
            $sql = "UPDATE waste_type SET waste_type_active = NOT waste_type_active, updated_at = NOW() WHERE waste_type_id IN ($placeholders)";

            $stmt = $this->Conn->prepare($sql);

            foreach ($ids as $index => $id) {
                $stmt->bindValue($index + 1, $id, PDO::PARAM_INT);
            }

            $stmt->execute();
            $result = $stmt->rowCount();

            // $this->Conn->commit();

            return ['data' => $data, 'total' => $result];
        } catch (PDOException $e) {
            // if ($this->Conn->inTransaction()) {
            //     $this->Conn->rollBack();
            // }
            $erresult = DatabaseException::handle($e);
            throw new Exception($erresult['message'], $erresult['code'] ?: 500);
        } catch (Exception $e) {
            // if ($this->Conn->inTransaction()) {
            //     $this->Conn->rollBack();
            // }
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function DeleteWasteTypeById($id): int
    {
        try {
            if (empty($id)) {
                throw new Exception('ID is required for deletion', 400);
            }

            $sql = "DELETE FROM waste_type WHERE waste_type_id = :waste_type_id";
            $stmt = $this->Conn->prepare($sql);
            $stmt->execute(['waste_type_id' => $id]);

            return $stmt->rowCount();
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage(), 500);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function DeleteWasteType(array $data): array
    {
        if (empty($data['waste_type_ids'] ?? []) || !is_array($data['waste_type_ids'])) {
            throw new Exception('Bad Request: waste_type_ids is required and must be an array', 400);
        }

        $ids = array_filter($data['waste_type_ids']);

        if (empty($ids)) {
            return ["data" => $data, "total" => 0];
        }

        try {

            $this->Conn->beginTransaction();

            $placeholders = str_repeat('?,', count($ids) - 1) . '?';
            $sql = "DELETE FROM waste_type WHERE waste_type_id IN ($placeholders)";

            $stmt = $this->Conn->prepare($sql);

            foreach ($ids as $index => $id) {
                $stmt->bindValue($index + 1, $id, PDO::PARAM_INT);
            }

            $stmt->execute();
            $rowCount = $stmt->rowCount();

            $this->Conn->commit();

            return ["data" => $data, "total" => $rowCount];
        } catch (PDOException $e) {
            if ($this->Conn->inTransaction()) {
                $this->Conn->rollBack();
            }
            throw new Exception("Database error: " . $e->getMessage(), 500);
        } catch (Exception $e) {
            if ($this->Conn->inTransaction()) {
                $this->Conn->rollBack();
            }
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }
}