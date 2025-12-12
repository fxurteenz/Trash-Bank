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
            
            $this->render('operater/home', [
                'title' => 'หน้าหลัก',
            ], self::$Layouts);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }

    }

}