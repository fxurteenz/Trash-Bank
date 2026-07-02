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
            // 1. Validation ข้อมูลพื้นฐาน
            if (empty($data)) {
                throw new Exception('No data provided', 400);
            }
            if (!isset($data['member_id']) || empty($data['member_id'])) {
                throw new Exception('กรุณาลองใหม่, ไม่พบข้อมูลผู้บริจาค', 400);
            }
            if (!isset($data['items']) || !is_array($data['items']) || count($data['items']) === 0) {
                throw new Exception('กรุณาลองใหม่, ไม่พบรายการสิ่งของที่บริจาค', 400);
            }

            $memberId = $data['member_id'];
            $description = $data['donation_description'] ?? null;
            $staffId = $staff['user_data']->member_id;

            // 2. Data Preparation & Calculation (วนลูปคำนวณยอดรวมก่อนเริ่ม Transaction)
            $overallTotalValue = 0;
            $overallGoodnessPoint = 0;
            $processedItems = [];

            foreach ($data['items'] as $index => $item) {
                // Validate แต่ละ Item
                if (empty($item['name'])) {
                    throw new Exception("กรุณาลองใหม่, ระบุชื่อสิ่งของที่ได้รับ (รายการที่ " . ($index + 1) . ")", 400);
                }
                if (empty($item['amount']) || !is_numeric($item['amount'])) {
                    throw new Exception("กรุณาลองใหม่, ระบุจำนวนที่ได้รับให้ถูกต้อง (รายการที่ " . ($index + 1) . ")", 400);
                }
                if (empty($item['value']) || !is_numeric($item['value'])) {
                    throw new Exception("กรุณาลองใหม่, ระบุมูลค่าต่อชิ้น (รายการที่ " . ($index + 1) . ")", 400);
                }

                $qty = (int) $item['amount'];
                $pricePerUnit = (float) $item['value'];
                $itemTotalValue = $qty * $pricePerUnit;
                $itemGoodnessPoint = $itemTotalValue * 10; // คำนวณแต้มความดี x10
                $redeemPoint = isset($item['redeem_point']) && is_numeric($item['redeem_point']) ? (int) $item['redeem_point'] : 0;

                $overallTotalValue += $itemTotalValue;
                $overallGoodnessPoint += $itemGoodnessPoint;

                // เก็บข้อมูลที่จัดเตรียมไว้เพื่อไป insert ในภายหลัง
                $processedItems[] = [
                    'name' => $item['name'],
                    'qty' => $qty,
                    'price' => $pricePerUnit,
                    'item_goodness_point' => $itemGoodnessPoint,
                    'redeem_point' => $redeemPoint
                ];
            }
            // ใช้เวลาจาก PHP เพื่อความแม่นยำ
            $now = date('Y-m-d H:i:s'); 
            // เริ่ม Transaction 
            $this->Conn->beginTransaction();

            // Step 3: Insert into 'donation' table (บันทึกข้อมูลหลัก / Header)
            $sqlDonation = "INSERT INTO donation 
                            (member_id, staff_id, donation_total_value, donation_total_goodness_point, donation_description, created_at) 
                            VALUES 
                            (:member_id, :staff_id, :total_value, :total_goodness_point, :description, :created_at)";

            $stmtDonation = $this->Conn->prepare($sqlDonation);
            $stmtDonation->execute([
                ':member_id' => $memberId,
                ':staff_id' => $staffId,
                ':total_value' => $overallTotalValue,
                ':total_goodness_point' => $overallGoodnessPoint,
                ':description' => $description,
                ":created_at" => $now
            ]);

            // ดึง ID ของ donation ล่าสุดเพื่อไปใส่ในตาราง detail
            $donationId = $this->Conn->lastInsertId();

            // เตรียม Statement สำหรับ Loop Insert (เพื่อประสิทธิภาพที่ดี)
            $sqlDetail = "INSERT INTO donation_detail 
                          (donation_id, donation_detail_item_name, donation_detail_item_value, donation_detail_item_amount, donation_detail_goodness_point) 
                          VALUES 
                          (:donation_id, :name, :value, :amount, :goodness_point)";
            $stmtDetail = $this->Conn->prepare($sqlDetail);

            $sqlInventory = "INSERT INTO donation_item 
                             (donation_item_name, donation_item_category_id, donation_item_amount, donation_item_redeem_point, updated_at) 
                             VALUES 
                             (:name, 1, :qty, :redeem_point, :updated_at) 
                             ON DUPLICATE KEY UPDATE 
                             donation_item_amount = donation_item_amount + VALUES(donation_item_amount), 
                             donation_item_redeem_point = VALUES(donation_item_redeem_point),
                             updated_at = VALUES(updated_at)";
            $stmtInventory = $this->Conn->prepare($sqlInventory);

            // Step 4: Loop Insert details และ Upsert into inventory
            foreach ($processedItems as $pItem) {
                // Insert ลงตาราง detail
                $stmtDetail->execute([
                    ':donation_id' => $donationId,
                    ':name' => $pItem['name'],
                    ':value' => $pItem['price'],
                    ':amount' => $pItem['qty'],
                    ':goodness_point' => $pItem['item_goodness_point']
                ]);

                // Upsert ลงตาราง inventory (donation_item)
                $stmtInventory->execute([
                    ':name' => $pItem['name'],
                    ':qty' => $pItem['qty'],
                    ':redeem_point' => $pItem['redeem_point'],
                    ':updated_at' => date('Y-m-d H:i:s')
                ]);
            }

            // Step 5: Update member goodness point (เพิ่มแต้มความดีรวมให้ผู้ใช้งาน)
            $sqlMember = "INSERT INTO member_point 
                            (member_id, goodness_point, total_goodness_point) 
                            VALUES 
                            (:member_id, :goodness_point, :goodness_point) 
                            ON DUPLICATE KEY UPDATE
                                goodness_point = goodness_point + VALUES(goodness_point),
                                total_goodness_point = total_goodness_point + VALUES(goodness_point)";
            $stmtMember = $this->Conn->prepare($sqlMember);
            $stmtMember->execute([
                ':goodness_point' => $overallGoodnessPoint,
                ':member_id' => $memberId
            ]);

            $this->Conn->commit();

            return [
                'status' => 'success',
                'donation_id' => $donationId,
                'donation_total_value' => $overallTotalValue,
                'donation_total_goodness_point' => $overallGoodnessPoint
            ];

        } catch (PDOException $e) {
            if ($this->Conn->inTransaction()) {
                $this->Conn->rollBack();
            }
            error_log($e->getMessage());
            throw new Exception("เกิดข้อผิดพลาด, กรุณาลองใหม่", (int) $e->getCode());
        } catch (Exception $e) {
            if ($this->Conn->inTransaction()) {
                $this->Conn->rollBack();
            }
            throw new Exception($e->getMessage(), (int) $e->getCode());
        }
    }

    public function CreateDonationItems(array $data): array
    {
        try {
            // 1. Validation
            if (empty($data)) {
                throw new Exception('No data provided', 400);
            }

            if (empty($data['donation_item_name'])) {
                throw new Exception('กรุณาระบุชื่อสิ่งของที่ได้รับ', 400);
            }
            if (!isset($data['donation_item_amount']) || !is_numeric($data['donation_item_amount'])) {
                throw new Exception('กรุณาระบุจำนวนที่ได้รับให้ถูกต้อง', 400);
            }
            if (!empty($data['donation_item_category_id'])) {
                if (!isset($data['donation_item_redeem_point']) || $data['donation_item_redeem_point'] === '') {
                    throw new Exception('กรุณาระบุแต้มที่ใช้แลก หรือตรวจสอบให้ถูกต้อง', 400);
                }
                if ((int) $data['donation_item_redeem_point'] === 0) {
                    throw new Exception('กรุณาระบุแต้มที่ใช้แลก และแต้มที่ใช้แลกต้องไม่เท่ากับ 0', 400);
                }
            }

            // 2. Data Preparation
            $qty = (int) $data['donation_item_amount'];
            $redeemPoint = isset($data['donation_item_redeem_point']) && is_numeric($data['donation_item_redeem_point']) ? (int) $data['donation_item_redeem_point'] : 0;
            $categoryId = !empty($data['donation_item_category_id']) ? $data['donation_item_category_id'] : null;
            $available = isset($data['donation_item_available']) ? (int) $data['donation_item_available'] : 1;

            $image = null;
            $imageFile = isset($_FILES['donation_item_image']) ? $_FILES['donation_item_image'] : (isset($data['donation_item_image']) && is_array($data['donation_item_image']) ? $data['donation_item_image'] : null);

            if ($imageFile && is_array($imageFile) && $imageFile['error'] === UPLOAD_ERR_OK) {
                $uploadDir = dirname(__DIR__, 2) . '/public/assets/images/donation_items/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $fileExtension = strtolower(pathinfo($imageFile['name'], PATHINFO_EXTENSION));
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

                if (in_array($fileExtension, $allowedExtensions)) {
                    $image = uniqid('item_') . '.' . $fileExtension;
                    $destPath = $uploadDir . $image;

                    if (!move_uploaded_file($imageFile['tmp_name'], $destPath)) {
                        throw new Exception('ไม่สามารถบันทึกไฟล์รูปภาพได้', 500);
                    }
                } else {
                    throw new Exception('ประเภทไฟล์รูปภาพไม่รองรับ', 400);
                }
            } else if (isset($data['donation_item_image']) && is_string($data['donation_item_image']) && !empty($data['donation_item_image'])) {
                $image = $data['donation_item_image'];
            }

            $sqlInventory = "INSERT INTO donation_item 
                         (donation_item_name, donation_item_amount, donation_item_redeem_point, donation_item_category_id, donation_item_available, donation_item_image, updated_at) 
                         VALUES 
                         (:name, :qty, :redeem_point, :category_id, :available, :image, NOW()) 
                         ON DUPLICATE KEY UPDATE 
                         donation_item_amount = donation_item_amount + :qty_update, 
                         donation_item_redeem_point = :redeem_point_update,
                         donation_item_category_id = :category_id_update,
                         donation_item_available = :available_update,
                         donation_item_image = COALESCE(:image_update, donation_item_image),
                         updated_at = NOW()";

            $stmtInventory = $this->Conn->prepare($sqlInventory);
            $stmtInventory->execute([
                ':name' => $data['donation_item_name'],
                ':qty' => $qty,
                ':redeem_point' => $redeemPoint,
                ':category_id' => $categoryId,
                ':available' => $available,
                ':image' => $image,
                ':qty_update' => $qty,
                ':redeem_point_update' => $redeemPoint,
                ':category_id_update' => $categoryId,
                ':available_update' => $available,
                ':image_update' => $image
            ]);

            $data['status'] = 'success';

            return $data;

        } catch (PDOException $e) {
            throw new DatabaseException($e->getMessage(), (int) $e->getCode());
        } catch (Exception $e) {
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

    public function GetAllDonationItem(array $query): array
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


            $sql = "SELECT *
                    FROM donation_item
                    $whereSql 
                    ORDER BY donation_item_id DESC";
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

    public function GetAvailableDonationItem(array $query): array
    {
        try {

            $where = ["d.donation_item_available = 1"];
            $params = [];

            if (!empty($query['search'])) {
                $where[] = '(d.donation_item_name LIKE :q OR dc.donation_item_category_name LIKE :q)';
                $params[':q'] = '%' . $query['search'] . '%';
            }

            $whereSql = !empty($where) ? ' WHERE ' . implode(' AND ', $where) : '';

            $sqlCount = "SELECT COUNT(*) AS total FROM donation_item d
                         LEFT JOIN donation_item_category dc ON d.donation_item_category_id = dc.donation_item_category_id
                         $whereSql";
            $stmtCount = $this->Conn->prepare($sqlCount);
            foreach ($params as $k => $v)
                $stmtCount->bindValue($k, $v);
            $stmtCount->execute();
            $total = (int) $stmtCount->fetch(PDO::FETCH_ASSOC)['total'];

            $sql = "SELECT d.*, dc.donation_item_category_name
                    FROM donation_item d
                    LEFT JOIN donation_item_category dc ON d.donation_item_category_id = dc.donation_item_category_id
                    $whereSql
                    ORDER BY d.donation_item_id DESC";
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

    public function GetCategorisedDonationItem(array $query): array
    {
        try {

            $where = ["donation_item_category_id != 1"];
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


            $sql = "SELECT *
                    FROM donation_item
                    $whereSql 
                    ORDER BY updated_at DESC";
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

    public function GetUncategorisedDonationItem(array $query): array
    {
        try {

            $where = ["donation_item_category_id = 1"];
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

            $sql = "SELECT *
                    FROM donation_item
                    $whereSql 
                    ORDER BY updated_at DESC";
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

    public function UpdateDonationItem($id, $data): array
    {
        try {
            if (empty($id) || (empty($data) && !is_array($data))) {
                throw new Exception('Bad Request: ข้อมูลไม่ครบถ้วน', 400);
            }
            if (array_key_exists('donation_item_name', $data) && $data['donation_item_name'] === '') {
                throw new Exception('กรุณาระบุชื่อสิ่งของที่ได้รับ', 400);
            }

            if (array_key_exists('donation_item_available', $data) && $data['donation_item_available'] == 1) {
                if (!isset($data['donation_item_redeem_point']) || $data['donation_item_redeem_point'] === '' || $data['donation_item_redeem_point'] === 0 || $data['donation_item_redeem_point'] === '0') {
                    throw new Exception('กรุณาระบุแต้มที่ใช้แลก หรือตรวจสอบให้ถูกต้อง ก่อนเปลี่ยนสถานะเป็นเปิดแลกพร้อมใช้งาน', 400);
                }
            }

            $imageFile = isset($_FILES['donation_item_image']) ? $_FILES['donation_item_image'] : (isset($data['donation_item_image']) && is_array($data['donation_item_image']) ? $data['donation_item_image'] : null);

            if ($imageFile && is_array($imageFile) && $imageFile['error'] === UPLOAD_ERR_OK) {
                $uploadDir = dirname(__DIR__, 2) . '/public/assets/images/donation_items/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                // ตรวจสอบรูปภาพเดิมในฐานข้อมูล
                $stmtCheck = $this->Conn->prepare("SELECT donation_item_image FROM donation_item WHERE donation_item_id = :id");
                $stmtCheck->execute([':id' => $id]);
                $oldItem = $stmtCheck->fetch(PDO::FETCH_ASSOC);

                if ($oldItem && !empty($oldItem['donation_item_image'])) {
                    $oldImagePath = $uploadDir . $oldItem['donation_item_image'];
                    if (file_exists($oldImagePath) && is_file($oldImagePath)) {
                        unlink($oldImagePath); // ลบรูปเก่าออกจากโฟลเดอร์
                    }
                }

                $fileExtension = strtolower(pathinfo($imageFile['name'], PATHINFO_EXTENSION));
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

                if (in_array($fileExtension, $allowedExtensions)) {
                    $image = uniqid('item_') . '.' . $fileExtension;
                    $destPath = $uploadDir . $image;

                    if (!move_uploaded_file($imageFile['tmp_name'], $destPath)) {
                        throw new Exception('ไม่สามารถบันทึกไฟล์รูปภาพได้', 500);
                    }
                    $data['donation_item_image'] = $image;
                } else {
                    throw new Exception('ประเภทไฟล์รูปภาพไม่รองรับ', 400);
                }
            } elseif (isset($data['donation_item_image']) && is_array($data['donation_item_image'])) {
                unset($data['donation_item_image']);
            }

            $data['updated_at'] = date('Y-m-d H:i:s');

            $setClauses = [];
            $updateData = [];
            foreach ($data as $column => $value) {
                // เซ็ตเป็น NULL ถ้ารับ donation_item_category_id เป็นค่าว่างหรือสตริงว่าง
                if ($column === 'donation_item_category_id' && ($value === '' || $value === null)) {
                    $setClauses[] = "`{$column}` = :{$column}";
                    $updateData[$column] = null;
                    continue;
                }

                // กรองเฉพาะค่าที่ถูกส่งมาเพื่ออัปเดต
                if (isset($value)) {
                    $setClauses[] = "`{$column}` = :{$column}";
                    $updateData[$column] = $value;
                }
            }

            if (empty($setClauses)) {
                return ['data' => $data, 'total' => 0]; // ไม่มีข้อมูลให้เปลี่ยนแปลง
            }

            $setClauseString = implode(', ', $setClauses);

            $sql = "UPDATE donation_item SET {$setClauseString} WHERE donation_item_id = :donation_item_id";

            $stmt = $this->Conn->prepare($sql);
            $stmt->execute(array_merge($updateData, ['donation_item_id' => $id]));
            $result = $stmt->rowCount();

            return ['data' => $data, 'total' => $result];
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage(), 500);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function GetDonationItemCategories(array $query = []): array
    {
        try {
            $where = [];
            $params = [];

            if (!empty($query['search'])) {
                $where[] = 'c.donation_item_category_name LIKE :q';
                $params[':q'] = '%' . $query['search'] . '%';
            }

            $whereSql = !empty($where) ? ' WHERE ' . implode(' AND ', $where) : '';

            $sql = "SELECT 
                        c.*,
                        COUNT(i.donation_item_id) as item_count
                    FROM donation_item_category c
                    LEFT JOIN donation_item i ON c.donation_item_category_id = i.donation_item_category_id
                    $whereSql 
                    GROUP BY c.donation_item_category_id
                    ORDER BY c.donation_item_category_id ASC";

            $stmt = $this->Conn->prepare($sql);
            $stmt->execute($params);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return ['data' => $rows, 'total' => count($rows)];
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage(), 500);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function GetDonationItemByCategoryId($cid, array $query = []): array
    {
        try {
            // บังคับกรองตามหมวดหมู่เสมอ
            $where = ['i.donation_item_category_id = :cid'];
            $params = [':cid' => $cid];

            if (!empty($query['search'])) {
                $where[] = '(i.donation_item_name LIKE :q OR c.donation_item_category_name LIKE :q)';
                $params[':q'] = '%' . $query['search'] . '%';
            }

            $whereSql = ' WHERE ' . implode(' AND ', $where);

            $sql = "SELECT 
                        i.*,
                        c.donation_item_category_name
                    FROM donation_item i
                    LEFT JOIN donation_item_category c ON i.donation_item_category_id = c.donation_item_category_id
                    $whereSql
                    ORDER BY i.updated_at DESC";

            $stmt = $this->Conn->prepare($sql);
            $stmt->execute($params);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return ['data' => $rows, 'total' => count($rows)];
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage(), 500);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function CreateDonationItemCategory(array $data): array
    {
        try {
            if (empty($data) || !is_array($data)) {
                throw new Exception('Bad Request: ข้อมูลไม่ครบถ้วน', 400);
            }

            if (empty($data['donation_item_category_name'])) {
                throw new Exception('กรุณาระบุชื่อหมวดหมู่สิ่งของบริจาค', 400);
            }

            $setClauses = [];
            $updateData = [];
            foreach ($data as $column => $value) {
                if (isset($value)) {
                    $setClauses[] = "`{$column}` = :{$column}";
                    $updateData[$column] = $value;
                }
            }

            $setClauseString = implode(', ', $setClauses);
            $sql = "INSERT INTO donation_item_category SET {$setClauseString}";

            $stmt = $this->Conn->prepare($sql);
            $stmt->execute($updateData);
            $id = $this->Conn->lastInsertId();

            return ['donation_item_category_id' => $id, 'donation_item_category_name' => $data['donation_item_category_name']];
        } catch (PDOException $e) {
            $error = DatabaseException::handle($e);
            throw new Exception($error['message'], $error['code']);
            // throw new Exception("Database error: " . $e->getMessage(), 500);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function UpdateDonationItemCategory($id, $data): array
    {
        try {
            if (empty($id) || (empty($data) && !is_array($data))) {
                throw new Exception('Bad Request: ข้อมูลไม่ครบถ้วน', 400);
            }

            $setClauses = [];
            $updateData = [];
            foreach ($data as $column => $value) {
                if (isset($value)) {
                    $setClauses[] = "`{$column}` = :{$column}";
                    $updateData[$column] = $value;
                }
            }

            if (empty($setClauses)) {
                return ['data' => $data, 'total' => 0]; // ไม่มีข้อมูลให้เปลี่ยนแปลง
            }

            $setClauseString = implode(', ', $setClauses);
            $sql = "UPDATE donation_item_category SET {$setClauseString} WHERE donation_item_category_id = :donation_item_category_id";

            $stmt = $this->Conn->prepare($sql);
            $stmt->execute(array_merge($updateData, ['donation_item_category_id' => $id]));
            $result = $stmt->rowCount();

            return ['data' => $data, 'total' => $result];
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage(), 500);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function DeleteDonationItem(array $data): array
    {
        if (empty($data['donation_item_ids']) || !is_array($data['donation_item_ids'])) {
            throw new Exception('กรุณาระบุรายการของรางวัลที่ต้องการลบ', 400);
        }

        $ids = array_filter($data['donation_item_ids']);
        if (empty($ids)) {
            return ['data' => $data, 'total' => 0];
        }

        try {
            $this->Conn->beginTransaction();

            // ลบรูปภาพเดิมออกจากเซิร์ฟเวอร์
            $placeholders = str_repeat('?,', count($ids) - 1) . '?';
            $sqlImg = "SELECT donation_item_image FROM donation_item WHERE donation_item_id IN ($placeholders)";
            $stmtImg = $this->Conn->prepare($sqlImg);
            $stmtImg->execute($ids);
            $images = $stmtImg->fetchAll(PDO::FETCH_COLUMN);

            $uploadDir = dirname(__DIR__, 2) . '/public/assets/images/donation_items/';
            foreach ($images as $img) {
                if (!empty($img) && file_exists($uploadDir . $img)) {
                    unlink($uploadDir . $img);
                }
            }

            $sql = "DELETE FROM donation_item WHERE donation_item_id IN ($placeholders)";
            $stmt = $this->Conn->prepare($sql);
            $stmt->execute($ids);
            $rowCount = $stmt->rowCount();

            $this->Conn->commit();

            return ['data' => $data, 'total' => $rowCount];
        } catch (Exception $e) {
            if ($this->Conn->inTransaction()) {
                $this->Conn->rollBack();
            }
            throw new Exception("Database error: " . $e->getMessage(), 500);
        }
    }

    public function ToggleDonationItemAvailable(array $data): array
    {
        if (empty($data['donation_item_ids']) || !is_array($data['donation_item_ids'])) {
            throw new Exception('กรุณาระบุรายการของรางวัลที่ต้องการแก้ไขสถานะ', 400);
        }

        $ids = array_filter($data['donation_item_ids']);
        if (empty($ids)) {
            return ['data' => $data, 'total' => 0];
        }

        try {
            $this->Conn->beginTransaction();

            $placeholders = str_repeat('?,', count($ids) - 1) . '?';

            // ตรวจสอบว่าไอเท็มที่กำลังจะเปลี่ยนจาก 0 (ไม่เปิด) -> 1 (เปิด) มีการตั้งค่าคะแนนหรือยัง
            $sqlCheck = "SELECT donation_item_id, donation_item_redeem_point, donation_item_available FROM donation_item WHERE donation_item_id IN ($placeholders)";
            $stmtCheck = $this->Conn->prepare($sqlCheck);
            $stmtCheck->execute(array_values($ids));
            $items = $stmtCheck->fetchAll(PDO::FETCH_ASSOC);

            foreach ($items as $item) {
                if ($item['donation_item_available'] == 0 && (empty($item['donation_item_redeem_point']) || $item['donation_item_redeem_point'] <= 0)) {
                    throw new Exception("ไม่สามารถเปิดสถานะได้: ยังไม่ได้กำหนดแต้มที่ใช้แลก", 400);
                }
            }

            $sql = "UPDATE donation_item SET donation_item_available = NOT donation_item_available, updated_at = NOW() WHERE donation_item_id IN ($placeholders)";
            $stmt = $this->Conn->prepare($sql);
            $stmt->execute(array_values($ids));
            $rowCount = $stmt->rowCount();

            $this->Conn->commit();

            return ['data' => $data, 'total' => $rowCount];
        } catch (PDOException $e) {
            if ($this->Conn->inTransaction()) {
                $this->Conn->rollBack();
            }
            throw new Exception("Database error: " . $e->getMessage(), 500);
        } catch (Exception $e) {
            if ($this->Conn->inTransaction()) {
                $this->Conn->rollBack();
            }
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function BulkUpdateDonationItemCategory(array $data): array
    {
        if (empty($data['donation_item_ids']) || !is_array($data['donation_item_ids'])) {
            throw new Exception('กรุณาระบุรายการของรางวัลที่ต้องการย้ายหมวดหมู่', 400);
        }

        $ids = array_filter($data['donation_item_ids']);
        if (empty($ids)) {
            return ['data' => $data, 'total' => 0];
        }

        // กรณีที่เลือก "[ ไม่จัดหมวดหมู่ ]" (value="" หรือ 'none' จากฝั่งหน้าบ้าน) จะถูกตั้งเป็น null
        $categoryId = !empty($data['donation_item_category_id']) && $data['donation_item_category_id'] !== 'none' ? (int) $data['donation_item_category_id'] : null;

        try {
            $this->Conn->beginTransaction();

            $placeholders = str_repeat('?,', count($ids) - 1) . '?';
            $sql = "UPDATE donation_item SET donation_item_category_id = ?, updated_at = NOW() WHERE donation_item_id IN ($placeholders)";

            $stmt = $this->Conn->prepare($sql);

            $params = [$categoryId];
            foreach ($ids as $id) {
                $params[] = (int) $id;
            }

            $stmt->execute($params);
            $rowCount = $stmt->rowCount();

            $this->Conn->commit();

            return ['data' => $data, 'total' => $rowCount];
        } catch (PDOException $e) {
            if ($this->Conn->inTransaction()) {
                $this->Conn->rollBack();
            }
            throw new Exception("Database error: " . $e->getMessage(), 500);
        } catch (Exception $e) {
            if ($this->Conn->inTransaction()) {
                $this->Conn->rollBack();
            }
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }
}
