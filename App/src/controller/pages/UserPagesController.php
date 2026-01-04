<?php
namespace App\Controller\Pages;

use Exception;
use App\Router\RouterBase;
use App\Utils\Authentication;
use App\Utils\AuthenticationException;

class UserPagesController extends RouterBase
{
    private static $UserTemplate = "main";

    public function Dashboard()
    {
        try {
            // Authentication::UserAuth(); // Uncomment when authentication is ready
            $this->render('user/userDashboard', [
                'pages' => 'userDashboard',
                'title' => 'แดชบอร์ดผู้ใช้',
                'script' => '../../js/UserDashboard.js',
                'activeTab' => 'dashboard',
                'footer' => 'user'
            ], self::$UserTemplate);
        } catch (AuthenticationException $th) {
            $this->errorPage(403, '403');
            header('location: /');
            exit;
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function Shop()
    {
        $this->render('user/shop', [
            'pages' => 'userShop',
            'title' => 'ร้านค้า',
            'script' => '../../js/UserDashboard.js',
            'activeTab' => 'shop',
            'footer' => 'user'
        ], self::$UserTemplate);
    }

    public function Equipment()
    {
        $this->render('user/equipment', [
            'pages' => 'userEquipment',
            'title' => 'อุปกรณ์',
            'script' => '../../js/UserDashboard.js',
            'activeTab' => 'equipment',
            'footer' => 'user'
        ], self::$UserTemplate);
    }

    public function Collection()
    {
        $this->render('user/collection', [
            'pages' => 'userCollection',
            'title' => 'ของสะสม',
            'script' => '../../js/UserDashboard.js',
            'activeTab' => 'collection',
            'footer' => 'user'
        ], self::$UserTemplate);
    }

    public function Quests()
    {
        $this->render('user/quests', [
            'pages' => 'userQuests',
            'title' => 'ภารกิจ',
            'script' => '../../js/UserDashboard.js',
            'activeTab' => 'quests',
            'footer' => 'user'
        ], self::$UserTemplate);
    }
}
