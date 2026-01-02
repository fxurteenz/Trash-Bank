<?php
namespace App\Controller\Api;

use App\Model\WasteCategoryModel;
use App\Router\RouterBase;
use App\Utils\Authentication;
use App\Utils\AuthenticationException;
use Exception;

class WasteCategoryController extends RouterBase
{
    private static $Data, $WasteCategoryModel, $QueryString;

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
        self::$WasteCategoryModel = new WasteCategoryModel();
    }

    public function GetAll()
    {
        try {
            // Authentication::OperateAuth();
            $result = self::$WasteCategoryModel->GetAllWasteCategories(self::$QueryString);

            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode([
                'success' => TRUE,
                'data' => $result['data'],
                'total' => $result['total'],
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
            Authentication::AdminAuth();
            $result = self::$WasteCategoryModel->CreateWasteCategory(self::$Data);

            header('Content-Type: application/json');
            http_response_code(201);
            echo json_encode([
                'success' => TRUE,
                'message' => 'สำเร็จ, เพิ่มข้อมูลแล้ว',
                'data' => $result
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
        }
    }

    public function Update($id)
    {
        try {
            Authentication::AdminAuth();
            $result = self::$WasteCategoryModel->UpdateWasteCategory($id, self::$Data);

            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode([
                'success' => TRUE,
                'message' => $result["total"] > 0 ? 'สำเร็จ, แก้ไขข้อมูลแล้ว' : 'เตือน, ไม่มีข้อมูลที่เปลี่ยนแปลง หรือไม่พบข้อมูลที่ต้องการแก้ไข',
                'data' => $result["data"],
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
            $result = self::$WasteCategoryModel->ToggleActiveWasteCategory(self::$Data);

            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode([
                'success' => TRUE,
                'message' => $result["total"] > 0 ? 'สำเร็จ, แก้ไขสถานะแล้ว' : 'เตือน, ไม่มีข้อมูลที่เปลี่ยนแปลง หรือไม่พบข้อมูลที่ต้องการแก้ไข',
                'data' => $result["data"],
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
            $result = self::$WasteCategoryModel->DeleteWasteCategory(self::$Data);

            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode([
                'success' => TRUE,
                'message' => $result["total"] > 0 ? "สำเร็จ, ลบข้อมูลแล้ว {$result['total']} รายการ" : 'เตือน, ไม่มีข้อมูลที่เปลี่ยนแปลง หรือไม่พบข้อมูลที่ต้องการลบ',
                'data' => $result['data'],
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