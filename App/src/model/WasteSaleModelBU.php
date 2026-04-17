<?php
namespace App\Model;

use App\Utils\Database;
use Exception;
use PDO;
use PDOException;

class WasteSaleModelBU
{
    private static $Database;
    private $Conn;

    public function __construct()
    {
        self::$Database = new Database();
        $this->Conn = self::$Database->connect();
    }

    public function GetAll($query)
    {
        try {
            $whereClauses = [];
            $params = [];

            if (!empty($query['start_date'])) {
                $whereClauses[] = "ws.waste_sale_date >= :start_date";
                $params[':start_date'] = $query['start_date'];
            }
            if (!empty($query['end_date'])) {
                $whereClauses[] = "ws.waste_sale_date <= :end_date";
                $params[':end_date'] = $query['end_date'];
            }
            if (!empty($query['status'])) {
                $whereClauses[] = "ws.waste_sale_status = :status";
                $params[':status'] = $query['status'];
            }

            $whereSql = !empty($whereClauses) ? " WHERE " . implode(" AND ", $whereClauses) : "";

            $sql = "SELECT 
                        ws.*,
                        m.member_name AS creator_name
                    FROM waste_sale ws
                    LEFT JOIN member m ON ws.created_by = m.member_id
                    {$whereSql}
                    ORDER BY ws.created_at DESC";

            // Pagination Logic
            $isPagination = isset($query['page']) && isset($query['limit']);
            if ($isPagination) {
                $page = (int) $query['page'];
                $limit = (int) $query['limit'];
                $offset = ($page - 1) * $limit;
                $sql .= " LIMIT :limit OFFSET :offset";
            }

            $stmt = $this->Conn->prepare($sql);
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            if ($isPagination) {
                $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
                $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            }
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $total = count($result);

            if ($isPagination) {
                $sqlCount = "SELECT COUNT(*) AS total FROM waste_sale {$whereSql}";
                $stmtCount = $this->Conn->prepare($sqlCount);
                foreach ($params as $key => $value) {
                    $stmtCount->bindValue($key, $value);
                }
                $stmtCount->execute();
                $total = $stmtCount->fetch(PDO::FETCH_ASSOC)['total'];
            }

            return [
                'data' => $result,
                'total' => $total
            ];

        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
    public function GetWasteSaleDetail($waste_sale_id, $query)
    {
        try {
            $params = [':waste_sale_id' => $waste_sale_id];
            $whereClauses = ["wsd.waste_sale_id = :waste_sale_id"];

            if (!empty($query['waste_type_id'])) {
                $whereClauses[] = "wt.waste_type_id = :waste_type_id";
                $params[':waste_type_id'] = $query['waste_type_id'];
            }
            if (!empty($query['waste_type_name'])) {
                $whereClauses[] = "wt.waste_type_name LIKE :waste_type_name";
                $params[':waste_type_name'] = "%" . $query['waste_type_name'] . "%";
            }
            if (isset($query['waste_sale_detail_status']) && $query['waste_sale_detail_status'] !== '') {
                $whereClauses[] = "wsd.waste_sale_detail_status = :waste_sale_detail_status";
                $params[':waste_sale_detail_status'] = $query['waste_sale_detail_status'];
            }

            $whereSql = " WHERE " . implode(" AND ", $whereClauses);

            $sql = "SELECT 
                        wsd.*,
                        ws.waste_sale_date,
                        ws.created_at,
                        wt.waste_type_name

                    FROM 
                        waste_sale_detail wsd
                    JOIN 
                        waste_type wt ON wsd.waste_type_id = wt.waste_type_id
                    JOIN 
                        waste_sale ws ON wsd.waste_sale_id = ws.waste_sale_id
                    {$whereSql}
                    ORDER BY ws.waste_sale_date DESC";

            $isPagination = isset($query['page']) && isset($query['limit']);
            if ($isPagination) {
                $page = (int) $query['page'];
                $limit = (int) $query['limit'];
                $offset = ($page - 1) * $limit;
                $sql .= " LIMIT :limit OFFSET :offset";
            }

            $stmt = $this->Conn->prepare($sql);
            foreach ($params as $key => &$value) {
                $stmt->bindParam($key, $value);
            }
            if ($isPagination) {
                $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
                $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            }

            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $sqlCount = "SELECT COUNT(*) as total FROM waste_sale_detail wsd JOIN waste_type wt ON wsd.waste_type_id = wt.waste_type_id {$whereSql}";
            $stmtCount = $this->Conn->prepare($sqlCount);
            foreach ($params as $key => &$value) {
                $stmtCount->bindParam($key, $value);
            }
            $stmtCount->execute();
            $total = $stmtCount->fetch(PDO::FETCH_ASSOC)['total'];

            return [
                'data' => $result,
                'total' => $total
            ];
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }
    public function GetWasteSaleWithDetails($query)
    {
        try {
            // Get waste sales with pagination
            $wasteSalesResult = $this->GetAll($query);
            $wasteSales = $wasteSalesResult['data'];
            $total = $wasteSalesResult['total'];

            if (empty($wasteSales)) {
                return [
                    'data' => [],
                    'total' => 0
                ];
            }

            // Extract waste_sale_ids
            $wasteSaleIds = array_column($wasteSales, 'waste_sale_id');

            // Fetch all details for the retrieved waste sales
            $placeholders = implode(',', array_fill(0, count($wasteSaleIds), '?'));
            $sql = "SELECT 
                        wsd.*,
                        wt.waste_type_name
                    FROM 
                        waste_sale_detail wsd
                    JOIN 
                        waste_type wt ON wsd.waste_type_id = wt.waste_type_id
                    WHERE 
                        wsd.waste_sale_id IN ($placeholders)";

            $stmt = $this->Conn->prepare($sql);
            $stmt->execute($wasteSaleIds);
            $allDetails = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Group details by waste_sale_id
            $detailsMap = [];
            foreach ($allDetails as $detail) {
                $detailsMap[$detail['waste_sale_id']][] = $detail;
            }

            // Attach details to each waste sale
            foreach ($wasteSales as &$sale) {
                $sale['waste_sale_detail'] = isset($detailsMap[$sale['waste_sale_id']]) ? $detailsMap[$sale['waste_sale_id']] : [];
            }

            return [
                'data' => $wasteSales,
                'total' => $total
            ];

        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
    public function CreateWasteSale($data, $memberId)
    {
        try {
            $this->Conn->beginTransaction();

            if (empty($data['waste_sale_date'])) {
                throw new Exception("ระบุวันที่ ในการใช้อ้างอิงขยะที่รับเข้าศูนย์(ก่อนวันที่ระบุทั้งหมด)", 400);
            }

            // Step 1: Insert into waste_sale dynamically
            $data['created_by'] = $memberId;
            $data['created_at'] = date('Y-m-d');

            $setClauses = [];
            $updateData = [];
            foreach ($data as $column => $value) {
                if ($value !== '' && $value !== null) {
                    $setClauses[] = "`{$column}` = :{$column}";
                    $updateData[$column] = $value;
                }
            }
            $setClauseString = implode(', ', $setClauses);

            $sql =
                "INSERT INTO 
                    waste_sale 
                SET
                    {$setClauseString}
                ";

            $stmt = $this->Conn->prepare($sql);
            $stmt->execute($updateData);

            $wasteSaleId = $this->Conn->lastInsertId();

            // Step 2: Find relevant waste_clearance_ids from waste_transaction_detail
            $stmt = $this->Conn->prepare(
                "SELECT DISTINCT wtd.waste_clearance_id
                        FROM waste_transaction_detail wtd
                        JOIN waste_clearance wc ON wtd.waste_clearance_id = wc.waste_clearance_id
                        WHERE wtd.waste_transaction_detail_status = 'อยู่ที่คลังศูนย์'
                            AND wc.waste_clearance_status = 'ยืนยันแล้ว'
                            AND wc.created_at <= ?"
            );
            $stmt->execute([$data['waste_sale_date']]);
            $clearanceIds = $stmt->fetchAll(PDO::FETCH_COLUMN);

            if (empty($clearanceIds)) {
                throw new Exception("ไม่มีขยะในระบบ หรือช่วงเวลานี้เคยทำการจำหน่ายแล้ว", 400);
            }

            // Step 3: Aggregate weights from clearance_detail for the found clearances
            $placeholders_clearance = implode(',', array_fill(0, count($clearanceIds), '?'));
            $stmt = $this->Conn->prepare(
                "SELECT waste_type_id, SUM(clearance_detail_clearance_weight) as total_weight
                        FROM clearance_detail
                        WHERE waste_clearance_id IN ($placeholders_clearance)
                        GROUP BY waste_type_id"
            );
            $stmt->execute($clearanceIds);
            $saleDetailsData = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Step 4: Insert into waste_sale_detail
            $stmt = $this->Conn->prepare(
                'INSERT INTO waste_sale_detail (waste_sale_id, waste_type_id, waste_sale_detail_data_weight) VALUES (?, ?, ?)'
            );
            foreach ($saleDetailsData as $detail) {
                $stmt->execute([$wasteSaleId, $detail['waste_type_id'], $detail['total_weight']]);
            }

            // Step 5: Update waste_transaction_detail status and link sale
            $stmt = $this->Conn->prepare(
                "UPDATE waste_transaction_detail
                        SET waste_transaction_detail_status = 'เตรียมจำหน่าย',
                            waste_sale_id = ?
                        WHERE waste_clearance_id IN ($placeholders_clearance)
                            AND waste_transaction_detail_status = 'อยู่ที่คลังศูนย์'"
            );
            $execute_params = array_merge([$wasteSaleId], $clearanceIds);
            $stmt->execute($execute_params);

            $this->Conn->commit();

            return ['saleDetailsData' =>$saleDetailsData, 'wasteSaleId' => $wasteSaleId];

        } catch (PDOException $e) {
            $this->Conn->rollBack();
            throw new Exception("Database error : " . $e->getMessage());
        } catch (Exception $e) {
            $this->Conn->rollBack();
            throw new Exception($e->getMessage());
        }
    }
    public function ConfirmWasteSaleDetail($wasteSaleDetailId, $data)
    {
        // Assuming waste_sale_detail_status column exists in waste_sale_detail (e.g., TINYINT, default 0)
        // Assuming waste_sale_status and waste_sale_completed_at columns exist in waste_sale
        // Assuming waste_transaction_detail_status enum is expanded to support state 5

        try {
            if (empty($data['waste_sale_detail_sold_weight'])) {
                throw new Exception("กรุณาระบุ นำหนักที่ชั่งได้", 400);
            }
            if (empty($data['waste_sale_detail_sold_rate'])) {
                throw new Exception("กรุณาระบุราคาต่อหน่วยที่รับซื้อ", 400);
            }
            if (empty($data['waste_sale_detail_total_price'])) {
                throw new Exception("กรุณาระบุราคาสุทธิ", 400);
            }
            $this->Conn->beginTransaction();

            // Step 1: Update waste_sale_detail
            $stmt = $this->Conn->prepare(
                'UPDATE waste_sale_detail 
                        SET waste_sale_detail_sold_weight = ?, 
                            waste_sale_detail_sold_rate = ?, 
                            waste_sale_detail_total_price = ?, 
                            waste_sale_detail_status = 1
                        WHERE waste_sale_detail_id = ?'
            );
            $stmt->execute([
                $data['waste_sale_detail_sold_weight'],
                $data['waste_sale_detail_sold_rate'],
                $data['waste_sale_detail_total_price'],
                $wasteSaleDetailId
            ]);

            // Step 2: Get waste_sale_id for the updated detail
            $stmt = $this->Conn->prepare('SELECT waste_sale_id FROM waste_sale_detail WHERE waste_sale_detail_id = ?');
            $stmt->execute([$wasteSaleDetailId]);
            $wasteSaleId = $stmt->fetchColumn();

            if (!$wasteSaleId) {
                throw new Exception("Waste sale detail not found.");
            }

            // Step 3: Check if all details for this sale are complete
            $stmt = $this->Conn->prepare(
                'SELECT COUNT(*) 
                        FROM waste_sale_detail 
                        WHERE waste_sale_id = ? AND (waste_sale_detail_status IS NULL OR waste_sale_detail_status != 1)'
            );
            $stmt->execute([$wasteSaleId]);
            $incompleteCount = $stmt->fetchColumn();

            if ($incompleteCount == 0) {
                // All details are complete, proceed to update parent sale and transaction details

                // Step 4: Update waste_sale status and completion date
                $date = date('Y-m-d H:i:s');
                $stmt = $this->Conn->prepare(
                    'UPDATE waste_sale 
                            SET waste_sale_status = 1, waste_sale_completed_at = ? 
                            WHERE waste_sale_id = ?'
                );
                $stmt->execute([$date, $wasteSaleId]);

                // Step 5: Find the clearance IDs again to update transaction details
                // First, get the sale date
                $stmt = $this->Conn->prepare('SELECT waste_sale_date FROM waste_sale WHERE waste_sale_id = ?');
                $stmt->execute([$wasteSaleId]);
                $saleDate = $stmt->fetchColumn();

                // Find relevant clearance_ids based on the original logic
                $stmt = $this->Conn->prepare(
                    "SELECT DISTINCT wc.waste_clearance_id
                            FROM waste_clearance wc
                            JOIN waste_transaction_detail wtd ON wc.waste_clearance_id = wtd.waste_clearance_id
                            WHERE wc.waste_clearance_status = 'ยืนยันแล้ว'
                                AND wc.created_at < ?
                                AND wtd.waste_transaction_detail_status = 'เตรียมจำหน่าย'"
                );
                $stmt->execute([$saleDate]);
                $clearanceIds = $stmt->fetchAll(PDO::FETCH_COLUMN);

                if (!empty($clearanceIds)) {
                    // Step 6: Update waste_transaction_detail status to 5
                    $placeholders = implode(',', array_fill(0, count($clearanceIds), '?'));
                    $stmt = $this->Conn->prepare(
                        "UPDATE waste_transaction_detail
                                SET waste_transaction_detail_status = 'จำหน่ายแล้ว' -- Assuming 5 is 'จำหน่ายแล้ว'
                                WHERE waste_clearance_id IN ($placeholders)
                                    AND waste_transaction_detail_status = 'เตรียมจำหน่าย'"
                    );
                    $stmt->execute($clearanceIds);
                }
            }

            $this->Conn->commit();
            if ($incompleteCount > 0) {
                return [
                    'incompleteCount' => $incompleteCount
                ];
            } else {
                return [
                    'incompleteCount' => $incompleteCount,
                    'wasteSaleId' => $wasteSaleId,
                    'wasteClearanceIds' => $clearanceIds,
                ];
            }

        } catch (PDOException $e) {
            $this->Conn->rollBack();
            throw new Exception("Database error: " . $e->getMessage());
        } catch (Exception $e) {
            $this->Conn->rollBack();
            throw new Exception($e->getMessage());
        }
    }
    public function CancelWasteSale($wasteSaleId)
    {
        try {
            $this->Conn->beginTransaction();

            // Step 1: Get sale info and check if it can be cancelled
            $stmt = $this->Conn->prepare('SELECT waste_sale_status FROM waste_sale WHERE waste_sale_id = ?');
            $stmt->execute([$wasteSaleId]);
            $sale = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$sale) {
                throw new Exception("ไม่พบข้อมูลการขายขยะ", 404);
            }

            if ($sale['waste_sale_status'] == 1) {
                throw new Exception("ไม่สามารถยกเลิกการขายที่เสร็จสมบูรณ์แล้วได้", 400);
            }

            // Step 2: Revert status of transaction details and unlink them from the sale
            $updateStmt = $this->Conn->prepare(
                "UPDATE waste_transaction_detail
                        SET waste_transaction_detail_status ',
                            waste_sale_id = NULL
                        WHERE waste_sale_id = ?"
            );
            $updateStmt->execute([$wasteSaleId]);

            // Step 3: Delete from waste_sale_detail
            $stmt = $this->Conn->prepare('DELETE FROM waste_sale_detail WHERE waste_sale_id = ?');
            $stmt->execute([$wasteSaleId]);

            // Step 4: Delete from waste_sale
            $stmt = $this->Conn->prepare('DELETE FROM waste_sale WHERE waste_sale_id = ?');
            $stmt->execute([$wasteSaleId]);

            $this->Conn->commit();

            return ['message' => 'การขายขยะถูกยกเลิกเรียบร้อยแล้ว'];

        } catch (PDOException $e) {
            $this->Conn->rollBack();
            throw new Exception("Database error: " . $e->getMessage());
        } catch (Exception $e) {
            $this->Conn->rollBack();
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }
}

