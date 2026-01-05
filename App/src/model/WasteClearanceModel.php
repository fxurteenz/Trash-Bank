<?php
namespace App\Model;

use App\Utils\Database;
use Exception;
use PDO;
use PDOException;

class WasteClearanceModel
{
    private static $Database;
    private $Conn;

    public function __construct()
    {
        self::$Database = new Database();
        $this->Conn = self::$Database->connect();
    }

    public function CreateClearance(array $data, $operaterData)
    {
        try {
            if (empty($data) && !is_array($data)) {
                throw new Exception('Bad Request =(', 400);
            }
            if (empty($data["faculty_id"])) {
                throw new Exception('ลองใหม่อีกครั้ง, กรุณาระบุคณะ', 400);
            }
            if (empty($data["waste_clearance_period_start"]) || empty($data["waste_clearance_period_end"])) {
                throw new Exception('ลองใหม่อีกครั้ง, กรุณาระบุวันเริ่มต้นและวันสิ้นสุด', 400);
            }
            if (empty($operaterData["user_data"]->member_id)) {
                throw new Exception("ไม่สามารถทำรายการได้ กรุณาเข้าสู่ระบบใหม่อีกครั้ง", 400);
            }

            // เริ่ม Transaction
            $this->Conn->beginTransaction();

            // 1. ดึงยอดเงินและแต้ม 
            $periodData = self::GetPeriodTransactions($data, $this->Conn);

            // 2. ดึงรายละเอียดขยะแต่ละประเภท
            $periodDetail = self::GetTransactionWasteTypeDetail($data, $this->Conn);

            // เตรียมข้อมูลสำหรับ INSERT ลง waste_clearance
            $clearancePayload = [];
            $clearancePayload["faculty_id"] = $data["faculty_id"] ?? null;
            $clearancePayload["waste_clearance_period_start"] = $data["waste_clearance_period_start"];
            $clearancePayload["waste_clearance_period_end"] = $data["waste_clearance_period_end"];
            $clearancePayload["waste_clearance_value_total"] = $periodData["value_total"];
            $clearancePayload["waste_clearance_member_point_total"] = $periodData["member_point_total"];
            $clearancePayload["waste_clearance_faculty_point_total"] = $periodData["faculty_point_total"];
            $clearancePayload["waste_clearance_status"] = "รออนุมัติ";
            $clearancePayload["waste_clearance_created_by"] = $operaterData["user_data"]->member_id;
            $clearancePayload["created_at"] = date('Y-m-d H:i:s');

            // สร้าง String สำหรับ INSERT
            $setClauses = [];
            $updateData = [];
            foreach ($clearancePayload as $column => $value) {
                if (isset($value)) {
                    $setClauses[] = "`{$column}` = :{$column}";
                    $updateData[$column] = $value;
                }
            }
            $clearanceSetClauseString = implode(', ', $setClauses);

            // 3. Insert ลงตารางหลัก (waste_clearance)
            $sql = "INSERT INTO waste_clearance SET {$clearanceSetClauseString}";
            $stmt = $this->Conn->prepare($sql);
            $stmt->execute($updateData);
            $insertedId = $this->Conn->lastInsertId();

            // 4. อัปเดต waste_transaction ว่า "ถูกเคลียร์แล้ว"
            $updateTxSql = "UPDATE waste_transaction 
                            SET waste_clearance_id = :clearance_id 
                            WHERE faculty_id = :faculty_id 
                            AND waste_transaction_date BETWEEN :start_date AND :end_date
                            AND waste_clearance_id IS NULL"; 

            $stmtUpdateTx = $this->Conn->prepare($updateTxSql);
            $stmtUpdateTx->execute([
                ':clearance_id' => $insertedId,
                ':faculty_id' => $data['faculty_id'],
                ':start_date' => $data['waste_clearance_period_start'],
                ':end_date' => $data['waste_clearance_period_end']
            ]);

            // 5. Insert รายละเอียด (clearance_detail)
            if (!empty($periodDetail)) {
                $sqlDetail = "INSERT INTO clearance_detail (waste_clearance_id, waste_type_id, clearance_detail_transaction_weight) VALUES ";
                $placeholders = [];
                $values = [];
                foreach ($periodDetail as $row) {
                    $placeholders[] = "(?, ?, ?)";
                    array_push($values, $insertedId, $row['waste_type_id'], $row['total_weight']);
                }

                $sqlDetail .= implode(', ', $placeholders);
                $stmtDetail = $this->Conn->prepare($sqlDetail);
                $stmtDetail->execute($values);
            }

            $this->Conn->commit();

            return [
                "clearance_id" => $insertedId,
                "start_date" => $data["waste_clearance_period_start"],
                "end_date" => $data["waste_clearance_period_end"]
            ];

        } catch (PDOException $th) {
            if ($this->Conn->inTransaction()) {
                $this->Conn->rollBack();
            }
            error_log(print_r($th->getMessage(), 1));
            throw new Exception($th->getMessage(), 500);
        } catch (Exception $ex) {
            if ($this->Conn->inTransaction()) {
                $this->Conn->rollBack();
            }
            error_log(print_r($ex->getMessage(), 1));
            throw new Exception($ex->getMessage(), $ex->getCode() ?: 400);
        }
    }

