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
            // use user auth
            $result = self::$LeaderModel->LeadingUsersByRole(self::$queryString);

            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode([
                'success' => TRUE,
                'result' => $result['user'],
                'total' => $result['total'],
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

    public function GetUsersLeaderByFaculty()
    {
        try {
            // use user auth
            $result = self::$LeaderModel->LeadingUsersByFaculty(self::$queryString);

            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode([
                'success' => TRUE,
                'result' => $result['user'],
                'total' => $result['total'],
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

    public function GetUsersLeaderByMajor()
    {
        try {
            // use user auth
            $result = self::$LeaderModel->LeadingUsersByMajor(self::$queryString);

            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode([
                'success' => TRUE,
                'result' => $result['user'],
                'total' => $result['total'],
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

    public function GetFacultyLeader()
    {
        try {
            // use user auth
            $result = self::$LeaderModel->LeadingFaculties(self::$queryString);

            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode([
                'success' => TRUE,
                'result' => $result['user'],
                'total' => $result['total'],
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

    public function GetFacultyWasteStats()
    {
        try {
            // use user auth
            $result = self::$LeaderModel->LeadingFacultyWasteStats(self::$queryString);

            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode([
                'success' => TRUE,
                'result' => $result['user'],
                'total' => $result['total'],
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

    public function GetUsersWasteStats()
    {
        try {
            // use user auth
            $result = self::$LeaderModel->LeadingUserWasteStats(self::$queryString);

            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode([
                'success' => TRUE,
                'result' => $result['stats'],
                'total' => $result['total'],
                'page' => (int) (self::$queryString['page'] ?? 1),
                'limmit' => (int) (self::$queryString['limit'] ?? 10),
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