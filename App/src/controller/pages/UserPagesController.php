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
            $user = Authentication::MemberAuth(); // Uncomment when authentication is ready
            $this->render('user/userDashboard', [
                'pages' => 'userDashboard',
                'title' => 'แดชบอร์ดผู้ใช้',
                'user' => $user['user_data'],
                // 'script' => '../../js/UserDashboard.js
                // 'script' => '../../js/UserDashboard.js',
                'activeTab' => 'dashboard',
                'footer' => 'user'
            ], self::$UserTemplate);
        } catch (AuthenticationException $th) {
            $this->errorPage(403, '403');
            header('location: /');
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }
    public function Profile()
    {
        try {
            $user = Authentication::MemberAuth(); // Uncomment when authentication is ready
            $this->render('user/profile', [
                'pages' => 'userDashboard',
                'title' => 'แดชบอร์ดผู้ใช้',
                'user' => $user['user_data'],
                // 'script' => '../../js/UserDashboard.js',
                'activeTab' => 'dashboard',
                'footer' => 'user'
            ], self::$UserTemplate);
        } catch (AuthenticationException $th) {
            $this->errorPage(403, '403');
            header('location: /');
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function Shop()
    {
        $this->render('user/shop', [
            'pages' => 'userShop',
            'title' => 'ร้านค้า',
            // 'script' => '../../js/UserDashboard.js',
            'activeTab' => 'shop',
            'footer' => 'user'
        ], self::$UserTemplate);
    }

    public function Equipment()
    {
        $this->render('user/equipment', [
            'pages' => 'userEquipment',
            'title' => 'อุปกรณ์',
            // 'script' => '../../js/UserDashboard.js',
            'activeTab' => 'equipment',
            'footer' => 'user'
        ], self::$UserTemplate);
    }

    public function Collection()
    {
        $this->render('user/collection', [
            'pages' => 'userCollection',
            'title' => 'ของสะสม',
            // 'script' => '../../js/UserDashboard.js',
            'activeTab' => 'collection',
            'footer' => 'user'
        ], self::$UserTemplate);
    }

    public function Quests()
    {
        $this->render('user/quests', [
            'pages' => 'userQuests',
            'title' => 'ภารกิจ',
            // 'script' => '../../js/UserDashboard.js',
            'activeTab' => 'quests',
            'footer' => 'user'
        ], self::$UserTemplate);
    }

    public function Barcode()
    {
        $user = Authentication::MemberAuth(); // Uncomment when authentication is ready

        $this->render('user/barcode', [
            'pages' => 'userBarcode',
            'title' => 'บาร์โค้ด',
            'user' => $user['user_data'],
            'activeTab' => 'barcode',
            'footer' => 'user'
        ], self::$UserTemplate);
    }

}
