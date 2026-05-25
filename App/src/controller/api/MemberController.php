<?php
namespace App\Controller\Api;

use App\Router\RouterBase;
use App\Model\MemberModel;
use App\Utils\Authentication;
use App\Utils\AuthenticationException;
use Exception;

class MemberController extends RouterBase
{
    private $data, $MemberModel, $queryString;
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
        $this->MemberModel = new MemberModel();
    }


    public function GetAll()
    {
        try {
            Authentication::OperateAuth();
            $result = $this->MemberModel->GetAllMembers($this->queryString);
            $response = [
                'success' => TRUE,
                'data' => $result['data'],
                'summary' => $result['summary'] ?? [],
                'total' => $result['total'],
                'message' => 'successfully =)'
            ];
            if (isset($this->queryString['page'])) {
                $response['page'] = (int) $this->queryString['page'];
            }
            if (isset($this->queryString['limit'])) {
                $response['limit'] = (int) $this->queryString['limit'];
            }
            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode($response);
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

    public function Create()
    {
        try {
            if ((int) $this->data["role_id"] == 1 || (int) $this->data["role_id"] == 3 || (int) $this->data["role_id"] == 4) {
                Authentication::CenterAuth();
            } else {
                Authentication::OperateAuth();
            }
            $result = $this->MemberModel->CreateMember($this->data);

            header('Content-Type: application/json');
            http_response_code(201);
            echo json_encode([
                'success' => TRUE,
                'message' => 'Member Created =]',
                'data' => $result
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
        }
    }

    public function Update($uid)
    {
        try {
            $role_id = isset($this->data["role_id"]) ? (int) $this->data["role_id"] : null;
            if ($role_id == 1 || $role_id == 3 || $role_id == 4) {
                Authentication::CenterAuth();
            } else {
                Authentication::OperateAuth();
            }
            $user = $this->MemberModel->UpdateMember($uid, $this->data);

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
        }
    }

    public function UpdateProfile($uid)
    {
        try {
            Authentication::MemberAuth();
            $restricted_keys = ['role_id', 'member_waste_point', 'member_goodness_point', 'faculty_id', 'major_id'];
            foreach ($restricted_keys as $key) {
                if (array_key_exists($key, $this->data)) {
                    unset($this->data[$key]);
                }
            }

            $user = $this->MemberModel->UpdateMember($uid, $this->data);
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
                'message' => $e->getMessage(),
                'data' => $this->data
            ]);
        } catch (Exception $e) {
            http_response_code($e->getCode() ?: 400);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()

            ]);
        }
    }
    public function Delete()
    {
        try {
            Authentication::OperateAuth();
            $affectedRows = $this->MemberModel->DeleteMember($this->data);

            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode([
                'success' => TRUE,
                'total' => $affectedRows,
                'message' => $affectedRows > 0 ? 'users deleted' : 'not found this user Id'
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

    public function GetProfile($member_id)
    {
        try {
            // Authentication::MemberAuth();
            $profile = $this->MemberModel->GetMemberProfile($member_id);

            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode([
                'success' => TRUE,
                'data' => $profile,
                'message' => 'Profile retrieved successfully =)'
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
        }
    }

    public function GetRoleCount()
    {
        try {
            Authentication::OperateAuth();
            $result = $this->MemberModel->GetMemberRoleCount($this->queryString);

            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode([
                'success' => TRUE,
                'data' => $result,
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
        }
    }
}