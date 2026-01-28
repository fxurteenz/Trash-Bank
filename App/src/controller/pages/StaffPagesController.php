<?php
namespace App\Controller\Pages;

use Exception;
use App\Router\RouterBase;
use App\Utils\Authentication;
use App\Utils\AuthenticationException;

class StaffPagesController extends RouterBase
{
    private static $Layouts = "staffLayout";

    public function HomePage()
    {
        try {
            $user = Authentication::OperateAuth();
            $this->render('staff/dashboard', [
                'user' => $user,
                'facultyId' => $user['user_data']->faculty_id,
                'facultyName' => $user['user_data']->faculty_name,
                'pages' => 'home',
                'title' => 'หน้าหลัก'
            ], self::$Layouts);
        } catch (AuthenticationException $th) {
            // $this->errorPage(403, '403');
            header('location: /');
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function WasteTransactionPage()
    {
        try {
            Authentication::OperateAuth();
            $this->render('transactions/waste_deposit', [
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

    public function WasteTransactionHistoryPage()
    {
        try {
            Authentication::OperateAuth();
            $this->render('history/waste_transaction', [
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

    public function ManageMemberPage()
    {
        try {
            $user = Authentication::OperateAuth();
            $this->render('manages/users', [
                'user' => $user["user_data"],
                'pages' => 'memberManagement',
                'title' => 'จัดการสมาชิก',
                // 'script' => '../../js/ManageUsers.js',
            ], self::$Layouts);
        } catch (AuthenticationException $th) {
            // $this->errorPage(403, '403');
            header('location: /');
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function ManageMemberDetailPage($member_id)
    {
        try {
            $user = Authentication::OperateAuth();
            $this->render('manages/details/user_detail', [
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
}