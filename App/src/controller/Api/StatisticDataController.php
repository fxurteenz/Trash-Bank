<?php

namespace App\Controller\Api;

use App\Model\StatisticDataModel;
use App\Utils\Authentication;
use App\Utils\AuthenticationException;
use Exception;

class StatisticDataController
{
    private static $data, $StatisticDataModel, $queryString;
    public function __construct()
    {
        $input = file_get_contents('php://input');
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $contentType = strtolower($_SERVER['CONTENT_TYPE'] ?? '');
        if ($requestMethod == "GET") {
            self::$queryString = $_GET;
        }
        switch (true) {
            case str_contains($contentType, 'application/json'):
                self::$data = json_decode($input, true);
                break;
            case str_contains($contentType, 'application/x-www-form-urlencoded'):
                parse_str($input, self::$data);
                break;
            case str_contains($contentType, 'multipart/form-data'):
                if ($_FILES) {
                    self::$data = array_merge($_POST, $_FILES);
                } else {
                    self::$data = $_POST;
                }
                break;
            default:
                self::$data = $input;
        }

        self::$StatisticDataModel = new StatisticDataModel();
    }

    public function GetHomePageData()
    {
        try {
            header('Content-Type: application/json');
            $result = self::$StatisticDataModel->GetHomePageStats(self::$queryString);
            http_response_code(200);
            echo json_encode([
                'success' => TRUE,
                'data' => $result,
                'message' => 'successfully =)'
            ]);
        } catch (AuthenticationException $e) {
            http_response_code($e->getCode() ?: 403);
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

    public function GetMemberStats($mid)
    {
        try {
            header('Content-Type: application/json');
            $result = self::$StatisticDataModel->GetMemberStats((int) $mid, self::$queryString);
            http_response_code(200);
            echo json_encode([
                'success' => TRUE,
                'data' => $result,
                'message' => 'successfully =)'
            ]);
        } catch (AuthenticationException $e) {
            http_response_code($e->getCode() ?: 403);
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


