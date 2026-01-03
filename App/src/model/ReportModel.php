<?php
namespace App\Model;
use App\Utils\Database;
use Exception;
use PDO;
use PDOException;

class ReportModel
{
    private static $Database;
    private $Conn;
    public function __construct()
    {
        self::$Database = new Database();
        $this->Conn = self::$Database->connect();
    }
    public function OverallReport($queryString): array
    {
        try {
            return [
                'total' => $this->ReportTotal($queryString),
                'total_type' => $this->ReportByType($queryString),
                'total_faculty' => $this->ReportByFaculty($queryString)
            ];
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage(), 500);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function ReportTotal($query)
    {
        $month = $query['month'] ?? null;
        $year = $query['year'] ?? null;

        $whereClauses = [];
        $params = [];

        if ($month) {
            $whereClauses[] = "MONTH(w.waste_transaction_date) = :month";
            $params[':month'] = $month;
        }
        if ($year) {
            $whereClauses[] = "YEAR(w.waste_transaction_date) = :year";
            $params[':year'] = $year;
        }

        $whereSql = !empty($whereClauses) ? "WHERE " . implode(" AND ", $whereClauses) : "";

        $sqlTotal = "SELECT 
                        COALESCE(SUM(w.waste_transaction_weight), 0) AS total_weight,
                        COALESCE(SUM(wt.waste_type_price * w.waste_transaction_weight), 0) AS total_value,
                        COALESCE(SUM(wt.waste_type_co2 * w.waste_transaction_weight), 0) AS total_co2
                     FROM waste_transaction w
                     LEFT JOIN waste_type wt ON w.waste_transaction_waste_type = wt.waste_type_id
                     {$whereSql}";

        $stmtTotal = $this->Conn->prepare($sqlTotal);
        $stmtTotal->execute($params);
        return $stmtTotal->fetch(PDO::FETCH_ASSOC);
    }

    public function ReportByType($query)
    {
        $month = $query['month'] ?? null;
        $year = $query['year'] ?? null;

        $whereClauses = [];
        $params = [];

        if ($month) {
            $whereClauses[] = "MONTH(w.waste_transaction_date) = :month";
            $params[':month'] = $month;
        }
        if ($year) {
            $whereClauses[] = "YEAR(w.waste_transaction_date) = :year";
            $params[':year'] = $year;
        }

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
                    GROUP BY wt.waste_type_id
                    ORDER BY wc.waste_category_id ASC, total_weight DESC";

        $stmtType = $this->Conn->prepare($sqlType);
        $stmtType->execute($params);
        $rawTypes = $stmtType->fetchAll(PDO::FETCH_ASSOC);

        $categories = [];
        foreach ($rawTypes as $row) {
            $catId = $row['waste_category_id'] ?? 0;
            $catName = $row['waste_category_name'] ?? 'Uncategorized';

            if (!isset($categories[$catId])) {
                $categories[$catId] = [
                    'waste_category_id' => $catId,
                    'waste_category_name' => $catName,
                    'total_weight' => 0,
                    'total_value' => 0,
                    'total_co2' => 0,
                    'details' => []
                ];
            }

            $categories[$catId]['total_weight'] += (float) $row['total_weight'];
            $categories[$catId]['total_value'] += (float) $row['total_value'];
            $categories[$catId]['total_co2'] += (float) $row['total_co2'];

            $categories[$catId]['details'][] = [
                'waste_type_id' => $row['waste_type_id'],
                'waste_type_name' => $row['waste_type_name'],
                'total_weight' => (float) $row['total_weight'],
                'total_value' => (float) $row['total_value'],
                'total_co2' => (float) $row['total_co2']
            ];
        }
        return array_values($categories);
    }

    public function ReportByFaculty($query)
    {
        $month = $query['month'] ?? null;
        $year = $query['year'] ?? null;

        $whereClauses = [];
        $params = [];

        if ($month) {
            $whereClauses[] = "MONTH(w.waste_transaction_date) = :month";
            $params[':month'] = $month;
        }
        if ($year) {
            $whereClauses[] = "YEAR(w.waste_transaction_date) = :year";
            $params[':year'] = $year;
        }

        $whereSql = !empty($whereClauses) ? "WHERE " . implode(" AND ", $whereClauses) : "";

        $sqlFaculty = "SELECT 
                            f.faculty_id,
                            f.faculty_name,
                            COALESCE(SUM(w.waste_transaction_faculty_fraction), 0) AS total_fraction,
                            COALESCE(SUM(w.waste_transaction_weight), 0) AS total_weight,
                            COALESCE(SUM(wt.waste_type_price * w.waste_transaction_weight), 0) AS total_value,
                            COALESCE(SUM(wt.waste_type_co2 * w.waste_transaction_weight), 0) AS total_co2
                       FROM waste_transaction w
                       LEFT JOIN waste_type wt ON w.waste_transaction_waste_type = wt.waste_type_id
                       LEFT JOIN faculty f ON w.faculty_id = f.faculty_id
                       {$whereSql}
                       GROUP BY f.faculty_id
                       ORDER BY total_weight DESC";

        $stmtFaculty = $this->Conn->prepare($sqlFaculty);
        $stmtFaculty->execute($params);
        return $stmtFaculty->fetchAll(PDO::FETCH_ASSOC);
    }
}
