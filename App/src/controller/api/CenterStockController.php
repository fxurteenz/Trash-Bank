<?php
namespace App\Controller\Api;

use App\Model\CenterStockModel;
use App\Utils\Authentication;
use Exception;

class CenterStockController
{
    private $data;
    private $CenterStockModel;
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

        $this->CenterStockModel = new CenterStockModel();
    }
    public function GetAll()
    {
        header('Content-Type: application/json');
        try {
            Authentication::CenterAuth();
            $result = $this->CenterStockModel->getCenterStock($this->queryString);

            http_response_code(200);
            echo json_encode([
                'success' => true,
                'data' => $result['data'],
                'total' => $result['total']
            ]);
        } catch (Exception $e) {
            http_response_code($e->getCode() ?: 500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        } finally {
            exit;
        }
    }
}
