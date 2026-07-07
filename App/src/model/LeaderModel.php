<?php
namespace App\Model;
use App\Utils\Database;
use Exception;
use PDO;
use PDOException;

class LeaderModel
{
    private static $Database;
    private $Conn;
    public function __construct()
    {
        self::$Database = new Database();
        $this->Conn = self::$Database->connect();
    }

    public function LeadingFaculty($query): array
    {
        try {
            $month = $query['month'] ?? null;
            $year = $query['year'] ?? null;
            $sort = $query['sort'] ?? 'point';
            $orderDirection = isset($query['order']) && strtoupper($query['order']) === 'ASC' ? 'ASC' : 'DESC';
            $orderBy = 'total_point';

            switch ($sort) {
                case 'carbon':
                    $orderBy = 'total_co2e';
                    break;
                case 'point':
                    $orderBy = 'total_point';
                    break;
                case 'weight':
                    $orderBy = 'total_weight';
                    break;
                case 'name':
                    $orderBy = 'f.faculty_name';
                    break;
            }

            $whereConditions = [];
            if ($month) {
                $whereConditions[] = "MONTH(w.created_at) = :month";
            }
            if ($year) {
                $whereConditions[] = "YEAR(w.created_at) = :year";
            }
            $whereClause = !empty($whereConditions) ? "WHERE " . implode(" AND ", $whereConditions) : "";

            $sql = "SELECT
                        f.faculty_id,
                        f.faculty_name,
                        COALESCE(SUM(w.waste_transaction_total_weight), 0) AS total_weight,
                        COALESCE(SUM(w.waste_transaction_total_point), 0) AS total_point,
                        COALESCE(SUM(w.waste_transaction_total_co2e), 0) AS total_co2e
                    FROM
                        faculty f
                    LEFT JOIN (
                        SELECT 
                            m.faculty_id,
                            w.waste_transaction_total_weight,
                            w.waste_transaction_total_point,
                            w.waste_transaction_total_co2e
                        FROM waste_transaction w
                        JOIN member m ON w.member_id = m.member_id
                        {$whereClause}
                    ) w ON f.faculty_id = w.faculty_id
                    GROUP BY
                        f.faculty_id
                    ORDER BY
                    {$orderBy} {$orderDirection}, f.faculty_name ASC";


            $isPagination = isset($query['page']) && isset($query['limit']);
            if ($isPagination) {
                $page = (int) $query['page'];
                $limit = (int) $query['limit'];
                $offset = ($page - 1) * $limit;
                $sql .= " LIMIT :limit OFFSET :offset";
            }

            $stmt = $this->Conn->prepare($sql);

            if ($isPagination) {
                $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
                $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            }

            if ($month) {
                $stmt->bindValue(':month', $month, PDO::PARAM_INT);
            }
            if ($year) {
                $stmt->bindValue(':year', $year, PDO::PARAM_INT);
            }

            $stmt->execute();
            $stats = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // นับจำนวนคณะทั้งหมดสำหรับ Pagination
            $countSql = "SELECT COUNT(faculty_id) FROM faculty";
            $countStmt = $this->Conn->prepare($countSql);
            $countStmt->execute();
            $totalRecords = (int) $countStmt->fetchColumn();

            return ['stats' => $stats, 'total' => $totalRecords];
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage() . $sql, 500);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function LeadingMember($query): array
    {
        try {
            $facultyId = $query['faculty_id'] ?? null;
            $month = $query['month'] ?? null;
            $year = $query['year'] ?? null;
            $sort = $query['sort'] ?? 'point';
            $orderDirection = isset($query['order']) && strtoupper($query['order']) === 'ASC' ? 'ASC' : 'DESC';
            $orderBy = 'total_waste_point';

            switch ($sort) {
                case 'carbon':
                    $orderBy = 'total_co2e';
                    break;
                case 'point':
                    $orderBy = 'total_point';
                    break;
                case 'goodness':
                    $orderBy = 'total_goodness';
                    break;
                case 'weight':
                    $orderBy = 'total_weight';
                    break;
                case 'name':
                    $orderBy = 'm.member_name';
                    break;
                case 'social':
                    $orderBy = 'total_social';
                    break;
                case 'event':
                    $orderBy = 'total_event';
                    break;
            }

            $params = [];
            $whereClauses = ["m.role_id IN (1,2,3)"];
            if ($facultyId) {
                $whereClauses[] = "m.faculty_id = :faculty_id";
                $params[':faculty_id'] = $facultyId;
            }

            $whereClause = "WHERE " . implode(" AND ", $whereClauses);

            $countSql = "SELECT COUNT(*) FROM member m {$whereClause}";
            $countStmt = $this->Conn->prepare($countSql);
            $countStmt->execute($params);
            $totalRecords = (int) $countStmt->fetchColumn();
            $sql = "SELECT
                    	mp.member_id,
                        mp.total_waste_point AS total_point,
                        mp.total_goodness_point AS total_goodness,
                        mp.total_social_point AS total_social,
                        mp.member_point_event_sum AS total_event,
                        mp.total_weight,
                        mp.total_co2e,
                        f.faculty_name,
                        mj.major_name,
                        m.member_name,
                        m.member_phone,
                        m.role_id,
                        r.role_name_th,
                        m.member_email,
                        m.member_personal_id
                    FROM
                    	member m
                    LEFT JOIN
                    	member_point mp ON m.member_id = mp.member_id
                    LEFT JOIN
                    	faculty f ON m.faculty_id = f.faculty_id
                    LEFT JOIN
                    	major mj ON m.major_id = mj.major_id
                    LEFT JOIN
                    	role r ON m.role_id = r.role_id    
                    {$whereClause}                   
                    ORDER BY
                        {$orderBy} {$orderDirection}, m.member_id ASC";

            $isPagination = isset($query['page']) && isset($query['limit']);
            if ($isPagination) {
                $page = (int) $query['page'];
                $limit = (int) $query['limit'];
                $offset = ($page - 1) * $limit;
                $sql .= " LIMIT :limit OFFSET :offset";
            }

            $stmt = $this->Conn->prepare($sql);

            if ($isPagination) {
                $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
                $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            }
            foreach ($params as $key => $val) {
                $stmt->bindValue($key, $val);
            }
            $stmt->execute();
            $stats = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return [
                'stats' => $stats,
                'total' => $totalRecords
            ];

        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage(), 500);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function LeadingFacultyDeposit($query): array
    {
        try {
            $sort = $query['sort'] ?? 'point';
            $orderDirection = isset($query['order']) && strtoupper($query['order']) === 'ASC' ? 'ASC' : 'DESC';
            $orderBy = 'total_point';

            switch ($sort) {
                case 'carbon':
                    $orderBy = 'total_co2e';
                    break;
                case 'point':
                    $orderBy = 'total_point';
                    break;
                case 'event':
                    $orderBy = 'total_event';
                    break;
                case 'goodness':
                    $orderBy = 'total_goodness';
                    break;
                case 'social':
                    $orderBy = 'total_social';
                    break;
                case 'weight':
                    $orderBy = 'total_weight';
                    break;
                case 'name':
                    $orderBy = 'f.faculty_name';
                    break;
               
                case 'member':
                    $orderBy = 'total_member';
                    break;
            }

            $whereConditions = [];
            $whereConditions[] = "f.isCenterBranch = 0";
            $whereClause = !empty($whereConditions) ? "WHERE " . implode(" AND ", $whereConditions) : "";

            $sql = "SELECT 
                        f.faculty_id,
                        f.faculty_name,
                        SUM(mp.total_waste_point) AS total_point,
                        SUM(mp.total_goodness_point) AS total_goodness,
                        SUM(mp.total_social_point) AS total_social,
                        SUM(mp.member_point_event_sum) AS total_event,
                        SUM(mp.total_co2e) AS total_co2e,
                        SUM(mp.total_weight) AS total_weight,
                        COUNT(m.member_id) AS total_member
                    FROM 
                        faculty f
                    JOIN 
                        member m ON f.faculty_id = m.faculty_id
                    JOIN 
                        member_point mp ON m.member_id = mp.member_id
                    {$whereClause}
                    GROUP BY 
                        f.faculty_id, 
                        f.faculty_name
                    ORDER BY 
                        {$orderBy} {$orderDirection}, f.faculty_name ASC";

            $isPagination = isset($query['page']) && isset($query['limit']);
            if ($isPagination) {
                $page = (int) $query['page'];
                $limit = (int) $query['limit'];
                $offset = ($page - 1) * $limit;
                $sql .= " LIMIT :limit OFFSET :offset";
            }

            $stmt = $this->Conn->prepare($sql);

            if ($isPagination) {
                $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
                $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            }

            $stmt->execute();
            $stats = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // นับจำนวนคณะทั้งหมดสำหรับ Pagination
            $countSql = "SELECT COUNT(faculty_id) FROM faculty WHERE isCenterBranch = 0";
            $countStmt = $this->Conn->prepare($countSql);
            $countStmt->execute();
            $totalRecords = (int) $countStmt->fetchColumn();

            return ['stats' => $stats, 'total' => $totalRecords];
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage() . $sql, 500);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function LeadingMajorDeposit($query): array
    {
        try {
            $month = $query['month'] ?? null;
            $year = $query['year'] ?? null;
            $sort = $query['sort'] ?? 'point';
            $orderDirection = isset($query['order']) && strtoupper($query['order']) === 'ASC' ? 'ASC' : 'DESC';
            $orderBy = 'total_point';

            switch ($sort) {
                case 'carbon':
                    $orderBy = 'total_co2e';
                    break;
                case 'point':
                    $orderBy = 'total_point';
                    break;
                case 'goodness':
                    $orderBy = 'total_goodness';
                    break;
                case 'event':
                    $orderBy = 'total_event';
                    break;
                case 'social':
                    $orderBy = 'total_social';
                    break;
                case 'weight':
                    $orderBy = 'total_weight';
                    break;
                case 'name':
                    $orderBy = 'mj.major_name';
                    break;
                case 'member':
                    $orderBy = 'total_member';
                    break;
            }

            $whereConditions = [];
            if ($month) {
                $whereConditions[] = "MONTH(w.created_at) = :month";
            }
            if ($year) {
                $whereConditions[] = "YEAR(w.created_at) = :year";
            }
            $whereClause = !empty($whereConditions) ? "WHERE " . implode(" AND ", $whereConditions) : "";

            $sql = "SELECT 
                        f.faculty_id,
                        f.faculty_name,
                        mj.major_id,
                        mj.major_name,
                        SUM(mp.total_waste_point) AS total_point,
                        SUM(mp.total_goodness_point) AS total_goodness,
                        SUM(mp.total_social_point) AS total_social,
                        SUM(mp.member_point_event_sum) AS total_event,
                        SUM(mp.total_co2e) AS total_c02e,
                        SUM(mp.total_weight) AS total_weight,
                        COUNT(m.member_id) AS total_member
                    FROM 
                        major mj
                    JOIN 
                        faculty f ON mj.faculty_id = f.faculty_id
                    JOIN 
                        member m ON mj.major_id = m.major_id
                    JOIN 
                        member_point mp ON m.member_id = mp.member_id
                    {$whereClause}
                    GROUP BY 
                        mj.major_id,
                        mj.major_name
                    ORDER BY 
                        {$orderBy} {$orderDirection}, mj.major_name ASC";

            $isPagination = isset($query['page']) && isset($query['limit']);
            if ($isPagination) {
                $page = (int) $query['page'];
                $limit = (int) $query['limit'];
                $offset = ($page - 1) * $limit;
                $sql .= " LIMIT :limit OFFSET :offset";
            }

            $stmt = $this->Conn->prepare($sql);

            if ($isPagination) {
                $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
                $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            }

            if ($month) {
                $stmt->bindValue(':month', $month, PDO::PARAM_INT);
            }
            if ($year) {
                $stmt->bindValue(':year', $year, PDO::PARAM_INT);
            }

            $stmt->execute();
            $stats = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // นับจำนวนสาขาทั้งหมดสำหรับ Pagination
            $countSql = "SELECT COUNT(major_id) FROM major";
            $countStmt = $this->Conn->prepare($countSql);
            $countStmt->execute();
            $totalRecords = (int) $countStmt->fetchColumn();

            return ['stats' => $stats, 'total' => $totalRecords];
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage() . $sql, 500);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }


}