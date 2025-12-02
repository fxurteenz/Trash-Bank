<?php
namespace App\Controller\Api;

use App\Router\RouterBase;
use App\Model\UserModel;
use App\Utils\Database;
use App\Utils\CookieBaker;
use App\Utils\Jwt;
use App\Utils\Authentication;
use App\Utils\AuthenticationException;
use Exception;
use PhpParser\Node\Stmt\TryCatch;

class UsersController extends RouterBase
{
    private $data, $UserModel, $Database, $Jwt, $CookieBaker, $queryString;
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

        $this->Database = new Database();
        $this->UserModel = new UserModel();
        $this->CookieBaker = new CookieBaker();
    }

    public function Login()
    {
        try {
            $user = $this->UserModel->Login(data: $this->data);
            $token = Jwt::jwt_encode($user);
            $cookieToken = $this->CookieBaker->BakeUserCookie($token);

            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode([
                'success' => TRUE,
                'result' => $cookieToken,
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

    public function GetAll()
    {
        try {
            Authentication::AdminAuth();
            $result = $this->UserModel->GetAllUsers($this->queryString);

            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode([
                'success' => TRUE,
                'result' => $result[0],
                'total' => $result[1],
                'page' => (int) $this->queryString['page'],
                'limmit' => (int) $this->queryString['limit'],
                'message' => 'successfully =)'
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

    public function Create()
    {
        try {
            Authentication::AdminAuth();
            $user = $this->UserModel->CreateUser($this->data);

            header('Content-Type: application/json');
            http_response_code(201);
            echo json_encode([
                'success' => TRUE,
                'result' => $user,
                'message' => 'user created successfully =)'
            ]);
            return;
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
        }
    }

    public function Update($uid)
    {
        try {
            Authentication::AdminAuth();
            $user = $this->UserModel->UpdateUser($uid, $this->data);

            header('Content-Type: application/json');
            http_response_code(201);
            echo json_encode([
                'success' => TRUE,
                'result' => $user,
                'message' => 'user updated successfully =)'
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

    public function Delete($uid)
    {
        try {
            Authentication::AdminAuth();
            $affectedRows = $this->UserModel->DeleteUser($uid);
            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode([
                'success' => TRUE,
                'result' => $affectedRows,
                'message' => $affectedRows > 0 ? 'user deleted successfully =)' : 'not found this user Id'
            ]);
            return;
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
        }
    }
}