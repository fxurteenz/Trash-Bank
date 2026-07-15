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
                            FROM faculty f
                            LEFT JOIN (
                                SELECT faculty_id, COUNT(major_id) AS total_major
                                FROM major
                                GROUP BY faculty_id
                            ) AS m_count ON f.faculty_id = m_count.faculty_id
                            WHERE f.faculty_id = :faculty_id";
            $fStmt = $this->Conn->prepare($facultySql);
            $fStmt->execute([':faculty_id' => $facultyId]);
            $faculty = $fStmt->fetch(PDO::FETCH_ASSOC) ?: [];

            $stockSql = "SELECT
                            fws.*,
                            wt.waste_type_name,
                            wc.waste_category_name
                        FROM 
                            faculty_waste_stock fws
                        LEFT JOIN 
                            waste_type wt ON fws.waste_type_id = wt.waste_type_id
                        LEFT JOIN 
                            waste_category wc ON wt.waste_category_id = wc.waste_category_id
                        WHERE 
                            faculty_id = :faculty_id
                        ORDER BY wt.waste_category_id, wt.waste_type_id";
            $stockStmt = $this->Conn->prepare($stockSql);
            $stockStmt->execute([':faculty_id' => $facultyId]);
            $stock = $stockStmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

            return [
                'faculty' => $faculty,
                'summary' => $summary,
                'summary_today' => $summaryToday,
                'stocks' => $stock
            ];

        } catch (PDOException $th) {
            throw new Exception("Database error: " . $th->getMessage(), 500);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function CenterDashboard(array $query = []): array
    {
        try {
            $summarySql = "SELECT 
                -- Total (ยอดรวมทั้งหมด)
                COUNT(w.waste_transaction_id) AS total_transaction_count,
                COALESCE(SUM(w.waste_transaction_total_weight), 0) AS total_weight,
                COALESCE(SUM(w.waste_transaction_total_point), 0) AS total_spend_point,
                COALESCE(SUM(w.waste_transaction_total_co2e), 0) AS total_co2e,
                
                -- Month (ยอดรวมของเดือนนี้)
                COUNT(CASE WHEN MONTH(w.created_at) = MONTH(CURRENT_DATE()) AND YEAR(w.created_at) = YEAR(CURRENT_DATE()) THEN w.waste_transaction_id END) AS month_transaction_count,
                COALESCE(SUM(CASE WHEN MONTH(w.created_at) = MONTH(CURRENT_DATE()) AND YEAR(w.created_at) = YEAR(CURRENT_DATE()) THEN w.waste_transaction_total_weight END), 0) AS month_weight,
                COALESCE(SUM(CASE WHEN MONTH(w.created_at) = MONTH(CURRENT_DATE()) AND YEAR(w.created_at) = YEAR(CURRENT_DATE()) THEN w.waste_transaction_total_point END), 0) AS month_spend_point,
                COALESCE(SUM(CASE WHEN MONTH(w.created_at) = MONTH(CURRENT_DATE()) AND YEAR(w.created_at) = YEAR(CURRENT_DATE()) THEN w.waste_transaction_total_co2e END), 0) AS month_co2e,
                
                -- Today (ยอดรวมของวันนี้)
                COUNT(CASE WHEN DATE(w.created_at) = CURRENT_DATE() THEN w.waste_transaction_id END) AS today_transaction_count,
                COALESCE(SUM(CASE WHEN DATE(w.created_at) = CURRENT_DATE() THEN w.waste_transaction_total_weight END), 0) AS today_weight,
                COALESCE(SUM(CASE WHEN DATE(w.created_at) = CURRENT_DATE() THEN w.waste_transaction_total_point END), 0) AS today_spend_point,
                COALESCE(SUM(CASE WHEN DATE(w.created_at) = CURRENT_DATE() THEN w.waste_transaction_total_co2e END), 0) AS today_co2e

            FROM waste_transaction w";

            $stmt = $this->Conn->prepare($summarySql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];

            $summary = [
                'transaction_count' => $result['total_transaction_count'] ?? 0,
                'total_weight' => $result['total_weight'] ?? 0,
                'total_spend_point' => $result['total_spend_point'] ?? 0,
                'total_co2e' => $result['total_co2e'] ?? 0,
            ];

            $summaryMonth = [
                'transaction_count' => $result['month_transaction_count'] ?? 0,
                'total_weight' => $result['month_weight'] ?? 0,
                'total_spend_point' => $result['month_spend_point'] ?? 0,
                'total_co2e' => $result['month_co2e'] ?? 0,
            ];

            $summaryToday = [
                'transaction_count' => $result['today_transaction_count'] ?? 0,
                'total_weight' => $result['today_weight'] ?? 0,
                'total_spend_point' => $result['today_spend_point'] ?? 0,
                'total_co2e' => $result['today_co2e'] ?? 0,
            ];

            // 2. ดึงข้อมูลจำนวนสมาชิก (แยกไว้เพราะเป็นคนละตารางกัน ไม่ควรนำไป JOIN ให้หนัก)
            $summaryMemberSql = "SELECT COUNT(m.member_id) FROM member m WHERE m.role_id IN (1, 2, 3)";
            $stmtMember = $this->Conn->prepare($summaryMemberSql);
            $stmtMember->execute();
            // ใช้ fetchColumn() เพื่อดึงค่า COUNT ออกมาเป็นตัวเลขโดยตรง (เร็วและสั้นกว่า)
            $totalMember = (int) ($stmtMember->fetchColumn() ?: 0);

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
