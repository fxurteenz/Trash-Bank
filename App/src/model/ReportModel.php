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
        [$whereSql, $params] = $this->buildDateFilters($query);

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
        [$whereSql, $params] = $this->buildDateFilters($query);

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
        [$whereSql, $params] = $this->buildDateFilters($query);

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

    public function MemberReport(int $memberId, array $query): array
    {
        [$whereSql, $params] = $this->buildDateFilters($query);
        $params[':member_id'] = $memberId;
        $whereSql = empty($whereSql) ? "WHERE w.member_id = :member_id" : $whereSql . " AND w.member_id = :member_id";

        $summarySql = "SELECT 
                            COALESCE(SUM(w.waste_transaction_weight), 0) AS total_weight,
                            COALESCE(SUM(wt.waste_type_price * w.waste_transaction_weight), 0) AS total_value,
                            COALESCE(SUM(wt.waste_type_co2 * w.waste_transaction_weight), 0) AS total_co2
                        FROM waste_transaction w
                        LEFT JOIN waste_type wt ON w.waste_transaction_waste_type = wt.waste_type_id
                        {$whereSql}";

        $stmt = $this->Conn->prepare($summarySql);
        $stmt->execute($params);
        $summary = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];

        $memberSql = "SELECT member_id, member_name, member_waste_point, member_goodness_point FROM member WHERE member_id = :member_id";
        $mStmt = $this->Conn->prepare($memberSql);
        $mStmt->execute([':member_id' => $memberId]);
        $member = $mStmt->fetch(PDO::FETCH_ASSOC) ?: [];

        $redeemSql = "SELECT 
                        COUNT(*) AS redeem_count,
                        COALESCE(SUM(member_reward_point_used),0) AS points_spent
                      FROM member_reward
                      WHERE member_id = :member_id";
        $rStmt = $this->Conn->prepare($redeemSql);
        $rStmt->execute([':member_id' => $memberId]);
        $redeem = $rStmt->fetch(PDO::FETCH_ASSOC) ?: [];

        return [
            'member' => $member,
            'summary' => $summary,
            'redeem' => $redeem,
        ];
    }

    public function FacultyReport(int $facultyId, array $query): array
    {
        try {
            [$whereSql, $params] = $this->buildDateFilters($query);
            $params[':faculty_id'] = $facultyId;
            $whereSql = empty($whereSql) ? "WHERE w.faculty_id = :faculty_id" : $whereSql . " AND w.faculty_id = :faculty_id";

            $summarySql = "SELECT 
                            COALESCE(SUM(w.waste_transaction_weight), 0) AS total_weight,
                            COALESCE(SUM(w.waste_transaction_member_point), 0) AS total_member_point,
                            COALESCE(SUM(w.waste_transaction_faculty_fraction), 0) AS faculty_fraction,
                            COALESCE(SUM(wt.waste_type_price * w.waste_transaction_weight), 0) AS total_value,
                            COALESCE(SUM(wt.waste_type_co2 * w.waste_transaction_weight), 0) AS total_co2,
                            COUNT(DISTINCT w.member_id) AS member_participation
                       FROM waste_transaction w
                       LEFT JOIN waste_type wt ON w.waste_transaction_waste_type = wt.waste_type_id
                       {$whereSql}";

            $stmt = $this->Conn->prepare($summarySql);
            $stmt->execute($params);
            $summary = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];

            $facultySql = "SELECT faculty_id, faculty_name FROM faculty WHERE faculty_id = :faculty_id";
            $fStmt = $this->Conn->prepare($facultySql);
            $fStmt->execute([':faculty_id' => $facultyId]);
            $faculty = $fStmt->fetch(PDO::FETCH_ASSOC) ?: [];

            return [
                'faculty' => $faculty,
                'summary' => $summary,
            ];
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage(), 500);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function MemberLeaderboard(array $query): array
    {
        [$whereSql, $params] = $this->buildDateFilters($query);
        $sort = $query['sort'] ?? 'weight';
        $orderMap = [
            'weight' => 'total_weight',
            'value' => 'total_value',
            'co2' => 'total_co2',
            'points' => 'total_points'
        ];
        $orderBy = $orderMap[$sort] ?? 'total_weight';
        $limit = isset($query['limit']) ? (int) $query['limit'] : 20;

        $sql = "SELECT 
                    m.member_id,
                    m.member_name,
                    COALESCE(SUM(w.waste_transaction_weight),0) AS total_weight,
                    COALESCE(SUM(wt.waste_type_price * w.waste_transaction_weight),0) AS total_value,
                    COALESCE(SUM(wt.waste_type_co2 * w.waste_transaction_weight),0) AS total_co2,
                    COALESCE(SUM(w.waste_transaction_member_point),0) AS total_points
                FROM waste_transaction w
                LEFT JOIN member m ON w.member_id = m.member_id
                LEFT JOIN waste_type wt ON w.waste_transaction_waste_type = wt.waste_type_id
                {$whereSql}
                GROUP BY m.member_id
                ORDER BY {$orderBy} DESC
                LIMIT :limit";

        $stmt = $this->Conn->prepare($sql);
        foreach ($params as $k => $v) {
            $stmt->bindValue($k, $v);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function FacultyLeaderboard(array $query): array
    {
        [$whereSql, $params] = $this->buildDateFilters($query);
        $sort = $query['sort'] ?? 'weight';
        $orderMap = [
            'weight' => 'total_weight',
            'value' => 'total_value',
            'co2' => 'total_co2',
            'fraction' => 'faculty_fraction'
        ];
        $orderBy = $orderMap[$sort] ?? 'total_weight';
        $limit = isset($query['limit']) ? (int) $query['limit'] : 20;

        $sql = "SELECT 
                    f.faculty_id,
                    f.faculty_name,
                    COALESCE(SUM(w.waste_transaction_weight),0) AS total_weight,
                    COALESCE(SUM(w.waste_transaction_faculty_fraction),0) AS faculty_fraction,
                    COALESCE(SUM(wt.waste_type_price * w.waste_transaction_weight),0) AS total_value,
                    COALESCE(SUM(wt.waste_type_co2 * w.waste_transaction_weight),0) AS total_co2,
                    COUNT(DISTINCT w.member_id) AS member_participation
                FROM waste_transaction w
                LEFT JOIN faculty f ON w.faculty_id = f.faculty_id
                LEFT JOIN waste_type wt ON w.waste_transaction_waste_type = wt.waste_type_id
                {$whereSql}
                GROUP BY f.faculty_id
                ORDER BY {$orderBy} DESC
                LIMIT :limit";

        $stmt = $this->Conn->prepare($sql);
        foreach ($params as $k => $v) {
            $stmt->bindValue($k, $v);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function CarbonImpact(array $query): array
    {
        [$whereSql, $params] = $this->buildDateFilters($query);

        if (!empty($query['faculty_id'])) {
            $whereSql = empty($whereSql) ? "WHERE w.faculty_id = :faculty_id" : $whereSql . " AND w.faculty_id = :faculty_id";
            $params[':faculty_id'] = $query['faculty_id'];
        }
        if (!empty($query['member_id'])) {
            $whereSql = empty($whereSql) ? "WHERE w.member_id = :member_id" : $whereSql . " AND w.member_id = :member_id";
            $params[':member_id'] = $query['member_id'];
        }

        $sql = "SELECT 
                    COALESCE(SUM(wt.waste_type_co2 * w.waste_transaction_weight), 0) AS total_co2,
                    COALESCE(SUM(w.waste_transaction_weight), 0) AS total_weight
                FROM waste_transaction w
                LEFT JOIN waste_type wt ON w.waste_transaction_waste_type = wt.waste_type_id
                {$whereSql}";

        $stmt = $this->Conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
    }

    public function RedemptionsReport(array $query): array
    {
        [$whereSql, $params] = $this->buildDateFiltersFor('mr.member_reward_date', $query);

        if (!empty($query['member_id'])) {
            $whereSql = empty($whereSql) ? "WHERE mr.member_id = :member_id" : $whereSql . " AND mr.member_id = :member_id";
            $params[':member_id'] = $query['member_id'];
        }
        if (!empty($query['status'])) {
            $whereSql = empty($whereSql) ? "WHERE mr.member_reward_status = :status" : $whereSql . " AND mr.member_reward_status = :status";
            $params[':status'] = $query['status'];
        }

        $summarySql = "SELECT 
                            COUNT(*) AS redeem_count,
                            COALESCE(SUM(mr.member_reward_point_used),0) AS points_spent,
                            COALESCE(SUM(mr.member_reward_qty),0) AS total_qty
                        FROM member_reward mr
                        {$whereSql}";

        $stmt = $this->Conn->prepare($summarySql);
        $stmt->execute($params);
        $summary = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];

        $listSql = "SELECT 
                        mr.*, 
                        r.reward_name,
                        r.reward_point_required,
                        m.member_name
                    FROM member_reward mr
                    LEFT JOIN reward r ON mr.reward_id = r.reward_id
                    LEFT JOIN member m ON mr.member_id = m.member_id
                    {$whereSql}
                    ORDER BY mr.member_reward_date DESC";

        $isPagination = isset($query['page']) && isset($query['limit']);
        if ($isPagination) {
            $page = (int) $query['page'];
            $limit = (int) $query['limit'];
            $offset = ($page - 1) * $limit;
            $listSql .= " LIMIT :limit OFFSET :offset";
        }

        $listStmt = $this->Conn->prepare($listSql);
        foreach ($params as $k => $v) {
            $listStmt->bindValue($k, $v);
        }
        if ($isPagination) {
            $listStmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $listStmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        }
        $listStmt->execute();
        $rows = $listStmt->fetchAll(PDO::FETCH_ASSOC);

        return [
            'summary' => $summary,
            'data' => $rows
        ];
    }

    public function GoodnessReport(array $query): array
    {
        // ไม่มีตารางธุรกรรมแต้มความดี แสดงสรุปจากค่าสะสมปัจจุบันของสมาชิก
        $whereClauses = [];
        $params = [];
        if (!empty($query['member_id'])) {
            $whereClauses[] = "member_id = :member_id";
            $params[':member_id'] = $query['member_id'];
        }
        $whereSql = !empty($whereClauses) ? "WHERE " . implode(' AND ', $whereClauses) : "";

        $sql = "SELECT 
                    COUNT(*) AS member_count,
                    COALESCE(SUM(member_goodness_point),0) AS total_goodness_points
                FROM member
                {$whereSql}";

        $stmt = $this->Conn->prepare($sql);
        $stmt->execute($params);
        $summary = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];

        return [
            'summary' => $summary
        ];
    }

    public function ClearanceReport(array $query): array
    {
        [$whereSql, $params] = $this->buildDateFiltersFor('wc.waste_clearance_period_start', $query);

        if (!empty($query['faculty_id'])) {
            $whereSql = empty($whereSql) ? "WHERE wc.faculty_id = :faculty_id" : $whereSql . " AND wc.faculty_id = :faculty_id";
            $params[':faculty_id'] = $query['faculty_id'];
        }
        if (!empty($query['status'])) {
            $whereSql = empty($whereSql) ? "WHERE wc.waste_clearance_status = :status" : $whereSql . " AND wc.waste_clearance_status = :status";
            $params[':status'] = $query['status'];
        }

        $summarySql = "SELECT 
                            COUNT(*) AS clearance_count,
                            COALESCE(SUM(waste_clearance_value_total),0) AS total_value,
                            COALESCE(SUM(waste_clearance_member_point_total),0) AS total_member_points,
                            COALESCE(SUM(waste_clearance_faculty_point_total),0) AS total_faculty_points
                        FROM waste_clearance wc
                        {$whereSql}";

        $stmt = $this->Conn->prepare($summarySql);
        $stmt->execute($params);
        $summary = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];

        $listSql = "SELECT 
                        wc.*, 
                        f.faculty_name,
                        m.member_name AS creator_name
                    FROM waste_clearance wc
                    LEFT JOIN faculty f ON wc.faculty_id = f.faculty_id
                    LEFT JOIN member m ON wc.waste_clearance_created_by = m.member_id
                    {$whereSql}
                    ORDER BY wc.created_at DESC";

        $isPagination = isset($query['page']) && isset($query['limit']);
        if ($isPagination) {
            $page = (int) $query['page'];
            $limit = (int) $query['limit'];
            $offset = ($page - 1) * $limit;
            $listSql .= " LIMIT :limit OFFSET :offset";
        }

        $listStmt = $this->Conn->prepare($listSql);
        foreach ($params as $k => $v) {
            $listStmt->bindValue($k, $v);
        }
        if ($isPagination) {
            $listStmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $listStmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        }
        $listStmt->execute();
        $rows = $listStmt->fetchAll(PDO::FETCH_ASSOC);

        return [
            'summary' => $summary,
            'data' => $rows
        ];
    }


}
