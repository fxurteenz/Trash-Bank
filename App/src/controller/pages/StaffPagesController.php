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
            $this->render('staff/home', [
                'user' => $user,
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
            $this->render('staff/transactions/waste', [
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
    public function ClearWasteTransactionPage()
    {
        try {
            $user = Authentication::OperateAuth();
            $this->render('staff/transactions/clear_waste', [
                'user' => $user['user_data'],
                'pages' => 'clearWasteTransaction',
                'title' => 'ระบบเคลียร์ยอดฝากขยะ',
            ], self::$Layouts);
        } catch (AuthenticationException $th) {
            // $this->errorPage(403, '403');
            header('location: /');
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function ManageTransactionClearancePage($wcid)
    {
        try {
            $user = Authentication::OperateAuth();
            $this->render('staff/transactions/manage/clear_waste', [
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

}