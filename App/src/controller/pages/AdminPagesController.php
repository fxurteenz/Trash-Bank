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

    public function DonationHistory()
    {
        try {
            Authentication::AdminAuth();
            $this->render('transactions/donation_history', [
                'pages' => "donationHistory",
                'title' => 'ประวัติการรับของบริจาค'
            ], self::$AdminTemplate);
        } catch (AuthenticationException $th) {
            header('location: /');
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function TransactionRedeemReward()
    {
        try {
            Authentication::AdminAuth();
            $this->render('transactions/redeem_reward_pos', [
                'pages' => "redeemRewardTransaction",
                'title' => 'บันทึกการแลกของรางวัล'
            ], self::$AdminTemplate);
        } catch (AuthenticationException $th) {
            header('location: /');
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function RedeemRewardHistory()
    {
        try {
            Authentication::AdminAuth();
            $this->render('transactions/redeem_reward_history', [
                'pages' => "redeemRewardHistory",
                'title' => 'ประวัติการแลกของรางวัล'
            ], self::$AdminTemplate);
        } catch (AuthenticationException $th) {
            header('location: /');
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function WasteSaleHistory()
    {
        try {
            Authentication::AdminAuth();
            $this->render('transactions/waste_sale_history', [
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
            $this->render('transactions/clear_waste_history', [
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
            $this->render('manages/waste_transaction', [
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