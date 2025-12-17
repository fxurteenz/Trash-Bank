<?php
namespace App\Model;

use App\Utils\Database;
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

    public function GetAllWasteCategory($query): array
    {
        try {
            // error_log("QUERY PARAMS : " . print_r($query));
            $sql = "SELECT * FROM waste_category";
            $isPagination = isset($query['page']) && isset($query['limit']);

            if ($isPagination) {
                $page = (int) $query['page'];
                $limit = (int) $query['limit'];
                $offset = ($page - 1) * $limit;
                $sql .= " LIMIT :limit OFFSET :offset";
            }

            $stmt = $this->Conn->prepare($sql);
            
            if ($isPagination) {
                $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
                $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            }

            $stmt->execute();
            $wasteCategory = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($isPagination) {
                $sqlCount = 'SELECT COUNT(*) AS all_category FROM waste_category';
                $stmtCount = $this->Conn->prepare($sqlCount);
                $stmtCount->execute();
                $total = $stmtCount->fetch(PDO::FETCH_ASSOC)['all_category'];
            } else {
                $total = count($wasteCategory);
            }

            return [$wasteCategory, $total];

        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage(), 500);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function CreateWasteCategory(array $data): int
    {
        try {

            if (!is_array($data)) {
                throw new Exception('Invalid data format', 400);
            }

            if (empty($data['waste_category_name'])) {
                error_log("ERROR : waste_category_name");
                throw new Exception('Waste type name not provided', 400);
            }

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

            $updated_row = $stmt->rowCount();
            return $updated_row;
        } catch (PDOException $e) {
            error_log("ERROR PDO : " . $e->getMessage());
            throw new Exception("Database error: " . $e->getMessage(), 500);
        } catch (Exception $e) {
            error_log("ERROR : " . $e->getMessage());
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
            // รวม array ข้อมูลที่จะอัปเดตเข้ากับ ID สำหรับ WHERE clause
            $stmt->execute(array_merge($updateData, ['waste_category_id' => $id]));

            $result = $stmt->rowCount();
            return $result;
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage(), 500);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function DeleteWasteCategoryById($id): int
    {
        try {
            if (empty($id)) {
                throw new Exception('ID is required for deletion', 400);
            }

            $sql = "DELETE FROM waste_category WHERE waste_category_id = :waste_category_id";
            $stmt = $this->Conn->prepare($sql);
            $stmt->execute(['waste_category_id' => $id]);

            return $stmt->rowCount();
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage(), 500);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function DeleteWasteCategory(array $data): int
    {
        if (empty($data['waste_category_ids'] ?? []) || !is_array($data['waste_category_ids'])) {
            throw new Exception('Bad Request: waste_category_ids is required and must be an array', 400);
        }

        $ids = array_filter($data['waste_category_ids']);

        if (empty($ids)) {
            return 0;
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
            $rowCount = $stmt->rowCount();

            $this->Conn->commit();

            return $rowCount;
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