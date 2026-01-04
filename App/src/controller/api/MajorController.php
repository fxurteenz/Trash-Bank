<?php
namespace App\Controller\Api;

use App\Model\MajorModel;
use App\Router\RouterBase;
use App\Utils\Authentication;
use App\Utils\AuthenticationException;
use App\Utils\Database;
use Exception;
class MajorController extends RouterBase
{
    private static $Data, $MajorModel, $Database, $QueryString;

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
                self::$Data = $input;
        }

        self::$Database = new Database();
        self::$MajorModel = new MajorModel();
    }

    public function Get($mid)
    {
        try {
            Authentication::AdminAuth();
            $result = self::$MajorModel->GetMajorById($mid);

            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode([
                'success' => TRUE,
                'result' => $result,
                'message' => 'successfully =)'
            ]);
        } catch (AuthenticationException $e) {
            http_response_code($e->getCode() ?: 401);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        } catch (Exception $e) {
            http_response_code($e->getCode() ?: 401);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        } finally {
            exit;
        }
    }

    public function GetAll()
    {
        try {
            Authentication::OperateAuth();
            [$majors, $total] = self::$MajorModel->GetAllMajor(self::$QueryString);

            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode([
                'success' => TRUE,
                'result' => $majors,
                'total' => $total,
                'message' => 'successfully =)'
            ]);
        } catch (AuthenticationException $e) {
            error_log("ERROR AUTH : " . $e->getMessage());
            http_response_code($e->getCode() ?: 403);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        } catch (Exception $e) {
            error_log("ERROR EXCEPTION: " . $e->getMessage());
            http_response_code($e->getCode() ?: 400);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        } finally {
            exit;
        }
    }

    public function GetByFaculty($fid)
    {
        try {
            Authentication::OperateAuth();
            [$majors, $total] = self::$MajorModel->GetMajorByFaculty($fid, self::$QueryString);

            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode([
                'success' => TRUE,
                'result' => $majors,
                'total' => $total,
                'message' => 'successfully retrieved majors by faculty'
            ]);
        } catch (AuthenticationException $e) {
            http_response_code($e->getCode() ?: 403);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        } catch (Exception $e) {
            http_response_code($e->getCode() ?: 400);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        } finally {
            exit;
        }
    }

    public function Create()
    {
        try {
            Authentication::AdminAuth();
            $result = self::$MajorModel->CreateMajor(self::$Data);

            header('Content-Type: application/json');
            http_response_code(201);
            echo json_encode([
                'success' => TRUE,
                'result' => $result,
                'message' => 'major created successfully =)'
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
        } finally {
            exit;
        }
    }

    public function Update($mid)
    {
        try {
            Authentication::AdminAuth();
            $result = self::$MajorModel->UpdateMajor($mid, self::$Data);

            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode([
                'success' => TRUE,
                'result' => $result,
                'message' => 'major updated successfully =)'
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
            $result = self::$MajorModel->DeleteMajorById($id);

            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode([
                'success' => TRUE,
                'result' => $result,
                'message' => 'major deleted successfully =)'
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
            $result = self::$MajorModel->DeleteMajor(self::$Data);

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