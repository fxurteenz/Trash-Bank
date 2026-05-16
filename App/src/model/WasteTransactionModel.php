<?php
namespace App\Model;

use App\Utils\Database;
use App\Utils\DatabaseException;
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
                $whereClauses[] = "DATE(w.created_at) >= :start_date";
                $params[':start_date'] = $query['start_date'];
            }
            if (!empty($query['end_date'])) {
                $whereClauses[] = "DATE(w.created_at) <= :end_date";
                $params[':end_date'] = $query['end_date'];
            }
            if (!empty($query['date'])) {
                $whereClauses[] = "DATE(w.created_at) = :date";
                $params[':date'] = $query['date'];
            }
            // กรองตามปีที่ระบุ (เช่น 2025)
            if (!empty($query['year'])) {
                $whereClauses[] = "YEAR(w.created_at) = :year";
                $params[':year'] = $query['year'];
            }
            // กรองตามเดือนที่ระบุ (1-12)
            if (!empty($query['month'])) {
                $whereClauses[] = "MONTH(w.created_at) = :month";
                $params[':month'] = $query['month'];
            }
            // กรองตามหมวดหมู่หรือชนิดขยะ
            if (!empty($query['category'])) {
                $whereClauses[] = "d.waste_category_id = :category_id";
                $params[':category_id'] = $query['category'];
            }
            if (!empty($query['type'])) {
                $whereClauses[] = "d.waste_type_id = :type_id";
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
                $params[':staff_search'] = "%" . $query['staff_search'] . "%";
            }

            $whereSql = !empty($whereClauses) ? " WHERE " . implode(" AND ", $whereClauses) : "";

            $sql = "SELECT 
                    d.waste_transaction_detail_id AS waste_transaction_id, -- Alias for backward compatibility
                    w.waste_transaction_id AS transaction_header_id,
                    w.created_at,
                    d.waste_transaction_detail_weight AS waste_transaction_weight,
                    d.waste_transaction_detail_point AS waste_transaction_member_point,
                    d.waste_transaction_detail_rate AS waste_transaction_rate,
                    a.member_id, a.member_name, a.member_personal_id, a.member_phone,a.member_email,
                    f.faculty_id, f.faculty_name,
                    t.waste_type_name, c.waste_category_name,
                    s.member_id AS staff_id,s.member_name AS staff_name, s.member_phone AS staff_tel, 
                    s.member_email AS staff_email, s.member_personal_id AS staff_personal_id
                FROM 
                    waste_transaction_detail d
                JOIN waste_transaction w ON d.waste_transaction_id = w.waste_transaction_id
                LEFT JOIN member a ON w.member_id = a.member_id
                LEFT JOIN faculty f ON a.faculty_id = f.faculty_id
                LEFT JOIN member s ON w.staff_id = s.member_id
                LEFT JOIN waste_type t ON d.waste_type_id = t.waste_type_id
                LEFT JOIN waste_category c ON d.waste_category_id = c.waste_category_id
                {$whereSql}
                ORDER BY w.created_at DESC";

            $isPagination = isset($query['page']) && isset($query['limit']);

            if ($isPagination) {

                $sql .= " LIMIT :limit OFFSET :offset";
            }

            $stmt = $this->Conn->prepare($sql);

            // Bind ค่าสำหรับการกรอง
            foreach ($params as $key => $val) {
                $stmt->bindValue($key, $val);
            }

            if ($isPagination) {
                $page = (int) $query['page'];
                $limit = (int) $query['limit'];
                $offset = ($page - 1) * $limit;

                $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
                $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            }

            $stmt->execute();
            $deposits = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($isPagination) {
                // ต้อง JOIN member a ด้วยหากมีการกรองตาม faculty_id ในการนับจำนวนทั้งหมด
                $sqlCount = "SELECT COUNT(*) AS allDeposit 
                             FROM waste_transaction_detail d 
                             JOIN waste_transaction w ON d.waste_transaction_id = w.waste_transaction_id 
                             LEFT JOIN member a ON w.member_id = a.member_id 
                             {$whereSql}";
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

    // Header list for transaction history (main table)
    public function GetTransactionHeaders(array $query): array
    {
        try {
            $where = [];
            $params = [];

            if (!empty($query['start_date'])) {
                $where[] = 'DATE(w.created_at) >= :start_date';
                $params[':start_date'] = $query['start_date'];
            }
            if (!empty($query['end_date'])) {
                $where[] = 'DATE(w.created_at) <= :end_date';
                $params[':end_date'] = $query['end_date'];
            }
            if (!empty($query['member_search'])) {
                $where[] = '(mem.member_name LIKE :m OR mem.member_phone LIKE :m OR mem.member_email LIKE :m OR mem.member_personal_id LIKE :m)';
                $params[':m'] = '%' . $query['member_search'] . '%';
            }
            if (!empty($query['staff_search'])) {
                $where[] = '(st.member_name LIKE :s OR st.member_phone LIKE :s OR st.member_email LIKE :s OR st.member_personal_id LIKE :s)';
                $params[':s'] = '%' . $query['staff_search'] . '%';
            }
            if (!empty($query['faculty'])) {
                $where[] = 'f.faculty_id = :fid';
                $params[':fid'] = $query['faculty'];
            }

            $whereSql = !empty($where) ? ' WHERE ' . implode(' AND ', $where) : '';

            $sqlCount = "SELECT COUNT(*) AS total
                         FROM waste_transaction w
                         LEFT JOIN member mem ON w.member_id = mem.member_id
                         LEFT JOIN member st ON w.staff_id = st.member_id
                         LEFT JOIN faculty f ON w.faculty_id = f.faculty_id
                         $whereSql";
            $stmtCount = $this->Conn->prepare($sqlCount);
            foreach ($params as $k => $v)
                $stmtCount->bindValue($k, $v);
            $stmtCount->execute();
            $total = (int) $stmtCount->fetch(PDO::FETCH_ASSOC)['total'];

            $sql = "SELECT 
                        w.*, 
                        mem.member_name AS member_name,
                        st.member_name AS staff_name,
                        f.faculty_name
                    FROM waste_transaction w
                    LEFT JOIN member mem ON w.member_id = mem.member_id
                    LEFT JOIN member st ON w.staff_id = st.member_id
                    LEFT JOIN faculty f ON w.faculty_id = f.faculty_id
                    $whereSql
                    ORDER BY w.created_at DESC";

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
            throw new Exception('Database error: ' . $e->getMessage(), 500);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function GetTransactionByIdWithDetails(int $id): array
    {
        try {
            // header
            $sql = "SELECT w.*, mem.member_name, st.member_name AS staff_name, f.faculty_name
                    FROM waste_transaction w
                    LEFT JOIN member mem ON w.member_id = mem.member_id
                    LEFT JOIN member st ON w.staff_id = st.member_id
                    LEFT JOIN faculty f ON w.faculty_id = f.faculty_id
                    WHERE w.waste_transaction_id = :id";
            $stmt = $this->Conn->prepare($sql);
            $stmt->execute([':id' => $id]);
            $header = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$header)
                return [];

            $sqld = "SELECT d.*, t.waste_type_name, c.waste_category_name
                     FROM waste_transaction_detail d
                     LEFT JOIN waste_type t ON d.waste_type_id = t.waste_type_id
                     LEFT JOIN waste_category c ON d.waste_category_id = c.waste_category_id
                     WHERE d.waste_transaction_id = :id";
            $stmtd = $this->Conn->prepare($sqld);
            $stmtd->execute([':id' => $id]);
            $details = $stmtd->fetchAll(PDO::FETCH_ASSOC);
            return ['transaction' => $header, 'detail' => $details];
        } catch (PDOException $e) {
            throw new Exception('Database error: ' . $e->getMessage(), 500);
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
            // กรองตามวันที่
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
            // กรองตามหมวดหมู่หรือชนิดขยะ
            if (!empty($query['category'])) {
                $whereClauses[] = "d.waste_category_id = :category_id";
                $params[':category_id'] = $query['category'];
            }
            if (!empty($query['type'])) {
                $whereClauses[] = "d.waste_type_id = :type_id";
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
                        d.waste_transaction_detail_id AS waste_transaction_id,
                        w.waste_transaction_id AS transaction_header_id,
                        w.waste_transaction_date,
                        d.waste_transaction_detail_weight AS waste_transaction_weight,
                        d.waste_transaction_detail_point AS waste_transaction_member_point,
                        d.waste_transaction_detail_rate AS waste_transaction_rate,
                        d.waste_transaction_detail_status AS waste_transaction_status,
                        a.member_id, a.member_name, a.member_personal_id, 
                        a.member_phone, a.member_email,f.faculty_id, 
                        f.faculty_name, c.waste_category_name, t.waste_type_name
                    FROM 
                        waste_transaction_detail d
                    JOIN waste_transaction w ON d.waste_transaction_id = w.waste_transaction_id
                    LEFT JOIN member a ON w.member_id = a.member_id
                    LEFT JOIN faculty f ON a.faculty_id = f.faculty_id
                    LEFT JOIN waste_type t ON d.waste_type_id = t.waste_type_id
                    LEFT JOIN waste_category c ON d.waste_category_id = c.waste_category_id
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
                $sqlCount = "SELECT COUNT(*) AS allDeposit 
                             FROM waste_transaction_detail d 
                             JOIN waste_transaction w ON d.waste_transaction_id = w.waste_transaction_id 
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

    public function GetAllTransactionByMemberId(int $memberId, array $query): array
    {
        try {
            $whereClauses = ["w.member_id = :member_id"];
            $params = [':member_id' => $memberId];

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
            if (!empty($query['category'])) {
                $whereClauses[] = "d.waste_category_id = :category_id";
                $params[':category_id'] = $query['category'];
            }
            if (!empty($query['type'])) {
                $whereClauses[] = "d.waste_type_id = :type_id";
                $params[':type_id'] = $query['type'];
            }

            $whereSql = " WHERE " . implode(" AND ", $whereClauses);

            $sql = "SELECT 
                        d.waste_transaction_detail_id AS waste_transaction_id,
                        w.waste_transaction_date,
                        d.waste_transaction_detail_weight AS waste_transaction_weight,
                        d.waste_transaction_detail_point AS waste_transaction_member_point,
                        d.waste_transaction_detail_rate AS waste_transaction_rate,
                        d.waste_transaction_detail_status AS waste_transaction_status,
                        c.waste_category_name,
                        t.waste_type_name
                    FROM waste_transaction_detail d
                    JOIN waste_transaction w ON d.waste_transaction_id = w.waste_transaction_id
                    LEFT JOIN waste_type t ON d.waste_type_id = t.waste_type_id
                    LEFT JOIN waste_category c ON d.waste_category_id = c.waste_category_id
                    $whereSql
                    ORDER BY w.waste_transaction_date DESC";

            $isPagination = isset($query['page']) && isset($query['limit']);
            if ($isPagination) {
                $page = (int) $query['page'];
                $limit = (int) $query['limit'];
                $offset = ($page - 1) * $limit;
                $sql .= " LIMIT :limit OFFSET :offset";
            }

            $stmt = $this->Conn->prepare($sql);
            foreach ($params as $key => $val) {
                $stmt->bindValue($key, $val);
            }
            if ($isPagination) {
                $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
                $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            }
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($isPagination) {
                $countSql = "SELECT COUNT(*) as total FROM waste_transaction_detail d JOIN waste_transaction w ON d.waste_transaction_id = w.waste_transaction_id $whereSql";
                $countStmt = $this->Conn->prepare($countSql);
                foreach ($params as $key => $val) {
                    $countStmt->bindValue($key, $val);
                }
                $countStmt->execute();
                $total = (int) $countStmt->fetch(PDO::FETCH_ASSOC)['total'];
            } else {
                $total = count($rows);
            }

            return ['data' => $rows, 'total' => $total];
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage(), 500);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function GetWasteDepositSummary()
    {
        try {

        } catch (PDOException $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 500);
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
            // Prepare items
            $items = [];
            if (isset($data['items']) && is_array($data['items'])) {
                $items = $data['items'];
            } elseif (isset($data['waste_category_id']) && isset($data['waste_type_id']) && isset($data['deposit_weight'])) {
                // Support single item (legacy)
                $items[] = $data;
            }

            if (empty($items)) {
                throw new Exception('กรุณาลองอีกครั้ง, ไม่พบรายการขยะ', 400);
            }


            /* Start SQL Transaction */
            $this->Conn->beginTransaction();

            $user = self::GetDepositorAccount($this->Conn, $data["depositor_member"]);

            if (empty($user["member_id"])) {
                throw new Exception("ERROR : Check faculty's user", 400);
            }

            $totalPoints = 0;
            $totalWeight = 0;
            $totalCo2e = 0;
            $details = [];

            foreach ($items as $item) {
                if (empty($item['waste_category_id']) || empty($item['waste_type_id']) || !isset($item['deposit_weight'])) {
                    throw new Exception('ข้อมูลรายการขยะไม่ครบถ้วน', 400);
                }

                $rateResult = self::GetWasteTypeRate($this->Conn, $item["waste_type_id"]);

                $value = ($rateResult["waste_type_price"] * $item["deposit_weight"]) / 2 * 10;
                $integer_point = (int) floor($value);

                $co2e = $rateResult["waste_type_co2"] * $item["deposit_weight"];

                $totalWeight += $item["deposit_weight"];
                $totalPoints += $integer_point;
                $totalCo2e += $co2e;

                $details[] = [
                    'waste_category_id' => $item["waste_category_id"],
                    'waste_type_id' => $item["waste_type_id"],
                    'weight' => $item["deposit_weight"],
                    'rate' => $rateResult["waste_type_price"],
                    'point' => $integer_point,
                    'co2e' => $co2e
                ];
            }

            self::CheckFacultyPoint($this->Conn, $staffData["user_data"]->faculty_id, $totalPoints);

            // 1. Insert Header (waste_transaction)
            $headerSql = "INSERT INTO waste_transaction SET 
                member_id = :mid, 
                faculty_id = :fid, 
                staff_id = :staffid,
                waste_transaction_total_weight = :tw,
                waste_transaction_total_point = :tp,
                waste_transaction_total_co2e = :co2e,
                created_at = :created";

            $stmtHeader = $this->Conn->prepare($headerSql);
            $stmtHeader->execute([
                ':mid' => $user["member_id"],
                ':fid' => $staffData["user_data"]->faculty_id,
                ':staffid' => $staffData["user_data"]->member_id,
                ':tw' => $totalWeight,
                ':tp' => $totalPoints,
                ':co2e' => $totalCo2e,
                ':created' => date('Y-m-d H:i:s')
            ]);
            $transactionId = $this->Conn->lastInsertId();

            // 2. Insert Details (waste_transaction_detail) and Update Stock
            $detailSql = "INSERT INTO waste_transaction_detail SET
                waste_transaction_id = :tid,
                waste_category_id = :cid,
                waste_type_id = :typeid,
                waste_transaction_detail_weight = :w,
                waste_transaction_detail_rate = :r,
                waste_transaction_detail_point = :p,
                waste_transaction_detail_co2e = :co2e";

            $stmtDetail = $this->Conn->prepare($detailSql);
            foreach ($details as $d) {
                $stmtDetail->execute([
                    ':tid' => $transactionId,
                    ':cid' => $d['waste_category_id'],
                    ':typeid' => $d['waste_type_id'],
                    ':w' => $d['weight'],
                    ':r' => $d['rate'],
                    ':p' => $d['point'],
                    ':co2e' => $d['co2e']
                ]);

                // Update faculty stock for each item
                self::UpdateFacultyWasteStock($this->Conn, $staffData["user_data"]->faculty_id, $d['waste_type_id'], $d['weight']);
            }

            $updatedUser = self::UpdateMemberPoint($this->Conn, $user["member_id"], $totalPoints);
            $updatedFaculty = self::UpdateFacultyPoint($this->Conn, $staffData["user_data"]->faculty_id, $totalPoints);

            $this->Conn->commit();

            return [
                'transaction_id' => $transactionId,
                'total_member_point' => $updatedUser['member_waste_point'],
                'total_faculty_point' => $updatedFaculty['faculty_point'] ?? null,
                'items_count' => count($details)
            ];

        } catch (PDOException $e) {
            if ($this->Conn->inTransaction()) {
                $this->Conn->rollBack();
            }
            // error_log("ERROR PDO : " . $e->getMessage());
            throw new Exception("Database error: " . $e->getMessage(), 500);
        } catch (Exception $e) {
            if ($this->Conn->inTransaction()) {
                $this->Conn->rollBack();
            }
            error_log("ERROR : " . $e->getMessage());
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function CreateWasteTransactionFromBranch(array $data, $staffData): array
    {
        try {
            if (!is_array($data)) {
                throw new Exception('Invalid data format', 400);
            }

            if (empty($data['depositor_member'])) {
                // error_log("ERROR : waste_type is missing");
                throw new Exception('กรุณาลองอีกครั้ง, ระบุข้อมูลผู้ทำการฝาก', 400);
            }

            // Prepare items
            $items = [];
            if (isset($data['items']) && is_array($data['items'])) {
                $items = $data['items'];
            } elseif (isset($data['waste_category_id']) && isset($data['waste_type_id']) && isset($data['deposit_weight'])) {
                // Support single item (legacy)
                $items[] = $data;
            }

            if (empty($items)) {
                throw new Exception('กรุณาลองอีกครั้ง, ไม่พบรายการขยะ', 400);
            }

            /* Start SQL Transaction */
            $this->Conn->beginTransaction();

            $user = self::GetDepositorAccount($this->Conn, $data["depositor_member"]);

            if (empty($user["member_id"])) {
                throw new Exception("ตรวจสอบข้อมูลผู้ใช้งานอีกครั้ง", 400);
            }

            if (empty($staffData["user_data"]->center_branch_id)) {
                throw new Exception("เกิดข้อผิดพลาดของข้อมูลเจ้าหน้าที่ เข้าสู่ระบบแล้วลองใหม่อีกครั้ง", 400);
            }

            $totalPoints = 0;
            $totalWeight = 0;
            $totalCo2e = 0;
            $details = [];

            foreach ($items as $item) {
                if (empty($item['waste_category_id']) || empty($item['waste_type_id']) || !isset($item['deposit_weight'])) {
                    throw new Exception('ข้อมูลรายการขยะไม่ครบถ้วน', 400);
                }

                $rateResult = self::GetWasteTypeRate($this->Conn, $item["waste_type_id"]);

                $value = ($rateResult["waste_type_price"] * $item["deposit_weight"]) / 2 * 10;
                $integer_point = (int) floor($value);

                $co2e = $rateResult["waste_type_co2"] * $item["deposit_weight"];

                $totalWeight += $item["deposit_weight"];
                $totalPoints += $integer_point;
                $totalCo2e += $co2e;

                $details[] = [
                    'waste_category_id' => $item["waste_category_id"],
                    'waste_type_id' => $item["waste_type_id"],
                    'weight' => $item["deposit_weight"],
                    'rate' => $rateResult["waste_type_price"],
                    'point' => $integer_point,
                    'co2e' => $co2e
                ];
            }

            self::CheckBranchPoint($this->Conn, $staffData["user_data"]->center_branch_id, $totalPoints);

            // 1. Insert Header (waste_transaction)
            $headerSql = "INSERT INTO waste_transaction SET 
                member_id = :mid, 
                center_branch_id = :bid, 
                staff_id = :staffid,
                waste_transaction_total_weight = :tw,
                waste_transaction_total_point = :tp,
                waste_transaction_total_co2e = :co2e,
                created_at = :created";

            $stmtHeader = $this->Conn->prepare($headerSql);
            $stmtHeader->execute([
                ':mid' => $user["member_id"],
                ':bid' => $staffData["user_data"]->center_branch_id,
                ':staffid' => $staffData["user_data"]->member_id,
                ':tw' => $totalWeight,
                ':tp' => $totalPoints,
                ':co2e' => $totalCo2e,
                ':created' => date('Y-m-d H:i:s')
            ]);
            $transactionId = $this->Conn->lastInsertId();

            // 2. Insert Details (waste_transaction_detail) and Update Stock
            $detailSql = "INSERT INTO waste_transaction_detail SET
                waste_transaction_id = :tid,
                waste_category_id = :cid,
                waste_type_id = :typeid,
                waste_transaction_detail_weight = :w,
                waste_transaction_detail_rate = :r,
                waste_transaction_detail_point = :p,
                waste_transaction_detail_co2e = :co2e";

            $stmtDetail = $this->Conn->prepare($detailSql);
            foreach ($details as $d) {
                $stmtDetail->execute([
                    ':tid' => $transactionId,
                    ':cid' => $d['waste_category_id'],
                    ':typeid' => $d['waste_type_id'],
                    ':w' => $d['weight'],
                    ':r' => $d['rate'],
                    ':p' => $d['point'],
                    ':co2e' => $d['co2e']
                ]);

                self::UpdateBranchWasteStock($this->Conn, $staffData["user_data"]->center_branch_id, $d['waste_type_id'], $d['weight']);
            }

            $updatedUser = self::UpdateMemberPoint($this->Conn, $user["member_id"], $totalPoints);
            $updatedBranch = self::UpdateBranchPoint($this->Conn, $staffData["user_data"]->center_branch_id, $totalPoints);

            $this->Conn->commit();

            return [
                'transaction_id' => $transactionId,
                'total_member_point' => $updatedUser['member_waste_point'],
                'total_branch_point' => $updatedBranch['center_branch_point'] ?? null,
                'items_count' => count($details)
            ];

        } catch (PDOException $e) {
            if ($this->Conn->inTransaction()) {
                $this->Conn->rollBack();
            }
            // error_log("ERROR PDO : " . $e->getMessage());
            throw new Exception("Database error: " . $e->getMessage(), 500);
        } catch (Exception $e) {
            if ($this->Conn->inTransaction()) {
                $this->Conn->rollBack();
            }
            error_log("ERROR : " . $e->getMessage());
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function DeleteWasteTransactionById($id): array
    {
        try {
            if (empty($id)) {
                throw new Exception('ID is required for deletion', 400);
            }

            $this->Conn->beginTransaction();

            $sqlWtd = "DELETE FROM waste_transaction_detail WHERE waste_transaction_detail_id = :waste_transaction_id";
            $stmt = $this->Conn->prepare($sqlWtd);
            $stmt->execute(['waste_transaction_id' => $id]);
            $rowCountWtd = $stmt->rowCount();

            $sqlWt = "DELETE FROM waste_transaction WHERE waste_transaction_id = :waste_transaction_id";
            $stmt = $this->Conn->prepare($sqlWt);
            $stmt->execute(['waste_transaction_id' => $id]);
            $rowCountWt = $stmt->rowCount();

            $this->Conn->commit();

            return ['rowCountWtd' => $rowCountWtd, 'rowCountWt' => $rowCountWt];
        } catch (PDOException $e) {
            $this->Conn->rollBack();
            throw new Exception("Database error: " . $e->getMessage(), 500);
        } catch (Exception $e) {
            if ($this->Conn->inTransaction()) {
                $this->Conn->rollBack();
            }
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
            $sql = "DELETE FROM waste_transaction_detail WHERE waste_transaction_detail_id IN ($placeholders)";

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

    private static function GetDepositorAccount($conn, $mid)
    {
        try {
            $sql =
                "SELECT 
                    member_id, role_id, faculty_id
                FROM
                    member
                WHERE
                    member_id = :mid 
                ";
            $stmt = $conn->prepare($sql);
            $stmt->execute(["mid" => $mid]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$result) {
                throw new Exception("member not found with mid: " . htmlspecialchars($mid) . "'", 404);
            }
            return $result;
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage(), 500);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

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

    private static function CheckFacultyPoint($conn, $facultyId, $point)
    {
        try {
            $sql = "SELECT faculty_point FROM faculty WHERE faculty_id = :faculty_id";
            $stmt = $conn->prepare($sql);
            $stmt->execute(["faculty_id" => $facultyId]);
            $faculty = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$faculty) {
                throw new Exception("ไม่พบคณะในระบบ : " . htmlspecialchars($facultyId), 404);
            }

            if ($faculty['faculty_point'] < $point) {
                throw new Exception("แต้มไม่เพียงพอทำรายการนี้ ต้องใช้ " . htmlspecialchars($point) . " แต้ม", 400);
            }

            return $faculty['faculty_point'];
        } catch (PDOException $e) {
            throw new Exception("Error while checking faculty point : " . $e->getMessage(), 500);

        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    private static function CheckBranchPoint($conn, $branchId, $point)
    {
        try {
            $sql = "SELECT center_branch_point FROM center_branch WHERE center_branch_id = :center_branch_id";
            $stmt = $conn->prepare($sql);
            $stmt->execute(["center_branch_id" => $branchId]);
            $branch = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$branch) {
                throw new Exception("ไม่พบศูนย์นี้ในระบบ : " . htmlspecialchars($branchId), 404);
            }

            if ($branch['center_branch_point'] < $point) {
                throw new Exception("แต้มไม่เพียงพอทำรายการนี้ ต้องใช้ " . htmlspecialchars($point) . " แต้ม", 400);
            }

            return $branch['center_branch_point'];
        } catch (PDOException $e) {
            throw new Exception("Error while checking faculty point : " . $e->getMessage(), 500);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    private static function UpdateFacultyWasteStock($conn, $facultyId, $wasteTypeId, $weight)
    {
        try {
            $sql = "INSERT INTO faculty_waste_stock (faculty_id, waste_type_id, stock_weight, updated_at) 
                    VALUES (:faculty_id, :waste_type_id, :weight, :now)
                    ON DUPLICATE KEY UPDATE 
                    stock_weight = stock_weight + VALUES(stock_weight), 
                    updated_at = VALUES(updated_at)";

            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':faculty_id' => $facultyId,
                ':waste_type_id' => $wasteTypeId,
                ':weight' => $weight,
                ':now' => date('Y-m-d H:i:s')
            ]);
        } catch (PDOException $e) {
            // Re-throw to be caught by the main function's transaction handler
            throw new Exception("Error while updating faculty stock : " . $e->getMessage(), 500);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }
    private static function UpdateBranchWasteStock($conn, $branchId, $wasteTypeId, $weight)
    {
        try {
            $sql = "INSERT INTO center_branch_waste_stock (center_branch_id, waste_type_id, stock_weight, updated_at) 
                    VALUES (:center_branch_id, :waste_type_id, :weight, :now)
                    ON DUPLICATE KEY UPDATE 
                    stock_weight = stock_weight + VALUES(stock_weight), 
                    updated_at = VALUES(updated_at)";

            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':center_branch_id' => $branchId,
                ':waste_type_id' => $wasteTypeId,
                ':weight' => $weight,
                ':now' => date('Y-m-d H:i:s')
            ]);
        } catch (PDOException $e) {
            // Re-throw to be caught by the main function's transaction handler
            throw new Exception("Error while updating faculty stock : " . $e->getMessage(), 500);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    private static function UpdateFacultyPoint($conn, $facultyId, $point)
    {
        try {
            $sql = "UPDATE faculty SET faculty_point = faculty_point - :point WHERE faculty_id = :faculty_id";

            $stmt = $conn->prepare($sql);
            $stmt->bindValue(":faculty_id", $facultyId, PDO::PARAM_INT);
            $stmt->bindValue(":point", $point, PDO::PARAM_INT);
            $stmt->execute();

            $sql = "SELECT faculty_point FROM faculty WHERE faculty_id = :faculty_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(":faculty_id", $facultyId, PDO::PARAM_INT);
            $stmt->execute();
            $updatedFaculty = $stmt->fetch(PDO::FETCH_ASSOC);

            return $updatedFaculty;
        } catch (PDOException $e) {
            // Re-throw to be caught by the main function's transaction handler
            throw new Exception("Error while updating faculty point : " . $e->getMessage(), 500);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    private static function UpdateBranchPoint($conn, $branchId, $point)
    {
        try {
            $sql = "UPDATE center_branch SET center_branch_point = center_branch_point - :point WHERE center_branch_id = :center_branch_id";

            $stmt = $conn->prepare($sql);
            $stmt->bindValue(":center_branch_id", $branchId, PDO::PARAM_INT);
            $stmt->bindValue(":point", $point, PDO::PARAM_INT);
            $stmt->execute();

            $sql = "SELECT center_branch_point FROM center_branch WHERE center_branch_id = :center_branch_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(":center_branch_id", $branchId, PDO::PARAM_INT);
            $stmt->execute();
            $updatedFaculty = $stmt->fetch(PDO::FETCH_ASSOC);

            return $updatedFaculty;
        } catch (PDOException $e) {
            // Re-throw to be caught by the main function's transaction handler
            throw new Exception("Error while updating faculty point : " . $e->getMessage(), 500);
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
                throw new Exception("ไม่พบสมาชิกในระบบ", 404);
            }

            return $updatedUser;

        } catch (PDOException $e) {
            throw new Exception("Error while updating member point : " . $e->getMessage(), 500);
        }
    }

}
// TODO: Modify delete transaction ตัดสต็อคคืน