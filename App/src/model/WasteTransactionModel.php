<?php
namespace App\Model;

use App\Utils\Database;
use Exception;
use PDO;
use PDOException;

class WasteTransactionModel
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

    public function CreateWasteTransaction(array $data, $operaterData): array
    {
        try {
            if (!is_array($data)) {
                throw new Exception('Invalid data format', 400);
            }

            if (empty($data['waste_type_id']) || empty($data["waste_category_id"])) {
                error_log("ERROR : waste_type or waste_category is missing");
                throw new Exception('Waste type or category not provided', 400);
            }

            if (!isset($data['deposit_weight'])) {
                error_log("ERROR : transaction_deposit_weight missing");
                throw new Exception('Weight not provided', 400);
            }
            /* Start SQL Transaction */
            $this->Conn->beginTransaction();

            $user = self::GetDepositorAccount($this->Conn, $data["depositor_account"]);

            $rateResult = self::GetWasteTypeRate($this->Conn, $data["waste_type_id"]);

            $payload = [];
            $payload["account_id"] = $user["account_id"];
            $payload["operater_id"] = $operaterData["user_data"]->account_id;

            if ($user["account_role"] === "user") {
                $payload["waste_transaction_from"] = 1;
            } else {
                $payload["waste_transaction_from"] = 2;
            }

            $payload["waste_transaction_waste_category"] = $data["waste_category_id"];
            $payload["waste_transaction_waste_type"] = $data["waste_type_id"];
            $payload["waste_transaction_weight"] = $data["deposit_weight"];
            $payload["waste_transaction_rate"] = $rateResult["waste_type_price"];

            $value = $rateResult["waste_type_price"] * $data["deposit_weight"];
            $integer_point = (int) floor($value);

            $payload["waste_transaction_value"] = $value;
            $payload["waste_transaction_point"] = $integer_point;
            $payload["waste_transaction_leftover"] = $value - $integer_point;
            $payload["waste_transaction_status"] = 1;
            $payload["waste_transaction_create_at"] = date('Y-m-d H:i:s');

            error_log("TRANSACTION DEPOSIT_WASTE DATA : " . print_r($payload, 1));

            $setClauses = [];
            foreach ($payload as $column => $value) {
                if (isset($value) && $value !== '') {
                    $setClauses[] = "`{$column}` = :{$column}";
                }
            }
            $setClauseString = implode(', ', $setClauses);

            $sql =
                "INSERT INTO 
                    waste_transaction
                SET
                    {$setClauseString}
                ";
            $stmt = $this->Conn->prepare($sql);
            $stmt->execute($payload);
            $insertedId = $this->Conn->lastInsertId();

            if (empty($user["account_id"]) || empty($user["faculty_id"])) {
                throw new Exception("ERROR : Check faculty's user", 400);
            }

            $updatedUser = self::UpdateDepositorAccountPoint($this->Conn, $user["account_id"], $integer_point);
            $updatedFaculty = self::UpdateFacultyPoint($this->Conn, $user["faculty_id"], $payload["waste_transaction_leftover"]);

            $this->Conn->commit();

            return [
                'transaction_id' => $insertedId,
                'user_points' => $updatedUser['account_point'],
                'faculty_points' => $updatedFaculty['faculty_point'] ?? null
            ];

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

    // static function for use in transaction 
    private static function GetWasteTypeRate($conn, $wasteTypeId)
    {
        try {
            $wasteRateSql =
                "SELECT 
                    waste_type_price
                FROM
                    waste_type
                WHERE
                    waste_type_id = :waste_type_id
                ";
            $rateStmt = $conn->prepare($wasteRateSql);
            $rateStmt->execute(["waste_type_id" => $wasteTypeId]);
            $result = $rateStmt->fetch(PDO::FETCH_ASSOC);
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

    private static function GetDepositorAccount($conn, $identifier)
    {
        try {
            $sql =
                "SELECT 
                    account_id, account_role, faculty_id
                FROM
                    account
                WHERE
                    account_personal_id = :identifier OR
                    account_tel = :identifier OR
                    account_email = :identifier OR
                    account_name = :identifier
                ";
            $stmt = $conn->prepare($sql);
            $stmt->execute(["identifier" => $identifier]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$result) {
                throw new Exception("account not found with identifier: " . htmlspecialchars($identifier) . "'", 404);
            }
            return $result;
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage(), 500);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    private static function UpdateDepositorAccountPoint($conn, $accountId, $point)
    {
        try {
            if ($point == 0) {
                $selectSql = "SELECT account_point FROM account WHERE account_id = :account_id";
                $stmt = $conn->prepare($selectSql);
                $stmt->execute(["account_id" => $accountId]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$user) {
                    throw new Exception("Account not found ID: " . htmlspecialchars($accountId), 404);
                }

                return $user;
            }

            $sql =
                "UPDATE
                    account
                SET
                    account_point = account_point + :point
                WHERE
                   account_id = :account_id
                ";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(":account_id", $accountId, PDO::PARAM_INT);
            $stmt->bindValue(":point", $point, PDO::PARAM_INT);
            $stmt->execute();

            $selectSql = "SELECT account_point FROM account WHERE account_id = :account_id";
            $stmt = $conn->prepare($selectSql);
            $stmt->execute(["account_id" => $accountId]);
            $updatedUser = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$updatedUser) {
                throw new Exception("Account not found after update attempt", 404);
            }

            return $updatedUser;

        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage(), 500);
        }
    }

    private static function UpdateFacultyPoint($conn, $facultyId, $point)
    {
        try {
            if ($point == 0) {
                $selectSql = "SELECT faculty_point FROM faculty WHERE faculty_id = :faculty_id";
                $stmt = $conn->prepare($selectSql);
                $stmt->execute(["faculty_id" => $facultyId]);
                $faculty = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$faculty) {
                    throw new Exception("faculty not found ID: " . htmlspecialchars($facultyId), 404);
                }

                return $faculty;
            }

            $updateSql =
                "UPDATE
                    faculty
                SET
                    faculty_point = faculty_point + :point
                WHERE
                    faculty_id = :faculty_id
                ";
            $stmt = $conn->prepare($updateSql);
            $stmt->execute([
                "faculty_id" => $facultyId,
                "point" => $point
            ]);

            $rowCount = $stmt->rowCount();
            if ($rowCount === 0) {
                throw new Exception("Faculty not found or points not updated for faculty ID: " . htmlspecialchars($facultyId) . "   " . htmlspecialchars($point), 404);
            }

            $selectSql = "SELECT faculty_point FROM faculty WHERE faculty_id = :faculty_id";
            $stmt = $conn->prepare($selectSql);
            $stmt->execute(["faculty_id" => $facultyId]);
            $updatedFaculty = $stmt->fetch(PDO::FETCH_ASSOC);

            return $updatedFaculty;

        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage(), 500);
        } catch (Exception $e) {
            throw $e;
        }
    }
}