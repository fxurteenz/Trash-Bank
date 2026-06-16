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
                            COALESCE(count(DISTINCT m.member_id), 0) AS member_count,
                            SUM(CASE WHEN role_id = 1 THEN 1 ELSE 0 END) as user_count,
                            SUM(CASE WHEN role_id = 2 THEN 1 ELSE 0 END) as professor_count,
                            SUM(CASE WHEN role_id = 3 THEN 1 ELSE 0 END) as employee_count,
                            SUM(CASE WHEN role_id = 2 THEN 1 ELSE 0 END) + SUM(CASE WHEN role_id = 3 THEN 1 ELSE 0 END) as professor_employee_count
                        FROM member m
                        WHERE m.role_id IN (1,2,3)";
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

    public function GetMemberStats(int $memberId, array $query = []): array
    {
        try {
            [$whereSql, $params] = $this->buildDateFilters($query);
            $result = ['transaction_breakdown' => [], 'category_breakdown' => []];
            $params[':member_id'] = $memberId;
            $whereSql = empty($whereSql) ? "WHERE w.member_id = :member_id" : $whereSql . " AND w.member_id = :member_id";

            $sql = "SELECT 
                        COUNT(w.waste_transaction_id) AS total_transactions,
                        COALESCE(SUM(w.waste_transaction_total_weight), 0) AS total_weight,
                        COALESCE(SUM(w.waste_transaction_total_point), 0) AS total_point,
                        COALESCE(SUM(w.waste_transaction_total_co2e), 0) AS total_co2e
                    FROM waste_transaction w
                    {$whereSql}";

            $stmt = $this->Conn->prepare($sql);
            $stmt->execute($params);
            $wtresult = $stmt->fetch(PDO::FETCH_ASSOC);

            $result['transaction_breakdown'] = $wtresult ?: [
                'total_transactions' => 0,
                'total_weight' => 0,
                'total_point' => 0,
                'total_co2e' => 0
            ];

            $sqlDetail = "SELECT 
                            d.waste_category_id,
                            c.waste_category_name,
                            COALESCE(SUM(d.waste_transaction_detail_weight), 0) AS category_total_weight
                        FROM waste_transaction w
                        JOIN waste_transaction_detail d ON w.waste_transaction_id = d.waste_transaction_id
                        LEFT JOIN waste_category c ON d.waste_category_id = c.waste_category_id
                        {$whereSql}
                        GROUP BY d.waste_category_id";

            $stmtDetail = $this->Conn->prepare($sqlDetail);
            $stmtDetail->execute($params);
            $result['category_breakdown'] = $stmtDetail->fetchAll(PDO::FETCH_ASSOC);

            return $result;
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage(), 500);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

}
