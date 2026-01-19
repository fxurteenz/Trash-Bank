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
                throw new Exception('กรุณาระบุคณะ', 400);
            }
            if (empty($data["waste_clearance_period_start"]) || empty($data["waste_clearance_period_end"])) {
                throw new Exception('กรุณาระบุวันเริ่มต้นและวันสิ้นสุด', 400);
            }
            if (empty($operaterData["user_data"]->member_id)) {
                throw new Exception("ไม่สามารถทำรายการได้ กรุณาเข้าสู่ระบบใหม่อีกครั้ง", 400);
            }

            // เริ่ม Transaction
            $this->Conn->beginTransaction();

            // 1. ดึงยอดรวม (Sum) โดย Join ไปที่ transaction_detail
            $periodData = self::GetPeriodTransactions($data, $this->Conn);

            // 2. ดึงรายละเอียดขยะแต่ละประเภท (Group By Type)
            $periodDetail = self::GetTransactionWasteTypeDetail($data, $this->Conn);

            // เตรียมข้อมูลสำหรับ INSERT ลง waste_clearance
            $clearancePayload = [];
            $clearancePayload["faculty_id"] = $data["faculty_id"];
            $clearancePayload["waste_clearance_period_start"] = $data["waste_clearance_period_start"];
            $clearancePayload["waste_clearance_period_end"] = $data["waste_clearance_period_end"];
            $clearancePayload["waste_clearance_value_total"] = $periodData["value_total"];
            $clearancePayload["waste_clearance_member_point_total"] = $periodData["member_point_total"];
            $clearancePayload["waste_clearance_faculty_point_total"] = $periodData["faculty_point_total"];

            // ใช้ Enum ตาม DB: 'รอการยืนยัน'
            $clearancePayload["waste_clearance_status"] = "รอการยืนยัน";
            $clearancePayload["waste_clearance_created_by"] = $operaterData["user_data"]->member_id;
            $clearancePayload["created_at"] = date('Y-m-d H:i:s');

            // 3. Insert ลงตารางหลัก (waste_clearance)
            $setClauses = [];
            $updateData = [];
            foreach ($clearancePayload as $column => $value) {
                if (isset($value)) {
                    $setClauses[] = "`{$column}` = :{$column}";
                    $updateData[$column] = $value;
                }
            }
            $sql = "INSERT INTO waste_clearance SET " . implode(', ', $setClauses);
            $stmt = $this->Conn->prepare($sql);
            $stmt->execute($updateData);
            $insertedId = $this->Conn->lastInsertId();

            // 4. อัปเดต waste_transaction_detail 
            // เปลี่ยนสถานะเป็น 'เตรียมนำเข้าศูนย์ใหญ่' และผูก waste_clearance_id
            $updateDetailSql = "UPDATE waste_transaction_detail wtd
                                JOIN waste_transaction wt ON wtd.waste_transaction_id = wt.waste_transaction_id
                                SET 
                                    wtd.waste_clearance_id = :clearance_id, 
                                    wtd.waste_transaction_detail_status = 'เตรียมส่งศูนย์'
                                WHERE 
                                    wt.faculty_id = :faculty_id 
                                    AND wt.waste_transaction_date BETWEEN :start_date AND :end_date
                                    AND wtd.waste_clearance_id IS NULL";

            $stmtUpdate = $this->Conn->prepare($updateDetailSql);
            $stmtUpdate->execute([
                ':clearance_id' => $insertedId,
                ':faculty_id' => $data['faculty_id'],
                ':start_date' => $data['waste_clearance_period_start'],
                ':end_date' => $data['waste_clearance_period_end']
            ]);

            // 5. Insert รายละเอียดการเคลียร์ (clearance_detail)
            // เก็บ Snapshot น้ำหนัก ณ วันที่เคลียร์
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
                "message" => "สร้างรายการเคลียร์ยอดเรียบร้อยแล้ว"
            ];

        } catch (PDOException $th) {
            if ($this->Conn->inTransaction())
                $this->Conn->rollBack();
            error_log($th->getMessage());
            throw new Exception("Database Error: " . $th->getMessage(), 500);
        } catch (Exception $ex) {
            if ($this->Conn->inTransaction())
                $this->Conn->rollBack();
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
            // รองรับ ENUM status
            if (!empty($query['status'])) {
                $whereClauses[] = "wc.waste_clearance_status = :status";
                $params[':status'] = $query['status'];
            }

            $whereSql = !empty($whereClauses) ? " WHERE " . implode(" AND ", $whereClauses) : "";

            $sql = "SELECT 
                        wc.*,
                        f.faculty_name,
                        m.member_name AS creator_name
                    FROM waste_clearance wc
                    LEFT JOIN faculty f ON wc.faculty_id = f.faculty_id
                    LEFT JOIN member m ON wc.waste_clearance_created_by = m.member_id
                    {$whereSql}
                    ORDER BY wc.created_at DESC";

            // Pagination Logic (Simplified)
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

            // Count Total
            $total = count($data);
            if ($isPagination) {
                $sqlCount = "SELECT COUNT(*) AS total FROM waste_clearance wc {$whereSql}";
                $stmtCount = $this->Conn->prepare($sqlCount);
                foreach ($params as $key => $val) {
                    $stmtCount->bindValue($key, $val);
                }
                $stmtCount->execute();
                $total = $stmtCount->fetch(PDO::FETCH_ASSOC)['total'];
            }

            return ["data" => $data, "total" => $total];
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage(), 500);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function GetClearanceDetail($id, $query = [])
    {
        try {
            if (empty($id)) {
                throw new Exception('ID is required', 400);
            }

            // ดึงข้อมูล Header
            $sqlHeader = "SELECT 
                                wc.*,
                                f.faculty_name,
                                m.member_name AS creator_name,
                                am.member_name AS approver_name
                            FROM waste_clearance wc
                            LEFT JOIN faculty f ON wc.faculty_id = f.faculty_id
                            LEFT JOIN member m ON wc.waste_clearance_created_by = m.member_id
                            LEFT JOIN member am ON wc.waste_clearance_approved_by = am.member_id
                            WHERE wc.waste_clearance_id = :id";
            $stmt = $this->Conn->prepare($sqlHeader);
            $stmt->execute([':id' => $id]);
            $header = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$header) {
                throw new Exception('Clearance not found', 404);
            }

            // ดึงข้อมูลรายการย่อยใน clearance_detail
            $sqlDetail = "SELECT 
                            cd.*,
                            wt.waste_type_name,
                            wt.waste_type_price,
                            wc.waste_category_name
                        FROM clearance_detail cd
                        LEFT JOIN waste_type wt ON cd.waste_type_id = wt.waste_type_id
                        LEFT JOIN waste_category wc ON wt.waste_category_id = wc.waste_category_id
                        WHERE cd.waste_clearance_id = :id";

            $stmtDetail = $this->Conn->prepare($sqlDetail);
            $stmtDetail->execute([':id' => $id]);
            $details = $stmtDetail->fetchAll(PDO::FETCH_ASSOC);

            return ['transaction' => $header, 'detail' => $details];
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage(), 500);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function ConfirmClearance($cdid, $data, $approverData)
    {
        try {
            if (empty($cdid))
                throw new Exception('ID is required', 400);

            $weight = $data['weight'] ?? null;
            if (!is_numeric($weight)) {
                throw new Exception('กรุณาระบุน้ำหนักที่ชั่งได้จริง', 400);
            }

            $this->Conn->beginTransaction();
            $now = date('Y-m-d H:i:s');

            // 1. Find waste_clearance_id from detail row
            $sqlGet = "SELECT waste_clearance_id FROM clearance_detail WHERE clearance_detail_id = :id";
            $stmtGet = $this->Conn->prepare($sqlGet);
            $stmtGet->execute([':id' => $cdid]);
            $wcid = $stmtGet->fetchColumn();

            if (!$wcid) {
                throw new Exception('Detail not found', 404);
            }

            // 2. Update the specific clearance detail item
            $sqlUpdateDetail = "UPDATE clearance_detail 
                                SET clearance_detail_clearance_weight = :weight,
                                    clearance_detail_success = 1,
                                    complete_date = :now
                                WHERE clearance_detail_id = :id";
            $stmtUpdate = $this->Conn->prepare($sqlUpdateDetail);
            $stmtUpdate->execute([':weight' => $weight, ':id' => $cdid, ':now' => $now]);

            // 3. Check if all items in this clearance are now complete
            $sqlCheck = "SELECT COUNT(*) as pending FROM clearance_detail 
                         WHERE waste_clearance_id = :wcid AND clearance_detail_success = 0";
            $stmtCheck = $this->Conn->prepare($sqlCheck);
            $stmtCheck->execute([':wcid' => $wcid]);
            $pendingCount = $stmtCheck->fetchColumn();

            // 4. If all items are complete, finalize the clearance
            if ($pendingCount == 0) {
                // 4.1 Update the main waste_clearance record
                $sqlUpdateMaster = "UPDATE waste_clearance 
                                    SET waste_clearance_status = 'ยืนยันแล้ว',
                                        waste_clearance_approved_by = :approver,
                                        approved_at = :now
                                    WHERE waste_clearance_id = :wcid";
                $stmtMaster = $this->Conn->prepare($sqlUpdateMaster);
                $stmtMaster->execute([
                    ':wcid' => $wcid,
                    ':approver' => $approverData['user_data']->member_id ?? null,
                    ':now' => $now
                ]);

                // 4.2 Update the related transaction details
                $sqlUpdateTx = "UPDATE waste_transaction_detail 
                                SET waste_transaction_detail_status = 'ส่งศูนย์แล้ว' 
                                WHERE waste_clearance_id = :wcid";
                $stmtTx = $this->Conn->prepare($sqlUpdateTx);
                $stmtTx->execute([':wcid' => $wcid]);
                
                // 4.3 Get Faculty ID for this clearance
                $stmtGetFaculty = $this->Conn->prepare("SELECT faculty_id FROM waste_clearance WHERE waste_clearance_id = :wcid");
                $stmtGetFaculty->execute([':wcid' => $wcid]);
                $facultyId = $stmtGetFaculty->fetchColumn();
                if (!$facultyId) {
                    $this->Conn->rollBack();
                    throw new Exception("Faculty not found for this clearance, cannot update stock.", 404);
                }
                
                // 4.4 Get all confirmed weights and update stocks
                $sqlGetDetails = "SELECT waste_type_id, clearance_detail_clearance_weight, clearance_detail_transaction_weight 
                                  FROM clearance_detail WHERE waste_clearance_id = :wcid";
                $stmtGetDetails = $this->Conn->prepare($sqlGetDetails);
                $stmtGetDetails->execute([':wcid' => $wcid]);
                $allClearanceDetails = $stmtGetDetails->fetchAll(PDO::FETCH_ASSOC);

                foreach ($allClearanceDetails as $detailItem) {
                    $itemWeight = $detailItem['clearance_detail_clearance_weight'];
                    $itemTransactionWeight = $detailItem['clearance_detail_transaction_weight'];
                    $wasteTypeId = $detailItem['waste_type_id'];

                    if (isset($itemWeight) && $itemWeight > 0) {
                        // Add to central stock
                        self::UpdateCenterWasteStock($this->Conn, $wasteTypeId, $itemWeight);
                        
                        // Subtract from faculty stock
                        self::DecreaseFacultyWasteStock($this->Conn, $facultyId, $wasteTypeId, $itemTransactionWeight);
                    }
                }
            }

            $this->Conn->commit();
            return true;
        } catch (PDOException $th) {
            if ($this->Conn->inTransaction())
                $this->Conn->rollBack();
            throw new Exception("Database error: " . $th->getMessage(), 500);
        } catch (Exception $ex) {
            if ($this->Conn->inTransaction())
                $this->Conn->rollBack();
            throw new Exception($ex->getMessage(), $ex->getCode() ?: 400);
        }
    }
    //TODO:edit cancle clerance
    public function CancelClearance($clearance_id)
    {
        try {
            if (empty($clearance_id)) {
                throw new Exception('Clearance ID is required', 400);
            }

            $this->Conn->beginTransaction();

            // 1. ตรวจสอบสถานะของ Clearance
            $sqlCheck = "SELECT waste_clearance_status FROM waste_clearance WHERE waste_clearance_id = :id";
            $stmtCheck = $this->Conn->prepare($sqlCheck);
            $stmtCheck->execute([':id' => $clearance_id]);
            $status = $stmtCheck->fetchColumn();

            if (!$status) {
                throw new Exception('ไม่พบรายการเคลียร์ยอดนี้', 404);
            }

            // อนุญาตให้ยกเลิกได้เฉพาะรายการที่ยัง "รอการยืนยัน"
            if ($status !== 'รอการยืนยัน') {
                throw new Exception('ไม่สามารถยกเลิกรายการที่ยืนยันไปแล้วหรือถูกยกเลิกไปแล้วได้', 400);
            }

            // 2. อัปเดต `waste_transaction_detail` ให้กลับไปสถานะเดิม
            // ตั้ง `waste_clearance_id` เป็น NULL และเปลี่ยนสถานะกลับเป็น 'อยู่ที่คลังคณะ'
            $sqlUpdateDetail = "UPDATE waste_transaction_detail
                                SET waste_clearance_id = NULL,
                                    waste_transaction_detail_status = 'อยู่ที่คลังคณะ'
                                WHERE waste_clearance_id = :id";
            $stmtUpdate = $this->Conn->prepare($sqlUpdateDetail);
            $stmtUpdate->execute([':id' => $clearance_id]);

            // 3. ลบรายการย่อยใน `clearance_detail`
            $sqlDeleteDetail = "DELETE FROM clearance_detail WHERE waste_clearance_id = :id";
            $stmtDeleteDetail = $this->Conn->prepare($sqlDeleteDetail);
            $stmtDeleteDetail->execute([':id' => $clearance_id]);

            // 4. ลบรายการหลักใน `waste_clearance`
            $sqlDeleteMaster = "DELETE FROM waste_clearance WHERE waste_clearance_id = :id";
            $stmtDeleteMaster = $this->Conn->prepare($sqlDeleteMaster);
            $stmtDeleteMaster->execute([':id' => $clearance_id]);

            $this->Conn->commit();

            return ['message' => 'ยกเลิกรายการเคลียร์ยอดสำเร็จ'];

        } catch (PDOException $th) {
            if ($this->Conn->inTransaction()) {
                $this->Conn->rollBack();
            }
            error_log("CancelClearance PDO Error: " . $th->getMessage());
            throw new Exception("Database Error: " . $th->getMessage(), 500);
        } catch (Exception $ex) {
            if ($this->Conn->inTransaction()) {
                $this->Conn->rollBack();
            }
            throw new Exception($ex->getMessage(), $ex->getCode() ?: 400);
        }
    }
    // TODO: confirmed detail editing function
    // --- Static Helper Functions ---

    protected static function UpdateCenterWasteStock($conn, $wasteTypeId, $weight)
    {
        try {
            $sql = "INSERT INTO center_waste_stock (waste_type_id, stock_weight, updated_at) 
                    VALUES (:waste_type_id, :weight, :now)
                    ON DUPLICATE KEY UPDATE 
                    stock_weight = stock_weight + VALUES(stock_weight), 
                    updated_at = VALUES(updated_at)";
            
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':waste_type_id' => $wasteTypeId,
                ':weight' => $weight,
                ':now' => date('Y-m-d H:i:s')
            ]);
        } catch (PDOException $e) {
            // Re-throw to be caught by the main function's transaction handler
            throw new Exception("Database error in UpdateCenterWasteStock: " . $e->getMessage(), 500);
        }
    }

    protected static function DecreaseFacultyWasteStock($conn, $facultyId, $wasteTypeId, $weight)
    {
        try {
            $sql = "UPDATE faculty_waste_stock 
                    SET stock_weight = stock_weight - :weight, 
                        updated_at = :now
                    WHERE faculty_id = :faculty_id AND waste_type_id = :waste_type_id";
            
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':weight' => $weight,
                ':now' => date('Y-m-d H:i:s'),
                ':faculty_id' => $facultyId,
                ':waste_type_id' => $wasteTypeId,
            ]);
        } catch (PDOException $e) {
            // Re-throw to be caught by the main function's transaction handler
            throw new Exception("Database error in DecreaseFacultyWasteStock: " . $e->getMessage(), 500);
        }
    }

    protected static function GetPeriodTransactions($query, $conn)
    {
        // ฟังก์ชันนี้จะคำนวณยอดรวมเพื่อเอาไปใส่ในตาราง waste_clearance
        // ต้อง JOIN waste_transaction_detail เพราะข้อมูล fraction/point อยู่ที่นั่น

        $faculty_id = $query['faculty_id'] ?? null;
        $start_date = $query['waste_clearance_period_start'] ?? null;
        $end_date = $query['waste_clearance_period_end'] ?? null;

        $sql = "SELECT 
                    wt.faculty_id,
                    f.faculty_name,
                    -- คำนวณ Point/Fraction รวม
                    COALESCE(SUM(wtd.waste_transaction_detail_fraction), 0) AS faculty_point_total,
                    COALESCE(SUM(wtd.waste_transaction_detail_point), 0) AS member_point_total,
                    -- คำนวณมูลค่ารวม (Weight * Price)
                    COALESCE(SUM(wtd.waste_transaction_detail_weight * type.waste_type_price), 0) AS value_total
                FROM waste_transaction wt
                JOIN waste_transaction_detail wtd ON wt.waste_transaction_id = wtd.waste_transaction_id
                JOIN waste_type type ON wtd.waste_type_id = type.waste_type_id
                LEFT JOIN faculty f ON wt.faculty_id = f.faculty_id
                WHERE wt.faculty_id = :fid 
                  AND wt.waste_transaction_date BETWEEN :start AND :end
                  AND wtd.waste_clearance_id IS NULL"; // เช็คที่ detail ว่ายังไม่ถูกเคลียร์

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':fid' => $faculty_id,
            ':start' => $start_date,
            ':end' => $end_date
        ]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Validation
        if (!$result || $result['value_total'] == 0) {
            throw new Exception("ไม่พบรายการขยะตกค้างในช่วงเวลานี้ หรือรายการทั้งหมดถูกเคลียร์ไปแล้ว", 400);
        }

        return $result;
    }

    protected static function GetTransactionWasteTypeDetail($query, $conn)
    {
        // ฟังก์ชันนี้ดึงข้อมูลเพื่อเอาไป Insert ลง clearance_detail

        $faculty_id = $query['faculty_id'] ?? null;
        $start_date = $query['waste_clearance_period_start'] ?? null;
        $end_date = $query['waste_clearance_period_end'] ?? null;

        $sql = "SELECT 
                    wtd.waste_type_id,
                    type.waste_type_name,
                    COALESCE(SUM(wtd.waste_transaction_detail_weight), 0) AS total_weight
                FROM waste_transaction wt
                JOIN waste_transaction_detail wtd ON wt.waste_transaction_id = wtd.waste_transaction_id
                JOIN waste_type type ON wtd.waste_type_id = type.waste_type_id
                WHERE wt.faculty_id = :fid 
                  AND wt.waste_transaction_date BETWEEN :start AND :end
                  AND wtd.waste_clearance_id IS NULL
                GROUP BY wtd.waste_type_id";

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':fid' => $faculty_id,
            ':start' => $start_date,
            ':end' => $end_date
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function CreateDirectClearance(array $data, $operaterData)
    {
        try {
            if (empty($data['clearance_items']) || !is_array($data['clearance_items'])) {
                throw new Exception('ข้อมูลรายการเคลียร์ยอดไม่ถูกต้อง', 400);
            }

            if (empty($operaterData["user_data"]->waste_center_id)) {
                throw new Exception("ไม่พบข้อมูลศูนย์กลาง", 400);
            }

            // เริ่ม Transaction
            $this->Conn->beginTransaction();

            // 1. เพิ่มน้ำหนักเข้าศูนย์กลาง (center_waste_stock)
            // 2. ลดน้ำหนักคณะตามประเภท (faculty_waste_stock)
            foreach ($data['clearance_items'] as $item) {
                $wasteTypeId = $item['waste_type_id'] ?? null;
                $weight = $item['weight'] ?? 0;

                if (!$wasteTypeId || $weight <= 0) {
                    throw new Exception('ข้อมูลรายการไม่ถูกต้อง', 400);
                }

                // ดึงข้อมูล waste_type
                $typeStmt = $this->Conn->prepare("
                    SELECT waste_type_id, waste_type_price, waste_type_point_per_kg 
                    FROM waste_type WHERE waste_type_id = :id
                ");
                $typeStmt->execute([':id' => $wasteTypeId]);
                $wasteType = $typeStmt->fetch(PDO::FETCH_ASSOC);

                if (!$wasteType) {
                    throw new Exception("ไม่พบประเภทขยะ ID: {$wasteTypeId}", 400);
                }

                // บันทึกลงตาราง center_waste_stock
                $addStockSql = "
                    INSERT INTO center_waste_stock (waste_type_id, stock_weight, updated_at)
                    VALUES (:waste_type_id, :weight, :now)
                    ON DUPLICATE KEY UPDATE 
                        stock_weight = stock_weight + :weight,
                        updated_at = :now
                ";
                $addStockStmt = $this->Conn->prepare($addStockSql);
                $addStockStmt->execute([
                    ':waste_type_id' => $wasteTypeId,
                    ':weight' => $weight,
                    ':now' => date('Y-m-d H:i:s')
                ]);
            }

            // Commit transaction
            $this->Conn->commit();

            return [
                'success' => true,
                'items_processed' => count($data['clearance_items']),
                'total_weight' => array_sum(array_column($data['clearance_items'], 'weight'))
            ];

        } catch (Exception $e) {
            // Rollback on error
            if ($this->Conn->inTransaction()) {
                $this->Conn->rollBack();
            }
            throw $e;
        }
    }
}