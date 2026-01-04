<?php
namespace App\Utils;

use PDOException;

class DatabaseException extends PDOException
{
    /**
     * แปลง PDOException เป็นข้อความที่เข้าใจง่าย
     *
     * @param PDOException $e
     * @return array [ 'code' => http_code, 'message' => friendly_message ]
     */
    public static function handle($e): array
    {
        // ดึง SQLSTATE (รหัสมาตรฐาน) และ Driver Code (รหัสเฉพาะของ Database เช่น MySQL)
        $sqlState = $e->getCode(); // หรือ $e->errorInfo[0]
        $driverCode = $e->errorInfo[1] ?? 0;
        $rawMessage = $e->getMessage();

        // ค่าเริ่มต้น (กรณีไม่ตรงเงื่อนไขใดๆ)
        $response = [
            'code' => 500,
            'message' => 'เกิดข้อผิดพลาดที่ไม่ทราบสาเหตุ (System Error)'
        ];

        // ตรวจสอบจาก SQLSTATE หรือ Driver Code
        switch ($sqlState) {
            // --- กรณี: เชื่อมต่อฐานข้อมูลไม่ได้ ---
            case 1045: // Access denied
            case 2002: // Connection refused
            case 'HY000': // General error (มักเกิดตอน connect ไม่ได้)
                $response['message'] = 'ไม่สามารถเชื่อมต่อกับฐานข้อมูลได้ กรุณาตรวจสอบ Server';
                $response['code'] = 503; // Service Unavailable
                break;

            // --- กรณี: ข้อจำกัดของข้อมูล (Integrity constraint violation) ---
            case '23000':
                // เจาะจงด้วย Driver Code ของ MySQL
                if ($driverCode == 1062) {
                    // Duplicate entry (เช่น สมัครสมาชิกด้วยอีเมลเดิม)
                    $response['message'] = 'ข้อมูลนี้มีอยู่ในระบบแล้ว (ห้ามซ้ำ)';
                    $response['code'] = 409; // Conflict

                    // ดึงชื่อ key จาก error message เพื่อระบุฟิลด์
                    if (preg_match("/key '([^']+)'/", $rawMessage, $matches)) {
                        $key = $matches[1];
                        // ตัดชื่อตารางออก (ถ้ามี) เช่น member.member_phone_UNIQUE -> member_phone_UNIQUE
                        if (strpos($key, '.') !== false) {
                            $parts = explode('.', $key);
                            $key = end($parts);
                        }

                        if (strpos($key, 'phone') !== false) {
                            $response['message'] = 'กรุณาลองใหม่อีกครั้ง, เบอร์โทรศัพท์นี้มีอยู่ในระบบแล้ว';
                        } elseif (strpos($key, 'email') !== false) {
                            $response['message'] = 'กรุณาลองใหม่อีกครั้ง, อีเมลนี้มีอยู่ในระบบแล้ว';
                        } elseif (strpos($key, 'personal_id') !== false) {
                            $response['message'] = 'กรุณาลองใหม่อีกครั้ง, รหัสบัตรประชาชนนี้มีอยู่ในระบบแล้ว';
                        } elseif (strpos($key, 'name') !== false) {
                            $response['message'] = 'กรุณาลองใหม่อีกครั้ง, ชื่อนี้มีอยู่ในระบบแล้ว';
                        }
                    }
                } elseif ($driverCode == 1451) {
                    // Foreign key constraint fails (ลบข้อมูลแม่ไม่ได้ เพราะมีลูกใช้อยู่)
                    $response['message'] = 'ไม่สามารถลบหรือแก้ไขข้อมูลนี้ได้ เนื่องจากมีการถูกใช้งานอยู่ในส่วนอื่น';
                    $response['code'] = 400; // Bad Request
                } elseif ($driverCode == 1452) {
                    // Foreign key fails (พยายามใส่ ID ที่ไม่มีจริงในตารางหลัก)
                    $response['message'] = 'ข้อมูลที่อ้างอิงไม่ถูกต้อง (ไม่พบข้อมูลหลัก)';
                    $response['code'] = 400;
                } else {
                    $response['message'] = 'ไม่สามารถบันทึกข้อมูลได้ เนื่องจากติดเงื่อนไขความถูกต้องของข้อมูล';
                }
                break;

            // --- กรณี: หาตารางหรือคอลัมน์ไม่เจอ (มักเกิดตอน Dev) ---
            case '42S02': // Table not found
            case '42S22': // Column not found
                $response['message'] = 'เกิดข้อผิดพลาดทางเทคนิค (หาตารางหรือฟิลด์ไม่พบ)';
                break;

            // --- กรณี: Syntax SQL ผิด ---
            case '42000':
                $response['message'] = 'คำสั่งการทำงานผิดพลาด (Syntax Error)';
                break;

            // --- กรณี: ข้อมูลยาวเกินกำหนด ---
            case '22001':
                $response['message'] = 'ข้อมูลยาวเกินกว่าที่ระบบกำหนด';
                $response['code'] = 400;
                break;
        }

        // (Optional) ในขั้นตอน Development อาจจะแนบ $rawMessage ไปด้วยเพื่อ debug
        // $response['debug'] = $rawMessage;

        return $response;
    }
}