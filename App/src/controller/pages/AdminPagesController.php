<?php
namespace App\Controller\Pages;

use Exception;
use App\Router\RouterBase;
use App\Utils\Authentication;
use App\Utils\AuthenticationException;

class AdminPagesController extends RouterBase
{
    private static $AdminTemplate = "adminLayout";
    private static $ReportLayout = "reportLayout";

    public function DashBoard()
    {
        try {
            $user = Authentication::AdminAuth();
            $this->render('admin/adminDashboard', [
                'page' => 'dashboard',
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
                'page' => "manageUsers",
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
                'page' => "manageUserDetail",
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
                'page' => "manageFaculty",
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
                'page' => "manageFaculty",
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

    public function ManageBranch()
    {
        try {
            $user = Authentication::AdminAuth();
            $this->render('manages/branch', [
                'page' => "manageBranch",
                'title' => 'จัดการคณะ/สาขา',
                'user' => $user["user_data"]
            ], self::$AdminTemplate);
        } catch (AuthenticationException $th) {
            // $this->errorPage(403, '403');
            header('location: /');
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function ManageBranchDetail($faculty_id)
    {
        try {
            $user = Authentication::AdminAuth();
            $this->render('manages/details/branch', [
                'page' => "manageBranch",
                'title' => 'จัดการข้อมูลหน่วยย่อย',
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

    public function ManageWasteCategory()
    {
        try {
            $user = Authentication::AdminAuth();
            $this->render('manages/waste_category', [
                'page' => "manageWasteType",
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

    public function ManageWasteType($wcid)
    {
        try {
            $user = Authentication::AdminAuth();
            $this->render('manages/waste_type', [
                'page' => "manageWasteType",
                'title' => 'จัดการหมวดหมู่ขยะ',
                'wcid' => !empty($wcid) ? $wcid : null,
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
                'page' => "manageWasteTransaction",
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
                'page' => "manageBadges",
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
                'page' => "manageBadges",
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
                'page' => "manageRewardCategories",
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
                'page' => "manageRewardCategories",
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
                'page' => "managePointGroup",
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
                'page' => "manageWasteStock",
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
                'page' => "manageWasteStock",
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
                'page' => "manageWasteStock",
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
                'page' => "wasteTransaction",
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
                'page' => "wasteClearance",
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
                'page' => "wasteSale",
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
                'page' => "donationTransaction",
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
                'page' => "redeemItemTransaction",
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
                'page' => "DonationHistory",
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
                'page' => "wasteSaleHistory",
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
                'page' => "clearWasteHistory",
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
                'page' => "wasteTransactionHistory",
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
                'page' => "DonationHistory",
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
                'page' => "reports",
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