<?php
namespace App\Controller\Api;

use App\Model\WasteCategoryModel;
use App\Router\RouterBase;
use App\Utils\Authentication;
use App\Utils\AuthenticationException;
use App\Utils\Database;
use Exception;

class WasteCategoryController extends RouterBase
{
    private static $Data, $WasteCategoryModel, $Database, $QueryString;

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

        self::$Database = new Database();
        self::$WasteCategoryModel = new WasteCategoryModel();
    }

    public function GetAll()
    {
        try {
            Authentication::OperateAuth();
            $wasteTypes = self::$WasteCategoryModel->GetAllWasteCategory(self::$QueryString);

            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode([
                'success' => TRUE,
                'result' => $wasteTypes,
                'message' => 'successfully =)'
            ]);
        } catch (AuthenticationException $e) {
            error_log("ERROR AUTH : " . $e->getMessage());
            http_response_code($e->getCode() ?: 403);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        } catch (Exception $e) {
            error_log("ERROR EXCEPTION: " . $e->getMessage());
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
            error_log("DATA RECEIVED: " . print_r(self::$Data, true));
            Authentication::OperateAuth();
            $result = self::$WasteCategoryModel->CreateWasteCategory(self::$Data);

            header('Content-Type: application/json');
            http_response_code(201);
            echo json_encode([
                'success' => TRUE,
                'result' => $result,
                'message' => 'waste type created successfully =)'
            ]);
        } catch (AuthenticationException $e) {
            // error_log("ERROR AUTH : " . $e->getMessage() . $e->getCode());
            http_response_code($e->getCode());
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        } catch (Exception $e) {
            // error_log("ERROR EXCEPTION : " . $e->getMessage() . $e->getCode());
            http_response_code($e->getCode());
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        } finally {
            exit;
        }
    }

    public function Update($id)
    {
        try {
            Authentication::OperateAuth();

            // เรียกฟังก์ชัน UpdateWasteCategory โดยส่ง $id และ Data
            $result = self::$WasteCategoryModel->UpdateWasteCategory($id, self::$Data);

            header('Content-Type: application/json');
            http_response_code(200); // 200 OK สำหรับการแก้ไขสำเร็จ
            echo json_encode([
                'success' => TRUE,
                'result' => $result,
                'message' => 'waste type updated successfully =)'
            ]);
        } catch (AuthenticationException $e) {
            http_response_code($e->getCode() ?: 401);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        } catch (Exception $e) {
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

            $result = self::$WasteCategoryModel->DeleteWasteCategory($id);

            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode([
                'success' => TRUE,
                'result' => $result,
                'message' => 'waste type deleted successfully =)'
            ]);
        } catch (AuthenticationException $e) {
            http_response_code($e->getCode() ?: 401);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        } catch (Exception $e) {
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
                'result' => $result,
                'message' => "Successfully deleted $result items."
            ]);
        } catch (AuthenticationException $e) {
            http_response_code($e->getCode() ?: 401);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        } catch (Exception $e) {
            http_response_code($e->getCode() ?: 400);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        } finally {
            exit;
        }
    }
}