<?php
namespace App\Controller\Api;

use App\Model\WasteSaleModel;
use App\Router\RouterBase;
use App\Utils\Authentication;
use App\Utils\AuthenticationException;
use Exception;

class WasteSaleController extends RouterBase
{
    private static $Data, $WasteSaleModel, $QueryString;

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

        self::$WasteSaleModel = new WasteSaleModel();
    }

    /**
     * Get all waste sales with optional filters
     */
    public function GetAll()
    {
        try {
            Authentication::OperateAuth();
            // Fetch sale headers with optional filters
            $sales = self::$WasteSaleModel->GetAll(self::$QueryString);

            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode([
                'success' => TRUE,
                'result' => $sales,
                'message' => 'Successfully fetched waste sales'
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
        }
    }

    /**
     * Get sales summary (grouped by waste type)
     */
    public function GetSummary()
    {
        try {
            Authentication::OperateAuth();
            // Basic summary from header rows
            $rows = self::$WasteSaleModel->GetAll(self::$QueryString);
            $data = $rows['data'] ?? [];
            $summary = [
                'count' => count($data),
                'total_weight' => array_sum(array_map(fn($r) => (float) ($r['waste_sale_total_weight'] ?? 0), $data)),
                'total_price' => array_sum(array_map(fn($r) => (float) ($r['waste_sale_total_price'] ?? 0), $data))
            ];

            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode([
                'success' => TRUE,
                'result' => $summary,
                'message' => 'Successfully fetched sales summary'
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
        }
    }

    /**
     * Get single waste sale by ID
     */
    public function GetById($id)
    {
        try {
            Authentication::OperateAuth();
            $sale = self::$WasteSaleModel->getByIdWithDetails($id);

            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode([
                'success' => TRUE,
                'result' => $sale,
                'message' => 'Successfully fetched waste sale'
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
        }
    }

    /**
     * Create a single waste sale
     */
    public function Create()
    {
        try {
            $user = Authentication::OperateAuth();
            $result = self::$WasteSaleModel->CreateWasteSale(self::$Data, $user['user_data']->member_id ?? null);

            header('Content-Type: application/json');
            http_response_code(201);
            echo json_encode([
                'success' => TRUE,
                'result' => $result,
                'message' => 'Waste sale created successfully'
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
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Create multiple waste sales in batch (POS-style)
     */
    public function CreateBatch()
    {
        try {
            Authentication::OperateAuth();
            if (empty(self::$Data['sales']) || !is_array(self::$Data['sales'])) {
                throw new Exception("Invalid batch data format. Expected 'sales' array.");
            }
            // Fallback simple implementation: create sale per item
            $created = [];
            foreach (self::$Data['sales'] as $sale) {
                $created[] = self::$WasteSaleModel->CreateWasteSale($sale, $sale['created_by'] ?? null);
            }

            header('Content-Type: application/json');
            http_response_code(201);
            echo json_encode([
                'success' => TRUE,
                'result' => $created,
                'message' => 'Batch waste sales created successfully'
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
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Update waste sale
     */
    public function Update($id)
    {
        try {
            Authentication::OperateAuth();
            // Not implemented at model level yet
            throw new Exception('Update is not supported currently', 400);

            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode([
                'success' => TRUE,
                'message' => 'No update performed'
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
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Delete waste sale by ID
     */
    public function DeleteById($id)
    {
        try {
            Authentication::OperateAuth();
            // Soft placeholder: not implemented delete logic here
            $deleted = false;

            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode([
                'success' => TRUE,
                'message' => $deleted ? 'Waste sale deleted successfully' : 'Delete route not implemented'
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
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Delete multiple waste sales
     */
    public function Delete()
    {
        try {
            Authentication::OperateAuth();
            // Not implemented, respond success for UI mock
            $result = ['deleted' => 0];

            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode([
                'success' => TRUE,
                'result' => $result,
                'message' => 'Delete route not implemented'
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
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
