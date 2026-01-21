<?php
namespace App\Model;

use App\Utils\Database;
use Exception;
use PDO;
use PDOException;

class WasteSaleModel
{
    private static $Database;
    private $Conn;

    public function __construct()
    {
        self::$Database = new Database();
        $this->Conn = self::$Database->connect();
    }

    public function CreateWasteSale(array $data, $userId)
    {
        try {
            if (empty($data['waste_sale_buyer'])) {
                throw new Exception('กรุณาระบุผู้ซื้อ', 400);
            }
            if (empty($data['items']) || !is_array($data['items'])) {
                throw new Exception('ไม่พบรายการขยะสำหรับขาย', 400);
            }

            $this->Conn->beginTransaction();

            // Pre-calculate totals and validate items
            $totalWeight = 0;
            $totalPrice = 0;
            foreach ($data['items'] as $item) {
                if (empty($item['waste_type_id']) || !isset($item['waste_sale_detail_weight']) || !isset($item['waste_sale_detail_price'])) {
                    throw new Exception('ข้อมูลรายการขายไม่ครบถ้วน', 400);
                }
                $totalWeight += $item['waste_sale_detail_weight'];
                $totalPrice += $item['waste_sale_detail_price'];
            }

            $now = date('Y-m-d H:i:s');

            // 1. Insert into waste_sale
            $saleData = [
                'waste_sale_buyer' => $data['waste_sale_buyer'],
                'waste_sale_total_weight' => $totalWeight,
                'waste_sale_total_price' => $totalPrice,
                'created_by' => $userId,
                'created_at' => $now
            ];
            if (!empty($data['waste_sale_note'])) {
                $saleData['waste_sale_note'] = $data['waste_sale_note'];
            }

            $columns = array_keys($saleData);
            $placeholders = implode(', ', array_fill(0, count($columns), '?'));
            $sql = sprintf('INSERT INTO waste_sale (%s) VALUES (%s)', implode(', ', $columns), $placeholders);
            $stmt = $this->Conn->prepare($sql);
            $stmt->execute(array_values($saleData));
            $wasteSaleId = $this->Conn->lastInsertId();

            // 2. Process items
            $detailSql = "INSERT INTO waste_sale_detail 
                          (waste_sale_id, waste_type_id, waste_sale_detail_weight, waste_sale_detail_price) 
                          VALUES (?, ?, ?, ?)";
            $stmtDetail = $this->Conn->prepare($detailSql);

            foreach ($data['items'] as $item) {
                $weight = $item['waste_sale_detail_weight'];
                $price = $item['waste_sale_detail_price'];

                // Insert sale detail
                $stmtDetail->execute([
                    $wasteSaleId,
                    $item['waste_type_id'],
                    $weight,
                    $price
                ]);

                $inStock = self::CheckCenterStock($this->Conn, $item['waste_type_id'], $weight);
                if ($weight > 0) {
                    if ($inStock['found']) {
                        if ($inStock['less']) {
                            self::DecreaseCenterWasteStock($this->Conn, $item['waste_type_id'], $inStock['stock_weight']);
                        } else {
                            self::DecreaseCenterWasteStock($this->Conn, $item['waste_type_id'], $item['weight']);
                        }
                    }
                }
            }

            $this->Conn->commit();

            return ['waste_sale_id' => $wasteSaleId, 'message' => 'สร้างรายการขายสำเร็จ'];

        } catch (PDOException $e) {
            $this->Conn->rollBack();
            throw new Exception("Database error: " . $e->getMessage());
        } catch (Exception $e) {
            if ($this->Conn->inTransaction()) {
                $this->Conn->rollBack();
            }
            throw new Exception($e->getMessage(), $e->getCode() ?? 400);
        }
    }

