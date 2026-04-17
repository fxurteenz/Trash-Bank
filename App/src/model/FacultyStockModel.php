<?php
namespace App\Model;

use App\Utils\Database;
use Exception;
use PDO;
use PDOException;

class FacultyStockModel
{
    private $Conn;

    public function __construct()
    {
        $this->Conn = (new Database())->connect();
    }

    public function getFacultyStock($facultyId, $query = [])
    {
        if (empty($facultyId)) {
            throw new Exception("Faculty ID is required", 400);
        }
        try {
            $where = " WHERE fws.faculty_id = :faculty_id AND fws.stock_weight > 0";
            $params = [];
            $params[':faculty_id'] = $facultyId;

            if (!empty($query['search'])) {
                $where .= " AND (wt.waste_type_name LIKE :search OR fws.waste_type_id LIKE :search)";
                $params[':search'] = '%' . $query['search'] . '%';
            }

            $isPagination = isset($query['page']) && isset($query['limit']);
            $total = 0;

            if ($isPagination) {
                $sqlCount = "SELECT COUNT(*) FROM faculty_waste_stock fws 
                         JOIN waste_type wt ON fws.waste_type_id = wt.waste_type_id 
                         $where";
                $stmtCount = $this->Conn->prepare($sqlCount);
                foreach ($params as $key => $val) {
                    $stmtCount->bindValue($key, $val);
                }
                $stmtCount->execute();
                $total = (int) $stmtCount->fetchColumn();
            }

            $sql = "SELECT fws.waste_type_id, fws.stock_weight, wt.waste_type_name, 
                       wc.waste_category_id, wc.waste_category_name
                FROM faculty_waste_stock fws
                JOIN waste_type wt ON fws.waste_type_id = wt.waste_type_id
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
            throw new Exception("Database error in getFacultyStock: " . $e->getMessage(), 500);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 500);
        }
    }
}
