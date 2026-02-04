<?php
namespace App\Controller\Pages;

use Exception;
use App\Router\RouterBase;
use App\Utils\Authentication;
use App\Utils\AuthenticationException;

class AdminPagesController extends RouterBase
{
    private static $AdminTemplate = "adminLayout";

    public function DashBoard()
    {
        try {
            $user = Authentication::AdminAuth();
            $this->render('admin/adminDashboard', [
                'pages' => 'dashboard',
                'title' => 'ผู้ดูแลระบบ',
                'module' => '../../js/Dashboard.mjs',
                'user' => $user
            ], self::$AdminTemplate);
        } catch (AuthenticationException $th) {
            $this->errorPage(403, '403');
            header('location: /');
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        } finally {
            exit;
        }
    }
    //  Manage Page
    public function ManageUsers()
    {
        try {
            $user = Authentication::AdminAuth();
            $this->render('manages/users', [
                'pages' => "manageUsers",
                'title' => 'จัดการผู้ใช้งาน',
                'script' => '../../js/ManageUsers.js',
                'user' => $user["user_data"]
            ], self::$AdminTemplate);
        } catch (AuthenticationException $th) {
            $this->errorPage(403, '403');
            header('location: /');
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        } finally {
            exit;
        }
    }

    public function ManageFaculty()
    {
        try {
            $user = Authentication::AdminAuth();
            $this->render('manages/faculty', [
                'pages' => "manageFaculty",
                'title' => 'จัดการคณะ/สาขา',
                'script' => '../../js/ManageFaculty.js',
                'user' => $user
            ], self::$AdminTemplate);
        } catch (AuthenticationException $th) {
            // $this->errorPage(403, '403');
            header('location: /');
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        } finally {
            exit;
        }
    }

    public function ManageWasteType()
    {
        try {
            $user = Authentication::AdminAuth();
            $this->render('manages/waste_type', [
                'pages' => "manageWasteType",
                'title' => 'จัดการหมวดหมู่ขยะ',
                'user' => $user
            ], self::$AdminTemplate);
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
            $user = Authentication::AdminAuth();
            $this->render('manages/waste_transaction', [
                'pages' => "manageWasteTransaction",
                'title' => 'ประวัติการดำเนินการ',
                'user' => $user
            ], self::$AdminTemplate);
        } catch (AuthenticationException $th) {
            // $this->errorPage(403, '403');
            header('location: /');
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        } finally {
            exit;
        }
    }

    public function ManageBadges()
    {
        try {
            $user = Authentication::AdminAuth();
            $this->render('manages/badges', [
                'pages' => "manageBadges",
                'title' => 'จัดการเหรียญตรา',
                'script' => '../../js/ManageBadges.js',
                'user' => $user
            ], self::$AdminTemplate);
        } catch (AuthenticationException $th) {
            header('location: /');
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        } finally {
            exit;
        }
    }

    public function ManagePointGroup()
    {
        try {
            $user = Authentication::AdminAuth();
            $this->render('manages/point_group', [
                'pages' => "managePointGroup",
                'title' => 'จัดการกลุ่มแต้ม',
                'user' => $user
            ], self::$AdminTemplate);
        } catch (AuthenticationException $th) {
            header('location: /');
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        } finally {
            exit;
        }
    }

    // Stock Page
    public function WasteStock()
    {
        try {
            Authentication::AdminAuth();
            $this->render('waste_center/manages/waste_stock', [
                'pages' => "manageWasteStock",
                'title' => 'คลังขยะ'
            ], self::$AdminTemplate);
        } catch (AuthenticationException $th) {
            header('location: /');
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    // Transaction & Redeem
    public function TransactionWaste()
    {
        try {
            $user = Authentication::AdminAuth();
            $this->render('transactions/waste_deposit', [
                'pages' => "wasteTransaction",
                'title' => 'ระบบฝากขยะ',
                'user' => $user
            ], self::$AdminTemplate);
        } catch (AuthenticationException $th) {
            // $this->errorPage(403, '403');
            header('location: /');
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        } finally {
            exit;
        }
    }

    public function TransactionClearance()
    {
        try {
            $user = Authentication::AdminAuth();
            $this->render('transactions/clear_waste', [
                'pages' => "clearWasteTransaction",
                'title' => 'ระบบเคลียร์ยอดฝากขยะ',
                'user' => $user
            ], self::$AdminTemplate);
        } catch (AuthenticationException $th) {
            // $this->errorPage(403, '403');
            header('location: /');
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        } finally {
            exit;
        }
    }

    public function TransactionWasteSale()
    {
        try {
            $user = Authentication::AdminAuth();
            $this->render('transactions/waste_sale', [
                'pages' => "saleWasteTransaction",
                'title' => 'ระบบบันทึกการจำหน่ายออก',
                'user' => $user
            ], self::$AdminTemplate);
        } catch (AuthenticationException $th) {
            // $this->errorPage(403, '403');
            header('location: /');
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        } finally {
            exit;
        }
    }

    public function TransactionDonation()
    {
        try {
            Authentication::AdminAuth();
            $this->render('transactions/donation_pos', [
                'pages' => "donationTransaction",
                'title' => 'บันทึกการรับของบริจาค'
            ], self::$AdminTemplate);
        } catch (AuthenticationException $th) {
            header('location: /');
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function TransactionRedeemDonationItem()
    {
        try {
            Authentication::AdminAuth();
            $this->render('transactions/redeem_item', [
                'pages' => "redeemItemTransaction",
                'title' => 'บันทึกการแลกสิ่งของ'
            ], self::$AdminTemplate);
        } catch (AuthenticationException $th) {
            header('location: /');
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    // History
    public function DonationHistory()
    {
        try {
            $user = Authentication::AdminAuth();
            $this->render('history/donation', [
                'pages' => "DonationHistory",
                'title' => 'ประวัติการบริจาคสิ่งของ',
                'user' => $user
            ], self::$AdminTemplate);
        } catch (AuthenticationException $th) {
            // $this->errorPage(403, '403');
            header('location: /');
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        } finally {
            exit;
        }
    }

    public function WasteSaleHistory()
    {
        try {
            Authentication::AdminAuth();
            $this->render('history/waste_sale_headers', [
                'pages' => "wasteSaleHistory",
                'title' => 'ประวัติการขายขยะ'
            ], self::$AdminTemplate);
        } catch (AuthenticationException $th) {
            header('location: /');
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function ClearWasteHistory()
    {
        try {
            Authentication::AdminAuth();
            $this->render('history/clear_waste', [
                'pages' => "clearWasteHistory",
                'title' => 'ประวัติการเคลียร์ยอด'
            ], self::$AdminTemplate);
        } catch (AuthenticationException $th) {
            header('location: /');
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function WasteTransactionHistory()
    {
        try {
            Authentication::AdminAuth();
            $this->render('history/waste_transaction', [
                'pages' => "wasteTransactionHistory",
                'title' => 'ประวัติการฝากขยะ'
            ], self::$AdminTemplate);
        } catch (AuthenticationException $th) {
            header('location: /');
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

}