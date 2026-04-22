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
            $user = Authentication::CenterAuth();
            $this->render('waste_center/dashboard', [
                'user' => $user["user_data"],
                'pages' => 'home',
                'title' => 'แดชบอร์ด',
                'module' => '../../js/Dashboard.mjs'
            ], self::$Layouts);
        } catch (AuthenticationException $th) {
            // $this->errorPage(403, '403');
            header('location: /');
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    // transaction pages
    public function WasteTransactionPage()
    {
        try {
            $user = Authentication::CenterAuth();
            $this->render('transactions/waste_deposit', [
                'user' => $user["user_data"],
                'pages' => 'wasteTransaction',
                'title' => 'ระบบฝากขยะ',
            ], self::$Layouts);
        } catch (AuthenticationException $th) {
            // $this->errorPage(403, '403');
            header('location: /');
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function TransactionClearancePage()
    {
        try {
            $user = Authentication::OperateAuth();
            $this->render('transactions/clear_waste', [
                'pages' => "clearWasteTransaction",
                'title' => 'ระบบเคลียร์ยอดฝากขยะ',
                'user' => $user["user_data"]
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

    public function TransactionWasteSalePage()
    {
        try {
            $user = Authentication::CenterAuth();
            $this->render('transactions/waste_sale', [
                'pages' => "saleWasteTransaction",
                'title' => 'ระบบบันทึกการจำหน่ายออก',
                'user' => $user["user_data"]
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

    public function TransactionDonationPage()
    {
        try {
            $user = Authentication::CenterAuth();
            $this->render('transactions/donation_pos', [
                'user' => $user["user_data"],
                'pages' => "donationTransaction",
                'title' => 'บันทึกการรับของบริจาค'
            ], self::$Layouts);
        } catch (AuthenticationException $th) {
            header('location: /');
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function TransactionRedeemDonationItemPage()
    {
        try {
            $user = Authentication::CenterAuth();
            $this->render('transactions/redeem_item', [
                'user' => $user["user_data"],
                'pages' => "redeemItemTransaction",
                'title' => 'บันทึกการแลกสิ่งของ'
            ], self::$Layouts);
        } catch (AuthenticationException $th) {
            header('location: /');
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    // history pages
    public function WasteTransactionHistoryPage()
    {
        try {
            $user = Authentication::CenterAuth();
            $this->render('history/waste_transaction', [
                'user' => $user["user_data"],
                'pages' => 'wasteTransactionHistory',
                'title' => 'ประวัติการฝากขยะ',
            ], self::$Layouts);
        } catch (AuthenticationException $th) {
            // $this->errorPage(403, '403');
            header('location: /');
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function ClearWasteHistoryPage()
    {
        try {
            $user = Authentication::CenterAuth();
            $this->render('history/clear_waste', [
                'user' => $user["user_data"],
                'pages' => "clearWasteHistory",
                'title' => 'ประวัติการเคลียร์ยอด'
            ], self::$Layouts);
        } catch (AuthenticationException $th) {
            header('location: /');
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function WasteSaleHistoryPage()
    {
        try {
            $user = Authentication::CenterAuth();
            $this->render('history/waste_sale_headers', [
                'user' => $user["user_data"],
                'pages' => "wasteSaleHistory",
                'title' => 'ประวัติการขายขยะ'
            ], self::$Layouts);
        } catch (AuthenticationException $th) {
            header('location: /');
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function DonationHistoryPage()
    {
        try {
            $user = Authentication::CenterAuth();
            $this->render('history/donation', [
                'user' => $user["user_data"],
                'pages' => "donationHistory",
                'title' => 'ประวัติการรับของบริจาค'
            ], self::$Layouts);
        } catch (AuthenticationException $th) {
            header('location: /');
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    // manage pages
    public function ManageMemberPage()
    {
        try {
            $user = Authentication::CenterAuth();
            $this->render('waste_center/manages/users', [
                'pages' => 'memberManagement',
                'title' => 'จัดการสมาชิก',
                'user' => $user["user_data"]
            ], self::$Layouts);
        } catch (AuthenticationException $th) {
            $this->errorPage(403, '403');
            header('location: /');
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function ManageMemberDetailPage($member_id)
    {
        try {
            $user = Authentication::CenterAuth();
            $this->render('waste_center/manages/details/user_detail', [
                'member_id' => !empty($member_id) ? (int) $member_id : null,
                'user' => $user["user_data"],
                'pages' => 'memberManagement',
                'title' => 'จัดการสมาชิก'
            ], self::$Layouts);
        } catch (AuthenticationException $th) {
            // $this->errorPage(403, '403');
            header('location: /');
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function WasteStockPage()
    {
        try {
            $user = Authentication::CenterAuth();
            $this->render('waste_center/manages/waste_stock', [
                'user' => $user["user_data"],
                'pages' => 'wasteStock',
                'title' => 'ขยะในคลัง'
            ], self::$Layouts);
        } catch (AuthenticationException $th) {
            header('location: /');
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

}
