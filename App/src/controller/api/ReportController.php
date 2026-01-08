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

    public function GetScopedReport()
    {
        try {
            $scope = self::$queryString['scope'] ?? 'overall';
            switch ($scope) {
                case 'member':
                    $memberId = self::$queryString['member_id'] ?? null;
                    if (!$memberId) {
                        throw new Exception('member_id is required', 400);
                    }
                    $result = self::$ReportModel->MemberReport((int) $memberId, self::$queryString);
                    break;
                case 'faculty':
                    $facultyId = self::$queryString['faculty_id'] ?? null;
                    if (!$facultyId) {
                        throw new Exception('faculty_id is required', 400);
                    }
                    $result = self::$ReportModel->FacultyReport((int) $facultyId, self::$queryString);
                    break;
                case 'leaderboard_members':
                    $result = self::$ReportModel->MemberLeaderboard(self::$queryString);
                    break;
                case 'leaderboard_faculties':
                    $result = self::$ReportModel->FacultyLeaderboard(self::$queryString);
                    break;
                case 'leaderboard':
                    $target = self::$queryString['target'] ?? 'members';
                    if ($target === 'faculties') {
                        $result = self::$ReportModel->FacultyLeaderboard(self::$queryString);
                    } else {
                        $result = self::$ReportModel->MemberLeaderboard(self::$queryString);
                    }
                    break;
                case 'carbon':
                    $result = self::$ReportModel->CarbonImpact(self::$queryString);
                    break;
                case 'redemptions':
                    $result = self::$ReportModel->RedemptionsReport(self::$queryString);
                    break;
                case 'goodness':
                    $result = self::$ReportModel->GoodnessReport(self::$queryString);
                    break;
                case 'clearances':
                    $result = self::$ReportModel->ClearanceReport(self::$queryString);
                    break;
                case 'overall':
                default:
                    $result = self::$ReportModel->OverallReport(self::$queryString);
                    break;
            }

            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode([
                'success' => TRUE,
                'data' => $result,
                'message' => 'successfully =)'
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

    public function GetMemberReport($mid)
    {
        try {
            $result = self::$ReportModel->MemberReport((int) $mid, self::$queryString);

            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode([
                'success' => TRUE,
                'data' => $result,
                'message' => 'successfully =)' 
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

    public function GetFacultyReport($fid)
    {
        try {
            $result = self::$ReportModel->FacultyReport((int) $fid, self::$queryString);

            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode([
                'success' => TRUE,
                'data' => $result,
                'message' => 'successfully =)' 
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

    public function GetMemberLeaderboard()
    {
        try {
            $result = self::$ReportModel->MemberLeaderboard(self::$queryString);

            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode([
                'success' => TRUE,
                'data' => $result,
                'message' => 'successfully =)' 
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

    public function GetFacultyLeaderboard()
    {
        try {
            $result = self::$ReportModel->FacultyLeaderboard(self::$queryString);

            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode([
                'success' => TRUE,
                'data' => $result,
                'message' => 'successfully =)' 
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

    public function GetCarbonImpact()
    {
        try {
            $result = self::$ReportModel->CarbonImpact(self::$queryString);

            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode([
                'success' => TRUE,
                'data' => $result,
                'message' => 'successfully =)' 
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