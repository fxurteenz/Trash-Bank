<?php
namespace App\Model;
use App\Utils\Database;
use Exception;
use PDO;
use PDOException;

class FacultyModel
{
    private static $Database;
    private $Conn;

    public function __construct()
    {
        self::$Database = new Database();
        $this->Conn = self::$Database->connect();
    }

    public function GetAllFaculty(): array
    {
        try {
            $sql = "SELECT * FROM faculty";
            $stmt = $this->Conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage(), 500);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function CreateFaculty(array $data): int
    {
        try {
            if (!is_array($data)) {
                throw new Exception('Invalid data format', 400);
            }

            if (empty($data['faculty_name'])) {
                throw new Exception('faculty name not provided', 400);
            }

            $setClauses = [];
            $updateData = [];
            foreach ($data as $column => $value) {
                if (!empty($value)) {
                    $setClauses[] = "`{$column}` = :{$column}";
                    $updateData[$column] = $value;
                }
            }
            $setClauseString = implode(', ', $setClauses);

            $sql =
                "INSERT INTO 
                    faculty
                SET
                    {$setClauseString}
                ";
            $stmt = $this->Conn->prepare($sql);
            $stmt->execute($updateData);

            $updated_row = $stmt->rowCount();
            return $updated_row;
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage(), 500);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function UpdateFaculty($fid, $data): mixed
    {
        try {
            if (empty($data) && !is_array($data) || empty($fid)) {
                throw new Exception('Bad Request =(', 400);
            }

            // $encodedPassword = password_hash(
            //     $data['password'],
            //     PASSWORD_DEFAULT,
            //     ['cost' => self::$SaltRound]
            // );

            $setClauses = [];
            $updateData = [];
            foreach ($data as $column => $value) {
                if (!empty($value)) {
                    $setClauses[] = "`{$column}` = :{$column}";
                    $updateData[$column] = $value;
                }
            }
            $setClauseString = implode(', ', $setClauses);

            $sql =
                "UPDATE faculty
                SET 
                    {$setClauseString}
                WHERE
                    faculty_id = :faculty_id
                ";

            $stmt = $this->Conn->prepare($sql);
            $stmt->execute(array_merge($updateData, ['faculty_id' => $fid]));

            $result = $stmt->rowCount();
            return $result;
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage(), 500);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode() ?: 400);
        }
    }
}

