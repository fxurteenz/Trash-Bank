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
            Authentication::OperateAuth();
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
            Authentication::AdminAuth();
            $this->render('transactions/waste', [
                'pages' => "wasteTransaction",
                'title' => 'ระบบฝากขยะ'
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

    public function ClearTransactionWaste()
    {
        try {
            Authentication::OperateAuth();
            $this->render('transactions/clear_waste', [
                'pages' => 'clearWasteTransaction',
                'title' => 'เคลียร์ยอดฝากขยะ'
            ], self::$Layouts);
        } catch (AuthenticationException $th) {
            header('location: /');
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function ManageWasteType()
    {
        try {
            Authentication::OperateAuth();
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
            Authentication::AdminAuth();
            $this->render('manages/waste_transaction', [
                'pages' => "manageWasteTransaction",
                'title' => 'ประวัติการดำเนินการ'
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
}
