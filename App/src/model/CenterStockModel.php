<?php
namespace App\Model;

use App\Utils\Database;
use Exception;
use PDO;
use PDOException;

class CenterStockModel
{
    private $Conn;

    public function __construct()
    {
        $this->Conn = (new Database())->connect();
    }

    public function getCenterStock($query = [])
    {
        try {
            $sql = "SELECT 
                        cws.waste_type_id,
                        cws.stock_weight,
                        wt.waste_type_name,
                        wc.waste_category_id,
                        wc.waste_category_name
                    FROM center_waste_stock cws
                    JOIN waste_type wt ON cws.waste_type_id = wt.waste_type_id
                    JOIN waste_category wc ON wt.waste_category_id = wc.waste_category_id
                    WHERE cws.stock_weight > 0";
            
            // Allow searching
            if (!empty($query['search'])) {
                $sql .= " AND (wt.waste_type_name LIKE :search OR cws.waste_type_id LIKE :search)";
            }

            $sql .= " ORDER BY wt.waste_type_name ASC";
            
            $stmt = $this->Conn->prepare($sql);

            if (!empty($query['search'])) {
                $stmt->bindValue(':search', '%' . $query['search'] . '%');
            }

            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if(!$result) {
                return [];
            }
            return $result;
        } catch (PDOException $e) {
            throw new Exception("Database error in getCenterStock: " . $e->getMessage(), 500);
        }
    }
}
