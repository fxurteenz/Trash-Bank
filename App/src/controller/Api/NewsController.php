<?php
namespace App\Controller\Api;

use App\Router\RouterBase;
use App\Model\NewsModel;
use App\Utils\Authentication;
use App\Utils\AuthenticationException;
use Exception;

class NewsController extends RouterBase
{
    private $data;
    private $NewsModel;
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

        $this->NewsModel = new NewsModel();
    }

    public function GetAll()
    {
        try {
            Authentication::CenterAuth();
            $rows = $this->NewsModel->GetAllNews($this->queryString ?? []);

            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode([
                'success' => true,
                'data' => $rows['data'],
                'total' => $rows['total'],
                'message' => 'ok'
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

    public function GetById($id)
    {
        try {
            Authentication::CenterAuth();
            $row = $this->NewsModel->GetNewsById((int) $id);
            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode(['success' => true, 'data' => $row, 'message' => 'ok']);
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
            // $user = Authentication::CenterAuth();
            $row = $this->NewsModel->CreateNews(is_array($this->data) ? $this->data : [], $this->data['file']);

            header('Content-Type: application/json');
            http_response_code(201);
            echo json_encode(['success' => true, 'data' => $row, 'message' => 'Point created =]']);
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
            Authentication::OperateAuth();
            $this->NewsModel->Update($id, is_array($this->data) ? $this->data : [], $this->data['file']);

            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode(['success' => true, 'message' => 'ok']);
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

    public function Delete($nid)
    {
        try {
            Authentication::CenterAuth();
            $result = $this->NewsModel->Delete($nid);
            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode(['success' => true, 'message' => 'Deleted successfully =]']);
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
