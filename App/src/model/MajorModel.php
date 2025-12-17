<?php
namespace App\Model;
use App\Utils\Database;
use Exception;
use PDO;
use PDOException;

class MajorModel
{
    private static $Database;
    private $Conn;

    public function __construct()
    {
        self::$Database = new Database();
        $this->Conn = self::$Database->connect();
    }

    public function GetMajor()
    {
    }

    public function GetMajorByFaculty($fid): array
    {
        try {
            $sql =
                "SELECT 
                    m.major_id, 
                    m.major_name,
                    SUM(CASE WHEN a.account_role = 'user' THEN 1 ELSE 0 END) AS count_user,
                    SUM(CASE WHEN a.account_role = 'faculty_staff' THEN 1 ELSE 0 END) AS count_staff,
                    SUM(CASE WHEN a.account_role = 'operater' THEN 1 ELSE 0 END) AS count_operater,
                    COUNT(a.account_id) AS total_all
                FROM 
                    major m
                LEFT JOIN 
                    account a ON m.major_id = a.major_id
                WHERE 
                    m.faculty_id = :faculty_id
                GROUP BY 
                    m.major_id";

            $stmt = $this->Conn->prepare($sql);
            $stmt->bindValue(':faculty_id', $fid, PDO::PARAM_INT);
            $stmt->execute();
            $allMajor = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $allMajor;
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage(), 500);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }


    public function CreateMajor(array $data): int
    {
        try {
            if (empty($data['major_name']) && empty($data['faculty_id'])) {
                throw new Exception('major name and faculty are not provided', 400);
            } else if (empty($data['major_name']) && $data['faculty_id']) {
                throw new Exception('major name is not provided', 400);
            } else if ($data['major_name'] && empty($data['faculty_id'])) {
                throw new Exception('faculty is not provided', 400);
            }

            $sql =
                "INSERT INTO 
                    major (faculty_id, major_name)
                VALUES
                    (:faculty_id, :major_name)
                ";

            $stmt = $this->Conn->prepare($sql);
            $stmt->execute([
                'faculty_id' => $data['faculty_id'],
                'major_name' => $data['major_name']
            ]);

            $updated_row = $stmt->rowCount();
            return $updated_row;
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage(), 500);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

}

