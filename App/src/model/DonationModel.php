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
                $whereClauses[] = "(m.member_name LIKE :search OR d.donation_description LIKE :search OR d.donation_reason LIKE :search)";
                $params[':search'] = '%' . $query['search'] . '%';
            }

            if (!empty($query['date'])) {
                $whereClauses[] = "d.donation_date = :date";
                $params[':date'] = $query['date'];
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
            $estimatedValue = isset($data['donation_estimated_value']) && $data['donation_estimated_value'] !== '' ? (float) $data['donation_estimated_value'] : null;
            $goodnessPoint = isset($data['donation_goodness_point']) && $data['donation_goodness_point'] !== '' ? (int) $data['donation_goodness_point'] : null;
            $reason = $data['donation_reason'] ?? null;
            $date = $data['donation_date'] ?? null;

            if (!$date) {
                $date = date('Y-m-d');
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
            $stmt->bindValue(':member_id', $memberId, $memberId === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
            $stmt->bindValue(':faculty_id', $facultyId, $facultyId === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
            $stmt->bindValue(':description', $description);
            $stmt->bindValue(':estimated_value', $estimatedValue);
            $stmt->bindValue(':goodness_point', $goodnessPoint, $goodnessPoint === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
            $stmt->bindValue(':reason', $reason);
            $stmt->bindValue(':donation_date', $date);
            $stmt->execute();

            $id = (int) $this->Conn->lastInsertId();
            return $this->GetDonationById($id);
        } catch (PDOException $e) {
            throw new DatabaseException($e->getMessage(), (int) $e->getCode());
        }
    }

    public function UpdateDonation(int $id, array $data): array
    {
        try {
            $fields = [];
            $params = [':id' => $id];

            $nullableIntFields = [
                'member_id' => PDO::PARAM_INT,
                'faculty_id' => PDO::PARAM_INT,
                'donation_goodness_point' => PDO::PARAM_INT,
            ];

            if (array_key_exists('member_id', $data)) {
                $fields[] = 'member_id = :member_id';
                $params[':member_id'] = ($data['member_id'] === '' || $data['member_id'] === null) ? null : (int) $data['member_id'];
            }
            if (array_key_exists('faculty_id', $data)) {
                $fields[] = 'faculty_id = :faculty_id';
                $params[':faculty_id'] = ($data['faculty_id'] === '' || $data['faculty_id'] === null) ? null : (int) $data['faculty_id'];
            }
            if (array_key_exists('donation_description', $data)) {
                $fields[] = 'donation_description = :description';
                $params[':description'] = $data['donation_description'];
            }
            if (array_key_exists('donation_estimated_value', $data)) {
                $fields[] = 'donation_estimated_value = :estimated_value';
                $params[':estimated_value'] = ($data['donation_estimated_value'] === '' || $data['donation_estimated_value'] === null) ? null : (float) $data['donation_estimated_value'];
            }
            if (array_key_exists('donation_goodness_point', $data)) {
                $fields[] = 'donation_goodness_point = :goodness_point';
                $params[':goodness_point'] = ($data['donation_goodness_point'] === '' || $data['donation_goodness_point'] === null) ? null : (int) $data['donation_goodness_point'];
            }
            if (array_key_exists('donation_reason', $data)) {
                $fields[] = 'donation_reason = :reason';
                $params[':reason'] = $data['donation_reason'];
            }
            if (array_key_exists('donation_date', $data) && $data['donation_date'] !== '') {
                $fields[] = 'donation_date = :donation_date';
                $params[':donation_date'] = $data['donation_date'];
            }

            if (empty($fields)) {
                throw new Exception('No fields to update', 400);
            }

            $sql = 'UPDATE donation SET ' . implode(', ', $fields) . ' WHERE donation_id = :id';
            $stmt = $this->Conn->prepare($sql);

            foreach ($params as $key => $value) {
                if ($value === null) {
                    $stmt->bindValue($key, null, PDO::PARAM_NULL);
                    continue;
                }
                $stmt->bindValue($key, $value);
            }

            $stmt->execute();
            if ($stmt->rowCount() === 0) {
                throw new Exception('Donation not found or no changes made', 404);
            }

            return $this->GetDonationById($id);
        } catch (PDOException $e) {
            throw new DatabaseException($e->getMessage(), (int) $e->getCode());
        }
    }

    public function DeleteDonation(array $data): int
    {
        try {
            $ids = is_array($data['donation_id'] ?? null) ? $data['donation_id'] : [$data['donation_id'] ?? null];
            $ids = array_values(array_filter($ids, fn($v) => $v !== null && $v !== ''));
            if (empty($ids)) {
                throw new Exception('donation_id is required', 400);
            }

            $placeholders = implode(',', array_fill(0, count($ids), '?'));
            $sql = "DELETE FROM donation WHERE donation_id IN ($placeholders)";
            $stmt = $this->Conn->prepare($sql);
            $stmt->execute($ids);
            return $stmt->rowCount();
        } catch (PDOException $e) {
            throw new DatabaseException($e->getMessage(), (int) $e->getCode());
        }
    }
}
