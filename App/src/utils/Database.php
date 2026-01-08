<?php
namespace App\Utils;
use PDO;
use PDOException;
use RuntimeException;
use Exception;

class Database{
    private $host,$port, $db_name, $username, $password, $options, $conn;
    public function __construct()
    {
        $requiredEnvVars = ['DB_HOST', 'DB_PORT', 'DB_DATABASE', 'DB_USERNAME', 'DB_PASSWORD'];
        $missingVars = [];

        foreach ($requiredEnvVars as $var) {
            if (!isset($_ENV[$var])) {
                $missingVars[] = $var;
            }
        }

        if (!empty($missingVars)) {
            throw new RuntimeException(
                'Missing required environment variables: ' . implode(', ', $missingVars)
            );
        }
        $this->host = $_ENV['DB_HOST'];
        $this->port = $_ENV['DB_PORT'];
        $this->db_name = $_ENV['DB_DATABASE'];
        $this->username = $_ENV['DB_USERNAME'];
        $this->password = $_ENV['DB_PASSWORD'];
        $this->options = [
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ];
    }
    public function connect(): ?PDO
    {
        $this->conn = null;
        try {
            $dsn = "mysql:host={$this->host};port={$this->port};dbname={$this->db_name};charset=utf8mb4";
            error_log("Attempting to connect with DSN: $dsn"); // เพิ่ม log

            $this->conn = new PDO(
                dsn: $dsn,
                username: $this->username,
                password: $this->password,
                options: $this->options
            );
            
            // Force UTF-8 character encoding
            $this->conn->exec("SET NAMES 'utf8mb4' COLLATE 'utf8mb4_unicode_ci'");
            $this->conn->exec("SET CHARACTER SET utf8mb4");
            
            return $this->conn;
        } catch (PDOException $e) {
            $error = "Database Connection Error: " . $e->getMessage();
            error_log($error);
            throw new Exception($error);
        }
    }
}
