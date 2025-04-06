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

    private function getConnectionDataBase() {
        try {
            $database = new DataBase();
            $this->conn = $database->getConnection();  
        } catch (PDOException $e) {
            die("Erro ao conectar com o banco de dados: " . $e->getMessage());
        }
    }

    private function listarEntregadores() {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM entregador");
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                return false;
            }
        } catch (PDOException $e) {
            echo "Erro ao listar os entregadores: " . $e->getMessage();
        }
    }

    private function listarEntregadorPorId($idEntregador) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM entregador WHERE id = ?");
            $stmt->bindParam(1, $idEntregador);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                return false;
            }

        } catch (PDOException $e) {
            echo "Erro ao listar entregador: " . $e->getMessage();
        }
    }
}