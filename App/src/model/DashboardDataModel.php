<?php
namespace App\Model;
use App\Utils\Database;
use Exception;
use PDO;
use PDOException;

class DashboardDataModel
{
    private static $Database;
    private $Conn;
    public function __construct()
    {
        self::$Database = new Database();
        $this->Conn = self::$Database->connect();
    }

    public function FacultyDashboard(int $facultyId, array $query): array
    {
        try {
            if (empty($facultyId)) {
                throw new Exception("Error Processing Request", 400);
            }
            // summary statistic (waste_transaction)
            $summarySql = "SELECT 
                                COUNT(w.waste_transaction_id) AS transaction_count,
                                COALESCE(SUM(w.waste_transaction_total_weight), 0) AS total_weight,
                                COALESCE(SUM(w.waste_transaction_total_point), 0) AS total_spend_point,
                                COALESCE(SUM(w.waste_transaction_total_co2e), 0) AS total_co2e
                            FROM waste_transaction w
                            WHERE w.faculty_id = :faculty_id";
            $stmt = $this->Conn->prepare($summarySql);
            $stmt->execute([":faculty_id" => $facultyId]);
            $summary = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];

            // summary today statistic (waste_transaction)
            $summaryTodaySql = "SELECT 
                                    COUNT(w.waste_transaction_id) AS transaction_count,
                                    COALESCE(SUM(w.waste_transaction_total_weight), 0) AS total_weight,
                                    COALESCE(SUM(w.waste_transaction_total_point), 0) AS total_spend_point,
                                    COALESCE(SUM(w.waste_transaction_total_co2e), 0) AS total_co2e
                                FROM waste_transaction w
                                WHERE w.faculty_id = :faculty_id AND DATE(w.created_at) = :today_date";
            $stmtToday = $this->Conn->prepare($summaryTodaySql);
            $stmtToday->execute([":faculty_id" => $facultyId, ":today_date" => date('Y-m-d')]);
            $summaryToday = $stmtToday->fetch(PDO::FETCH_ASSOC) ?: [];

            // faculty_detail
            $facultySql = "SELECT
                                f.*, 
                                COALESCE(m_count.total_major, 0) AS major_count_total
                                -- COALESCE(mem_count.member_count, 0) AS member_count,
                                -- COALESCE(mem_count.professor_count, 0) AS professor_count,
                                -- COALESCE(mem_count.employee_count, 0) AS employee_count,
                                -- COALESCE(mem_count.staff_count, 0) AS staff_count,
                                -- COALESCE(mem_count.total_member, 0) AS total_member
                            FROM faculty f
                            LEFT JOIN (
                                SELECT faculty_id, COUNT(major_id) AS total_major
                                FROM major
                                GROUP BY faculty_id
                            ) AS m_count ON f.faculty_id = m_count.faculty_id
                            -- LEFT JOIN (
                            --     SELECT faculty_id,
                            --         SUM(CASE WHEN role_id = 1 THEN 1 ELSE 0 END) as member_count,
                            --         SUM(CASE WHEN role_id = 2 THEN 1 ELSE 0 END) as professor_count,
                            --         SUM(CASE WHEN role_id = 3 THEN 1 ELSE 0 END) as employee_count,
                            --         SUM(CASE WHEN role_id = 4 THEN 1 ELSE 0 END) as staff_count,
                            --         COUNT(member_id) as total_member
                            --     FROM member
                            --     GROUP BY faculty_id
                            -- ) AS mem_count ON f.faculty_id = mem_count.faculty_id
                            WHERE f.faculty_id = :faculty_id";
            $fStmt = $this->Conn->prepare($facultySql);
            $fStmt->execute([':faculty_id' => $facultyId]);
            $faculty = $fStmt->fetch(PDO::FETCH_ASSOC) ?: [];

            // $majorSql = "SELECT * FROM major WHERE faculty_id = :faculty_id";
            // $majorStmt = $this->Conn->prepare($majorSql);
            // $majorStmt->bindValue(':faculty_id', $facultyId, PDO::PARAM_INT);
            // $majorStmt->execute();
            // $faculty['majors'] = $majorStmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

            return [
                'faculty' => $faculty,
                'summary' => $summary,
                'summary_today' => $summaryToday,
            ];

        } catch (PDOException $th) {
            throw new Exception("Database error: " . $th->getMessage(), 500);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function CenterDashboard(array $query): array
    {
        try {
            // summary statistic (waste_transaction)
            $summarySql = "SELECT 
                            COUNT(w.waste_transaction_id) AS transaction_count,
                            COALESCE(SUM(w.waste_transaction_total_weight), 0) AS total_weight,
                            COALESCE(SUM(w.waste_transaction_total_point), 0) AS total_spend_point,
                            COALESCE(SUM(w.waste_transaction_total_co2e), 0) AS total_co2e
                       FROM waste_transaction w";

            $stmt = $this->Conn->prepare($summarySql);
            $stmt->execute();
            $summary = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
            // summary statistic (waste_transaction)
            $summaryMonthSql = "SELECT 
                            COUNT(w.waste_transaction_id) AS transaction_count,
                            COALESCE(SUM(w.waste_transaction_total_weight), 0) AS total_weight,
                            COALESCE(SUM(w.waste_transaction_total_point), 0) AS total_spend_point,
                            COALESCE(SUM(w.waste_transaction_total_co2e), 0) AS total_co2e
                       FROM waste_transaction w
                       WHERE MONTH(w.created_at) = MONTH(NOW()) AND YEAR(w.created_at) = YEAR(NOW())";

            $stmt = $this->Conn->prepare($summaryMonthSql);
            $stmt->execute();
            $summaryMonth = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
            // summary today statistic (waste_transaction)
            $summaryTodaySql = "SELECT 
                            COUNT(w.waste_transaction_id) AS transaction_count,
                            COALESCE(SUM(w.waste_transaction_total_weight), 0) AS total_weight,
                            COALESCE(SUM(w.waste_transaction_total_point), 0) AS total_spend_point,
                            COALESCE(SUM(w.waste_transaction_total_co2e), 0) AS total_co2e
                       FROM waste_transaction w
                       WHERE DATE(w.created_at) = :today_date";
            $stmtToday = $this->Conn->prepare($summaryTodaySql);
            $stmtToday->execute([":today_date" => date('Y-m-d')]);
            $summaryToday = $stmtToday->fetch(PDO::FETCH_ASSOC) ?: [];

            $summaryMemberSql = "SELECT 
                                    COUNT(m.member_id) AS member_count
                                FROM 
                                    member m
                                WHERE m.role_id IN (2, 4)";
            $stmtMember = $this->Conn->prepare($summaryMemberSql);
            $stmtMember->execute();
            $summaryMember = $stmtMember->fetch(PDO::FETCH_ASSOC) ?: [];
            $totalMember = $summaryMember['member_count'];

            $transactionCounts = [
                'total' => (int) ($summary['transaction_count'] ?? 0),
                'month' => (int) ($summaryMonth['transaction_count'] ?? 0),
                'today' => (int) ($summaryToday['transaction_count'] ?? 0),
            ];

            return [
                'summary' => $summary,
                'summary_month' => $summaryMonth,
                'summary_today' => $summaryToday,
                'total_member' => $totalMember
            ];

        } catch (PDOException $th) {
            throw new Exception("Database error: " . $th->getMessage(), 500);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

}
