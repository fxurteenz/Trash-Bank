<?php
namespace App\Controller\Pages;

use Exception;
use App\Router\RouterBase;
use App\Utils\Authentication;
use App\Utils\AuthenticationException;

class OperaterPagesController extends RouterBase
{
    private static $Layouts = "operaterLayout";
    public function HomePage()
    {
        try {
            Authentication::OperateAuth();
            $this->render('operater/home', [
                'pages' => 'home',
                'title' => 'หน้าหลัก',
            ], self::$Layouts);
        } catch (AuthenticationException $th) {
            // $this->errorPage(403, '403');
            header('location: /');
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }
    public function RedeemPage()
    {
        try {
            Authentication::OperateAuth();
            $this->render('operater/redeem', [
                'pages' => 'redeem',
                'title' => 'แลกของรางวัล',
            ], self::$Layouts);
        } catch (AuthenticationException $th) {
            // $this->errorPage(403, '403');
            header('location: /');
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

}