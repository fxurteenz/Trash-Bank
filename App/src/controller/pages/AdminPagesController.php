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
            $this->render('manages/users', [
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
            $this->render('manages/faculty', [
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
            $this->render('manages/waste_type', [
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
            $this->render('manages/waste_transaction', [
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

    public function TransactionWaste()
    {
        try {
            Authentication::AdminAuth();
            $this->render('transactions/waste', [
                'pages' => "wasteTransaction",
                'title' => 'ระบบฝากขยะ'
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

    public function TransactionClearance()
    {
        try {
            Authentication::AdminAuth();
            $this->render('transactions/clear_waste', [
                'pages' => "clearWasteTransaction",
                'title' => 'ระบบเคลียร์ยอดฝากขยะ'
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
    public function ManageTransactionClearance($wcid)
    {
        try {
            Authentication::AdminAuth();
            $this->render('transactions/manage/clear_waste', [
                'pages' => "clearWasteTransaction",
                'title' => 'ระบบเคลียร์ยอดฝากขยะ',
                'wcid' => $wcid
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