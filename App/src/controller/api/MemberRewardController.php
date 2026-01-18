<?php
namespace App\Controller\Api;

use App\Model\MemberRewardModel;
use Exception;

class MemberRewardController
{
    private $Model;

    public function __construct()
    {
        $this->Model = new MemberRewardModel();
    }

    public function GetAll()
    {
        try {
            $query = $_GET;
            $result = $this->Model->GetAllMemberRewards($query);

            echo json_encode([
                'success' => true,
                'data' => $result['data'],
                'total' => $result['total']
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function Get()
    {
        try {
            $id = isset($_GET['id']) ? (int) $_GET['id'] : null;

            if (!$id) {
                throw new Exception('ID is required', 400);
            }

            $data = $this->Model->GetMemberRewardById($id);

            echo json_encode([
                'success' => true,
                'data' => $data
            ]);
        } catch (Exception $e) {
            $code = $e->getCode() ?: 400;
            http_response_code($code);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function Create()
    {
        try {
            $data = [];
            $contentType = $_SERVER['CONTENT_TYPE'] ?? '';

            if (strpos($contentType, 'application/json') !== false) {
                $data = json_decode(file_get_contents('php://input'), true);
            } else {
                $data = $_POST;
            }

            $result = $this->Model->CreateMemberReward($data);

            echo json_encode([
                'success' => true,
                'data' => $result
            ]);
        } catch (Exception $e) {
            $code = $e->getCode() ?: 400;
            http_response_code($code);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function Update()
    {
        try {
            $id = isset($_GET['id']) ? (int) $_GET['id'] : null;

            if (!$id) {
                throw new Exception('ID is required', 400);
            }

            $data = [];
            $contentType = $_SERVER['CONTENT_TYPE'] ?? '';

            if (strpos($contentType, 'application/json') !== false) {
                $data = json_decode(file_get_contents('php://input'), true);
            } else {
                $data = $_POST;
            }

            $result = $this->Model->UpdateMemberReward($id, $data);

            echo json_encode([
                'success' => true,
                'data' => $result
            ]);
        } catch (Exception $e) {
            $code = $e->getCode() ?: 400;
            http_response_code($code);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function Delete()
    {
        try {
            $id = isset($_GET['id']) ? (int) $_GET['id'] : null;

            if (!$id) {
                throw new Exception('ID is required', 400);
            }

            $result = $this->Model->DeleteMemberReward($id);

            echo json_encode([
                'success' => true,
                'data' => $result
            ]);
        } catch (Exception $e) {
            $code = $e->getCode() ?: 400;
            http_response_code($code);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
