<?php
namespace App\Model;

use App\Utils\Database;
use Exception;
use PDO;
use PDOException;

class WasteTransactionModel
{
    private static $Database;
    private $Conn;

    public function __construct()
    {
        self::$Database = new Database();
        $this->Conn = self::$Database->connect();
    }

    public function GetAllTransaction($query): array
    {
        try {
            $whereClauses = [];
            $params = [];

            // กรองตามช่วงวันที่ (ถ้ามี)
            if (!empty($query['start_date'])) {
                $whereClauses[] = "DATE(w.waste_transaction_date) >= :start_date";
                $params[':start_date'] = $query['start_date'];
            }
            if (!empty($query['end_date'])) {
                $whereClauses[] = "DATE(w.waste_transaction_date) <= :end_date";
                $params[':end_date'] = $query['end_date'];
            }
            if (!empty($query['date'])) {
                $whereClauses[] = "DATE(w.waste_transaction_date) = :date";
                $params[':date'] = $query['date'];
            }
            // กรองตามปีที่ระบุ (เช่น 2025)
            if (!empty($query['year'])) {
                $whereClauses[] = "YEAR(w.waste_transaction_date) = :year";
                $params[':year'] = $query['year'];
            }
            // กรองตามเดือนที่ระบุ (1-12)
            if (!empty($query['month'])) {
                $whereClauses[] = "MONTH(w.waste_transaction_date) = :month";
                $params[':month'] = $query['month'];
            }
            // กรองตามหมวดหมู่หรือชนิดขยะ
            if (!empty($query['type'])) {
                $whereClauses[] = "w.waste_type_id = :type_id";
                $params[':type_id'] = $query['type'];
            }
            // กรองตามเจ้าหน้าที่ หรือ ผู้ฝาก
            if (!empty($query['operater'])) {
                $whereClauses[] = "w.operater_id = :operater_id";
                $params[':operater_id'] = $query['operater'];
            }
            if (!empty($query['member'])) {
                $whereClauses[] = "w.member_id = :member_id";
                $params[':member_id'] = $query['member'];
            }
            // กรองตามคณะ (Faculty) ของผู้ฝาก
            if (!empty($query['faculty'])) {
                $whereClauses[] = "a.faculty_id = :faculty";
                $params[':faculty'] = $query['faculty'];
            }

            // ค้นหาจากชื่อ, รหัสประจำตัว, เบอร์โทร หรืออีเมลของผู้ฝาก
            if (!empty($query['member_search'])) {
                $whereClauses[] = "(a.member_name LIKE :member_search 
                                    OR a.member_personal_id LIKE :member_search 
                                    OR a.member_phone LIKE :member_search 
                                    OR a.member_email LIKE :member_search)";
                $params[':member_search'] = "%" . $query['member_search'] . "%";
            }

            if (!empty($query['staff_search'])) {
                $whereClauses[] = "(s.member_name LIKE :staff_search 
                                    OR s.member_personal_id LIKE :staff_search 
                                    OR s.member_phone LIKE :staff_search 
                                    OR s.member_email LIKE :staff_search)";
                $params[':staff_search'] = "%" . $query['operater_search'] . "%";
            }

            $whereSql = !empty($whereClauses) ? " WHERE " . implode(" AND ", $whereClauses) : "";

            $sql = "SELECT 
                        w.*,a.member_id, a.member_name, a.member_personal_id, a.member_phone,a.member_email,
                        f.faculty_id, f.faculty_name,
                        t.waste_type_name, c.waste_category_name,
                        s.member_id AS staff_id,s.member_name AS staff_name, s.member_phone AS staff_tel, 
                        s.member_email AS staff_email, s.member_personal_id AS staff_personal_id
                    FROM 
                        waste_transaction w
                    LEFT JOIN member a ON w.member_id = a.member_id
                    LEFT JOIN faculty f ON a.faculty_id = f.faculty_id
                    LEFT JOIN member s ON w.staff_id = s.member_id
                    LEFT JOIN waste_type t ON w.waste_transaction_waste_type = t.waste_type_id
                    LEFT JOIN waste_category c ON t.waste_category_id = c.waste_category_id
                    {$whereSql}
                    ORDER BY w.created_at DESC;
                ";

