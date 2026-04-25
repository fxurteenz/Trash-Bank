<?php
namespace App\Model;
use App\Utils\Database;
use Exception;
use PDO;
use PDOException;

class StatisticDataModel
{
    private static $Database;
    private $Conn;
    public function __construct()
    {
        self::$Database = new Database();
        $this->Conn = self::$Database->connect();
    }

    /**
     * Build date filter SQL and params from month/year or start_date/end_date.
     */
    private function buildDateFilters(array $query): array
    {
        $whereClauses = [];
        $params = [];

        if (!empty($query['month'])) {
            $whereClauses[] = "MONTH(w.waste_transaction_date) = :month";
            $params[':month'] = $query['month'];
        }
        if (!empty($query['year'])) {
            $whereClauses[] = "YEAR(w.waste_transaction_date) = :year";
            $params[':year'] = $query['year'];
        }
        if (!empty($query['start_date'])) {
            $whereClauses[] = "DATE(w.waste_transaction_date) >= :start_date";
            $params[':start_date'] = $query['start_date'];
        }
        if (!empty($query['end_date'])) {
            $whereClauses[] = "DATE(w.waste_transaction_date) <= :end_date";
            $params[':end_date'] = $query['end_date'];
        }

        $whereSql = !empty($whereClauses) ? "WHERE " . implode(" AND ", $whereClauses) : "";
        return [$whereSql, $params];
    }

    /**
     * Generic date filter builder for any date column.
     */
    private function buildDateFiltersFor(string $column, array $query): array
    {
        $whereClauses = [];
        $params = [];

        if (!empty($query['month'])) {
            $whereClauses[] = "MONTH({$column}) = :month";
            $params[':month'] = $query['month'];
        }
        if (!empty($query['year'])) {
            $whereClauses[] = "YEAR({$column}) = :year";
            $params[':year'] = $query['year'];
        }
        if (!empty($query['start_date'])) {
            $whereClauses[] = "DATE({$column}) >= :start_date";
            $params[':start_date'] = $query['start_date'];
        }
        if (!empty($query['end_date'])) {
            $whereClauses[] = "DATE({$column}) <= :end_date";
            $params[':end_date'] = $query['end_date'];
        }

        $whereSql = !empty($whereClauses) ? "WHERE " . implode(" AND ", $whereClauses) : "";
        return [$whereSql, $params];
    }

    public function GetHomePageStats($query): array
    {
        try {
            [$whereSql, $params] = $this->buildDateFilters($query);

            $sqlTotal = "SELECT 
                        COALESCE(SUM(w.waste_transaction_total_weight), 0) AS total_weight,
                        COALESCE(SUM(w.waste_transaction_total_co2e), 0) AS total_co2e,
                        COALESCE(SUM(w.waste_transaction_total_point), 0) AS total_point
                     FROM waste_transaction w
                     {$whereSql}";

            $stmtTotal = $this->Conn->prepare($sqlTotal);
            $stmtTotal->execute($params);
            $transactionTotal = $stmtTotal->fetch(PDO::FETCH_ASSOC);
            $sqlMember = "SELECT 
                        COALESCE(count(DISTINCT m.member_id), 0) AS member_count
                        FROM member m
                        WHERE m.role_id = 2";
            $stmtMember = $this->Conn->prepare($sqlMember);
            $stmtMember->execute();
            $memberTotal = $stmtMember->fetch(PDO::FETCH_ASSOC);
            $total = $transactionTotal + $memberTotal;

            return $total;
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage(), 500);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }

    }

}
