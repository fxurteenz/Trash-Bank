<?php
namespace App\Controller\Api;

use App\Router\RouterBase;
use App\Model\MemberItemModel;
use App\Utils\Authentication;
use App\Utils\AuthenticationException;
use Exception;

class MemberItemController extends RouterBase
{
    private $data;
    private $MemberItemModel;

    public function __construct()
    {
        $input = file_get_contents('php://input');
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $contentType = strtolower($_SERVER['CONTENT_TYPE'] ?? '');

        if ($requestMethod === 'GET') {
            $this->queryString = $_GET;
        }

        switch (true) {
            case str_contains($contentType, 'application/json'):
                $this->data = json_decode($input, true);
                break;
            case str_contains($contentType, 'application/x-www-form-urlencoded'):
                parse_str($input, $this->data);
                break;
            case str_contains($contentType, 'multipart/form-data'):
                if ($_FILES) {
                    $this->data = array_merge($_POST, $_FILES);
                } else {
                    $this->data = $_POST;
                }
                break;
            default:
                $this->data = [];
        }

        $this->MemberItemModel = new MemberItemModel();
    }

    public function Redeem()
    {
        try {
            $user = Authentication::OperateAuth();
            $row = $this->MemberItemModel->RedeemDonationItem(is_array($this->data) ? $this->data : [], $user);

            header('Content-Type: application/json');
            http_response_code(201);
            echo json_encode(['success' => true, 'data' => $row, 'message' => 'Item redeemed successfully']);
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
