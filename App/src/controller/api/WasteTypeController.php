<?php
namespace App\Controller\Api;

use App\Model\WasteTypeModel;
use App\Router\RouterBase;
use App\Utils\Authentication;
use App\Utils\AuthenticationException;
use Exception;

class WasteTypeController extends RouterBase
{
    private static $Data, $WasteTypeModel, $QueryString;

    public function __construct()
    {
        $input = file_get_contents('php://input');
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $contentType = strtolower($_SERVER['CONTENT_TYPE'] ?? '');

        if ($requestMethod == "GET") {
            self::$QueryString = $_GET;
        }

        switch (true) {
            case str_contains($contentType, 'application/json'):
                self::$Data = json_decode($input, true);
                break;
            case str_contains($contentType, 'application/x-www-form-urlencoded'):
                parse_str($input, self::$Data);
                break;
            case str_contains($contentType, 'multipart/form-data'):
                if ($_FILES) {
                    self::$Data = array_merge($_POST, $_FILES);
                } else {
                    self::$Data = $_POST;
                }
                break;
            default:
                self::$Data = [];
        }
        self::$WasteTypeModel = new WasteTypeModel();
    }

    public function GetAll()
    {
        try {
            // Authentication::OperateAuth();
            $result = self::$WasteTypeModel->GetAllWasteType(self::$QueryString);

            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode([
                'success' => TRUE,
                'data' => $result["data"],
                'total' => $result["total"],
                'message' => 'successfully =)'
            ]);
        } catch (AuthenticationException $e) {
            // error_log("ERROR AUTH : " . $e->getMessage());
            header('Content-Type: application/json');
            http_response_code($e->getCode() ?: 403);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        } catch (Exception $e) {
            // error_log("ERROR EXCEPTION: " . $e->getMessage());
            header('Content-Type: application/json');
            http_response_code($e->getCode() ?: 400);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        } finally {
            exit;
        }
    }

    public function GetByCategoryId($cid)
    {
        try {
            // Authentication::OperateAuth();
            $result = self::$WasteTypeModel->GetWasteTypeByCategory(self::$QueryString, $cid);

            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode([
                'success' => TRUE,
                'data' => $result["data"],
                'total' => $result["total"],
                'message' => 'successfully =)'
            ]);
        } catch (AuthenticationException $e) {
            // error_log("ERROR AUTH : " . $e->getMessage());
            header('Content-Type: application/json');
            http_response_code($e->getCode() ?: 403);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        } catch (Exception $e) {
            // error_log("ERROR EXCEPTION: " . $e->getMessage());
            header('Content-Type: application/json');
            http_response_code($e->getCode() ?: 400);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        } finally {
            exit;
        }
    }

    public function Create()
    {
        try {
            // error_log("DATA RECEIVED: " . print_r(self::$Data, true));
            Authentication::AdminAuth();
            $result = self::$WasteTypeModel->CreateWasteType(self::$Data);

            header('Content-Type: application/json');
            http_response_code(201);
            echo json_encode([
                'success' => TRUE,
                'result' => $result,
                'message' => 'สำเร็จ, เพิ่มข้อมูลแล้ว!'
            ]);
        } catch (AuthenticationException $e) {
            // error_log("ERROR AUTH : " . $e->getMessage() . $e->getCode());
            header('Content-Type: application/json');
            http_response_code($e->getCode());
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        } catch (Exception $e) {
            // error_log("ERROR EXCEPTION : " . $e->getMessage() . $e->getCode());
            header('Content-Type: application/json');
            http_response_code($e->getCode());
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        } finally {
            exit;
        }
    }

    public function Update($wtid)
    {
        try {
            Authentication::OperateAuth();

            $result = self::$WasteTypeModel->UpdateWasteType($wtid, self::$Data);

            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode([
                'success' => TRUE,
                'data' => $result,
                'message' => $result["total"] > 0 ? "สำเร็จ, แก้ไขข้อมูลแล้ว" : "ไม่พบข้อมูลที่ต้องการแก้ไข"
            ]);
        } catch (AuthenticationException $e) {
            header('Content-Type: application/json');
            http_response_code($e->getCode() ?: 401);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        } catch (Exception $e) {
            header('Content-Type: application/json');
            http_response_code($e->getCode() ?: 400);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        } finally {
            exit;
        }
    }

    public function ToggleActive()
    {
        try {
            Authentication::AdminAuth();

            $result = self::$WasteTypeModel->ToggleActiveWasteType(self::$Data);

            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode([
                'success' => TRUE,
                'data' => $result["data"],
                'message' => "สำเร็จ, แก้ไขข้อมูลแล้ว {$result["total"]} รายการ"
            ]);
        } catch (AuthenticationException $e) {
            header('Content-Type: application/json');
            http_response_code($e->getCode() ?: 401);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        } catch (Exception $e) {
            header('Content-Type: application/json');
            http_response_code($e->getCode() ?: 400);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        } finally {
            exit;
        }
    }

    public function DeleteById($id)
    {
        try {
            Authentication::OperateAuth();

            $result = self::$WasteTypeModel->DeleteWasteType($id);

            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode([
                'success' => TRUE,
                'result' => $result,
                'message' => 'waste type deleted successfully =)'
            ]);
        } catch (AuthenticationException $e) {
            header('Content-Type: application/json');
            http_response_code($e->getCode() ?: 401);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        } catch (Exception $e) {
            header('Content-Type: application/json');
            http_response_code($e->getCode() ?: 400);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        } finally {
            exit;
        }
    }

    public function Delete()
    {
        try {
            Authentication::AdminAuth();

            $result = self::$WasteTypeModel->DeleteWasteType(self::$Data);

            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode([
                'success' => TRUE,
                'data' => $result["data"],
                'message' => "ลบข้อมูลแล้ว {$result['total']} รายการ"
            ]);
        } catch (AuthenticationException $e) {
            header('Content-Type: application/json');
            http_response_code($e->getCode() ?: 401);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        } catch (Exception $e) {
            header('Content-Type: application/json');
            http_response_code($e->getCode() ?: 400);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        } finally {
            exit;
        }
    }
}