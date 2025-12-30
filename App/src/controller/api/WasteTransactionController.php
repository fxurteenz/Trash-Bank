<?php
namespace App\Controller\Api;

use App\Model\WasteTransactionModel;
use App\Router\RouterBase;
use App\Utils\Authentication;
use App\Utils\AuthenticationException;
use Exception;

class WasteTransactionController extends RouterBase
{
    private static $Data, $WasteTransactionModel, $QueryString;

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

        self::$WasteTransactionModel = new WasteTransactionModel();
    }

    public function GetAll()
    {
        try {
            Authentication::OperateAuth();
            $deposits = self::$WasteTransactionModel->GetAllTransaction(self::$QueryString);

            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode([
                'success' => TRUE,
                'result' => $deposits,
                'message' => 'successfully =)'
            ]);
        } catch (AuthenticationException $e) {
            error_log("ERROR AUTH : " . $e->getMessage());
            header('Content-Type: application/json');
            http_response_code($e->getCode() ?: 403);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        } catch (Exception $e) {
            error_log("ERROR EXCEPTION: " . $e->getMessage());
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

    public function GetAllByOperater()
    {
        try {
            $user = Authentication::OperateAuth();
            error_log("USER DATA :" . print_r($user, 1));
            $deposits = self::$WasteTransactionModel->GetAllTransactionByStaffId(self::$QueryString, $user);

            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode([
                'success' => TRUE,
                'result' => $deposits,
                'message' => 'successfully =)'
            ]);
        } catch (AuthenticationException $e) {
            error_log("ERROR AUTH : " . $e->getMessage());
            header('Content-Type: application/json');
            http_response_code($e->getCode() ?: 403);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        } catch (Exception $e) {
            error_log("ERROR EXCEPTION: " . $e->getMessage());
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
            $operaterData = Authentication::OperateAuth();
            $result = self::$WasteTransactionModel->CreateWasteTransaction(self::$Data, $operaterData);

            header('Content-Type: application/json');
            http_response_code(201);
            echo json_encode([
                'success' => TRUE,
                'result' => $result,
                'message' => 'trasaction completed successfully =)'
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

    // public function Update($id)
    // {
    //     try {
    //         Authentication::OperateAuth();

    //         $result = self::$WasteTransactionModel->UpdateWasteTransaction($id, self::$Data);

    //         header('Content-Type: application/json');
    //         http_response_code(200);
    //         echo json_encode([
    //             'success' => TRUE,
    //             'result' => $result,
    //             'message' => 'deposit updated successfully =)'
    //         ]);
    //     } catch (AuthenticationException $e) {
    //         header('Content-Type: application/json');
    //         http_response_code($e->getCode() ?: 401);
    //         echo json_encode([
    //             'success' => false,
    //             'message' => $e->getMessage()
    //         ]);
    //     } catch (Exception $e) {
    //         header('Content-Type: application/json');
    //         http_response_code($e->getCode() ?: 400);
    //         echo json_encode([
    //             'success' => false,
    //             'message' => $e->getMessage()
    //         ]);
    //     } finally {
    //         exit;
    //     }
    // }

    public function DeleteById($id)
    {
        try {
            Authentication::OperateAuth();
            $result = self::$WasteTransactionModel->DeleteWasteTransactionById($id);

            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode([
                'success' => TRUE,
                'data' => $result,
                'message' => 'transaction deleted successfully =)'
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
            $result = self::$WasteTransactionModel->DeleteWasteTransaction(self::$Data);

            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode([
                'success' => TRUE,
                'result' => $result,
                'message' => "Successfully deleted $result items."
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