<?php
namespace App\Model;

use App\Utils\Database;
use App\Utils\DatabaseException;
use Exception;
use PDO;
use PDOException;

class MemberItemModel
{
    private static $Database;
    private $Conn;

    public function __construct()
    {
        self::$Database = new Database();
        $this->Conn = self::$Database->connect();
    }
    public function RedeemDonationItem(array $data, array $staff): array
    {
        try {
            $this->Conn->beginTransaction();

            $memberId = (int) $data['member_id'];
            $staffId = (int) $staff['user_data']->member_id;

            // สร้าง Array สำหรับลูป หากเป็นการส่งค่าเข้ามาหลายชิ้น
            $itemsToProcess = [];
            if (isset($data['items']) && is_array($data['items'])) {
                $itemsToProcess = $data['items'];
            } else {
                // Fallback สำหรับการรับค่าส่งแบบเดิม (ชิ้นเดียว)
                $itemsToProcess[] = [
                    'donation_item_id' => $data['donation_item_id'],
                    'member_item_qty' => $data['member_item_qty'] ?? 1
                ];
            }

            // Lock and fetch member
            $memberSql = "SELECT member_waste_point as total_points FROM member WHERE member_id = :member_id FOR UPDATE";
            $memberStmt = $this->Conn->prepare($memberSql);
            $memberStmt->bindValue(':member_id', $memberId, PDO::PARAM_INT);
            $memberStmt->execute();
            $member = $memberStmt->fetch(PDO::FETCH_ASSOC);

            if (!$member) {
                throw new Exception("Member not found", 404);
            }

            $overallTotalPoints = 0;
            $insertedIds = [];

            // เตรียมคำสั่ง SQL
            $itemSql = "SELECT * 
                        FROM donation_item 
                        WHERE donation_item_id = :donation_item_id FOR UPDATE";
            $itemStmt = $this->Conn->prepare($itemSql);

            $insertSql = "INSERT INTO member_item (member_id, staff_id, donation_item_id, member_item_qty, member_item_point_used, created_at) VALUES (:member_id, :staff_id, :donation_item_id, :qty, :points, :created_at)";
            $insertStmt = $this->Conn->prepare($insertSql);

            $updateItemSql = "UPDATE donation_item SET donation_item_amount = donation_item_amount - :qty WHERE donation_item_id = :donation_item_id";
            $updateItemStmt = $this->Conn->prepare($updateItemSql);

            foreach ($itemsToProcess as $processItem) {
                $donationItemId = (int) $processItem['donation_item_id'];
                $qty = isset($processItem['member_item_qty']) && $processItem['member_item_qty'] !== '' ? (int) $processItem['member_item_qty'] : 1;

                // ดึงข้อมูลสินค้า
                $itemStmt->bindValue(':donation_item_id', $donationItemId, PDO::PARAM_INT);
                $itemStmt->execute();
                $item = $itemStmt->fetch(PDO::FETCH_ASSOC);

                if (!$item) {
                    throw new Exception("ไม่พบรายการของรางวัลในระบบ (ID: $donationItemId)", 404);
                }

                // เช็คสต็อก
                if ($item['donation_item_amount'] < $qty) {
                    throw new Exception("สต็อกของรางวัล '{$item['donation_item_name']}' ไม่เพียงพอ", 400);
                }

                $pointsPerItem = !empty($item['donation_item_redeem_point']) ? $item['donation_item_redeem_point'] : 0;
                $itemTotalPoints = $pointsPerItem * $qty;
                $overallTotalPoints += $itemTotalPoints;

                // บันทึกประวัติการแลก
                $insertStmt->bindValue(':member_id', $memberId, PDO::PARAM_INT);
                $insertStmt->bindValue(':staff_id', $staffId, PDO::PARAM_INT);
                $insertStmt->bindValue(':donation_item_id', $donationItemId, PDO::PARAM_INT);
                $insertStmt->bindValue(':qty', $qty, PDO::PARAM_INT);
                $insertStmt->bindValue(':points', $itemTotalPoints, PDO::PARAM_INT);
                $insertStmt->bindValue(':created_at', date('Y-m-d H:i:s'), PDO::PARAM_STR);
                $insertStmt->execute();

                $insertedIds[] = (int) $this->Conn->lastInsertId();

                // ตัดสต็อก
                $updateItemStmt->bindValue(':qty', $qty, PDO::PARAM_INT);
                $updateItemStmt->bindValue(':donation_item_id', $donationItemId, PDO::PARAM_INT);
                $updateItemStmt->execute();
            }

            // เช็คว่าแต้มรวมทั้งหมดเพียงพอหรือไม่
            if ($member['total_points'] < $overallTotalPoints) {
                throw new Exception("แต้มของคุณไม่เพียงพอสำหรับการแลกของรางวัลทั้งหมด", 400);
            }

            // Update member points
            $updateMemberSql = "UPDATE member 
                               SET member_waste_point = GREATEST(0, member_waste_point - :points)
                               WHERE member_id = :member_id";
            $updateMemberStmt = $this->Conn->prepare($updateMemberSql);
            $updateMemberStmt->bindValue(':points', $overallTotalPoints, PDO::PARAM_INT);
            $updateMemberStmt->bindValue(':member_id', $memberId, PDO::PARAM_INT);
            $updateMemberStmt->execute();

            $this->Conn->commit();

            // ตอบกลับไปเป็นรายการ Array กรณีแลกหลายชิ้น
            if (count($insertedIds) === 1) {
                return $this->GetById($insertedIds[0]);
            }
            return ['inserted_ids' => $insertedIds, 'total_points_used' => $overallTotalPoints];
        } catch (PDOException $e) {
            $this->Conn->rollBack();
            throw new DatabaseException($e->getMessage(), (int) $e->getCode());
        } catch (Exception $e) {
            $this->Conn->rollBack();
            throw $e;
        }
    }

    public function GetById(int $id): array
    {
        try {
            $sql = "SELECT
                        mi.*,
                        m.member_name,
                        di.donation_item_name,
                        s.member_name as staff_name
                    FROM member_item mi
                    LEFT JOIN member m ON mi.member_id = m.member_id
                    LEFT JOIN donation_item di ON mi.donation_item_id = di.donation_item_id
                    LEFT JOIN member s ON mi.staff_id = s.member_id
                    WHERE mi.member_item_id = :id";
            $stmt = $this->Conn->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$row) {
                throw new Exception('Member item not found', 404);
            }
            return $row;
        } catch (PDOException $e) {
            throw new DatabaseException($e->getMessage(), (int) $e->getCode());
        }
    }
}
