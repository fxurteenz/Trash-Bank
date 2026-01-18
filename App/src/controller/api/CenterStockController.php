<?php
namespace App\Controller\Api;

use App\Model\CenterStockModel;
use Exception;

class CenterStockController
{
    public function getCenterStock()
    {
        header('Content-Type: application/json');
        try {
            $query = $_GET;
            $model = new CenterStockModel();
            $stock = $model->getCenterStock($query);
            
            http_response_code(200);
            echo json_encode(['status' => 'success', 'data' => $stock]);
        } catch (Exception $e) {
            http_response_code($e->getCode() ?: 500);
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}
