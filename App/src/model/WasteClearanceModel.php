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

    public function CreateClearance(array $data, $centerData): array
    {
        try {
            if (!is_array($data)) {
                throw new Exception('Invalid data format', 400);
            }

            if (empty($data['faculty_id'])) {
                // error_log("ERROR : waste_type is missing");
                throw new Exception('กรุณาลองอีกครั้ง, ระบุข้อมูลผู้ทำการฝาก', 400);
            }

            // Prepare items
            $items = [];
            if (isset($data['items']) && is_array($data['items'])) {
                $items = $data['items'];
            } elseif (isset($data['waste_category_id']) && isset($data['waste_type_id']) && isset($data['clearance_weight'])) {
                // Support single item (legacy)
                $items[] = $data;
            }

            if (empty($items)) {
                throw new Exception('กรุณาลองอีกครั้ง, ไม่พบรายการขยะ', 400);
            }

            /* Start SQL Transaction */
            $this->Conn->beginTransaction();

            $totalPoints = 0;
            $totalWeight = 0;
            $details = [];

            foreach ($items as $item) {
                if (empty($item['waste_category_id']) || empty($item['waste_type_id']) || !isset($item['clearance_weight'])) {
                    throw new Exception('ข้อมูลรายการขยะไม่ครบถ้วน', 400);
                }

                $rateResult = self::GetWasteTypeRate($this->Conn, $item["waste_type_id"]);

                $value = ($rateResult["waste_type_price"] * $item["clearance_weight"]) / 2 * 10;
                $integer_point = (int) floor($value);

                $totalWeight += $item["clearance_weight"];
                $totalPoints += $integer_point;

                $details[] = [
                    'waste_category_id' => $item["waste_category_id"],
                    'waste_type_id' => $item["waste_type_id"],
                    'weight' => $item["clearance_weight"],
                    'rate' => $rateResult["waste_type_price"],
                    'point' => $integer_point
                ];
            }

            // 1. Insert Header
            $headerSql = "INSERT INTO waste_clearance SET 
                faculty_id = :fid, 
                center_staff_id = :staffid,
                waste_clearance_total_weight = :tw,
                waste_clearance_total_point = :tp,
                created_at = :created";

            $stmtHeader = $this->Conn->prepare($headerSql);
            $stmtHeader->execute([
                ':fid' => $data["faculty_id"],
                ':staffid' => $centerData["user_data"]->member_id,
                ':tw' => $totalWeight,
                ':tp' => $totalPoints,
                ':created' => date('Y-m-d H:i:s')
            ]);
            $clearanceId = $this->Conn->lastInsertId();

            // 2. Insert Details and Update Stock
            $detailSql = "INSERT INTO waste_clearance_detail SET
                waste_clearance_id = :tid,
                waste_category_id = :cid,
                waste_type_id = :typeid,
                waste_clearance_detail_weight = :w,
                waste_clearance_detail_rate = :r,
                waste_clearance_detail_point = :p";

            $stmtDetail = $this->Conn->prepare($detailSql);
            foreach ($details as $d) {
                $stmtDetail->execute([
                    ':tid' => $clearanceId,
                    ':cid' => $d['waste_category_id'],
                    ':typeid' => $d['waste_type_id'],
                    ':w' => $d['weight'],
                    ':r' => $d['rate'],
                    ':p' => $d['point']
                ]);
                $inStock = self::CheckFacultyStock($this->Conn, $data["faculty_id"], $d["waste_type_id"], $d["weight"]);
                self::UpdateCenterWasteStock($this->Conn, $d["waste_type_id"], $d['weight']);
                if ($inStock['found']){
                    if ($inStock['less']) {
                        self::DecreaseFacultyWasteStock($this->Conn, $data["faculty_id"], $d['waste_type_id'], $inStock['stock_weight']);
                    } else {
                        self::DecreaseFacultyWasteStock($this->Conn, $data["faculty_id"], $d['waste_type_id'], $d['weight']);
                    }
                }  
            }

            $updatedFaculty = self::UpdateFacultyPoint($this->Conn, $data["faculty_id"], $totalPoints);
            $this->Conn->commit();

            return [
                'transaction_id' => $clearanceId,
                'total_faculty_point' => $updatedFaculty['faculty_point'] ?? null,
                'items_count' => count($details)
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

    //TODO:edit cancle clerance
    public function CancelClearance($clearance_id)
    {
        try {
            if (empty($clearance_id)) {
                throw new Exception('Clearance ID is required', 400);
            }

            $this->Conn->beginTransaction();

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

    // --- Static Helper Functions ---//

    private static function GetWasteTypeRate($conn, $wasteTypeId)
    {
        try {
            $wasteRateSql = "SELECT 
                    waste_type_price,waste_type_co2
                FROM
                    waste_type
                WHERE
                    waste_type_id = :waste_type_id";
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

    private static function CheckFacultyStock($conn, $facultyId, $wasteTypeId, $weight)
    {
        try {
            $result = [];
            $sql = "SELECT ft.stock_weight, wt.waste_type_name 
                    FROM faculty_waste_stock ft
                    INNER JOIN waste_type wt ON ft.waste_type_id = wt.waste_type_id
                    WHERE ft.faculty_id = :faculty_id AND ft.waste_type_id = :waste_type_id";

            $stmt = $conn->prepare($sql);
            $stmt->execute([':faculty_id' => $facultyId, ':waste_type_id' => $wasteTypeId]);
            $stock = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$stock) {
                $result['found'] = false;
                return $result;
            }
            if ($stock['stock_weight'] < $weight) {
                $result['found'] = true;
                $result['less'] = true;
                $result['stock_weight'] = $stock['stock_weight'];
            } else {
                $result['found'] = true;
                $result['less'] = false;
            }

            return $result;
        } catch (PDOException $e) {
            throw new Exception("error while checking faculty stock : " . $e->getMessage(), 500);
        }
    }

    private static function UpdateFacultyPoint($conn, $facultyId, $point)
    {
        try {
            $sql = "UPDATE faculty SET faculty_point = faculty_point + :point WHERE faculty_id = :faculty_id";

            $stmt = $conn->prepare($sql);
            $stmt->bindValue(":faculty_id", $facultyId, PDO::PARAM_INT);
            $stmt->bindValue(":point", $point, PDO::PARAM_INT);
            $stmt->execute();

            $sql = "SELECT faculty_point FROM faculty WHERE faculty_id = :faculty_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(":faculty_id", $facultyId, PDO::PARAM_INT);
            $stmt->execute();
            $updatedFaculty = $stmt->fetch(PDO::FETCH_ASSOC);

            return $updatedFaculty;
        } catch (PDOException $e) {
            // Re-throw to be caught by the main function's transaction handler
            throw new Exception("Error while updating faculty point : " . $e->getMessage(), 500);
        }
    }

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
            throw new Exception("error while decreasing faculty stock : " . $e->getMessage(), 500);
        }
    }

}