    protected static function GetPeriodTransactions($query, $conn)
    {
        try {
            $faculty_id = $query['faculty_id'] ?? null;
            $start_date = $query['waste_clearance_period_start'] ?? null;
            $end_date = $query['waste_clearance_period_end'] ?? null;

            $whereClauses = [];
            $params = [];

            if ($faculty_id) {
                $whereClauses[] = "w.faculty_id = :faculty_id";
                $params[':faculty_id'] = $faculty_id;
            }
            if ($start_date && $end_date) {
                $whereClauses[] = "w.waste_transaction_date BETWEEN :start_date AND :end_date";
                $params[':start_date'] = $start_date;
                $params[':end_date'] = $end_date;
            }

            // กรองเฉพาะรายการที่ยังไม่ถูกเคลียร์ (IS NULL)
            $whereClauses[] = "w.waste_clearance_id IS NULL";

            $whereSql = !empty($whereClauses) ? "WHERE " . implode(" AND ", $whereClauses) : "";

            $sqlFaculty = "SELECT 
                            f.faculty_id,
                            f.faculty_name,
                            COALESCE(SUM(w.waste_transaction_faculty_fraction), 0) AS faculty_point_total,
                            COALESCE(SUM(w.waste_transaction_member_point), 0) AS member_point_total,
                            COALESCE(SUM(wt.waste_type_price * w.waste_transaction_weight), 0) AS value_total
                        FROM waste_transaction w
                        LEFT JOIN waste_type wt ON w.waste_transaction_waste_type = wt.waste_type_id
                        LEFT JOIN faculty f ON w.faculty_id = f.faculty_id
                        {$whereSql}";

            $stmtFaculty = $conn->prepare($sqlFaculty);
            $stmtFaculty->execute($params);
            $result = $stmtFaculty->fetch(PDO::FETCH_ASSOC);

            if ((float) $result["faculty_point_total"] === 0 && (int) $result["member_point_total"] === 0 && (float) $result["value_total"] === 0) {
                // เปลี่ยนข้อความแจ้งเตือนเล็กน้อยเพื่อให้เข้าใจง่ายขึ้น
                throw new Exception("ไม่พบรายการขยะตกค้างในช่วงเวลานี้ หรือรายการทั้งหมดถูกเคลียร์ไปแล้ว", 400);
            }
            if (empty($result["faculty_id"])) { // เอา check faculty_name ออกเผื่อบางเคส join ไม่เจอแต่มียอด
                // ถ้าหา faculty ไม่เจอจริงๆ ค่อย throw แต่ปกติ query บนจะ return null ทั้งหมดถ้าไม่เจอ row
                if ($faculty_id) {
                    // โค้ดเดิมคุณอาจจะต้องการแค่เช็คว่ามี Data ไหม
                }
            }

            return $result;
        } catch (PDOException $th) {
            throw new Exception($th->getMessage(), 500);
        } catch (Exception $ex) {
            throw new Exception($ex->getMessage(), $ex->getCode() ?: 400);
        }
    }

    protected static function GetTransactionWasteTypeDetail($query, $conn)
    {
        try {
            $faculty_id = $query['faculty_id'] ?? null;
            $start_date = $query['waste_clearance_period_start'] ?? null;
            $end_date = $query['waste_clearance_period_end'] ?? null;

            $whereClauses = [];
            $params = [];

            if ($faculty_id) {
                $whereClauses[] = "w.faculty_id = :faculty_id";
                $params[':faculty_id'] = $faculty_id;
            }

            if ($start_date && $end_date) {
                $whereClauses[] = "w.waste_transaction_date BETWEEN :start_date AND :end_date";
                $params[':start_date'] = $start_date;
                $params[':end_date'] = $end_date;
            }

            // กรองเฉพาะรายการที่ยังไม่ถูกเคลียร์ (IS NULL)
            $whereClauses[] = "w.waste_clearance_id IS NULL";

            $whereSql = !empty($whereClauses) ? "WHERE " . implode(" AND ", $whereClauses) : "";

            $sqlType = "SELECT 
                        wc.waste_category_id,
                        wc.waste_category_name,
                        wt.waste_type_id,
                        wt.waste_type_name,
                        COALESCE(SUM(w.waste_transaction_weight), 0) AS total_weight,
                        COALESCE(SUM(wt.waste_type_price * w.waste_transaction_weight), 0) AS total_value,
                        COALESCE(SUM(wt.waste_type_co2 * w.waste_transaction_weight), 0) AS total_co2
                    FROM waste_transaction w
                    LEFT JOIN waste_type wt ON w.waste_transaction_waste_type = wt.waste_type_id
                    LEFT JOIN waste_category wc ON wt.waste_category_id = wc.waste_category_id
                    {$whereSql}
                    GROUP BY wt.waste_type_id";

            $stmtType = $conn->prepare($sqlType);
            $stmtType->execute($params);
            return $stmtType->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $th) {
            throw new Exception($th->getMessage(), 500);
        } catch (Exception $ex) {
            throw new Exception($ex->getMessage(), $ex->getCode() ?: 400);
        }
    }
}