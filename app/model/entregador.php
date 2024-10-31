<?php 
require_once '../config/database.php';

class Entregador {
    private $conn;

    public function __construct() {
        $database = new DataBase();
        $this->conn = $database->getConnection();
    }

    public function listarEntregadores() {
        try {
            $stmt = $this->conn->prepare("CALL ListarEntregadores()");
            $stmt->execute(); 

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Erro ao listar entregadores: " . $e->getMessage());
        }
    }

    public function listarEntregadorPorId($idEntregador) {
        try {
            $stmt = $this->conn->prepare("CALL ListarEntregadorPorID(?)");
            $stmt->bindParam(1, $idEntregador);
            $stmt->execute(); 
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Erro ao listar entregador: " . $e->getMessage());
        }
    }
}
?>
