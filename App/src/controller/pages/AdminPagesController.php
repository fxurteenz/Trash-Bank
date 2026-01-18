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

    public function ManageUsers()
    {
        try {
            $user = Authentication::AdminAuth();
            $this->render('manages/users', [
                'pages' => "manageUsers",
                'title' => 'จัดการผู้ใช้งาน',
                'script' => '../../js/ManageUsers.js',
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

    public function ManageRewards()
    {
        try {
            $user = Authentication::AdminAuth();
            $this->render('manages/rewards', [
                'pages' => "manageRewards",
                'title' => 'จัดการของรางวัล',
                'script' => '../../js/ManageRewards.js',
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

    public function ManageTransactionClearance($wcid)
    {
        try {
            $user = Authentication::AdminAuth();
            $this->render('transactions/manage/clear_waste', [
                'pages' => "clearWasteTransaction",
                'title' => 'ระบบเคลียร์ยอดฝากขยะ',
                'wcid' => $wcid,
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

    public function ManageDonations()
    {
        try {
            $user = Authentication::AdminAuth();
            $this->render('manages/donations', [
                'pages' => "manageDonations",
                'title' => 'บริจาคสิ่งของ/วัสดุ',
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

    public function RedeemRewards()
    {
        try {
            $user = Authentication::AdminAuth();
            $this->render('manages/redeem_rewards', [
                'pages' => "redeemRewards",
                'title' => 'แลกของรางวัล',
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

    public function WasteSaleHistory()
    {
        try {
            $user = Authentication::AdminAuth();
            $this->render('manages/waste_sale', [
                'pages' => "wasteSaleHistory",
                'title' => 'ประวัติการจำหน่ายขยะ',
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
}