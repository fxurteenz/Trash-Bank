<?php
namespace App\Controller\Api;

use App\Router\RouterBase;
use App\Model\DonationModel;
use App\Utils\Authentication;
use App\Utils\AuthenticationException;
use Exception;

class DonationController extends RouterBase
{
    private $data;
    private $DonationModel;
    private $queryString;

    public function __construct()
    {
        $input = file_get_contents('php://input');
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $contentType = strtolower($_SERVER['CONTENT_TYPE'] ?? '');

        if ($requestMethod === 'GET') {
            $this->queryString = $_GET;
        }

        switch (true) {
            case str_contains($contentType, 'application/json'):
                $this->data = json_decode($input, true);
                break;
            case str_contains($contentType, 'application/x-www-form-urlencoded'):
                parse_str($input, $this->data);
                break;
            case str_contains($contentType, 'multipart/form-data'):
                if ($_FILES) {
                    $this->data = array_merge($_POST, $_FILES);
                } else {
                    $this->data = $_POST;
                }
                break;
            default:
                $this->data = [];
        }

        $this->DonationModel = new DonationModel();
    }

    public function GetAll()
    {
        try {
            Authentication::CenterAuth();
            $rows = $this->DonationModel->GetAll($this->queryString ?? []);

            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode([
                'success' => true,
                'data' => $rows['data'],
                'total' => $rows['total'],
                'message' => 'ok'
            ]);
        } catch (AuthenticationException $e) {
            http_response_code($e->getCode() ?: 401);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        } catch (Exception $e) {
            http_response_code($e->getCode() ?: 400);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function Get($id)
    {
        try {
            Authentication::CenterAuth();
            $row = $this->DonationModel->GetById((int) $id);
            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode(['success' => true, 'data' => $row, 'message' => 'ok']);
        } catch (AuthenticationException $e) {
            http_response_code($e->getCode() ?: 401);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        } catch (Exception $e) {
            http_response_code($e->getCode() ?: 400);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function Create()
    {
        try {
            $user = Authentication::CenterAuth();
            $row = $this->DonationModel->CreateDonation(is_array($this->data) ? $this->data : [], $user);

            header('Content-Type: application/json');
            http_response_code(201);
            echo json_encode(['success' => true, 'data' => $row, 'message' => 'Donation created =]']);
        } catch (AuthenticationException $e) {
            http_response_code($e->getCode() ?: 401);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        } catch (Exception $e) {
            http_response_code($e->getCode() ?: 400);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function CreateItem()
    {
        try {
            Authentication::CenterAuth();
            $row = $this->DonationModel->CreateDonationItems(is_array($this->data) ? $this->data : []);

            header('Content-Type: application/json');
            http_response_code(201);
            echo json_encode(['success' => true, 'data' => $row, 'message' => 'Donation created =]']);
        } catch (AuthenticationException $e) {
            http_response_code($e->getCode() ?: 401);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        } catch (Exception $e) {
            http_response_code($e->getCode() ?: 400);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    public function GetItems()
    {
        try {
            Authentication::OperateAuth();
            $result = $this->DonationModel->GetAllDonationItem($this->queryString ?? []);

            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode(['success' => true, 'data' => $result["data"], 'total' => $result["total"], 'message' => 'ok']);
        } catch (AuthenticationException $e) {
            http_response_code($e->getCode() ?: 401);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        } catch (Exception $e) {
            http_response_code($e->getCode() ?: 400);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    public function GetAvailableItems()
    {
        try {
            // Authentication::OperateAuth();
            $result = $this->DonationModel->GetAvailableDonationItem($this->queryString ?? []);

            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode(['success' => true, 'data' => $result["data"], 'total' => $result["total"], 'message' => 'ok']);
        } catch (AuthenticationException $e) {
            http_response_code($e->getCode() ?: 401);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        } catch (Exception $e) {
            http_response_code($e->getCode() ?: 400);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    public function GetCategorisedItems()
    {
        try {
            Authentication::OperateAuth();
            $result = $this->DonationModel->GetCategorisedDonationItem($this->queryString ?? []);

            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode(['success' => true, 'data' => $result["data"], 'total' => $result["total"], 'message' => 'ok']);
        } catch (AuthenticationException $e) {
            http_response_code($e->getCode() ?: 401);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        } catch (Exception $e) {
            http_response_code($e->getCode() ?: 400);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function GetUncategorisedItems()
    {
        try {
            Authentication::CenterAuth();
            $rows = $this->DonationModel->GetUncategorisedDonationItem($this->queryString ?? []);

            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode([
                'success' => true,
                'data' => $rows['data'],
                'total' => $rows['total'],
                'message' => 'ok'
            ]);
        } catch (AuthenticationException $e) {
            http_response_code($e->getCode() ?: 401);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        } catch (Exception $e) {
            http_response_code($e->getCode() ?: 400);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function UpdateItem($id)
    {
        try {
            Authentication::CenterAuth();
            $row = $this->DonationModel->UpdateDonationItem((int) $id, is_array($this->data) ? $this->data : []);
            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode(['success' => true, 'data' => $row, 'message' => 'ok']);
        } catch (AuthenticationException $e) {
            http_response_code($e->getCode() ?: 401);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        } catch (Exception $e) {
            http_response_code($e->getCode() ?: 400);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function Delete()
    {
        try {
            Authentication::AdminAuth();
            $result = $this->DonationModel->DeleteDonationItem(is_array($this->data) ? $this->data : []);
            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode(['success' => true, 'message' => 'ลบข้อมูลสำเร็จ', 'data' => $result]);
        } catch (AuthenticationException $e) {
            http_response_code($e->getCode() ?: 401);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        } catch (Exception $e) {
            http_response_code($e->getCode() ?: 400);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function GetItemCategories()
    {
        try {
            Authentication::OperateAuth();
            $result = $this->DonationModel->GetDonationItemCategories($this->queryString ?? []);

            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode([
                'success' => true,
                'data' => $result['data'],
                'total' => $result['total'],
                'message' => 'ok'
            ]);
        } catch (AuthenticationException $e) {
            http_response_code($e->getCode() ?: 401);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        } catch (Exception $e) {
            http_response_code($e->getCode() ?: 400);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function GetItemByCategories($cid)
    {
        try {
            Authentication::OperateAuth();
            $result = $this->DonationModel->GetDonationItemByCategoryId($cid, $this->queryString ?? []);

            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode([
                'success' => true,
                'data' => $result['data'],
                'total' => $result['total'],
                'message' => 'ok'
            ]);
        } catch (AuthenticationException $e) {
            http_response_code($e->getCode() ?: 401);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        } catch (Exception $e) {
            http_response_code($e->getCode() ?: 400);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function CreateItemCategory()
    {
        try {
            Authentication::CenterAuth();
            $row = $this->DonationModel->CreateDonationItemCategory(is_array($this->data) ? $this->data : []);

            header('Content-Type: application/json');
            http_response_code(201);
            echo json_encode(['success' => true, 'data' => $row, 'message' => 'Donation created =]']);
        } catch (AuthenticationException $e) {
            http_response_code($e->getCode() ?: 401);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        } catch (Exception $e) {
            http_response_code($e->getCode() ?: 400);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function UpdateItemCategory($id)
    {
        try {
            Authentication::CenterAuth();
            $row = $this->DonationModel->UpdateDonationItemCategory((int) $id, is_array($this->data) ? $this->data : []);
            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode(['success' => true, 'data' => $row, 'message' => 'ok']);
        } catch (AuthenticationException $e) {
            http_response_code($e->getCode() ?: 401);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        } catch (Exception $e) {
            http_response_code($e->getCode() ?: 400);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function BulkUpdateItemCategory()
    {
        try {
            Authentication::CenterAuth();
            $row = $this->DonationModel->BulkUpdateDonationItemCategory(is_array($this->data) ? $this->data : []);
            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode(['success' => true, 'data' => $row, 'message' => 'Bulk update successful']);
        } catch (AuthenticationException $e) {
            http_response_code($e->getCode() ?: 401);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        } catch (Exception $e) {
            http_response_code($e->getCode() ?: 400);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function ToggleItemAvailable()
    {
        try {
            Authentication::CenterAuth();
            $row = $this->DonationModel->ToggleDonationItemAvailable(is_array($this->data) ? $this->data : []);
            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode(['success' => true, 'data' => $row, 'message' => 'Toggle successful']);
        } catch (AuthenticationException $e) {
            http_response_code($e->getCode() ?: 401);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        } catch (Exception $e) {
            http_response_code($e->getCode() ?: 400);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
