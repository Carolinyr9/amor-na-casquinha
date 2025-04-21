<?php
namespace app\repository;

use app\model\Entregador;
use app\config\DataBase;
use PDO;
use PDOException;

class EntregadorRepository{
    private $conn;

    public function __construct() {
        $this->getConnectionDataBase();
    }

    public function getConnectionDataBase() {
        try {
            $database = new DataBase();
            $this->conn = $database->getConnection();  
        } catch (PDOException $e) {
            die("Erro ao conectar com o banco de dados: " . $e->getMessage());
        }
    }

    public function listarEntregadores() {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM entregador");
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: false;
        } catch (PDOException $e) {
            throw new Exception("Erro ao listar entregadores: " . $e->getMessage());
        }
    }

    public function listarEntregadorPorId($idEntregador) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM entregador WHERE id = ?");
            $stmt->bindParam(1, $idEntregador);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC) ?: false;
        } catch (PDOException $e) {
            throw new Exception("Erro ao listar entregador: " . $e->getMessage());
        }
    }

    public function listarEntregadorPorEmail($email) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM entregador WHERE email = ?");
            $stmt->bindParam(1, $email);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC) ?: false;
        } catch (PDOException $e) {
            throw new Exception("Erro ao listar entregador: " . $e->getMessage());
        }
    }
}