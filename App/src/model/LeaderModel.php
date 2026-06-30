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
            $orderBy = 'total_point';

            switch ($sort) {
                case 'carbon':
                    $orderBy = 'total_co2';
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
                    $orderBy = 'a.member_name';
                    break;
                case 'social':
                    $orderBy = 'member_social_point';
                    break;
            }

            // 1. เงื่อนไขสำหรับการ JOIN (กรองข้อมูล Transaction ตามเวลา)
            $params = [];
            $onClause = "w.member_id = a.member_id";
            if ($month) {
                $onClause .= " AND MONTH(w.created_at) = :month";
                $params[':month'] = $month;
            }
            if ($year) {
                $onClause .= " AND YEAR(w.created_at) = :year";
                $params[':year'] = $year;
            }

            // 2. เงื่อนไขสำหรับการ WHERE (กรองที่ตัว Member)
            $whereClauses = ["a.role_id IN (1,2,3)"];
            $countParams = [];
            if ($facultyId) {
                $whereClauses[] = "a.faculty_id = :faculty_id";
                $params[':faculty_id'] = $facultyId;
                $countParams[':faculty_id'] = $facultyId;
            }
            $whereClause = "WHERE " . implode(" AND ", $whereClauses);

            $countSql = "SELECT COUNT(*) FROM member a {$whereClause}";
            $countStmt = $this->Conn->prepare($countSql);
            $countStmt->execute($countParams);
            $totalRecords = (int) $countStmt->fetchColumn();
            // -------------------------------------------------------

            // 3. SQL สำหรับดึงข้อมูลหลัก
            $sql = "
            SELECT
                a.member_id,
                a.member_name,
                a.member_phone,
                f.faculty_name,
                m.major_name,
                COALESCE(SUM(w.waste_transaction_total_point), 0) AS total_point,
                a.member_goodness_point AS total_goodness,
                a.member_social_point,
                COALESCE(SUM(w.waste_transaction_total_weight), 0) AS total_weight,
                COALESCE(SUM(w.waste_transaction_total_co2e), 0) AS total_co2
            FROM
                member a
            LEFT JOIN
                faculty f ON a.faculty_id = f.faculty_id
            LEFT JOIN
                major m ON a.major_id = m.major_id
            LEFT JOIN
                waste_transaction w ON {$onClause}
            {$whereClause}
            GROUP BY
                a.member_id, 
                a.member_name, 
                a.member_phone,
                f.faculty_name,
                m.major_name,
                a.member_goodness_point,
                a.member_social_point
            ORDER BY
                {$orderBy} {$orderDirection}, a.member_id ASC
        ";

            $isPagination = isset($query['page']) && isset($query['limit']);
            if ($isPagination) {
                $page = (int) $query['page'];
                $limit = (int) $query['limit'];
                $offset = ($page - 1) * $limit;
                $sql .= " LIMIT :limit OFFSET :offset";
            }

            $stmt = $this->Conn->prepare($sql);

            // Bind ค่าต่างๆ
            if ($isPagination) {
                $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
                $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            }
            foreach ($params as $key => $val) {
                $stmt->bindValue($key, $val);
            }
            $stmt->execute();
            $stats = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // ส่งค่า totalRecords ที่นับได้จริงกลับไป
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
                case 'social':
                    $orderBy = 'total_social_point';
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
                        COALESCE(member_stats.total_member, 0) AS total_member,
                        COALESCE(member_stats.total_social_point, 0) AS total_social_point,
                        COALESCE(wt.total_weight, 0) AS total_weight,
                        COALESCE(wt.total_point, 0) AS total_point,
                        COALESCE(wt.total_co2e, 0) AS total_co2e
                    FROM
                        faculty f
                    LEFT JOIN ( SELECT faculty_id, COUNT(member_id) as total_member, SUM(member_social_point) as total_social_point FROM member GROUP BY faculty_id
                    ) AS member_stats ON f.faculty_id = member_stats.faculty_id
                    LEFT JOIN (
                        SELECT 
                            m.faculty_id,
                            w.waste_transaction_total_weight,
                            w.waste_transaction_total_point,
                            w.waste_transaction_total_co2e,
                            w.created_at
                        FROM waste_transaction w
                        JOIN member m ON w.member_id = m.member_id
                    ) w ON f.faculty_id = w.faculty_id {$whereClause}
                    LEFT JOIN (
                        SELECT m.faculty_id, SUM(w.waste_transaction_total_weight) AS total_weight, SUM(w.waste_transaction_total_point) AS total_point, SUM(w.waste_transaction_total_co2e) AS total_co2e
                        FROM waste_transaction w JOIN member m ON w.member_id = m.member_id {$whereClause} GROUP BY m.faculty_id
                    ) wt ON f.faculty_id = wt.faculty_id
                    WHERE f.isCenterBranch = 0
                    GROUP BY
                        f.faculty_id, f.faculty_name, member_stats.total_member, member_stats.total_social_point
                    ORDER BY
                    {$orderBy} {$orderDirection},f.faculty_name ASC";

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
                case 'weight':
                    $orderBy = 'total_weight';
                    break;
                case 'name':
                    $orderBy = 'mj.major_name';
                    break;
                case 'social':
                    $orderBy = 'total_social_point';
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
                        mj.major_id,
                        mj.major_name,
                        f.faculty_name,
                        COALESCE(member_stats.total_member, 0) AS total_member,
                        COALESCE(member_stats.total_social_point, 0) AS total_social_point,
                        COALESCE(wt.total_weight, 0) AS total_weight,
                        COALESCE(wt.total_point, 0) AS total_point,
                        COALESCE(wt.total_co2e, 0) AS total_co2e
                    FROM
                        major mj
                    LEFT JOIN faculty f ON mj.faculty_id = f.faculty_id
                    LEFT JOIN ( SELECT major_id, COUNT(member_id) as total_member, SUM(member_social_point) as total_social_point FROM member GROUP BY major_id
                    ) AS member_stats ON mj.major_id = member_stats.major_id
                    LEFT JOIN (
                        SELECT 
                            m.major_id,
                            w.waste_transaction_total_weight,
                            w.waste_transaction_total_point,
                            w.waste_transaction_total_co2e,
                            w.created_at
                        FROM waste_transaction w
                        JOIN member m ON w.member_id = m.member_id
                    ) w ON mj.major_id = w.major_id {$whereClause}
                    LEFT JOIN (
                        SELECT m.major_id, SUM(w.waste_transaction_total_weight) AS total_weight, SUM(w.waste_transaction_total_point) AS total_point, SUM(w.waste_transaction_total_co2e) AS total_co2e
                        FROM waste_transaction w JOIN member m ON w.member_id = m.member_id {$whereClause} GROUP BY m.major_id
                    ) wt ON mj.major_id = wt.major_id
                    GROUP BY
                        mj.major_id, mj.major_name, f.faculty_name, member_stats.total_member, member_stats.total_social_point
                    ORDER BY
                    {$orderBy} {$orderDirection},mj.major_name ASC";

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