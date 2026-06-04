<?php
namespace App\Controller\Pages;

use Exception;
use App\Router\RouterBase;
use App\Utils\Authentication;
use App\Utils\AuthenticationException;

class ReportPagesController extends RouterBase
{
    private static $ReportLayout = "reportLayout";
    // Report
    public function ReportUsers()
    {
        try {
            $user = Authentication::AdminAuth();
            $this->render('/waste_center/reports/users', [
                'pages' => "reports",
                'title' => "รายงานสมาชิก",
                'user' => $user["user_data"]
            ], self::$ReportLayout);
        } catch (AuthenticationException $th) {
            header('location: /');
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function ReportAllFaculties()
    {
        try {
            $user = Authentication::AdminAuth();
            $this->render('/waste_center/reports/faculties', [
                'pages' => "report all faculties",
                'title' => "รายงานรายชื่อคณะ",
                'user' => $user["user_data"]
            ], self::$ReportLayout);
        } catch (AuthenticationException $th) {
            header('location: /');
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function ReportFacultyDetails($fid)
    {
        try {
            $user = Authentication::OperateAuth();
            $this->render('/waste_center/reports/details/faculty', [
                'pages' => "report all faculties",
                'title' => "รายงานรายละเอียดคณะ",
                'fid' => !empty($fid) ? (int) $fid : null,
                'user' => $user["user_data"]
            ], self::$ReportLayout);
        } catch (AuthenticationException $th) {
            header('location: /');
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function ReportAllBranches()
    {
        try {
            $user = Authentication::AdminAuth();
            $this->render('/waste_center/reports/branches', [
                'pages' => "reportAllBranches",
                'title' => "รายงานรายชื่อหน่วยบริการ",
                'user' => $user["user_data"]
            ], self::$ReportLayout);
        } catch (AuthenticationException $th) {
            header('location: /');
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function ReportFacultiesAndBranches()
    {
        try {
            $user = Authentication::AdminAuth();
            $this->render('/waste_center/reports/faculties_and_branches', [
                'pages' => "reportAllFacultiesAndBranches",
                'title' => "รายงานรายชื่อคณะและหน่วยบริการ",
                'user' => $user["user_data"]
            ], self::$ReportLayout);
        } catch (AuthenticationException $th) {
            header('location: /');
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function ReportFacultyStock($fid)
    {
        try {
            $user = Authentication::OperateAuth();
            $this->render('/waste_center/reports/faculty_stock', [
                'pages' => "reportStock",
                'title' => "รายงานคลังขยะ",
                'user' => $user["user_data"],
                'faculty_id' => !empty($fid) ? (int) $fid : null
            ], self::$ReportLayout);
        } catch (AuthenticationException $th) {
            header('location: /');
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function ReportMajors()
    {
        try {
            $user = Authentication::OperateAuth();
            $this->render('/waste_center/reports/majors', [
                'pages' => "reportMajors",
                'title' => "รายงานรายชื่อสาขาในคณะ",
                'user' => $user["user_data"],
                'faculty_id' => !empty($fid) ? (int) $fid : null
            ], self::$ReportLayout);
        } catch (AuthenticationException $th) {
            header('location: /');
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function ReportRewardCategories()
    {
        try {
            $user = Authentication::OperateAuth();
            $this->render('/waste_center/reports/reward_categories', [
                'pages' => "reportRewardCategories",
                'title' => "รายงานหมวดหมู่ของรางวัล",
                'user' => $user["user_data"]
            ], self::$ReportLayout);
        } catch (AuthenticationException $th) {
            header('location: /');
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function ReportWasteCategories()
    {
        try {
            $user = Authentication::OperateAuth();
            $this->render('/waste_center/reports/waste_categories', [
                'pages' => "reportWasteCategories",
                'title' => "รายงานหมวดหมู่ขยะ",
                'user' => $user["user_data"],
            ], self::$ReportLayout);
        } catch (AuthenticationException $th) {
            header('location: /');
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }
    
}