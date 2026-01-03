<?php
namespace App\Controller\Pages;

use Exception;
use App\Router\RouterBase;
use App\Utils\Authentication;
use App\Utils\AuthenticationException;

class CenterPagesController extends RouterBase
{
    private static $Layouts = "operaterLayout";

    public function HomePage()
    {
        try {
            Authentication::OperateAuth();
            $this->render('center/home', [
                'pages' => 'center_home',
                'title' => 'ศูนย์กลาง'
            ], self::$Layouts);
        } catch (AuthenticationException $th) {
            header('location: /');
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }
}
