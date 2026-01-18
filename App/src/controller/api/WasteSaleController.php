<?php
namespace App\Controller\Api;

use App\Model\WasteSaleModel;
use App\Router\RouterBase;
use App\Utils\Authentication;
use App\Utils\AuthenticationException;
use Exception;

class WasteSaleController extends RouterBase
{
    private static $Data, $WasteSaleModel, $QueryString;

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

        self::$WasteSaleModel = new WasteSaleModel();
    }

    public function GetAll()
    {
        try {
            header('Content-Type: application/json');
            $result = self::$WasteSaleModel->GetAll(self::$QueryString);
            http_response_code(200);
            echo json_encode([
                'success' => TRUE,
                'result' => $result,
                'message' => 'Successfully'
            ]);
        } catch (AuthenticationException $e) {
            error_log("ERROR AUTH : " . $e->getMessage());
            http_response_code($e->getCode() ?: 403);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        } catch (Exception $e) {
            http_response_code($e->getCode() ?: 500);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        } finally {
            exit;
        }
    }
    public function GetDetail($waste_sale_id)
    {
        try {
            header('Content-Type: application/json');
            $result = self::$WasteSaleModel->getWasteSaleDetail($waste_sale_id, self::$QueryString);
            http_response_code(200);
            echo json_encode([
                'success' => TRUE,
                'result' => $result,
                'message' => 'Successfully'
            ]);
        } catch (AuthenticationException $e) {
            error_log("ERROR AUTH : " . $e->getMessage());
            http_response_code($e->getCode() ?: 403);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        } catch (Exception $e) {
            http_response_code($e->getCode() ?: 500);
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
            header('Content-Type: application/json');
            $user = Authentication::OperateAuth();
            $memberId = $user['user_data']->member_id;

            $result = self::$WasteSaleModel->CreateWasteSale(self::$Data, $memberId);

            http_response_code(201);
            echo json_encode([
                'success' => TRUE,
                'result' => $result,
                'message' => 'Waste sale created successfully'
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
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        } finally {
            exit;
        }
    }

}
