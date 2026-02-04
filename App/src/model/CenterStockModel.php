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
            $where = " WHERE cws.stock_weight > 0";
            $params = [];

            if (!empty($query['search'])) {
                $where .= " AND (wt.waste_type_name LIKE :search OR cws.waste_type_id LIKE :search)";
                $params[':search'] = '%' . $query['search'] . '%';
            }

            $isPagination = isset($query['page']) && isset($query['limit']);
            $total = 0; 

            if ($isPagination) {
                $sqlCount = "SELECT COUNT(*) FROM center_waste_stock cws 
                         JOIN waste_type wt ON cws.waste_type_id = wt.waste_type_id 
                         $where";
                $stmtCount = $this->Conn->prepare($sqlCount);
                foreach ($params as $key => $val) {
                    $stmtCount->bindValue($key, $val);
                }
                $stmtCount->execute();
                $total = (int) $stmtCount->fetchColumn();
            }

            $sql = "SELECT cws.waste_type_id, cws.stock_weight, wt.waste_type_name, 
                       wc.waste_category_id, wc.waste_category_name
                FROM center_waste_stock cws
                JOIN waste_type wt ON cws.waste_type_id = wt.waste_type_id
                JOIN waste_category wc ON wt.waste_category_id = wc.waste_category_id
                $where
                ORDER BY wt.waste_type_name ASC";

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
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (!$isPagination) {
                $total = count($result);
            }

            return ['data' => $result, 'total' => $total];

        } catch (PDOException $e) {
            throw new Exception("Database error in getCenterStock: " . $e->getMessage(), 500);
        }
    }
}
