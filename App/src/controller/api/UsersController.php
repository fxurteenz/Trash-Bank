<?php
namespace App\Controller\Api;

use App\Router\RouterBase;
use App\Services\UserService;
use App\Utils\Database;
use App\Utils\CookieBaker;
use App\Utils\Jwt;
use App\Utils\Authentication;
use App\Utils\AuthenticationException;
use Exception;

class UsersController extends RouterBase
{
    private $data, $UserService, $Database, $Jwt, $CookieBaker;
    public function __construct()
    {
        $input = file_get_contents('php://input');
        $this->data = json_decode($input, true);
        if (!$this->data) {
            $this->data = $_POST;
        }
        $this->Database = new Database();
        $this->UserService = new UserService($this->Database);
        $this->CookieBaker = new CookieBaker();
    }

    public function Login()
    {
        try {
            $user = $this->UserService->Login(data: $this->data);
            $token = Jwt::jwt_encode($user);
            $cookieToken = $this->CookieBaker->BakeUserCookie($token);

            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode([
                'status' => TRUE,
                'result' => $cookieToken,
                'message' => 'login successfully =)'
            ]);
            return;
        } catch (Exception $e) {
            http_response_code($e->getCode() ?: 400);
            echo json_encode([
                'status' => false,
                'message' => $e->getMessage(),
                'data' => $this->data
            ]);
        }
    }

    public function CreateUser()
    {
        try {
            $authenticated = Authentication::Auth();
            if ($authenticated->account_role !== 0) {
                throw new AuthenticationException('Forbidden', 403);
            }

            $user = $this->UserService->CreateUser($this->data);

            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode([
                'status' => TRUE,
                'result' => $user,
                'message' => 'user created successfully =)'
            ]);
            return;
        } catch (AuthenticationException $e) {
            http_response_code($e->getCode() ?: 401);
            echo json_encode([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        } catch (Exception $e) {
            http_response_code($e->getCode() ?: 400);
            echo json_encode([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

}