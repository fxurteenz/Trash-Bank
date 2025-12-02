<?php
namespace App\Controller\Pages;

use Exception;
use App\Model\UserModel;
use App\Router\RouterBase;
use App\Utils\Authentication;
use App\Utils\AuthenticationException;

class AdminPagesController extends RouterBase
{
    private static $AdminTemplate = "adminDashboard";

    public function DashBoard()
    {
        $this->render('admin/adminDashboard', [
            'pages' => 'dashboard',
            'title' => 'ผู้ดูแลระบบ',
            'script' => '../js/Dashboard.js'
        ], self::$AdminTemplate);
    }

    public function ManageUsers()
    {
        try {
            Authentication::AdminAuth();
            $model = new UserModel();
            $AllUsersData = $model->GetAllUsers();

            $this->render('admin/manages/users', [
                'pages' => "manageUsers",
                'title' => 'จัดการผู้ใช้งาน',
                'AllUsersData' => $AllUsersData,
            ], self::$AdminTemplate);
        } catch (AuthenticationException $th) {
            $this->errorPage(403, '403');
            header('location: /');
            exit;
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }
}