    private static function DecreaseCenterWasteStock($conn, $wasteTypeId, $weight)
    {
        try {
            $sql = "UPDATE center_waste_stock 
                    SET stock_weight = stock_weight - :weight, 
                        updated_at = :now
                    WHERE waste_type_id = :waste_type_id";

            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':weight' => $weight,
                ':now' => date('Y-m-d H:i:s'),
                ':waste_type_id' => $wasteTypeId,
            ]);
        } catch (PDOException $e) {
            throw new Exception("Database error in DecreaseCenterWasteStock: " . $e->getMessage(), 500);
        }
    }

    private static function CheckCenterStock($conn, $wasteTypeId, $weight)
    {
        try {
            $result = [];
            $sql = "SELECT cs.stock_weight, wt.waste_type_name 
                    FROM center_waste_stock cs
                    INNER JOIN waste_type wt ON cs.waste_type_id = wt.waste_type_id
                    WHERE cs.waste_type_id = :waste_type_id";

            $stmt = $conn->prepare($sql);
            $stmt->execute([':waste_type_id' => $wasteTypeId]);
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

    public function GetAll($query)
    {
        try {
            $whereClauses = [];
            $params = [];

            // Date range
            if (!empty($query['start_date'])) {
                $whereClauses[] = "DATE(ws.created_at) >= :start_date";
                $params[':start_date'] = $query['start_date'];
            }
            if (!empty($query['end_date'])) {
                $whereClauses[] = "DATE(ws.created_at) <= :end_date";
                $params[':end_date'] = $query['end_date'];
            }
            // Buyer text search
            if (!empty($query['buyer'])) {
                $whereClauses[] = "(ws.waste_sale_buyer LIKE :buyer OR m.member_name LIKE :buyer)";
                $params[':buyer'] = "%" . $query['buyer'] . "%";
            }
            // Optional status
            if (!empty($query['status'])) {
                $whereClauses[] = "ws.waste_sale_status = :status";
                $params[':status'] = $query['status'];
            }
            // Filter by waste type id through details when provided
            $joinDetail = false;
            if (!empty($query['type_id'])) {
                $joinDetail = true;
                $whereClauses[] = "wsd.waste_type_id = :type_id";
                $params[':type_id'] = $query['type_id'];
            }

            $whereSql = !empty($whereClauses) ? " WHERE " . implode(" AND ", $whereClauses) : "";

            $joinSql = $joinDetail ? " LEFT JOIN waste_sale_detail wsd ON ws.waste_sale_id = wsd.waste_sale_id " : "";

            $sqlCount = "SELECT COUNT(DISTINCT ws.waste_sale_id) AS total 
                         FROM waste_sale ws
                         LEFT JOIN member m ON ws.created_by = m.member_id
                         {$joinSql}
                         {$whereSql}";
            $stmtCount = $this->Conn->prepare($sqlCount);
            foreach ($params as $key => $value) {
                $stmtCount->bindValue($key, $value);
            }
            $stmtCount->execute();
            $total = $stmtCount->fetch(PDO::FETCH_ASSOC)['total'];

            $sql = "SELECT 
                        ws.*, 
                        ws.created_at AS waste_sale_date,
                        m.member_name AS creator_name
                    FROM waste_sale ws
                    LEFT JOIN member m ON ws.created_by = m.member_id
                    {$joinSql}
                    {$whereSql}
                    GROUP BY ws.waste_sale_id
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

            $whereSql = " WHERE " . implode(" AND ", $whereClauses);

            $sql = "SELECT 
                        wsd.*,
                        ws.created_at,
                        wt.waste_type_name

                    FROM 
                        waste_sale_detail wsd
                    JOIN 
                        waste_type wt ON wsd.waste_type_id = wt.waste_type_id
                    JOIN 
                        waste_sale ws ON wsd.waste_sale_id = ws.waste_sale_id
                    {$whereSql}
                    ORDER BY ws.created_at DESC";

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
    public function getByIdWithDetails($waste_sale_id)
    {
        try {
            $sql = "SELECT 
                        ws.*,
                        m.member_name AS creator_name
                    FROM waste_sale ws
                    LEFT JOIN member m ON ws.created_by = m.member_id
                    WHERE ws.waste_sale_id = :waste_sale_id";
            $stmt = $this->Conn->prepare($sql);
            $stmt->execute([':waste_sale_id' => $waste_sale_id]);
            $sale = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$sale) {
                return null;
            }

            $detailSql = "SELECT 
                            wsd.*,
                            wt.waste_type_name
                        FROM 
                            waste_sale_detail wsd
                        JOIN 
                            waste_type wt ON wsd.waste_type_id = wt.waste_type_id
                        WHERE 
                            wsd.waste_sale_id = :waste_sale_id";
            $detailStmt = $this->Conn->prepare($detailSql);
            $detailStmt->execute([':waste_sale_id' => $waste_sale_id]);
            $details = $detailStmt->fetchAll(PDO::FETCH_ASSOC);

            $sale['details'] = $details;

            return $sale;

        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }

}