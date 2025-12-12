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
                if ($type === 'user' || $type === 'staff') {
                    $whereClause = "WHERE account_tb.account_role = :type";
                    $params[':type'] = $type;
                } else {
                    throw new Exception("Invalid role parameter", 400);
                }
            } else {
                throw new Exception("Missing role parameter", 400);
            }

            $page = isset($query['page']) ? (int) $query['page'] : 1;
            $limit = isset($query['limit']) ? (int) $query['limit'] : 0;
            if ($limit <= 0) {
                $limit = 10;
            }
            $offset = ($page - 1) * $limit;

            $sql =
                "SELECT
                	account_tb.account_name, account_tb.account_points, 
                    faculty_tb.faculty_name, major_tb.major_name
                FROM
                	account_tb
                LEFT JOIN
                    faculty_tb ON account_tb.faculty_id = faculty_tb.faculty_id
                LEFT JOIN
                    major_tb ON account_tb.major_id = major_tb.major_id
                {$whereClause}
                ORDER BY
                	account_tb.account_points 
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

            $stmtCount = "SELECT COUNT(*) AS allUser FROM account_tb {$whereClause}";
            $stmtCount = $this->Conn->prepare($stmtCount);
            if (!empty($params[':type'])) {
                $stmtCount->bindValue(':type', $params[':type'], PDO::PARAM_STR);
            }
            $stmtCount->execute();
            $total = $stmtCount->fetch(PDO::FETCH_ASSOC);

            $result = [$users, $total['allUser']];
            return $result;
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage() . $sql, 500);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function LeadingUsersByFacultyMajor($request)
    {
        try {
            $type = $query['type'] ?? null;
            $type = $query['type'] ?? null;

            $whereClause = "";
            $params = [];

            if ($type) {
                if ($type === 'faculty' || $type === 'major') {
                    $whereClause = "WHERE account_tb.{$type}_id = :id";
                    $params[':id'] = $type;
                } else {
                    throw new Exception("Invalid role parameter", 400);
                }
            } else {
                throw new Exception("Missing role parameter", 400);
            }

        } catch (\Throwable $th) {
            //throw $th;
        }
    }

}
