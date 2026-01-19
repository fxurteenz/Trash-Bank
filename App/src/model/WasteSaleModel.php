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
}