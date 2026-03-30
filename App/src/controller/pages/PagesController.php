<?php
namespace App\Controller\Pages;

use App\Router\RouterBase;
use App\Utils\Authentication;

class PagesController extends RouterBase
{
    public function LoginPage()
    {
        if (empty($_COOKIE["user_token"])) {
            $this->render('login', [
                'title' => 'เข้าสู่ระบบ',
                'script' => '../js/Login.js'
            ]);
        } else {
            $decodedToken = Authentication::CookieAuth();
            $roleId = (int) $decodedToken->role_id;
            switch ($roleId) {
                case 1:
                    $this->redirect('/admin');
                    break;
                case 2:
                    $this->redirect('/user');
                    break;
                case 3:
                    $this->redirect('/staff');
                    break;
                case 4:
                    $this->redirect('/waste_center');
                    break;
                default:
                    $this->redirect('/');
                    break;
            }

        }

    }

    public function HomePage()
    {
        $this->render('home', [
            'title' => 'ธนาคารขยะ'
        ], "landing");
    }
    public function RegisterPage()
    {
        $this->render('register', [
            'title' => 'ธนาคารขยะ'
        ]);
    }
}