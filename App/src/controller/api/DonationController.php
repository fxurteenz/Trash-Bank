<?php
namespace App\Controller\Api;

use App\Router\RouterBase;
use App\Model\DonationModel;
use App\Utils\Authentication;
use App\Utils\AuthenticationException;
use Exception;

class DonationController extends RouterBase
{
    private $data, $DonationModel, $queryString;
    
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
        
        $this->DonationModel = new DonationModel();
    }

    public function GetAll()
    {
        try {
            $result = $this->DonationModel->GetAllDonations($this->queryString);
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
            $donation = $this->DonationModel->GetDonationById($id);
            
            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode([
                'success' => TRUE,
                'data' => $donation,
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
            $result = $this->DonationModel->CreateDonation($this->data);
            
            header('Content-Type: application/json');
            http_response_code(201);
            echo json_encode([
                'success' => TRUE,
                'data' => $result,
                'message' => 'Donation created successfully'
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
            $result = $this->DonationModel->UpdateDonation($id, $this->data);
            
            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode([
                'success' => TRUE,
                'data' => $result,
                'message' => 'Donation updated successfully'
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

    public function Delete($id)
    {
        try {
            $result = $this->DonationModel->DeleteDonation($id);
            
            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode([
                'success' => TRUE,
                'data' => $result,
                'message' => 'Donation deleted successfully'
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
