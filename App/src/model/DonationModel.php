<?php
namespace App\Model;

use App\Utils\Database;
use App\Utils\DatabaseException;
use Exception;
use PDO;
use PDOException;

class DonationModel
{
    private static $Database;
    private $Conn;

    public function __construct()
    {
        self::$Database = new Database();
        $this->Conn = self::$Database->connect();
    }

    public function GetAllDonations(array $query): array
    {
        try {
            $whereClauses = [];
            $params = [];

            if (!empty($query['search'])) {
                $whereClauses[] = "(m.member_name LIKE :search OR d.donation_description LIKE :search)";
                $params[':search'] = '%' . $query['search'] . '%';
            }

            if (!empty($query['start_date'])) {
                $whereClauses[] = "d.donation_date >= :start_date";
                $params[':start_date'] = $query['start_date'];
            }

            if (!empty($query['end_date'])) {
                $whereClauses[] = "d.donation_date <= :end_date";
                $params[':end_date'] = $query['end_date'];
            }

            $whereSql = !empty($whereClauses) ? ' WHERE ' . implode(' AND ', $whereClauses) : '';

            $sql = "SELECT
                        d.*,
                        m.member_name,
                        f.faculty_name
                    FROM donation d
                    LEFT JOIN member m ON d.member_id = m.member_id
                    LEFT JOIN faculty f ON d.faculty_id = f.faculty_id" .
                    $whereSql .
                    " ORDER BY d.donation_date DESC, d.donation_id DESC";

            $limit = isset($query['limit']) ? (int) $query['limit'] : null;
            $page = isset($query['page']) ? (int) $query['page'] : null;
            if ($limit && $page) {
                $offset = ($page - 1) * $limit;
                $sql .= " LIMIT :limit OFFSET :offset";
            }

            $stmt = $this->Conn->prepare($sql);
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            if ($limit && $page) {
                $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
                $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            }
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $countSql = "SELECT COUNT(*) as total
                        FROM donation d
                        LEFT JOIN member m ON d.member_id = m.member_id
                        LEFT JOIN faculty f ON d.faculty_id = f.faculty_id" . $whereSql;
            $countStmt = $this->Conn->prepare($countSql);
            foreach ($params as $key => $value) {
                $countStmt->bindValue($key, $value);
            }
            $countStmt->execute();
            $total = (int) ($countStmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0);

            return ['data' => $data, 'total' => $total];
        } catch (PDOException $e) {
            throw new DatabaseException($e->getMessage(), (int) $e->getCode());
        }
    }

    public function GetDonationById(int $id): array
    {
        try {
            $sql = "SELECT
                        d.*,
                        m.member_name,
                        f.faculty_name
                    FROM donation d
                    LEFT JOIN member m ON d.member_id = m.member_id
                    LEFT JOIN faculty f ON d.faculty_id = f.faculty_id
                    WHERE d.donation_id = :id";
            $stmt = $this->Conn->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$row) {
                throw new Exception('Donation not found', 404);
            }
            return $row;
        } catch (PDOException $e) {
            throw new DatabaseException($e->getMessage(), (int) $e->getCode());
        }
    }

    public function CreateDonation(array $data): array
    {
        try {
            $memberId = isset($data['member_id']) && $data['member_id'] !== '' ? (int) $data['member_id'] : null;
            $facultyId = isset($data['faculty_id']) && $data['faculty_id'] !== '' ? (int) $data['faculty_id'] : null;
            $description = $data['donation_description'] ?? null;
            $estimatedValue = isset($data['donation_estimated_value']) ? (float) $data['donation_estimated_value'] : 0;
            $goodnessPoint = isset($data['donation_goodness_point']) ? (int) $data['donation_goodness_point'] : 0;
            $reason = $data['donation_reason'] ?? null;
            $donationDate = $data['donation_date'] ?? date('Y-m-d');

            if (!$memberId) {
                throw new Exception('Member ID is required', 400);
            }

            $sql = "INSERT INTO donation (
                        member_id,
                        faculty_id,
                        donation_description,
                        donation_estimated_value,
                        donation_goodness_point,
                        donation_reason,
                        donation_date
                    ) VALUES (
                        :member_id,
                        :faculty_id,
                        :description,
                        :estimated_value,
                        :goodness_point,
                        :reason,
                        :donation_date
                    )";
            
            $stmt = $this->Conn->prepare($sql);
            $stmt->bindValue(':member_id', $memberId, PDO::PARAM_INT);
            $stmt->bindValue(':faculty_id', $facultyId, PDO::PARAM_INT);
            $stmt->bindValue(':description', $description);
            $stmt->bindValue(':estimated_value', $estimatedValue);
            $stmt->bindValue(':goodness_point', $goodnessPoint, PDO::PARAM_INT);
            $stmt->bindValue(':reason', $reason);
            $stmt->bindValue(':donation_date', $donationDate);
            $stmt->execute();

            $id = $this->Conn->lastInsertId();

            return [
                'donation_id' => $id,
                'success' => true
            ];
        } catch (PDOException $e) {
            throw new DatabaseException($e->getMessage(), (int) $e->getCode());
        }
    }

    public function UpdateDonation(int $id, array $data): array
    {
        try {
            $updateFields = [];
            $params = [':id' => $id];

            if (isset($data['member_id']) && $data['member_id'] !== '') {
                $updateFields[] = "member_id = :member_id";
                $params[':member_id'] = (int) $data['member_id'];
            }

            if (isset($data['faculty_id']) && $data['faculty_id'] !== '') {
                $updateFields[] = "faculty_id = :faculty_id";
                $params[':faculty_id'] = (int) $data['faculty_id'];
            }

            if (isset($data['donation_description'])) {
                $updateFields[] = "donation_description = :description";
                $params[':description'] = $data['donation_description'];
            }

            if (isset($data['donation_estimated_value'])) {
                $updateFields[] = "donation_estimated_value = :estimated_value";
                $params[':estimated_value'] = (float) $data['donation_estimated_value'];
            }

            if (isset($data['donation_goodness_point'])) {
                $updateFields[] = "donation_goodness_point = :goodness_point";
                $params[':goodness_point'] = (int) $data['donation_goodness_point'];
            }

            if (isset($data['donation_reason'])) {
                $updateFields[] = "donation_reason = :reason";
                $params[':reason'] = $data['donation_reason'];
            }

            if (isset($data['donation_date'])) {
                $updateFields[] = "donation_date = :donation_date";
                $params[':donation_date'] = $data['donation_date'];
            }

            if (empty($updateFields)) {
                throw new Exception('No fields to update', 400);
            }

            $sql = "UPDATE donation SET " . implode(', ', $updateFields) . " WHERE donation_id = :id";
            $stmt = $this->Conn->prepare($sql);

            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            $stmt->execute();

            if ($stmt->rowCount() === 0) {
                throw new Exception('Donation not found or no changes made', 404);
            }

            return ['success' => true];
        } catch (PDOException $e) {
            throw new DatabaseException($e->getMessage(), (int) $e->getCode());
        }
    }

    public function DeleteDonation(int $id): array
    {
        try {
            $sql = "DELETE FROM donation WHERE donation_id = :id";
            $stmt = $this->Conn->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() === 0) {
                throw new Exception('Donation not found', 404);
            }

            return ['success' => true];
        } catch (PDOException $e) {
            throw new DatabaseException($e->getMessage(), (int) $e->getCode());
        }
    }
}
