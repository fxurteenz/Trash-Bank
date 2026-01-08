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
            $clearancePayload["waste_clearance_status"] = "1";
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

            // 4. อัปเดต waste_transaction ว่า "เตรียมนำเข้าศูนย์ใหญ่"
            $updateTxSql = "UPDATE waste_transaction 
                            SET waste_clearance_id = :clearance_id, waste_transaction_status = 2 
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

    public function GetAllClearance($query): array
    {
        try {
            $whereClauses = [];
            $params = [];

            if (!empty($query['faculty'])) {
                $whereClauses[] = "wc.faculty_id = :faculty_id";
                $params[':faculty_id'] = $query['faculty'];
            }
            if (!empty($query['start_date'])) {
                $whereClauses[] = "wc.waste_clearance_period_start >= :start_date";
                $params[':start_date'] = $query['start_date'];
            }
            if (!empty($query['end_date'])) {
                $whereClauses[] = "wc.waste_clearance_period_end <= :end_date";
                $params[':end_date'] = $query['end_date'];
            }
            if (!empty($query['status'])) {
                $whereClauses[] = "wc.waste_clearance_status = :status";
                $params[':status'] = $query['status'];
            }
            if (!empty($query['creater'])) {
                $whereClauses[] = "wc.waste_clearance_created_by = :created_by";
                $params[':created_by'] = $query['creater'];
            }

            $whereSql = !empty($whereClauses) ? " WHERE " . implode(" AND ", $whereClauses) : "";

            $sql = "SELECT 
                        wc.*,
                        f.faculty_name,
                        m.member_name AS creator_name
                    FROM 
                        waste_clearance wc
                    LEFT JOIN 
                        faculty f ON wc.faculty_id = f.faculty_id
                    LEFT JOIN 
                        member m ON wc.waste_clearance_created_by = m.member_id
                    {$whereSql}
                    ORDER BY wc.created_at DESC";

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

            if ($isPagination) {
                $sqlCount = "SELECT COUNT(*) AS total FROM waste_clearance wc {$whereSql}";
                $stmtCount = $this->Conn->prepare($sqlCount);
                foreach ($params as $key => $val) {
                    $stmtCount->bindValue($key, $val);
                }
                $stmtCount->execute();
                $total = $stmtCount->fetch(PDO::FETCH_ASSOC)['total'];
            } else {
                $total = count($data);
            }

            return ["data" => $data, "total" => $total];
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage(), 500);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function GetClearanceDetail($id, $query = []): array
    {
        try {
            if (empty($id)) {
                throw new Exception('ID is required', 400);
            }

            $whereClauses = ["cd.waste_clearance_id = :id"];
            $params = [':id' => $id];

            if (!empty($query['waste_type'])) {
                $whereClauses[] = "cd.waste_type_id = :waste_type_id";
                $params[':waste_type_id'] = $query['waste_type'];
            }

            if (!empty($query['waste_category'])) {
                $whereClauses[] = "wt.waste_category_id = :waste_category_id";
                $params[':waste_category_id'] = $query['waste_category'];
            }

            $whereSql = "WHERE " . implode(" AND ", $whereClauses);

            $sqlDetail = "SELECT 
                        cd.*,
                        wt.waste_type_name,
                        wt.waste_type_price,
                        wt.waste_type_co2,
                        wc.waste_category_name
                    FROM 
                        clearance_detail cd
                    LEFT JOIN 
                        waste_type wt ON cd.waste_type_id = wt.waste_type_id
                    LEFT JOIN 
                        waste_category wc ON wt.waste_category_id = wc.waste_category_id
                    {$whereSql}";

            $stmt = $this->Conn->prepare($sqlDetail);
            $stmt->execute($params);
            $clearanceDetails = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $sql = "SELECT 
                        wc.*,
                        f.faculty_name,
                        m.member_name AS creator_name
                    FROM 
                        waste_clearance wc
                    LEFT JOIN 
                        faculty f ON wc.faculty_id = f.faculty_id
                    LEFT JOIN 
                        member m ON wc.waste_clearance_created_by = m.member_id
                    WHERE wc.waste_clearance_id = :id";
            $stmt = $this->Conn->prepare($sql);
            $stmt->execute([':id' => $id]);
            $details = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return ['transaction' => $details, 'detail' => $clearanceDetails];
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage(), 500);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function ConfirmClearance($cdid, $data)
    {
        try {
            if (empty($cdid)) {
                throw new Exception('ID is required', 400);
            }

            $weight = $data['weight'] ?? null;
            if (!isset($weight) || $weight === '') {
                throw new Exception('Weight is required', 400);
            }

            $this->Conn->beginTransaction();
            $date = date('Y-m-d H:i:s');

            // 1. Get waste_clearance_id
            $sqlGet = "SELECT waste_clearance_id FROM clearance_detail WHERE clearance_detail_id = :id";
            $stmtGet = $this->Conn->prepare($sqlGet);
            $stmtGet->execute([':id' => $cdid]);
            $detail = $stmtGet->fetch(PDO::FETCH_ASSOC);

            if (!$detail) {
                throw new Exception('Clearance detail not found', 404);
            }

            $wasteClearanceId = $detail['waste_clearance_id'];

            // 2. Update detail
            $sqlUpdate = "UPDATE clearance_detail 
                          SET clearance_detail_clearance_weight = :weight,
                              clearance_detail_success = 1,
                              complete_date = :complete_date
                          WHERE clearance_detail_id = :id";
            $stmtUpdate = $this->Conn->prepare($sqlUpdate);
            $stmtUpdate->execute([
                ':weight' => $weight,
                ':id' => $cdid,
                'complete_date' => $date
            ]);

            // 3. Check all details for this clearance
            $sqlCheck = "SELECT COUNT(*) as pending FROM clearance_detail 
                         WHERE waste_clearance_id = :wcid AND clearance_detail_success = 0";
            $stmtCheck = $this->Conn->prepare($sqlCheck);
            $stmtCheck->execute([':wcid' => $wasteClearanceId]);
            $resultCheck = $stmtCheck->fetch(PDO::FETCH_ASSOC);

            // 4. Update master status if all done
            if ($resultCheck['pending'] == 0) {
                $sqlUpdateMaster = "UPDATE waste_clearance 
                                    SET waste_clearance_status = '2',
                                        approved_at = :complete_date
                                    WHERE waste_clearance_id = :wcid";
                $stmtUpdateMaster = $this->Conn->prepare($sqlUpdateMaster);
                $stmtUpdateMaster->execute([
                    ':wcid' => $wasteClearanceId,
                    "complete_date" => $date
                ]);


                $sqlUpdateTx = "UPDATE waste_transaction 
                                SET waste_transaction_status = 3 
                                WHERE waste_clearance_id = :wcid";
                $stmtUpdateTx = $this->Conn->prepare($sqlUpdateTx);
                $stmtUpdateTx->execute([
                    ":wcid" => $wasteClearanceId
                ]);
            }

            $this->Conn->commit();
            return true;
        } catch (PDOException $th) {
            if ($this->Conn->inTransaction()) {
                $this->Conn->rollBack();
            }
            throw new Exception("Database error: " . $th->getMessage(), 500);
        } catch (Exception $ex) {
            if ($this->Conn->inTransaction()) {
                $this->Conn->rollBack();
            }
            throw new Exception($ex->getMessage(), $ex->getCode() ?: 400);
        }
    }

    // static functions
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

            if (((float) $result["faculty_point_total"] == 0) && ((int) $result["member_point_total"] == 0) && ((float) $result["value_total"] == 0)) {
                throw new Exception("ไม่พบรายการขยะตกค้างในช่วงเวลานี้ หรือรายการทั้งหมดถูกเคลียร์ไปแล้ว", 400);
            }

            if (empty($result["faculty_id"])) {
                if ($faculty_id) {
                    throw new Exception("ลองใหม่อีกครั้ง, ไม่พบข้อมูลคณะ", 400);
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