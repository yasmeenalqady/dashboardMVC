<?php
namespace App\Core;

use PDO;
use PDOException;

class Database {
    private $host;
    private $db;
    private $user;
    private $pass;
    private $charset;
    private $pdo;

    public function __construct() {
        // تحميل القيم من ملف .env
        $this->host    = $_ENV['DB_HOST'] ?? '127.0.0.1';
        $this->db      = $_ENV['DB_NAME'] ?? 'mydata';
        $this->user    = $_ENV['DB_USER'] ?? 'root';
        $this->pass    = $_ENV['DB_PASS'] ?? '';
        $this->charset = $_ENV['DB_CHARSET'] ?? 'utf8mb4';
    }

    public function connect() {
        if ($this->pdo) return $this->pdo;

        $dsn = "mysql:host={$this->host};dbname={$this->db};charset={$this->charset}";
        $opt = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ];

        try {
            $this->pdo = new PDO($dsn, $this->user, $this->pass, $opt);
            return $this->pdo;
        } catch (PDOException $e) {
            die(json_encode([
                'success' => false,
                'message' => 'DB connection error: ' . $e->getMessage()
            ]));
        }
    }
}
