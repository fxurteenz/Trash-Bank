<?php
namespace App\Controller\Api;

use App\Router\RouterBase;
use App\Model\UsersModel;
use Exception;

class UsersController extends RouterBase
{
    private $data, $UsersModel, $queryString;
    public function __construct()
    {
        $input = file_get_contents('php://input');
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $contentType = strtolower($_SERVER['CONTENT_TYPE'] ?? '');
        if ($requestMethod == "GET") {
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
                $this->data = $input;
        }
        $this->UsersModel = new UsersModel();
    }

    public function Login()
    {
        try {
            [$data, $cookieToken] = $this->UsersModel->UsersLogin(data: $this->data);

            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode([
                'success' => TRUE,
                'data' => [
                    "token" => $cookieToken,
                    "user_data" => $data
                ],
                'message' => 'login successfully =)'
            ]);
            return;
        } catch (Exception $e) {
            http_response_code($e->getCode() ?: 400);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => $this->data
            ]);
        }
    }

    public function Logout()
    {
        try {
            $result = $this->UsersModel->UsersLogout();
            if ($result['success']) {
                header('Location: /');
                exit;
            }
        } catch (Exception $e) {
            http_response_code($e->getCode() ?: 400);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => $this->data
            ]);
            exit;
        }
    }

    public function Register()
    {
        try {
            $result = $this->UsersModel->UsersRegister(data: $this->data);
            http_response_code(201);
            echo json_encode([
                'success' => true,
                'message' => "Registered =]",
                'data' => $result
            ]);
        } catch (Exception $e) {
            http_response_code($e->getCode() ?: 400);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => $this->data
            ]);
        } finally {
            exit;
        }
    }

}