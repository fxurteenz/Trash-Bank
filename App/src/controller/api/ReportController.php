<?php
namespace App\Controller\Api;

use App\Model\ReportModel;
use App\Utils\Authentication;
use App\Utils\AuthenticationException;
use Exception;

class ReportController
{
    private static $data, $ReportModel, $queryString;
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

        self::$ReportModel = new ReportModel();
    }

    public function GetOverallReport()
    {
        try {
            $result = self::$ReportModel->OverallReport(self::$queryString);

            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode([
                'success' => TRUE,
                'data' => $result,
                'message' => 'successfully =)'
            ]);
        } catch (AuthenticationException $e) {
            header('Content-Type: application/json');
            http_response_code($e->getCode() ?: 401);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        } catch (Exception $e) {
            header('Content-Type: application/json');
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