<?php
// config/database.php

class Database {
    private $host = 'localhost';
    private $db_name = 'filemanager';
    private $username = 'root'; 
    private $password = ''; 
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8mb4";
            $this->conn = new PDO($dsn, $this->username, $this->password);
            
            // Force strict exception handling for errors
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Disable emulated prepares to enforce native parameterized queries (Security)
            $this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            
        } catch(PDOException $exception) {
            die("Database Connection Error: " . $exception->getMessage());
        }
        return $this->conn;
    }
}
?>