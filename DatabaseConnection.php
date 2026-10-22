<?php
class DatabaseConnection {
    private static $instance = null;
    private $connection;
    private $host = 'localhost';
    private $database = 'manajemen_produk'; 
    private $username = 'root';
    private $pass = '';

    private function __construct() {
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->database};charset=utf8";
            $this->connection = new PDO($dsn, $this->username, $this->pass);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Koneksi database gagal: " . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->connection;
    }
}