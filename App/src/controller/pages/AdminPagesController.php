<?php
namespace App\Controller\Pages;

use Exception;
use App\Router\RouterBase;
use App\Utils\Authentication;
use App\Utils\AuthenticationException;

class AdminPagesController extends RouterBase
{
    private static $AdminTemplate = "adminLayout";

    public function DashBoard()
    {
        try {
            Authentication::AdminAuth();
            $this->render('admin/adminDashboard', [
                'pages' => 'dashboard',
                'title' => 'ผู้ดูแลระบบ',
                'module' => '../../js/Dashboard.mjs'
            ], self::$AdminTemplate);
        } catch (AuthenticationException $th) {
            $this->errorPage(403, '403');
            header('location: /');
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        } finally {
            exit;
        }
    }

    public function ManageUsers()
    {
        try {
            Authentication::AdminAuth();
            $this->render('admin/manages/users', [
                'pages' => "manageUsers",
                'title' => 'จัดการผู้ใช้งาน',
                'script' => '../../js/ManageUsers.js'
            ], self::$AdminTemplate);
        } catch (AuthenticationException $th) {
            $this->errorPage(403, '403');
            header('location: /');
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        } finally {
            exit;
        }
    }

    public function ManageFaculty()
    {
        try {
            Authentication::AdminAuth();
            $this->render('admin/manages/faculty', [
                'pages' => "manageFaculty",
                'title' => 'จัดการคณะ/สาขา',
                'script' => '../../js/ManageFaculty.js'
            ], self::$AdminTemplate);
        } catch (AuthenticationException $th) {
            // $this->errorPage(403, '403');
            header('location: /');
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        } finally {
            exit;
        }
    }

    public function ManageWasteType()
    {
        try {
            Authentication::AdminAuth();
            $this->render('admin/manages/waste_type', [
                'pages' => "manageWasteType",
                'title' => 'จัดการหมวดหมู่ขยะ'
            ], self::$AdminTemplate);
        } catch (AuthenticationException $th) {
            // $this->errorPage(403, '403');
            header('location: /');
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        } finally {
            exit;
        }
    }

    public function ManageWasteTransaction()
    {
        try {
            Authentication::AdminAuth();
            $this->render('admin/manages/waste_transaction', [
                'pages' => "manageWasteTransaction",
                'title' => 'ประวัติการดำเนินการ'
            ], self::$AdminTemplate);
        } catch (AuthenticationException $th) {
            // $this->errorPage(403, '403');
            header('location: /');
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        } finally {
            exit;
        }
    }
}