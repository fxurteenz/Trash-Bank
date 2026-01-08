<?php
namespace App\Controller\Pages;

use Exception;
use App\Router\RouterBase;
use App\Utils\Authentication;
use App\Utils\AuthenticationException;

class OperaterPagesController extends RouterBase
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
            $this->render('transactions/waste', [
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
            Authentication::OperateAuth();
            $this->render('transactions/clear_waste', [
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

}