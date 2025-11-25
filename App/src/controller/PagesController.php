<?php
namespace App\Controller;

use App\Router\RouterBase;

class PagesController extends RouterBase{
    public function LoginPage()
    {
        $this->render('login', [
            'title' => 'เข้าสู่ระบบ',
            'script' => '../js/Login.js'
        ]);
    }

}