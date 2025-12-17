<?php
namespace App\Model;

use App\Utils\Database;
use Exception;
use PDO;
use PDOException;

class DepositModel
{
    private static $Database;
    private $Conn;

    public function __construct()
    {
        self::$Database = new Database();
        $this->Conn = self::$Database->connect();
    }

    public function GetAllDeposit($query): array
    {
        try {
            $sql = "SELECT * FROM waste_deposit_transaction";

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
            $deposits = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($isPagination) {
                $sqlCount = 'SELECT COUNT(*) AS allDeposit FROM waste_deposit_transaction';
                $stmtCount = $this->Conn->prepare($sqlCount);
                $stmtCount->execute();
                $total = $stmtCount->fetch(PDO::FETCH_ASSOC)['allDeposit'];
            } else {
                $total = count($deposits);
            }

            return [$deposits, $total];

        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage(), 500);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function CreateDeposit(array $data, $operaterData): int
    {
        try {
            if (!is_array($data)) {
                throw new Exception('Invalid data format', 400);
            }

            // Validation ตัวอย่าง: เช็คค่าที่จำเป็น (ปรับเปลี่ยนได้ตาม Business Logic จริง)
            if (empty($data['waste_type_id'])) {
                error_log("ERROR : account_id or waste_type_id missing");
                throw new Exception('User ID or Waste Type ID not provided', 400);
            }

            if (!isset($data['deposit_weight'])) {
                error_log("ERROR : transaction_deposit_weight missing");
                throw new Exception('Weight not provided', 400);
            }

            $this->Conn->beginTransaction();
            $user = self::GetUserAccountId($this->Conn, $data["user_name"]);
            unset($data["user_name"]);
            $rateResult = self::GetWasteTypeRate($this->Conn, $data["waste_type_id"]);
            $wasteRate = $rateResult["waste_type_rate"];
            $wastePointRate = $rateResult["waste_type_point_rate"];

            $data["user_id"] = $user["account_id"];
            $data["faculty_id"] = $user["faculty_id"];
            $data["operater_id"] = $operaterData["user_data"]->account_id;
            $data["deposit_rate"] = $wasteRate;
            $data["deposit_point_rate"] = $wastePointRate;
            $data["deposit_points"] = ($wasteRate / 2) * $data["deposit_weight"];
            $data["deposit_value"] = $wasteRate * $data["deposit_weight"];
            // *** การคำนวณ deposit_user_point และ deposit_leftover ***
            $deposit_points_float = $data["deposit_points"];
            $integer_points = floor($data["deposit_points"]);
            // deposit_leftover คือส่วนทศนิยมที่ถูกตัดออก
            $leftover = $deposit_points_float - $integer_points;
            $data["deposit_user_points"] = $integer_points;
            $data["deposit_leftover"] = $leftover;

            error_log("TRANSACTION DEPOSIT DATA : " . print_r($data, 1));
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
                    waste_deposit_transaction
                SET
                    {$setClauseString}
                ";
            $stmt = $this->Conn->prepare($sql);
            $stmt->execute($updateData);
            //TODO:update user/operater/faculty points 

            $this->Conn->commit();
            $updated_row = $stmt->rowCount();

            return $updated_row;
        } catch (PDOException $e) {
            if ($this->Conn->inTransaction()) {
                $this->Conn->rollBack();
            }
            error_log("ERROR PDO : " . $e->getMessage());
            throw new Exception("Database error: " . $e->getMessage(), 500);
        } catch (Exception $e) {
            if ($this->Conn->inTransaction()) {
                $this->Conn->rollBack();
            }
            error_log("ERROR : " . $e->getMessage());
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function UpdateDeposit($id, $data): mixed
    {
        try {
            if ((empty($data) && !is_array($data)) || empty($id)) {
                throw new Exception('Bad Request =(', 400);
            }

            $setClauses = [];
            $updateData = [];
            foreach ($data as $column => $value) {
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
                "UPDATE waste_deposit_transaction
                SET 
                    {$setClauseString}
                WHERE
                    transaction_deposit_id = :transaction_deposit_id
                ";

            $stmt = $this->Conn->prepare($sql);
            // รวม array ข้อมูลที่จะอัปเดตเข้ากับ ID สำหรับ WHERE clause
            $stmt->execute(array_merge($updateData, ['transaction_deposit_id' => $id]));

            $result = $stmt->rowCount();
            return $result;
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage(), 500);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function DeleteDepositById($id): int
    {
        try {
            if (empty($id)) {
                throw new Exception('ID is required for deletion', 400);
            }

            $sql = "DELETE FROM waste_deposit_transaction WHERE transaction_deposit_id = :transaction_deposit_id";
            $stmt = $this->Conn->prepare($sql);
            $stmt->execute(['transaction_deposit_id' => $id]);

            return $stmt->rowCount();
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage(), 500);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function DeleteDeposit(array $data): int
    {
        // เปลี่ยน Key เป็น transaction_deposit_ids ให้สื่อความหมายตรงตาราง
        if (empty($data['transaction_deposit_ids'] ?? []) || !is_array($data['transaction_deposit_ids'])) {
            throw new Exception('Bad Request: transaction_deposit_ids is required and must be an array', 400);
        }

        $ids = array_filter($data['transaction_deposit_ids']);

        if (empty($ids)) {
            return 0;
        }

        try {

            $this->Conn->beginTransaction();

            $placeholders = str_repeat('?,', count($ids) - 1) . '?';
            $sql = "DELETE FROM waste_deposit_transaction WHERE transaction_deposit_id IN ($placeholders)";

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

    private static function GetWasteTypeRate($conn, $wasteTypeId)
    {
        try {
            $wasteRateSql =
                "SELECT 
                    waste_type_rate, waste_type_point_rate
                FROM
                    waste_type_tb
                WHERE
                    waste_type_id = :waste_type_id
                ";
            $rateStmt = $conn->prepare($wasteRateSql);
            $rateStmt->execute(["waste_type_id" => $wasteTypeId]);
            $result = $rateStmt->fetch(PDO::FETCH_ASSOC);
            // error_log(print_r($result, 1));
            if (!$result) {
                throw new Exception("Unknow Waste Type", 500);
            }
            return $result;
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage(), 500);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    private static function GetUserAccountId($conn, $accountName)
    {
        try {
            $wasteRateSql =
                "SELECT 
                    account_id,faculty_id
                FROM
                    account_tb
                WHERE
                    account_name = :account_name
                ";
            $stmt = $conn->prepare($wasteRateSql);
            $stmt->execute(["account_name" => $accountName]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$result) {
                throw new Exception("Invalid User Name", 500);
            }
            // error_log(print_r($result, 1));
            return $result;
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage(), 500);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }
}