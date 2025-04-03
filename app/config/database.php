<?php

namespace app\config;

use PDO;
use PDOException;

class DataBase {
    private $host;
    private $db;
    private $user;
    private $pass;
    private $charset;
    private $conn;

    public function __construct($db = null) {
        $env = file_get_contents(__DIR__."/.env");
        $lines = explode("\n", $env);

        foreach ($lines as $line) {
            preg_match("/([^#]+)\=(.*)/", $line, $matches);
            if (isset($matches[2])) {
                putenv(trim($line));
            }
        }

        $this->host = 'localhost';
        $this->db = $db ?? 'db_sorveteria'; // Se não passar db, usa o db padrão
        $this->user = 'root';
        $this->pass = '';
        $this->charset = 'utf8mb4';
    }

    public function getConnection() {
        if (!$this->conn) {
            $dsn = "mysql:host={$this->host};dbname={$this->db};charset={$this->charset}";
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            try {
                $this->conn = new PDO($dsn, $this->user, $this->pass, $options);
            } catch (PDOException $e) {
                throw new PDOException($e->getMessage(), (int)$e->getCode());
            }
        }

        return $this->conn;
    }

    public function createTestDatabase() {
        $this->conn->exec("CREATE DATABASE IF NOT EXISTS db_test");
        $this->conn->exec("USE db_test");
    }

    public function dropTestDatabase() {
        $this->conn->exec("DROP DATABASE IF EXISTS db_test");
    }

    public function closeConnection() {
        $this->conn = null;
    }
}
?>