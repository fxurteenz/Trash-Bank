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
                'title' => 'BRU Go Green : ธนาคารขยะ เข้าสู่ระบบ'
            ], 'landing');
        } else {
            $decodedToken = Authentication::CookieAuth();
            $roleId = (int) $decodedToken->role_id;
            if ($roleId == 1 || $roleId == 2 || $roleId == 3) {
                $this->redirect('/user');
            } else if ($roleId == 4) {
                $this->redirect('/staff');
            } else if ($roleId == 5) {
                $this->redirect('/waste_center');
            } else if ($roleId == 6) {
                $this->redirect('/admin');
            } else {
                $this->redirect('/login');
            }
        }
    }

    public function HomePage()
    {
        $this->render('home', [
            'title' => 'BRU Go Green : ธนาคารขยะ'
        ], "landing");
    }
    public function Leaderboard()
    {
        $this->render('leaderboard', [
            'title' => 'BRU Go Green : ธนาคารขยะ'
        ], "landing");
    }
    public function RegisterPage()
    {
        $this->render('register', [
            'title' => 'BRU Go Green : ธนาคารขยะ สมัครสมาชิก'
        ], "landing");
    }
}