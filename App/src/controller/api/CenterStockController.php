<?php
namespace App\Controller\Api;

use App\Model\CenterStockModel;
use App\Utils\Authentication;
use Exception;

class CenterStockController
{
    public function GetAll()
    {
        header('Content-Type: application/json');
        try {
            Authentication::CenterAuth();
            $query = $_GET;
            $model = new CenterStockModel();
            $stock = $model->getCenterStock($query);
            
            http_response_code(200);
            echo json_encode(['success' => true, 'data' => $stock, 'message' => 'successfully =)']);
        } catch (Exception $e) {
            http_response_code($e->getCode() ?: 500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        } finally {
            exit;
        }
    }
}
