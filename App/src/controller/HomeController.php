<?php
namespace App\Controller;

use App\Router\RouterBase;

class HomeController extends RouterBase
{
    public function homePage()
    {
        $this->render('home', [
            'title' => 'หน้าหลัก'
        ]);
    }
}