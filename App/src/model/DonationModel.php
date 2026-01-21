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

    public function CreateDonation(array $data, $staff): array
    {
        try {
            // 1. Validation
            if (empty($data)) {
                throw new Exception('No data provided', 400);
            }

            if (empty($data['donation_item_name'])) {
                throw new Exception('กรุณาระบุชื่อสิ่งของที่ได้รับ', 400);
            }
            if (empty($data['donation_item_qty']) || !is_numeric($data['donation_item_qty'])) {
                throw new Exception('กรุณาระบุจำนวนที่ได้รับให้ถูกต้อง', 400);
            }
            if (empty($data['donation_item_value']) || !is_numeric($data['donation_item_value'])) {
                throw new Exception('กรุณาระบุมูลค่าต่อชิ้น', 400);
            }

            // 2. Data Preparation
            $qty = (int) $data['donation_item_qty'];
            $pricePerUnit = (float) $data['donation_item_value'];
            $totalValue = $qty * $pricePerUnit;
            $description = isset($data['donation_description']) ? $data['donation_description'] : null;
            $memberId = isset($data['member_id']) ? $data['member_id'] : null; // กรณี member บริจาค ถ้าไม่มีให้เป็น null

            // เริ่ม Transaction (สำคัญมากเมื่อต้องลง 2 ตารางพร้อมกัน)
            $this->Conn->beginTransaction();

            // Step 3: Insert into 'donation' table (History Log)
            $sqlDonation = "INSERT INTO donation 
                        (member_id, staff_id, donation_item_name, donation_item_qty, 
                         donation_total_value, donation_goodness_point, donation_description, created_at) 
                        VALUES 
                        (:member_id, :staff_id, :item_name, :item_qty, 
                         :total_value, :goodness_point, :description, NOW())";

            $stmtDonation = $this->Conn->prepare($sqlDonation);
            $stmtDonation->execute([
                ':member_id' => $memberId,
                ':staff_id' => $staff['user_data']->member_id,
                ':item_name' => $data['donation_item_name'],
                ':item_qty' => $qty,
                ':total_value' => $totalValue,
                ':goodness_point' => $totalValue,
                ':description' => $description
            ]);

            // Step 4: Upsert into 'donation_item' table (Inventory)
            $sqlInventory = "INSERT INTO donation_item 
                         (donation_item_name, donation_item_price, donation_item_amount, updated_at) 
                         VALUES 
                         (:name, :price, :qty, NOW()) 
                         ON DUPLICATE KEY UPDATE 
                         donation_item_amount = donation_item_amount + :qty_update, 
                         donation_item_price = :price_update, 
                         updated_at = NOW()";

            $stmtInventory = $this->Conn->prepare($sqlInventory);
            $stmtInventory->execute([
                ':name' => $data['donation_item_name'],
                ':price' => $pricePerUnit,
                ':qty' => $qty,
                ':qty_update' => $qty,
                ':price_update' => $pricePerUnit
            ]);

            $this->Conn->commit();

            $data['donation_total_value'] = $totalValue;
            $data['status'] = 'success';

            return $data;

        } catch (PDOException $e) {
            if ($this->Conn->inTransaction()) {
                $this->Conn->rollBack();
            }
            throw new DatabaseException($e->getMessage(), (int) $e->getCode());
        } catch (Exception $e) {
            if ($this->Conn->inTransaction()) {
                $this->Conn->rollBack();
            }
            throw new Exception($e->getMessage(), (int) $e->getCode());
        }
    }

    public function GetAll(array $query): array
    {
        try {
            $where = [];
            $params = [];

            if (!empty($query['start_date'])) {
                $where[] = 'DATE(d.created_at) >= :start_date';
                $params[':start_date'] = $query['start_date'];
            }
            if (!empty($query['end_date'])) {
                $where[] = 'DATE(d.created_at) <= :end_date';
                $params[':end_date'] = $query['end_date'];
            }
            if (!empty($query['search'])) {
                $where[] = '(donor.member_name LIKE :q OR staff.member_name LIKE :q OR d.donation_item_name LIKE :q)';
                $params[':q'] = '%' . $query['search'] . '%';
            }

            $whereSql = !empty($where) ? ' WHERE ' . implode(' AND ', $where) : '';

            $sqlCount = "SELECT COUNT(*) AS total FROM donation d
                         LEFT JOIN member donor ON d.member_id = donor.member_id
                         LEFT JOIN member staff ON d.staff_id = staff.member_id
                         $whereSql";
            $stmtCount = $this->Conn->prepare($sqlCount);
            foreach ($params as $k => $v)
                $stmtCount->bindValue($k, $v);
            $stmtCount->execute();
            $total = (int) $stmtCount->fetch(PDO::FETCH_ASSOC)['total'];

            $sql = "SELECT 
                        d.*, 
                        donor.member_name AS donor_name, 
                        staff.member_name AS staff_name
                    FROM donation d
                    LEFT JOIN member donor ON d.member_id = donor.member_id
                    LEFT JOIN member staff ON d.staff_id = staff.member_id
                    $whereSql
                    ORDER BY d.created_at DESC";

            $isPagination = isset($query['page']) && isset($query['limit']);
            if ($isPagination) {
                $sql .= ' LIMIT :limit OFFSET :offset';
            }

            $stmt = $this->Conn->prepare($sql);
            foreach ($params as $k => $v)
                $stmt->bindValue($k, $v);
            if ($isPagination) {
                $limit = (int) $query['limit'];
                $offset = ((int) $query['page'] - 1) * $limit;
                $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
                $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            }
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return ['data' => $rows, 'total' => $total];
        } catch (PDOException $e) {
            throw new DatabaseException($e->getMessage(), (int) $e->getCode());
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), (int) $e->getCode());
        }
    }

    public function GetById(int $id): array|null
    {
        try {
            $sql = "SELECT 
                        d.*, 
                        donor.member_name AS donor_name, 
                        staff.member_name AS staff_name
                    FROM donation d
                    LEFT JOIN member donor ON d.member_id = donor.member_id
                    LEFT JOIN member staff ON d.staff_id = staff.member_id
                    WHERE d.donation_id = :id";
            $stmt = $this->Conn->prepare($sql);
            $stmt->execute([':id' => $id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row ?: null;
        } catch (PDOException $e) {
            throw new DatabaseException($e->getMessage(), (int) $e->getCode());
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), (int) $e->getCode());
        }
    }

    public function GetDonationItem(array $query): array
    {
        try {

            $where = [];
            $params = [];

            if (!empty($query['search'])) {
                $where[] = 'donation_item_name LIKE :q';
                $params[':q'] = '%' . $query['search'] . '%';
            }

            $whereSql = !empty($where) ? ' WHERE ' . implode(' AND ', $where) : '';

            $sqlCount = "SELECT COUNT(*) AS total FROM donation_item $whereSql";
            $stmtCount = $this->Conn->prepare($sqlCount);
            foreach ($params as $k => $v)
                $stmtCount->bindValue($k, $v);
            $stmtCount->execute();
            $total = (int) $stmtCount->fetch(PDO::FETCH_ASSOC)['total'];


            $sql = "SELECT * FROM donation_item $whereSql ORDER BY updated_at DESC";
            $isPagination = isset($query['page']) && isset($query['limit']);

            if ($isPagination) {
                $sql .= ' LIMIT :limit OFFSET :offset';
            }

            $stmt = $this->Conn->prepare($sql);
            foreach ($params as $k => $v)
                $stmt->bindValue($k, $v);

            if ($isPagination) {
                $limit = (int) $query['limit'];
                $offset = ((int) $query['page'] - 1) * $limit;
                $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
                $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            }

            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return ['data' => $rows, 'total' => $total];
        } catch (PDOException $e) {
            throw new Exception("Error Processing Request", 503);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    
}
