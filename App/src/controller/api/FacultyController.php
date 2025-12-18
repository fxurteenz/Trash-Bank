<?php
namespace App\Controller\Api;

use App\Model\FacultyModel;
use App\Router\RouterBase;
use App\Utils\Authentication;
use App\Utils\AuthenticationException;
use App\Utils\Database;
use Exception;
class FacultyController extends RouterBase
{
    private static $Data, $FacultyModel, $Database, $QueryString;

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

        self::$Database = new Database();
        self::$FacultyModel = new FacultyModel();
    }

    public function GetAll()
    {
        try {
            Authentication::OperateAuth();
            [$faculties, $total] = self::$FacultyModel->GetAllFaculty(self::$QueryString);

            header('Content-Type: application/json');
            http_response_code(200);

            $response = [
                'success' => TRUE,
                'result' => $faculties,
                'total' => $total,
                'message' => 'successfully fetched faculties'
            ];

            if (isset(self::$QueryString['page'])) {
                $response['page'] = (int) self::$QueryString['page'];
            }
            if (isset(self::$QueryString['limit'])) {
                $response['limit'] = (int) self::$QueryString['limit'];
            }

            echo json_encode($response);
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
        } finally {
            exit;
        }
    }

    public function GetFacultybyMajor($mid)
    {

        try {
            Authentication::OperateAuth();
            [$faculties, $total] = self::$FacultyModel->GetFacultyByMajor($mid);

            header('Content-Type: application/json');
            http_response_code(200);

            $response = [
                'success' => TRUE,
                'result' => $faculties,
                'total' => $total,
                'message' => 'successfully fetched faculties'
            ];

            if (isset(self::$QueryString['page'])) {
                $response['page'] = (int) self::$QueryString['page'];
            }
            if (isset(self::$QueryString['limit'])) {
                $response['limit'] = (int) self::$QueryString['limit'];
            }

            echo json_encode($response);
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
        } finally {
            exit;
        }
    }

    public function Create()
    {
        try {
            error_log("DATA RECEIVED: " . print_r(self::$Data, true));
            Authentication::AdminAuth();
            $faculty = self::$FacultyModel->CreateFaculty(self::$Data);

            header('Content-Type: application/json');
            http_response_code(201);
            echo json_encode([
                'success' => TRUE,
                'result' => $faculty,
                'message' => 'faculty created successfully =)'
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

    public function Update($fid)
    {
        try {
            Authentication::AdminAuth();
            $result = self::$FacultyModel->UpdateFaculty($fid, self::$Data);

            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode([
                'success' => TRUE,
                'result' => $result,
                'message' => 'faculty updated successfully =)'
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

    public function DeleteById($id)
    {
        try {
            Authentication::AdminAuth();
            $result = self::$FacultyModel->DeleteFacultyById($id);

            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode([
                'success' => TRUE,
                'result' => $result,
                'message' => 'faculty deleted successfully'
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
            $result = self::$FacultyModel->DeleteFaculty(self::$Data);

            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode([
                'success' => TRUE,
                'result' => $result,
                'message' => "Successfully deleted $result items."
            ]);
        } catch (AuthenticationException $e) {
            http_response_code($e->getCode() ?: 401);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        } catch (Exception $e) {
            http_response_code($e->getCode() ?: 400);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        } finally {
            exit;
        }
    }

}