<?php
namespace App\Model;

use App\Utils\Database;
use App\Utils\DatabaseException;
use Exception;
use PDO;
use PDOException;

class DonationItemPointModel
{
    private static $Database;
    private $Conn;

    public function __construct()
    {
        self::$Database = new Database();
        $this->Conn = self::$Database->connect();
    }

    public function GetAllPoint($query)
    {
        try {

            $query = "SELECT * FROM donation_item_point";
            $isPagination = isset($query['page']) && isset($query['limit']);

            if ($isPagination) {
                $page = (int) $query['page'];
                $limit = (int) $query['limit'];
                $offset = ($page - 1) * $limit;
                $query .= " LIMIT :limit OFFSET :offset";
            }

            $stmt = $this->Conn->prepare($query);

            if ($isPagination) {
                $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
                $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            }

            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $total = count($result);

            return ["data" => $result, "total" => $total];
        } catch (PDOException $e) {
            throw new Exception($e->getMessage(), (int) $e->getCode() ?? 500);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?? 400);
        }
    }

    public function GetPointById($id)
    {
        try {
            $query = "SELECT * FROM donation_item_point WHERE donation_item_point_id = :id";
            $stmt = $this->Conn->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception($e->getMessage(), (int) $e->getCode() ?? 500);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?? 400);
        }
    }

    public function CreateDonationItemPoint($data)
    {
        try {
            $query = "INSERT INTO donation_item_point (redeem_point) VALUES (:redeem_point)";
            $stmt = $this->Conn->prepare($query);
            $stmt->bindParam(':redeem_point', $data['redeem_point']);
            $stmt->execute();
            return $this->Conn->lastInsertId();
        } catch (PDOException $e) {
            throw new Exception($e->getMessage(), (int) $e->getCode() ?? 500);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?? 400);
        }
    }

    public function UpdatePoint($id, $data)
    {
        try {
            $query = "UPDATE donation_item_point SET redeem_point = :redeem_point WHERE donation_item_point_id = :id";
            $stmt = $this->Conn->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':redeem_point', $data['redeem_point']);
            $stmt->execute();
            return $stmt->rowCount();
        } catch (PDOException $e) {
            throw new Exception($e->getMessage(), (int) $e->getCode() ?? 500);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?? 400);
        }
    }

    public function DeleteDonationItemPoint($data)
    {
        if (empty($data['point_ids'] ?? []) || !is_array($data['point_ids'])) {
            throw new Exception('Bad Request: point_ids is required and must be an array', 400);
        }
        $ids = $data['point_ids'];
        $ids = array_filter($ids);

        if (empty($ids)) {
            return 0;
        }
        try {
            $this->Conn->beginTransaction();

            $placeholders = str_repeat('?,', count($ids) - 1) . '?';
            $query = "DELETE FROM donation_item_point WHERE donation_item_point_id IN ($placeholders)";
            $stmt = $this->Conn->prepare($query);
            foreach ($ids as $index => $id) {
                $stmt->bindValue($index + 1, $id, PDO::PARAM_STR);
            }
            $stmt->execute();
            $this->Conn->commit();

            return $stmt->rowCount();
        } catch (PDOException $e) {
            throw new Exception($e->getMessage(), (int) $e->getCode() ?? 500);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?? 400);
        }
    }
}
