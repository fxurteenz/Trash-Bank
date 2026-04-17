<?php
namespace App\Controller\Api;

use App\Router\RouterBase;
use App\Model\BadgeModel;
use App\Utils\Authentication;
use App\Utils\AuthenticationException;
use Exception;

class BadgeController extends RouterBase
{
    private $data, $BadgeModel, $queryString;
    
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
        
        $this->BadgeModel = new BadgeModel();
    }

    public function GetAll()
    {
        try {
            $result = $this->BadgeModel->GetAllBadges($this->queryString);
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
            $badge = $this->BadgeModel->GetBadgeById($id);
            
            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode([
                'success' => TRUE,
                'data' => $badge,
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
            
            // Handle file upload
            if (isset($_FILES['badge_image']) && $_FILES['badge_image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . '/../../../public/assets/images/badges/';
                $fileExtension = pathinfo($_FILES['badge_image']['name'], PATHINFO_EXTENSION);
                $fileName = uniqid('badge_') . '.' . $fileExtension;
                $uploadPath = $uploadDir . $fileName;
                
                if (move_uploaded_file($_FILES['badge_image']['tmp_name'], $uploadPath)) {
                    $this->data['badge_image'] = $fileName;
                }
            }
            
            $result = $this->BadgeModel->CreateBadge($this->data);

            header('Content-Type: application/json');
            http_response_code(201);
            echo json_encode([
                'success' => TRUE,
                'message' => 'Badge Created =]',
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
            
            // Handle file upload
            if (isset($_FILES['badge_image']) && $_FILES['badge_image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . '/../../../public/assets/images/badges/';
                $fileExtension = pathinfo($_FILES['badge_image']['name'], PATHINFO_EXTENSION);
                $fileName = uniqid('badge_') . '.' . $fileExtension;
                $uploadPath = $uploadDir . $fileName;
                
                if (move_uploaded_file($_FILES['badge_image']['tmp_name'], $uploadPath)) {
                    $this->data['badge_image'] = $fileName;
                }
            }
            
            $badge = $this->BadgeModel->UpdateBadge($id, $this->data);

            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode([
                'success' => TRUE,
                'result' => $badge,
                'message' => 'Badge updated successfully =)'
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
            $affectedRows = $this->BadgeModel->DeleteBadge($this->data);
            
            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode([
                'success' => TRUE,
                'total' => $affectedRows,
                'message' => $affectedRows > 0 ? 'Badges deleted' : 'Not found this badge Id'
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