            $isPagination = isset($query['page']) && isset($query['limit']);

            if ($isPagination) {
                $page = (int) $query['page'];
                $limit = (int) $query['limit'];
                $offset = ($page - 1) * $limit;

                $sql .= " LIMIT :limit OFFSET :offset";
            }

            $stmt = $this->Conn->prepare($sql);

            // Bind ค่าสำหรับการกรอง
            foreach ($params as $key => $val) {
                $stmt->bindValue($key, $val);
            }

            if ($isPagination) {
                $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
                $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            }

            $stmt->execute();
            $deposits = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($isPagination) {
                // ต้อง JOIN member a ด้วยหากมีการกรองตาม faculty_id ในการนับจำนวนทั้งหมด
                $sqlCount = "SELECT COUNT(*) AS allDeposit 
                             FROM waste_transaction w 
                             LEFT JOIN member a ON w.member_id = a.member_id 
                             $whereSql";
                $stmtCount = $this->Conn->prepare($sqlCount);
                foreach ($params as $key => $val) {
                    $stmtCount->bindValue($key, $val);
                }
                $stmtCount->execute();
                $total = $stmtCount->fetch(PDO::FETCH_ASSOC)['allDeposit'];
            } else {
                $total = count($deposits);
            }

            return ["data" => $deposits, "total" => $total];

        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage(), 500);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function GetAllTransactionByStaffId($query, $staffData): array
    {
        try {
            $whereClauses = ["w.staff_id = :staff_id"];
            $params = [':staff_id' => $staffData["user_data"]->member_id];

            // กรองตามช่วงวันที่
            if (!empty($query['start_date'])) {
                $whereClauses[] = "DATE(w.waste_transaction_date) >= :start_date";
                $params[':start_date'] = $query['start_date'];
            }
            if (!empty($query['end_date'])) {
                $whereClauses[] = "DATE(w.waste_transaction_date) <= :end_date";
                $params[':end_date'] = $query['end_date'];
            }
            if (!empty($query['date'])) {
                $whereClauses[] = "DATE(w.waste_transaction_date) = :date";
                $params[':date'] = $query['date'];
            }
            if (!empty($query['year'])) {
                $whereClauses[] = "YEAR(w.waste_transaction_date) = :year";
                $params[':year'] = $query['year'];
            }
            if (!empty($query['month'])) {
                $whereClauses[] = "MONTH(w.waste_transaction_date) = :month";
                $params[':month'] = $query['month'];
            }

            if (!empty($query['type'])) {
                $whereClauses[] = "w.waste_transaction_waste_type = :type_id";
                $params[':type_id'] = $query['type'];
            }

            // กรองตามผู้ฝาก หรือ คณะ
            if (!empty($query['member'])) {
                $whereClauses[] = "w.member_id = :member_id";
                $params[':member_id'] = $query['member'];
            }
            if (!empty($query['faculty'])) {
                $whereClauses[] = "a.faculty_id = :faculty";
                $params[':faculty'] = $query['faculty'];
            }

            // ค้นหาข้อมูลผู้ฝาก (ชื่อ, รหัส, เบอร์โทร, อีเมล)
            if (!empty($query['member_search'])) {
                $whereClauses[] = "(a.member_name LIKE :member_search 
                                    OR a.member_personal_id LIKE :member_search 
                                    OR a.member_phone LIKE :member_search 
                                    OR a.member_email LIKE :member_search)";
                $params[':member_search'] = "%" . $query['member_search'] . "%";
            }

            $whereSql = " WHERE " . implode(" AND ", $whereClauses);

            $sql = "SELECT 
                        w.*, a.member_id, a.member_name, a.member_personal_id, 
                        a.member_phone, a.member_email,f.faculty_id, 
                        f.faculty_name, c.waste_category_name, t.waste_type_name
                    FROM 
                        waste_transaction w
                    LEFT JOIN member a ON w.member_id = a.member_id
                    LEFT JOIN faculty f ON a.faculty_id = f.faculty_id
                    LEFT JOIN waste_type t ON w.waste_transaction_waste_type = t.waste_type_id
                    LEFT JOIN waste_category c ON t.waste_category_id = c.waste_category_id
                    $whereSql
                    ORDER BY w.created_at DESC";

            $isPagination = isset($query['page']) && isset($query['limit']);

            if ($isPagination) {
                $page = (int) $query['page'];
                $limit = (int) $query['limit'];
                $offset = ($page - 1) * $limit;

                $sql .= " LIMIT :limit OFFSET :offset";
            }

            $stmt = $this->Conn->prepare($sql);

            // Bind ค่าทั้งหมด
            foreach ($params as $key => $val) {
                $stmt->bindValue($key, $val);
            }

            if ($isPagination) {
                $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
                $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            }

            $stmt->execute();
            $deposits = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($isPagination) {
                $sqlCount = "SELECT COUNT(*) AS allDeposit FROM waste_transaction w LEFT JOIN member a ON w.member_id = a.member_id $whereSql";
                $stmtCount = $this->Conn->prepare($sqlCount);
                foreach ($params as $key => $val) {
                    $stmtCount->bindValue($key, $val);
                }
                $stmtCount->execute();
                $total = $stmtCount->fetch(PDO::FETCH_ASSOC)['allDeposit'];
            } else {
                $total = count($deposits);
            }

            return ["data" => $deposits, "total" => $total];

        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage(), 500);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function CreateWasteTransaction(array $data, $staffData): array
    {
        try {
            if (!is_array($data)) {
                throw new Exception('Invalid data format', 400);
            }

            if (empty($data['depositor_member'])) {
                // error_log("ERROR : waste_type is missing");
                throw new Exception('กรุณาลองอีกครั้ง, ระบุข้อมูลผู้ทำการฝาก', 400);
            }

            if (empty($data['waste_type_id'])) {
                // error_log("ERROR : waste_type is missing");
                throw new Exception('กรุณาลองอีกครั้ง, เลือกประเภทขยะ', 400);
            }

            if (!isset($data['deposit_weight'])) {
                error_log("ERROR : transaction_deposit_weight missing");
                throw new Exception('กรุณาลองอีกครั้ง, ระบุน้ำหนักที่ฝาก', 400);
            }

            /* Start SQL Transaction */
            $this->Conn->beginTransaction();

            $user = self::GetDepositorAccount($this->Conn, $data["depositor_member"]);
            $rateResult = self::GetWasteTypeRate($this->Conn, $data["waste_type_id"]);

            $payload = [];
            $payload["member_id"] = $user["member_id"];
            $payload["faculty_id"] = $user["faculty_id"];
            $payload["staff_id"] = $staffData["user_data"]->member_id;

            // if ($user["member_role"] === "user") {
            //     $payload["waste_transaction_from"] = 1;
            // } else {
            //     $payload["waste_transaction_from"] = 2;
            // }

            $payload["waste_transaction_waste_type"] = $data["waste_type_id"];
            $payload["waste_transaction_weight"] = $data["deposit_weight"];
            // $payload["waste_transaction_rate"] = $rateResult["waste_type_price"];

            $value = $rateResult["waste_type_price"] * $data["deposit_weight"];
            $integer_point = (int) floor($value);

            // $payload["waste_transaction_value"] = $value;
            $payload["waste_transaction_member_point"] = $integer_point;
            $payload["waste_transaction_faculty_fraction"] = $value - $integer_point;
            // $payload["waste_transaction_status"] = 1;
            $payload["waste_transaction_date"] = date('Y-m-d');
            $payload["created_at"] = date('Y-m-d H:i:s');

            error_log("TRANSACTION DEPOSIT_WASTE DATA : " . print_r($payload, 1));

            $setClauses = [];
            foreach ($payload as $column => $value) {
                if (isset($value) && $value !== '') {
                    $setClauses[] = "`{$column}` = :{$column}";
                }
            }
            $setClauseString = implode(', ', $setClauses);

            $sql =
                "INSERT INTO 
                    waste_transaction
                SET
                    {$setClauseString}
                ";
            $stmt = $this->Conn->prepare($sql);
            $stmt->execute($payload);
            $insertedId = $this->Conn->lastInsertId();

            if (empty($user["member_id"]) || empty($user["faculty_id"])) {
                throw new Exception("ERROR : Check faculty's user", 400);
            }

            $updatedUser = self::UpdateMemberPoint($this->Conn, $user["member_id"], $integer_point);
            $updatedFaculty = self::CreateFacultyPointHistory($this->Conn, $user["faculty_id"], $payload["waste_transaction_faculty_fraction"]);

            $this->Conn->commit();

            return [
                'transaction_id' => $insertedId,
                'total_member_point' => $updatedUser['member_waste_point'],
                'total_faculty_point' => $updatedFaculty['faculty_point'] ?? null
            ];

        } catch (PDOException $e) {
            if ($this->Conn->inTransaction()) {
                $this->Conn->rollBack();
            }
            error_log("ERROR PDO : " . $e->getMessage());
            throw new Exception("Database error: " . $e->getMessage(), 500);
        } catch (Exception $e) {
            if ($this->Conn->inTransaction()) {
                $this->Conn->rollBack();
            }
            error_log("ERROR : " . $e->getMessage());
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    // public function UpdateWasteTransaction($id, $data): mixed
    // {
    //     try {
    //         if ((empty($data) && !is_array($data)) || empty($id)) {
    //             throw new Exception('Bad Request =(', 400);
    //         }

    //         $setClauses = [];
    //         $updateData = [];
    //         foreach ($data as $column => $value) {
    //             if (isset($value)) {
    //                 $setClauses[] = "`{$column}` = :{$column}";
    //                 $updateData[$column] = $value;
    //             }
    //         }

    //         if (empty($setClauses)) {
    //             return 0; // ไม่มีข้อมูลให้เปลี่ยนแปลง
    //         }

    //         $setClauseString = implode(', ', $setClauses);

    //         $sql =
    //             "UPDATE waste_deposit_transaction
    //             SET 
    //                 {$setClauseString}
    //             WHERE
    //                 transaction_deposit_id = :transaction_deposit_id
    //             ";

    //         $stmt = $this->Conn->prepare($sql);
    //         // รวม array ข้อมูลที่จะอัปเดตเข้ากับ ID สำหรับ WHERE clause
    //         $stmt->execute(array_merge($updateData, ['transaction_deposit_id' => $id]));

    //         $result = $stmt->rowCount();
    //         return $result;
    //     } catch (PDOException $e) {
    //         throw new Exception("Database error: " . $e->getMessage(), 500);
    //     } catch (Exception $e) {
    //         throw new Exception($e->getMessage(), $e->getCode() ?: 400);
    //     }
    // }

    public function DeleteWasteTransactionById($id): int
    {
        try {
            if (empty($id)) {
                throw new Exception('ID is required for deletion', 400);
            }

            $sql = "DELETE FROM waste_transaction WHERE waste_transaction_id = :waste_transaction_id";
            $stmt = $this->Conn->prepare($sql);
            $stmt->execute(['waste_transaction_id' => $id]);

            return $stmt->rowCount();
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage(), 500);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function DeleteWasteTransaction(array $data): int
    {
        // เปลี่ยน Key เป็น transaction_deposit_ids ให้สื่อความหมายตรงตาราง
        if (empty($data['waste_transaction_ids'] ?? []) || !is_array($data['waste_transaction_ids'])) {
            throw new Exception('Bad Request: waste_transaction_ids is required and must be an array', 400);
        }

        $ids = array_filter($data['waste_transaction_ids']);

        if (empty($ids)) {
            return 0;
        }

        try {

            $this->Conn->beginTransaction();

            $placeholders = str_repeat('?,', count($ids) - 1) . '?';
            $sql = "DELETE FROM waste_transaction WHERE waste_transaction_id IN ($placeholders)";

            $stmt = $this->Conn->prepare($sql);

            foreach ($ids as $index => $id) {
                $stmt->bindValue($index + 1, $id, PDO::PARAM_INT);
            }

            $stmt->execute();
            $rowCount = $stmt->rowCount();

            $this->Conn->commit();

            return $rowCount;
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

    // static function for use in transaction 
    private static function GetWasteTypeRate($conn, $wasteTypeId)
    {
        try {
            $wasteRateSql =
                "SELECT 
                    waste_type_price,waste_type_co2
                FROM
                    waste_type
                WHERE
                    waste_type_id = :waste_type_id
                ";
            $rateStmt = $conn->prepare($wasteRateSql);
            $rateStmt->execute(["waste_type_id" => $wasteTypeId]);
            $result = $rateStmt->fetch(PDO::FETCH_ASSOC);
            if (!$result) {
                throw new Exception("Unknow Waste Type", 500);
            }
            return $result;
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage(), 500);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    private static function GetDepositorAccount($conn, $identifier)
    {
        try {
            $sql =
                "SELECT 
                    member_id, role_id, faculty_id
                FROM
                    member
                WHERE
                    member_personal_id = :identifier OR
                    member_phone = :identifier OR
                    member_email = :identifier OR
                    member_name = :identifier
                ";
            $stmt = $conn->prepare($sql);
            $stmt->execute(["identifier" => $identifier]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$result) {
                throw new Exception("member not found with identifier: " . htmlspecialchars($identifier) . "'", 404);
            }
            return $result;
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage(), 500);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    private static function UpdateMemberPoint($conn, $memberId, $point)
    {
        try {
            if ($point == 0) {
                $selectSql = "SELECT member_waste_point FROM member WHERE member_id = :member_id";
                $stmt = $conn->prepare($selectSql);
                $stmt->execute(["member_id" => $memberId]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$user) {
                    throw new Exception("Account not found ID: " . htmlspecialchars($memberId), 404);
                }

                return $user;
            }

            $sql =
                "UPDATE
                    member
                SET
                    member_waste_point = member_waste_point + :point
                WHERE
                   member_id = :member_id
                ";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(":member_id", $memberId, PDO::PARAM_INT);
            $stmt->bindValue(":point", $point, PDO::PARAM_INT);
            $stmt->execute();

            $selectSql = "SELECT member_waste_point FROM member WHERE member_id = :member_id";
            $stmt = $conn->prepare($selectSql);
            $stmt->execute(["member_id" => $memberId]);
            $updatedUser = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$updatedUser) {
                throw new Exception("Account not found after update attempt", 404);
            }

            return $updatedUser;

        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage(), 500);
        }
    }

    private static function CreateFacultyPointHistory($conn, $facultyId, $point)
    {
        try {
            // if ($point == 0) {
            //     $selectSql = "SELECT faculty_point FROM faculty WHERE faculty_id = :faculty_id";
            //     $stmt = $conn->prepare($selectSql);
            //     $stmt->execute(["faculty_id" => $facultyId]);
            //     $faculty = $stmt->fetch(PDO::FETCH_ASSOC);

            //     if (!$faculty) {
            //         throw new Exception("faculty not found ID: " . htmlspecialchars($facultyId), 404);
            //     }

            //     return $faculty;
            // }
            $payload = [];
            $payload["faculty_id"] = $facultyId;
            $payload["faculty_point_amount"] = $point;
            $payload["faculty_point_source"] = "member";
            $payload["faculty_point_date"] = date('Y-m-d');
            $payload["created_at"] = date('Y-m-d H:i:s');

            $setClauses = [];
            foreach ($payload as $column => $value) {
                if (isset($value) && $value !== '') {
                    $setClauses[] = "`{$column}` = :{$column}";
                }
            }
            $setClauseString = implode(', ', $setClauses);

            $updateSql =
                "INSERT INTO
                    faculty_point
                SET
                    {$setClauseString}
                ";
            $stmt = $conn->prepare($updateSql);
            $stmt->execute($payload);

            $rowCount = $stmt->rowCount();
            if ($rowCount === 0) {
                throw new Exception("Faculty not found or points not updated for faculty ID: " . htmlspecialchars($facultyId) . "   " . htmlspecialchars($point), 404);
            }

            $selectSql = "SELECT SUM(faculty_point_amount) AS faculty_point FROM faculty_point WHERE faculty_id = :faculty_id";
            $stmt = $conn->prepare($selectSql);
            $stmt->execute(["faculty_id" => $facultyId]);
            $updatedFaculty = $stmt->fetch(PDO::FETCH_ASSOC);

            return $updatedFaculty;

        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage(), 500);
        } catch (Exception $e) {
            throw $e;
        }
    }
}