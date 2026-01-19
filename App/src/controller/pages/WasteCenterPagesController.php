<?php
namespace App\Controller\Pages;

use Exception;
use App\Router\RouterBase;
use App\Utils\Authentication;
use App\Utils\AuthenticationException;

class WasteCenterPagesController extends RouterBase
{
    private static $Layouts = "wasteCenterLayout";

    public function HomePage()
    {
        try {
            Authentication::CenterAuth();
            $this->render('waste_center/index', [
                'pages' => 'home',
                'title' => 'ศูนย์กลาง'
            ], self::$Layouts);
        } catch (AuthenticationException $th) {
            header('location: /');
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function TransactionWaste()
    {
        try {
            Authentication::CenterAuth();
            $this->render('transactions/waste', [
                'pages' => "wasteTransaction",
                'title' => 'ระบบฝากขยะ'
            ], self::$Layouts);
        } catch (AuthenticationException $th) {
            // $this->errorPage(403, '403');
            header('location: /');
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function WasteDepositPOS()
    {
        try {
            Authentication::CenterAuth();
            $this->render('transactions/waste_deposit_pos', [
                'pages' => "wasteDepositPOS",
                'title' => 'ระบบฝากขยะ POS'
            ], self::$Layouts);
        } catch (AuthenticationException $th) {
            header('location: /');
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function ClearTransactionWaste()
    {
        try {
            $user = Authentication::CenterAuth();
            $this->render('transactions/clear_waste', [
                'pages' => 'clearWasteTransaction',
                'title' => 'เคลียร์ยอดฝากขยะ',
                'user' => $user['user_data']
            ], self::$Layouts);
        } catch (AuthenticationException $th) {
            header('location: /');
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function ManageTransactionClearancePage($wcid)
    {
        try {
            $user = Authentication::CenterAuth();
            $this->render('transactions/manage/clear_waste', [
                'user' => $user['user_data'],
                'pages' => 'clearWasteTransaction',
                'title' => 'ระบบเคลียร์ยอดฝากขยะ',
                'wcid' => $wcid,
            ], self::$Layouts);
        } catch (AuthenticationException $th) {
            // $this->errorPage(403, '403');
            header('location: /');
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function ManageWasteType()
    {
        try {
            Authentication::CenterAuth();
            $this->render('manages/waste_type', [
                'pages' => "manageWasteType",
                'title' => 'จัดการหมวดหมู่ขยะ'
            ], self::$Layouts);
        } catch (AuthenticationException $th) {
            // $this->errorPage(403, '403');
            header('location: /');
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        } finally {
            exit;
        }
    }

    public function ManageWasteTransaction()
    {
        try {
            Authentication::CenterAuth();
            $this->render('manages/waste_transaction', [
                'pages' => "manageWasteTransaction",
                'title' => 'ประวัติการดำเนินการ'
            ], self::$Layouts);
        } catch (AuthenticationException $th) {
            // $this->errorPage(403, '403');
            header('location: /');
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function ManageRewards()
    {
        try {
            Authentication::CenterAuth();
            $this->render('manages/rewards', [
                'pages' => "manageRewards",
                'title' => 'จัดการของรางวัล',
                'script' => '../../js/ManageRewards.js'
            ], self::$Layouts);
        } catch (AuthenticationException $th) {
            header('location: /');
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        } finally {
            exit;
        }
    }

    public function WasteSaleTransaction()
    {
        try {
            Authentication::CenterAuth();
            $this->render('transactions/waste_sale_pos', [
                'pages' => 'wasteSalePOS',
                'title' => 'ระบบขายขยะ POS'
            ], self::$Layouts);
        } catch (AuthenticationException $th) {
            header('location: /');
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function WasteSaleHistory()
    {
        try {
            Authentication::CenterAuth();
            $this->render('transactions/waste_sale_history', [
                'pages' => 'wasteSaleHistory',
                'title' => 'ประวัติการขายขยะ'
            ], self::$Layouts);
        } catch (AuthenticationException $th) {
            header('location: /');
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function DonationExchange()
    {
        try {
            Authentication::CenterAuth();
            $this->render('transactions/donation_exchange', [
                'pages' => 'donationExchange',
                'title' => 'แลกของบริจาค'
            ], self::$Layouts);
        } catch (AuthenticationException $th) {
            header('location: /');
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function FacultyDetail()
    {
        try {
            Authentication::CenterAuth();
            
            $facultyId = $_GET['faculty_id'] ?? null;
            
            if (!$facultyId) {
                throw new Exception("Faculty ID not provided", 400);
            }

            // Import models
            $Database = new \App\Utils\Database();
            $conn = $Database->connect();

            // Get faculty info
            $sqlFaculty = "SELECT * FROM faculty WHERE faculty_id = :faculty_id";
            $stmtFaculty = $conn->prepare($sqlFaculty);
            $stmtFaculty->execute([':faculty_id' => $facultyId]);
            $faculty = $stmtFaculty->fetch(\PDO::FETCH_ASSOC);

            if (!$faculty) {
                throw new Exception("Faculty not found", 404);
            }

            // Get waste items in faculty storage
            $sqlWaste = "
                SELECT 
                    fws.faculty_id,
                    fws.waste_type_id,
                    fws.stock_weight,
                    wt.waste_type_name,
                    wt.waste_type_point_per_kg
                FROM faculty_waste_stock fws
                JOIN waste_type wt ON fws.waste_type_id = wt.waste_type_id
                WHERE fws.faculty_id = :faculty_id
                ORDER BY wt.waste_type_name ASC
            ";
            $stmtWaste = $conn->prepare($sqlWaste);
            $stmtWaste->execute([':faculty_id' => $facultyId]);
            $wasteItems = $stmtWaste->fetchAll(\PDO::FETCH_ASSOC);

            // Calculate current points from waste items
            $currentPoints = 0;
            foreach ($wasteItems as $item) {
                $currentPoints += ($item['stock_weight'] * $item['waste_type_point_per_kg']);
            }

            // Get given points from faculty_point table
            $sqlGivenPoints = "
                SELECT COALESCE(SUM(faculty_point_amount), 0) as total_given
                FROM faculty_point
                WHERE faculty_id = :faculty_id
            ";
            $stmtGiven = $conn->prepare($sqlGivenPoints);
            $stmtGiven->execute([':faculty_id' => $facultyId]);
            $givenResult = $stmtGiven->fetch(\PDO::FETCH_ASSOC);
            $givenPoints = $givenResult['total_given'] ?? 0;

            $facultyStats = [
                'current_points' => $currentPoints,
                'given_points' => $givenPoints
            ];

            $this->render('waste_center/faculty_detail', [
                'pages' => 'facultyDetail',
                'title' => 'รายละเอียดคณะ',
                'faculty' => $faculty,
                'wasteItems' => $wasteItems,
                'facultyStats' => $facultyStats
            ], self::$Layouts);
        } catch (AuthenticationException $th) {
            header('location: /');
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }
}
