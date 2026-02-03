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

            $summaryMemberSql = "SELECT 
                                    COUNT(m.member_id) AS member_count
                                FROM member m
                                WHERE m.faculty_id = :faculty_id AND m.role_id = 2";
            $stmtToday = $this->Conn->prepare($summaryMemberSql);
            $stmtToday->execute([":faculty_id" => $facultyId]);
            $summaryMember = $stmtToday->fetch(PDO::FETCH_ASSOC) ?: [];
            $summary['total_member'] = $summaryMember['member_count'];

            // faculty_detail
            $facultySql = "SELECT * FROM faculty WHERE faculty_id = :faculty_id";
            $fStmt = $this->Conn->prepare($facultySql);
            $fStmt->execute([':faculty_id' => $facultyId]);
            $faculty = $fStmt->fetch(PDO::FETCH_ASSOC) ?: [];

            return [
                'faculty' => $faculty,
                'summary' => $summary,
                'summary_today' => $summaryToday
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
                                WHERE m.role_id = 2";
            $stmtToday = $this->Conn->prepare($summaryMemberSql);
            $stmtToday->execute();
            $summaryMember = $stmtToday->fetch(PDO::FETCH_ASSOC) ?: [];
            $summary['total_member'] = $summaryMember['member_count'];

            // faculty_detail
            $facultySql = "SELECT * FROM faculty";
            $fStmt = $this->Conn->prepare($facultySql);
            $fStmt->execute();
            $faculty = $fStmt->fetch(PDO::FETCH_ASSOC) ?: [];

            return [
                'faculty' => $faculty,
                'summary' => $summary,
                'summary_today' => $summaryToday
            ];

        } catch (PDOException $th) {
            throw new Exception("Database error: " . $th->getMessage(), 500);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

}
