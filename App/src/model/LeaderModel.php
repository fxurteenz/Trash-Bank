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

    public function LeadingUsersByRole($query): array
    {
        try {
            $type = $query['type'] ?? null;

            $whereClause = "";
            $params = [];

            if ($type) {
                if ($type === 'user' || $type === 'faculty staff' || $type === 'operater') {
                    $whereClause = "WHERE a.account_role = :type";
                    $params[':type'] = $type;
                } else {
                    throw new Exception("Invalid role parameter", 400);
                }
            } else {
                throw new Exception("Missing role parameter", 400);
            }

            $sort = $query['sort'] ?? 'point';
            $orderBy = 'a.account_point';

            if ($sort === 'carbon') {
                $orderBy = 'a.account_carbon';
            }

            $page = isset($query['page']) ? (int) $query['page'] : 1;
            $limit = isset($query['limit']) ? (int) $query['limit'] : 0;
            if ($limit <= 0) {
                $limit = 10;
            }
            $offset = ($page - 1) * $limit;

            $sql =
                "SELECT
                	a.account_personal_id, a.account_tel, 
                    a.account_name, a.account_point,
                    a.account_carbon, f.faculty_name, m.major_name
                FROM
                	account a
                LEFT JOIN
                    faculty f ON a.faculty_id = f.faculty_id
                LEFT JOIN
                    major m ON a.major_id = m.major_id
                {$whereClause}
                ORDER BY
                	{$orderBy} 
                DESC
                LIMIT :limit OFFSET :offset;";

            $stmt = $this->Conn->prepare($sql);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            if (!empty($params[':type'])) {
                $stmt->bindValue(':type', $params[':type'], PDO::PARAM_STR);
            }
            $stmt->execute();
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $stmtCount = "SELECT COUNT(*) AS allUser FROM account a {$whereClause}";
            $stmtCount = $this->Conn->prepare($stmtCount);
            if (!empty($params[':type'])) {
                $stmtCount->bindValue(':type', $params[':type'], PDO::PARAM_STR);
            }
            $stmtCount->execute();
            $total = $stmtCount->fetch(PDO::FETCH_ASSOC);

            $result = ['user' => $users, 'total' => $total['allUser']];
            return $result;
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage() . $sql, 500);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function LeadingUsersByFaculty($query): array
    {
        try {
            $facultyId = $query['fid'] ?? null;

            if (empty($facultyId)) {
                throw new Exception("Missing faculty_id parameter", 400);
            }

            $sort = $query['sort'] ?? 'point';
            $orderBy = 'a.account_point';

            if ($sort === 'carbon') {
                $orderBy = 'a.account_carbon';
            }

            $page = isset($query['page']) ? (int) $query['page'] : 1;
            $limit = isset($query['limit']) ? (int) $query['limit'] : 0;
            if ($limit <= 0) {
                $limit = 10;
            }
            $offset = ($page - 1) * $limit;

            $sql =
                "SELECT
                	a.account_personal_id, a.account_tel, 
                    a.account_name, a.account_point,
                    a.account_carbon, f.faculty_name, m.major_name
                FROM
                	account a
                LEFT JOIN
                    faculty f ON a.faculty_id = f.faculty_id
                LEFT JOIN
                    major m ON a.major_id = m.major_id
                WHERE
                    a.faculty_id = :faculty_id
                ORDER BY
                	{$orderBy} 
                DESC
                LIMIT :limit OFFSET :offset;";

            $stmt = $this->Conn->prepare($sql);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->bindValue(':faculty_id', $facultyId, PDO::PARAM_INT);
            $stmt->execute();
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $stmtCount = "SELECT COUNT(*) AS allUser FROM account a WHERE a.faculty_id = :faculty_id";
            $stmtCount = $this->Conn->prepare($stmtCount);
            $stmtCount->bindValue(':faculty_id', $facultyId, PDO::PARAM_INT);
            $stmtCount->execute();
            $total = $stmtCount->fetch(PDO::FETCH_ASSOC);

            $result = ['user' => $users, 'total' => $total['allUser']];
            return $result;
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage() . $sql, 500);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function LeadingUsersByMajor($query): array
    {
        try {
            $majorId = $query['mid'] ?? null;

            if (empty($majorId)) {
                throw new Exception("Missing major_id parameter", 400);
            }

            $sort = $query['sort'] ?? 'point';
            $orderBy = 'a.account_point';

            if ($sort === 'carbon') {
                $orderBy = 'a.account_carbon';
            }

            $page = isset($query['page']) ? (int) $query['page'] : 1;
            $limit = isset($query['limit']) ? (int) $query['limit'] : 0;
            if ($limit <= 0) {
                $limit = 10;
            }
            $offset = ($page - 1) * $limit;

            $sql =
                "SELECT
                	a.account_personal_id, a.account_tel, 
                    a.account_name, a.account_point,
                    a.account_carbon, f.faculty_name, m.major_name
                FROM
                	account a
                LEFT JOIN
                    faculty f ON a.faculty_id = f.faculty_id
                LEFT JOIN
                    major m ON a.major_id = m.major_id
                WHERE
                    a.major_id = :major_id
                ORDER BY
                	{$orderBy} 
                DESC
                LIMIT :limit OFFSET :offset;";

            $stmt = $this->Conn->prepare($sql);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->bindValue(':major_id', $majorId, PDO::PARAM_INT);
            $stmt->execute();
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $stmtCount = "SELECT COUNT(*) AS allUser FROM account a WHERE a.major_id = :major_id";
            $stmtCount = $this->Conn->prepare($stmtCount);
            $stmtCount->bindValue(':major_id', $majorId, PDO::PARAM_INT);
            $stmtCount->execute();
            $total = $stmtCount->fetch(PDO::FETCH_ASSOC);

            $result = ['user' => $users, 'total' => $total['allUser']];
            return $result;
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage() . $sql, 500);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function LeadingFaculties($query): array
    {
        try {
            $sort = $query['sort'] ?? 'point';
            $orderBy = 'faculty_point';

            if ($sort === 'carbon') {
                $orderBy = 'faculty_carbon';
            }

            $page = isset($query['page']) ? (int) $query['page'] : 1;
            $limit = isset($query['limit']) ? (int) $query['limit'] : 0;
            if ($limit <= 0) {
                $limit = 10;
            }
            $offset = ($page - 1) * $limit;

            $sql =
                "SELECT
                    faculty_id, faculty_name, faculty_point, faculty_carbon
                FROM
                    faculty
                ORDER BY
                    {$orderBy} 
                DESC
                LIMIT :limit OFFSET :offset;";

            $stmt = $this->Conn->prepare($sql);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            $faculties = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $stmtCount = "SELECT COUNT(*) AS allFaculty FROM faculty";
            $stmtCount = $this->Conn->prepare($stmtCount);
            $stmtCount->execute();
            $total = $stmtCount->fetch(PDO::FETCH_ASSOC);

            $result = ['faculty' => $faculties, 'total' => $total['allFaculty']];
            return $result;
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage() . $sql, 500);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function LeadingFacultyWasteStats($query): array
    {
        try {
            $month = $query['month'] ?? null;
            $year = $query['year'] ?? null;
            $sort = $query['sort'] ?? 'point';
            $orderBy = 'total_point';

            switch ($sort) {
                case 'weight':
                    $orderBy = 'total_weight';
                    break;
                case 'value':
                    $orderBy = 'total_value';
                    break;
                case 'leftover':
                    $orderBy = 'total_leftover';
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
                    SUM(w.waste_transaction_value) AS total_value,
                    SUM(w.waste_transaction_point) AS total_point,
                    SUM(w.waste_transaction_leftover) AS total_leftover
                FROM
                    waste_transaction w
                LEFT JOIN
                    account a ON w.account_id = a.account_id
                LEFT JOIN
                    faculty f ON a.faculty_id = f.faculty_id
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
                          LEFT JOIN account a ON w.account_id = a.account_id
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
                case 'weight':
                    $orderBy = 'total_weight';
                    break;
                case 'value':
                    $orderBy = 'total_value';
                    break;
                case 'leftover':
                    $orderBy = 'total_leftover';
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
                $conditions[] = "a.account_role = :role";
            if ($month)
                $conditions[] = "MONTH(w.waste_transaction_create_date) = :month";
            if ($year)
                $conditions[] = "YEAR(w.waste_transaction_create_date) = :year";
            if (!empty($conditions))
                $whereClause = "WHERE " . implode(" AND ", $conditions);

            $sql =
                "SELECT
                    a.account_id,
                    a.account_name,
                    SUM(w.waste_transaction_weight) AS total_weight,
                    SUM(w.waste_transaction_value) AS total_value,
                    SUM(w.waste_transaction_point) AS total_point,
                    SUM(w.waste_transaction_leftover) AS total_leftover
                FROM
                    waste_transaction w
                LEFT JOIN
                    account a ON w.account_id = a.account_id
                {$whereClause}
                GROUP BY
                    w.account_id
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

            $stmtCount = "SELECT COUNT(DISTINCT w.account_id) AS total_rows FROM waste_transaction w LEFT JOIN account a ON w.account_id = a.account_id {$whereClause}";
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
