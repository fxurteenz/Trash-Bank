<?php
namespace App\Model;

use App\Utils\Database;
use App\Utils\DatabaseException;
use ArrayIterator;
use DateException;
use Exception;
use PDO;
use PDOException;

class WasteCategoryModel
{
    private static $Database;
    private $Conn;

    public function __construct()
    {
        self::$Database = new Database();
        $this->Conn = self::$Database->connect();
    }

    public function GetAllWasteCategories($query): array
    {
        try {
            $whereClauses = [];
            $params = [];

            if (!empty($query['search'])) {
                $whereClauses[] = "waste_category_name LIKE :search";
                $params[':search'] = "%" . $query['search'] . "%";
            }

            if (isset($query['active']) && $query['active'] !== '') {
                $whereClauses[] = "waste_category_active = :active";
                $params[':active'] = $query['active'];
            }

            $whereSql = !empty($whereClauses) ? " WHERE " . implode(" AND ", $whereClauses) : "";

            $sql = "SELECT * FROM waste_category{$whereSql}";
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
            $wasteCategory = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($isPagination) {
                $sqlCount = "SELECT COUNT(*) AS all_category FROM waste_category{$whereSql}";
                $stmtCount = $this->Conn->prepare($sqlCount);
                foreach ($params as $key => $val) {
                    $stmtCount->bindValue($key, $val);
                }
                $stmtCount->execute();
                $total = $stmtCount->fetch(PDO::FETCH_ASSOC)['all_category'];
            } else {
                $total = count($wasteCategory);
            }

            return ["data" => $wasteCategory, "total" => $total];
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage(), 500);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function CreateWasteCategory(array $data): array
    {
        try {

            if (!is_array($data)) {
                throw new Exception('Invalid data format', 400);
            }

            if (empty($data['waste_category_name'])) {
                // error_log("ERROR : waste_category_name");b
                throw new Exception('กรุณาลองใหม่, ระบุชื่อหมวดหมู่ขยะ', 400);
            }

            if (empty($data['waste_category_co2_per_kg'])) {
                // error_log("ERROR : waste_category_name");
                throw new Exception('กรุณาลองใหม่, ระบุปริมาณการลด CO2 ต่อกิโลกรัม', 400);
            }

            $data["updated_at"] = date('Y-m-d H:i:s');

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
                    waste_category
                SET
                    {$setClauseString}
                ";
            $stmt = $this->Conn->prepare($sql);
            $stmt->execute($updateData);
            $id = $this->Conn->lastInsertId();

            return ["waste_category_name" => $data["waste_category_name"], "waste_category_id" => $id];
        } catch (PDOException $e) {
            $error = DatabaseException::handle($e);
            throw new Exception($error["message"], $error["code"] ?: 500);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function UpdateWasteCategory($id, $data): mixed
    {
        try {
            if ((empty($data) && !is_array($data)) || empty($id)) {
                throw new Exception('Bad Request =(', 400);
            }

            $setClauses = [];
            $updateData = [];
            foreach ($data as $column => $value) {
                // อัปเดตเฉพาะค่าที่ส่งมา
                if (isset($value)) {
                    $setClauses[] = "`{$column}` = :{$column}";
                    $updateData[$column] = $value;
                }
            }

            if (empty($setClauses)) {
                return 0; // ไม่มีข้อมูลให้เปลี่ยนแปลง
            }

            $setClauseString = implode(', ', $setClauses);

            $sql =
                "UPDATE waste_category
                SET 
                    {$setClauseString}
                WHERE
                    waste_category_id = :waste_category_id
                ";

            $stmt = $this->Conn->prepare($sql);
            $stmt->execute(array_merge($updateData, ['waste_category_id' => $id]));
            $result = $stmt->rowCount();

            return ['data' => $data, 'total' => $result];
        } catch (PDOException $e) {
            $error = DatabaseException::handle($e);
            throw new Exception($error["message"], $error["code"] ?: 500);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function ToggleActiveWasteCategory(array $data): array
    {
        if (empty($data['waste_category_ids'] ?? []) || !is_array($data['waste_category_ids'])) {
            throw new Exception('ผิดพลาด, ระบุข้อมูลที่ต้องการแก้ไข', 400);
        }

        $ids = array_filter($data['waste_category_ids']);

        if (empty($ids)) {
            return ['data' => $data, 'total' => 0];

        }

        try {

            $this->Conn->beginTransaction();

            $placeholders = str_repeat('?,', count($ids) - 1) . '?';
            $sql = "UPDATE waste_category SET waste_category_active = NOT waste_category_active WHERE waste_category_id IN ($placeholders)";

            $stmt = $this->Conn->prepare($sql);

            foreach ($ids as $index => $id) {
                $stmt->bindValue($index + 1, $id, PDO::PARAM_INT);
            }

            $stmt->execute();
            $result = $stmt->rowCount();

            $this->Conn->commit();

            return ['data' => $data, 'total' => $result];
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

    public function DeleteWasteCategory(array $data): array
    {
        if (empty($data['waste_category_ids'] ?? []) || !is_array($data['waste_category_ids'])) {
            throw new Exception('ผิดพลาด, ระบุข้อมูลที่ต้องการลบ', 400);
        }

        $ids = array_filter($data['waste_category_ids']);

        if (empty($ids)) {
            return ['data' => $data, 'total' => 0];

        }

        try {

            $this->Conn->beginTransaction();

            $placeholders = str_repeat('?,', count($ids) - 1) . '?';
            $sql = "DELETE FROM waste_category WHERE waste_category_id IN ($placeholders)";

            $stmt = $this->Conn->prepare($sql);

            foreach ($ids as $index => $id) {
                $stmt->bindValue($index + 1, $id, PDO::PARAM_INT);
            }

            $stmt->execute();
            $result = $stmt->rowCount();

            $this->Conn->commit();

            return ['data' => $data, 'total' => $result];
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