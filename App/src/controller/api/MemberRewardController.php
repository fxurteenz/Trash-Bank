<?php
namespace App\Controller\Api;

use App\Router\RouterBase;
use App\Model\MemberRewardModel;
use App\Utils\Authentication;
use App\Utils\AuthenticationException;
use Exception;

class MemberRewardController extends RouterBase
{
    private $data;
    private $MemberRewardModel;
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

        $this->MemberRewardModel = new MemberRewardModel();
    }

    public function GetAll()
    {
        try {
            Authentication::AdminAuth();
            $result = $this->MemberRewardModel->GetAll($this->queryString ?? []);

            $response = [
                'success' => true,
                'data' => $result['data'],
                'total' => $result['total'],
                'message' => 'successfully =)' 
            ];

            if (isset(($this->queryString ?? [])['page'])) {
                $response['page'] = (int) $this->queryString['page'];
            }
            if (isset(($this->queryString ?? [])['limit'])) {
                $response['limit'] = (int) $this->queryString['limit'];
            }

            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode($response);
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

    public function Get($id)
    {
        try {
            Authentication::AdminAuth();
            $row = $this->MemberRewardModel->GetById((int) $id);

            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode(['success' => true, 'data' => $row, 'message' => 'successfully =)']);
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

    public function Create()
    {
        try {
            Authentication::AdminAuth();
            $row = $this->MemberRewardModel->Create(is_array($this->data) ? $this->data : []);

            header('Content-Type: application/json');
            http_response_code(201);
            echo json_encode(['success' => true, 'data' => $row, 'message' => 'Redemption created =]']);
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

    public function Update($id)
    {
        try {
            Authentication::AdminAuth();
            $row = $this->MemberRewardModel->Update((int) $id, is_array($this->data) ? $this->data : []);

            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode(['success' => true, 'data' => $row, 'message' => 'Redemption updated successfully =)']);
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
