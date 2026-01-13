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

    /**
     * Get all waste sales with optional filters
     */
    public function GetAllSales($query = []): array
    {
        try {
            $whereClauses = [];
            $params = [];

            // Filter by date range
            if (!empty($query['start_date'])) {
                $whereClauses[] = "DATE(ws.waste_sale_date) >= :start_date";
                $params[':start_date'] = $query['start_date'];
            }
            if (!empty($query['end_date'])) {
                $whereClauses[] = "DATE(ws.waste_sale_date) <= :end_date";
                $params[':end_date'] = $query['end_date'];
            }
            if (!empty($query['date'])) {
                $whereClauses[] = "DATE(ws.waste_sale_date) = :date";
                $params[':date'] = $query['date'];
            }

            // Filter by year/month
            if (!empty($query['year'])) {
                $whereClauses[] = "YEAR(ws.waste_sale_date) = :year";
                $params[':year'] = $query['year'];
            }
            if (!empty($query['month'])) {
                $whereClauses[] = "MONTH(ws.waste_sale_date) = :month";
                $params[':month'] = $query['month'];
            }

            // Filter by waste type
            if (!empty($query['waste_type_id'])) {
                $whereClauses[] = "ws.waste_sale_type_id = :waste_type_id";
                $params[':waste_type_id'] = $query['waste_type_id'];
            }

            // Filter by waste category
            if (!empty($query['category_id'])) {
                $whereClauses[] = "wt.waste_category_id = :category_id";
                $params[':category_id'] = $query['category_id'];
            }

            // Search by buyer name
            if (!empty($query['buyer_search'])) {
                $whereClauses[] = "ws.waste_sale_buyer LIKE :buyer_search";
                $params[':buyer_search'] = "%" . $query['buyer_search'] . "%";
            }

            $whereSql = !empty($whereClauses) ? " WHERE " . implode(" AND ", $whereClauses) : "";

            $sql = "SELECT 
                    ws.*,
                    wt.waste_type_name,
                    wt.waste_type_price,
                    wc.waste_category_name
                FROM 
                    waste_sale ws
                LEFT JOIN 
                    waste_type wt ON ws.waste_sale_type_id = wt.waste_type_id
                LEFT JOIN 
                    waste_category wc ON wt.waste_category_id = wc.waste_category_id
                {$whereSql}
                ORDER BY ws.waste_sale_date DESC, ws.created_at DESC";

            $isPagination = isset($query['page']) && isset($query['limit']);

            if ($isPagination) {
                $page = (int) $query['page'];
                $limit = (int) $query['limit'];
                $offset = ($page - 1) * $limit;

                $sql .= " LIMIT :limit OFFSET :offset";
                $params[':limit'] = $limit;
                $params[':offset'] = $offset;
            }

            $stmt = $this->Conn->prepare($sql);
            $stmt->execute($params);

            if ($isPagination) {
                $countSql = "SELECT COUNT(*) as total FROM waste_sale ws 
                            LEFT JOIN waste_type wt ON ws.waste_sale_type_id = wt.waste_type_id
                            LEFT JOIN waste_category wc ON wt.waste_category_id = wc.waste_category_id
                            {$whereSql}";
                $countStmt = $this->Conn->prepare($countSql);
                $countStmt->execute(array_filter($params, fn($k) => !in_array($k, [':limit', ':offset']), ARRAY_FILTER_USE_KEY));
                $total = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];
                $totalPages = ceil($total / $limit);

                return [
                    'data' => $stmt->fetchAll(PDO::FETCH_ASSOC),
                    'pagination' => [
                        'page' => $page,
                        'limit' => $limit,
                        'total' => $total,
                        'totalPages' => $totalPages
                    ]
                ];
            }

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("DATABASE ERROR: " . $e->getMessage());
            throw new Exception("Failed to fetch waste sales: " . $e->getMessage());
        }
    }

    /**
     * Get a single waste sale by ID
     */
    public function GetSaleById($saleId): array
    {
        try {
            $sql = "SELECT 
                    ws.*,
                    wt.waste_type_name,
                    wt.waste_type_price,
                    wc.waste_category_name
                FROM 
                    waste_sale ws
                LEFT JOIN 
                    waste_type wt ON ws.waste_sale_type_id = wt.waste_type_id
                LEFT JOIN 
                    waste_category wc ON wt.waste_category_id = wc.waste_category_id
                WHERE ws.waste_sale_id = :id";

            $stmt = $this->Conn->prepare($sql);
            $stmt->execute([':id' => $saleId]);
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$result) {
                throw new Exception("Waste sale not found");
            }
            
            return $result;
        } catch (PDOException $e) {
            error_log("DATABASE ERROR: " . $e->getMessage());
            throw new Exception("Failed to fetch waste sale: " . $e->getMessage());
        }
    }

    /**
     * Get waste sales grouped by type for a specific date range
     */
    public function GetSalesSummary($query = []): array
    {
        try {
            $whereClauses = [];
            $params = [];

            if (!empty($query['start_date'])) {
                $whereClauses[] = "DATE(ws.waste_sale_date) >= :start_date";
                $params[':start_date'] = $query['start_date'];
            }
            if (!empty($query['end_date'])) {
                $whereClauses[] = "DATE(ws.waste_sale_date) <= :end_date";
                $params[':end_date'] = $query['end_date'];
            }
            if (!empty($query['date'])) {
                $whereClauses[] = "DATE(ws.waste_sale_date) = :date";
                $params[':date'] = $query['date'];
            }

            $whereSql = !empty($whereClauses) ? " WHERE " . implode(" AND ", $whereClauses) : "";

            $sql = "SELECT 
                    wt.waste_type_id,
                    wt.waste_type_name,
                    wc.waste_category_name,
                    wt.waste_type_price,
                    SUM(ws.waste_sale_weight) as total_weight,
                    SUM(ws.waste_sale_actual_price) as total_revenue,
                    COUNT(ws.waste_sale_id) as transaction_count
                FROM 
                    waste_sale ws
                LEFT JOIN 
                    waste_type wt ON ws.waste_sale_type_id = wt.waste_type_id
                LEFT JOIN 
                    waste_category wc ON wt.waste_category_id = wc.waste_category_id
                {$whereSql}
                GROUP BY wt.waste_type_id, wt.waste_type_name, wc.waste_category_name, wt.waste_type_price
                ORDER BY total_revenue DESC";

            $stmt = $this->Conn->prepare($sql);
            $stmt->execute($params);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("DATABASE ERROR: " . $e->getMessage());
            throw new Exception("Failed to fetch sales summary: " . $e->getMessage());
        }
    }

    /**
     * Create a new waste sale record
     */
    public function CreateSale($data): array
    {
        try {
            // Validate required fields
            if (empty($data['waste_sale_type_id']) || empty($data['waste_sale_weight']) || empty($data['waste_sale_date'])) {
                throw new Exception("Missing required fields: waste_type_id, weight, date");
            }

            $sql = "INSERT INTO waste_sale (
                        waste_sale_type_id,
                        waste_sale_weight,
                        waste_sale_actual_price,
                        waste_sale_buyer,
                        waste_sale_date,
                        created_at
                    ) VALUES (
                        :waste_type_id,
                        :weight,
                        :price,
                        :buyer,
                        :date,
                        NOW()
                    )";

            $stmt = $this->Conn->prepare($sql);
            
            $wasteWeight = (float) $data['waste_sale_weight'];
            $wastePrice = isset($data['waste_sale_actual_price']) ? (float) $data['waste_sale_actual_price'] : null;
            
            $stmt->execute([
                ':waste_type_id' => $data['waste_sale_type_id'],
                ':weight' => $wasteWeight,
                ':price' => $wastePrice,
                ':buyer' => $data['waste_sale_buyer'] ?? null,
                ':date' => $data['waste_sale_date']
            ]);

            $saleId = $this->Conn->lastInsertId();
            
            return $this->GetSaleById($saleId);
        } catch (PDOException $e) {
            error_log("DATABASE ERROR: " . $e->getMessage());
            throw new Exception("Failed to create waste sale: " . $e->getMessage());
        }
    }

    /**
     * Create multiple waste sales in batch (for POS-style transactions)
     */
    public function CreateBatchSales($salesData): array
    {
        try {
            if (empty($salesData) || !is_array($salesData)) {
                throw new Exception("Invalid sales data");
            }

            $createdSales = [];
            
            // Start transaction
            $this->Conn->beginTransaction();

            try {
                foreach ($salesData as $saleData) {
                    $createdSales[] = $this->CreateSale($saleData);
                }

                $this->Conn->commit();
                
                return [
                    'success' => true,
                    'created_count' => count($createdSales),
                    'sales' => $createdSales
                ];
            } catch (Exception $e) {
                $this->Conn->rollBack();
                throw $e;
            }
        } catch (Exception $e) {
            error_log("BATCH ERROR: " . $e->getMessage());
            throw new Exception("Failed to create batch sales: " . $e->getMessage());
        }
    }

    /**
     * Update waste sale
     */
    public function UpdateSale($saleId, $data): array
    {
        try {
            $updateFields = [];
            $params = [':id' => $saleId];

            if (isset($data['waste_sale_weight'])) {
                $updateFields[] = "waste_sale_weight = :weight";
                $params[':weight'] = (float) $data['waste_sale_weight'];
            }

            if (isset($data['waste_sale_actual_price'])) {
                $updateFields[] = "waste_sale_actual_price = :price";
                $params[':price'] = (float) $data['waste_sale_actual_price'];
            }

            if (isset($data['waste_sale_buyer'])) {
                $updateFields[] = "waste_sale_buyer = :buyer";
                $params[':buyer'] = $data['waste_sale_buyer'];
            }

            if (isset($data['waste_sale_date'])) {
                $updateFields[] = "waste_sale_date = :date";
                $params[':date'] = $data['waste_sale_date'];
            }

            if (empty($updateFields)) {
                throw new Exception("No fields to update");
            }

            $sql = "UPDATE waste_sale SET " . implode(", ", $updateFields) . " WHERE waste_sale_id = :id";
            
            $stmt = $this->Conn->prepare($sql);
            $stmt->execute($params);

            return $this->GetSaleById($saleId);
        } catch (PDOException $e) {
            error_log("DATABASE ERROR: " . $e->getMessage());
            throw new Exception("Failed to update waste sale: " . $e->getMessage());
        }
    }

    /**
     * Delete waste sale by ID
     */
    public function DeleteSaleById($saleId): bool
    {
        try {
            $sql = "DELETE FROM waste_sale WHERE waste_sale_id = :id";
            $stmt = $this->Conn->prepare($sql);
            $result = $stmt->execute([':id' => $saleId]);
            
            return $result && $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("DATABASE ERROR: " . $e->getMessage());
            throw new Exception("Failed to delete waste sale: " . $e->getMessage());
        }
    }

    /**
     * Delete multiple waste sales
     */
    public function DeleteSales($ids): array
    {
        try {
            if (empty($ids) || !is_array($ids)) {
                throw new Exception("Invalid IDs provided");
            }

            $placeholders = implode(',', array_fill(0, count($ids), '?'));
            $sql = "DELETE FROM waste_sale WHERE waste_sale_id IN ($placeholders)";
            
            $stmt = $this->Conn->prepare($sql);
            $stmt->execute($ids);
            
            return [
                'success' => true,
                'deleted_count' => $stmt->rowCount()
            ];
        } catch (PDOException $e) {
            error_log("DATABASE ERROR: " . $e->getMessage());
            throw new Exception("Failed to delete waste sales: " . $e->getMessage());
        }
    }
}
