<?php
namespace App\Model;
use App\Utils\Database;
use Exception;
use PDO;
use PDOException;

class ReportModel
{
    private static $Database;
    private $Conn;
    public function __construct()
    {
        self::$Database = new Database();
        $this->Conn = self::$Database->connect();
    }

    public function GetFacultyReportById($faculty_id)
    {
        try{
            
        }catch(Exception $e){

        }catch(PDOException $e){

        }
    }
}
