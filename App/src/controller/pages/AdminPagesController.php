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
                'user' => $user["user_data"]
            ], self::$AdminTemplate);
        } catch (AuthenticationException $th) {
            $this->errorPage(403, '403');
            header('location: /');
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }
    //  Manage Page
    public function ManageUsers()
    {
        try {
            $user = Authentication::AdminAuth();
            $this->render('waste_center/manages/users', [
                'pages' => "manageUsers",
                'title' => 'จัดการผู้ใช้งาน',
                'user' => $user["user_data"]
            ], self::$AdminTemplate);
        } catch (AuthenticationException $th) {
            $this->errorPage(403, '403');
            header('location: /');
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }
    public function ManageUsersDetail($member_id)
    {
        try {
            $user = Authentication::AdminAuth();
            $this->render('waste_center/details/user_detail', [
                'pages' => "manageUsers",
                'title' => 'จัดการผู้ใช้งาน',
                'member_id' => !empty($member_id) ? (int) $member_id : null,
                'user' => $user["user_data"]
            ], self::$AdminTemplate);
        } catch (AuthenticationException $th) {
            $this->errorPage(403, '403');
            header('location: /');
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
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
                'user' => $user["user_data"]
            ], self::$AdminTemplate);
        } catch (AuthenticationException $th) {
            // $this->errorPage(403, '403');
            header('location: /');
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }
    public function ManageFacultyDetail($faculty_id)
    {
        try {
            $user = Authentication::AdminAuth();
            $this->render('manages/details/faculty', [
                'pages' => "manageFaculty",
                'title' => 'จัดการข้อมูลคณะ',
                'faculty_id' => !empty($faculty_id) ? (int) $faculty_id : null,
                'user' => $user["user_data"]
            ], self::$AdminTemplate);
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
            $user = Authentication::AdminAuth();
            $this->render('manages/waste_type', [
                'pages' => "manageWasteType",
                'title' => 'จัดการหมวดหมู่ขยะ',
                'user' => $user["user_data"]
            ], self::$AdminTemplate);
        } catch (AuthenticationException $th) {
            // $this->errorPage(403, '403');
            header('location: /');
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function ManageWasteCategoryDetail($wcid)
    {
        try {
            $user = Authentication::AdminAuth();
            $this->render('manages/details/waste_type', [
                'pages' => "manageWasteType",
                'title' => 'จัดการหมวดหมู่ขยะ',
                'wcid' => !empty($wcid) ? (int) $wcid : null,
                'user' => $user["user_data"]
            ], self::$AdminTemplate);
        } catch (AuthenticationException $th) {
            // $this->errorPage(403, '403');
            header('location: /');
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function ManageWasteTransaction()
    {
        try {
            $user = Authentication::AdminAuth();
            $this->render('manages/waste_transaction', [
                'pages' => "manageWasteTransaction",
                'title' => 'ประวัติการดำเนินการ',
                'user' => $user["user_data"]
            ], self::$AdminTemplate);
        } catch (AuthenticationException $th) {
            // $this->errorPage(403, '403');
            header('location: /');
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function ManageBadges()
    {
        try {
            $user = Authentication::AdminAuth();
            $this->render('maintenance', [
                'pages' => "manageBadges",
                'title' => 'จัดการเหรียญตรา',
                'script' => '../../js/ManageBadges.js',
                'user' => $user["user_data"]
            ], self::$AdminTemplate);
        } catch (AuthenticationException $th) {
            header('location: /');
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function ManageRewards()
    {
        try {
            $user = Authentication::AdminAuth();
            $this->render('manages/rewards', [
                'pages' => "manageBadges",
                'title' => 'จัดการของรางวัล',
                'user' => $user["user_data"]
            ], self::$AdminTemplate);
        } catch (AuthenticationException $th) {
            header('location: /');
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function ManageRewardCategories()
    {
        try {
            $user = Authentication::AdminAuth();
            $this->render('manages/reward_categories', [
                'pages' => "manageRewardCategories",
                'title' => 'จัดการหมวดหมู่ของรางวัล',
                'user' => $user["user_data"]
            ], self::$AdminTemplate);
        } catch (AuthenticationException $th) {
            header('location: /');
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function ManageRewardCategoryDetail($cid)
    {
        try {
            $user = Authentication::AdminAuth();
            $this->render('manages/details/reward_category', [
                'pages' => "manageRewardCategories",
                'title' => 'รายการของรางวัลในหมวดหมู่',
                'cid' => !empty($cid) ? (int) $cid : null,
                'user' => $user["user_data"]
            ], self::$AdminTemplate);
        } catch (AuthenticationException $th) {
            header('location: /');
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function ManagePointGroup()
    {
        try {
            $user = Authentication::AdminAuth();
            $this->render('manages/point_group', [
                'pages' => "managePointGroup",
                'title' => 'จัดการกลุ่มแต้ม',
                'user' => $user["user_data"]
            ], self::$AdminTemplate);
        } catch (AuthenticationException $th) {
            header('location: /');
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    // Stock Page
    public function WasteStock()
    {
        try {
            $user = Authentication::AdminAuth();
            $this->render('waste_center/manages/waste_stock', [
                'pages' => "manageWasteStock",
                'title' => 'คลังขยะ',
                'user' => $user["user_data"]
            ], self::$AdminTemplate);
        } catch (AuthenticationException $th) {
            header('location: /');
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }
    public function BranchWasteStock()
    {
        try {
            $user = Authentication::AdminAuth();
            $this->render('/manages/waste_stock', [
                'pages' => "manageWasteStock",
                'title' => 'คลังขยะ',
                'user' => $user["user_data"]
            ], self::$AdminTemplate);
        } catch (AuthenticationException $th) {
            header('location: /');
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }
    public function RewardStock()
    {
        try {
            $user = Authentication::AdminAuth();
            $this->render('maintenance', [
                'pages' => "manageWasteStock",
                'title' => 'คลังของรางวัล',
                'user' => $user["user_data"]
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
                'user' => $user["user_data"]
            ], self::$AdminTemplate);
        } catch (AuthenticationException $th) {
            // $this->errorPage(403, '403');
            header('location: /');
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function TransactionClearance()
    {
        try {
            $user = Authentication::AdminAuth();
            $this->render('transactions/clear_waste', [
                'pages' => "clearWasteTransaction",
                'title' => 'ระบบเคลียร์ยอดฝากขยะ',
                'user' => $user["user_data"]
            ], self::$AdminTemplate);
        } catch (AuthenticationException $th) {
            // $this->errorPage(403, '403');
            header('location: /');
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function TransactionWasteSale()
    {
        try {
            $user = Authentication::AdminAuth();
            $this->render('transactions/waste_sale', [
                'pages' => "saleWasteTransaction",
                'title' => 'ระบบบันทึกการจำหน่ายออก',
                'user' => $user["user_data"]

            ], self::$AdminTemplate);
        } catch (AuthenticationException $th) {
            // $this->errorPage(403, '403');
            header('location: /');
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function TransactionDonation()
    {
        try {
            $user = Authentication::AdminAuth();
            $this->render('transactions/donation_pos', [
                'pages' => "donationTransaction",
                'title' => 'บันทึกการรับของบริจาค',
                'user' => $user["user_data"]

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
            $user = Authentication::AdminAuth();
            $this->render('transactions/redeem_item', [
                'pages' => "redeemItemTransaction",
                'title' => 'แลกของรางวัล',
                'user' => $user["user_data"]

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
                'user' => $user["user_data"]

            ], self::$AdminTemplate);
        } catch (AuthenticationException $th) {
            // $this->errorPage(403, '403');
            header('location: /');
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function WasteSaleHistory()
    {
        try {
            $user = Authentication::AdminAuth();
            $this->render('history/waste_sale_headers', [
                'pages' => "wasteSaleHistory",
                'title' => 'ประวัติการขายขยะ',
                'user' => $user["user_data"]

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
            $user = Authentication::AdminAuth();
            $this->render('history/clear_waste', [
                'pages' => "clearWasteHistory",
                'title' => 'ประวัติการเคลียร์ยอด',
                'user' => $user["user_data"]

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
            $user = Authentication::AdminAuth();
            $this->render('history/waste_transaction', [
                'pages' => "wasteTransactionHistory",
                'title' => 'ประวัติการฝากขยะ',
                'user' => $user["user_data"]

            ], self::$AdminTemplate);
        } catch (AuthenticationException $th) {
            header('location: /');
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function RedeemHistory()
    {
        try {
            $user = Authentication::AdminAuth();
            $this->render('maintenance', [
                'pages' => "DonationHistory",
                'title' => 'ประวัติการแลกของรางวัล',
                'user' => $user["user_data"]

            ], self::$AdminTemplate);
        } catch (AuthenticationException $th) {
            // $this->errorPage(403, '403');
            header('location: /');
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }
    // Report
    public function Report()
    {
        try {
            $user = Authentication::AdminAuth();
            $this->render('maintenance', [
                'pages' => "reports",
                'title' => "รายงาน",
                'user' => $user["user_data"]
            ], self::$AdminTemplate);
        } catch (AuthenticationException $th) {
            header('location: /');
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }
}