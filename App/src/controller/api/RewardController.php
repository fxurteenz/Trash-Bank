<?php
namespace App\Controller\Api;

use App\Router\RouterBase;
use App\Model\RewardModel;
use App\Utils\Authentication;
use App\Utils\AuthenticationException;
use Exception;

class RewardController extends RouterBase
{
    private $data, $RewardModel, $queryString;
    
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
        
        $this->RewardModel = new RewardModel();
    }

    public function GetAll()
    {
        try {
            $result = $this->RewardModel->GetAllRewards($this->queryString);
            $response = [
                'success' => TRUE,
                'data' => $result['data'],
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

    public function Get($id)
    {
        try {
            $reward = $this->RewardModel->GetRewardById($id);
            
            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode([
                'success' => TRUE,
                'data' => $reward,
                'message' => 'successfully =)'
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
            $result = $this->RewardModel->CreateReward($this->data);

            header('Content-Type: application/json');
            http_response_code(201);
            echo json_encode([
                'success' => TRUE,
                'message' => 'Reward Created =]',
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
        } finally {
            exit;
        }
    }

    public function Update($id)
    {
        try {
            Authentication::AdminAuth();
            $reward = $this->RewardModel->UpdateReward($id, $this->data);

            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode([
                'success' => TRUE,
                'result' => $reward,
                'message' => 'Reward updated successfully =)'
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
            $affectedRows = $this->RewardModel->DeleteReward($this->data);
            
            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode([
                'success' => TRUE,
                'total' => $affectedRows,
                'message' => $affectedRows > 0 ? 'Rewards deleted' : 'Not found this reward Id'
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
}
