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

    public function LeadingFacultyWasteStats($query): array
    {
        try {
            $month = $query['month'] ?? null;
            $year = $query['year'] ?? null;
            $sort = $query['sort'] ?? 'point';
            $orderBy = 'total_point';

            switch ($sort) {
                case 'carbon':
                    $orderBy = 'total_co2';
                    break;
                case 'point':
                    $orderBy = 'total_point';
                    break;
                case 'fraction':
                    $orderBy = 'total_fraction';
                    break;
                case 'weight':
                    $orderBy = 'total_weight';
                    break;
                case 'value':
                    $orderBy = 'total_value';
                    break;
            }

            $page = isset($query['page']) ? (int) $query['page'] : 1;
            $limit = isset($query['limit']) ? (int) $query['limit'] : 0;
            if ($limit <= 0) {
                $limit = 10;
            }
            $offset = ($page - 1) * $limit;

            $whereClause = "";
            $conditions = [];
            if ($month)
                $conditions[] = "MONTH(w.waste_transaction_create_date) = :month";
            if ($year)
                $conditions[] = "YEAR(w.waste_transaction_create_date) = :year";
            if (!empty($conditions)) {
                $whereClause = "WHERE " . implode(" AND ", $conditions);
            }

            $sql =
                "SELECT
                    f.faculty_id,
                    f.faculty_name,
                    SUM(w.waste_transaction_weight) AS total_weight,
                    SUM(w.waste_transaction_member_point) AS total_point,
                    SUM(w.waste_transaction_faculty_fraction) AS total_fraction,
                    SUM(wt.waste_type_price * w.waste_transaction_weight) AS total_value,
                    SUM(wt.waste_type_co2 * w.waste_transaction_weight) AS total_co2
                FROM
                    waste_transaction w
                LEFT JOIN
                    member a ON w.member_id = a.member_id
                LEFT JOIN
                    faculty f ON a.faculty_id = f.faculty_id
                LEFT JOIN
                    waste_type wt ON w.waste_transaction_waste_type = wt.waste_type_id
                {$whereClause}
                GROUP BY
                    f.faculty_id
                ORDER BY
                    {$orderBy} DESC
                LIMIT :limit OFFSET :offset;";

            $stmt = $this->Conn->prepare($sql);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            if ($month)
                $stmt->bindValue(':month', $month, PDO::PARAM_INT);
            if ($year)
                $stmt->bindValue(':year', $year, PDO::PARAM_INT);
            $stmt->execute();
            $stats = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $stmtCount = "SELECT COUNT(DISTINCT a.faculty_id) AS total_rows 
                          FROM waste_transaction w 
                          LEFT JOIN member a ON w.member_id = a.member_id
                          {$whereClause}";
            $stmtCount = $this->Conn->prepare($stmtCount);
            if ($month)
                $stmtCount->bindValue(':month', $month, PDO::PARAM_INT);
            if ($year)
                $stmtCount->bindValue(':year', $year, PDO::PARAM_INT);
            $stmtCount->execute();
            $total = $stmtCount->fetch(PDO::FETCH_ASSOC);

            return ['stats' => $stats, 'total' => $total['total_rows']];
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage() . $sql, 500);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function LeadingUserWasteStats($query): array
    {
        try {
            $role = $query['role'] ?? null;
            $month = $query['month'] ?? null;
            $year = $query['year'] ?? null;
            $sort = $query['sort'] ?? 'point';
            $orderBy = 'total_point';

            switch ($sort) {
                case 'carbon':
                    $orderBy = 'total_co2';
                    break;
                case 'point':
                    $orderBy = 'total_point';
                    break;
                case 'fraction':
                    $orderBy = 'total_fraction';
                    break;
                case 'weight':
                    $orderBy = 'total_weight';
                    break;
                case 'value':
                    $orderBy = 'total_value';
                    break;
            }

            $page = isset($query['page']) ? (int) $query['page'] : 1;
            $limit = isset($query['limit']) ? (int) $query['limit'] : 0;
            if ($limit <= 0) {
                $limit = 10;
            }
            $offset = ($page - 1) * $limit;

            $whereClause = "";
            $conditions = [];
            if ($role)
                $conditions[] = "a.member_role = :role";
            if ($month)
                $conditions[] = "MONTH(w.waste_transaction_create_date) = :month";
            if ($year)
                $conditions[] = "YEAR(w.waste_transaction_create_date) = :year";
            if (!empty($conditions))
                $whereClause = "WHERE " . implode(" AND ", $conditions);

            $sql =
                "SELECT
                    a.member_id,
                    a.member_name,
                    a.member_personal_id,
                    a.member_phone,
                    SUM(w.waste_transaction_weight) AS total_weight,
                    SUM(w.waste_transaction_member_point) AS total_point,
                    SUM(w.waste_transaction_faculty_fraction) AS total_fraction,
                    SUM(wt.waste_type_price * w.waste_transaction_weight) AS total_value,
                    SUM(wt.waste_type_co2 * w.waste_transaction_weight) AS total_co2
                FROM
                    waste_transaction w
                LEFT JOIN
                    member a ON w.member_id = a.member_id
                LEFT JOIN
                    waste_type wt ON w.waste_transaction_waste_type = wt.waste_type_id
                {$whereClause}
                GROUP BY
                    w.member_id
                ORDER BY
                    {$orderBy} DESC
                LIMIT :limit OFFSET :offset;";

            $stmt = $this->Conn->prepare($sql);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            if ($role) {
                $stmt->bindValue(':role', $role);
            }
            if ($month) {
                $stmt->bindValue(':month', $month, PDO::PARAM_INT);
            }
            if ($year) {
                $stmt->bindValue(':year', $year, PDO::PARAM_INT);
            }
            $stmt->execute();
            $stats = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $stmtCount =
                "SELECT 
                    COUNT(DISTINCT w.member_id) AS total_rows 
                FROM 
                    waste_transaction w 
                LEFT JOIN 
                    member a ON w.member_id = a.member_id 
                {$whereClause}";

            $stmtCount = $this->Conn->prepare($stmtCount);
            if ($role) {
                $stmtCount->bindValue(':role', $role);
            }
            if ($month) {
                $stmtCount->bindValue(':month', $month, PDO::PARAM_INT);
            }
            if ($year) {
                $stmtCount->bindValue(':year', $year, PDO::PARAM_INT);
            }
            $stmtCount->execute();
            $total = $stmtCount->fetch(PDO::FETCH_ASSOC);

            return ['stats' => $stats, 'total' => $total['total_rows']];
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage() . $sql, 500);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }
}
