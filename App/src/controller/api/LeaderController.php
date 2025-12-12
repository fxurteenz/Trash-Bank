<?php
namespace App\Controller\Api;

use App\Model\LeaderModel;
use App\Utils\Authentication;
use App\Utils\AuthenticationException;
use Exception;

class LeaderController
{
    private static $data, $LeaderModel, $queryString;
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

        self::$LeaderModel = new LeaderModel();
    }

    public function GetUsersLeaderByRole()
    {
        try {
            Authentication::AdminAuth();
            $result = self::$LeaderModel->LeadingUsersByRole(self::$queryString);

            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode([
                'success' => TRUE,
                'result' => $result[0],
                'total' => $result[1],
                'page' => (int) self::$queryString['page'],
                'limmit' => (int) self::$queryString['limit'],
                'message' => 'successfully =)'
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