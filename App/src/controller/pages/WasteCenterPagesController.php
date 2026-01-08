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
}
