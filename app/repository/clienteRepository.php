<?php 
namespace app\repository;

use app\config\DataBase;
use PDO;
use PDOException;

class ClienteRepository {
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

    public function listarClientePorEmail($email) {
        try {
            $stmt = $this->conn->prepare(" SELECT * FROM clientes WHERE email = ?");
            $stmt->bindParam(1, $email);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC) ?: false;
        } catch (PDOException $e) {
            throw new Exception("Erro ao listar o cliente: " . $e->getMessage());
        }
    }

    public function editarCliente($cliente) {
        try {
            $stmt = $this->conn->prepare(" UPDATE clientes SET nome = ?, telefone = ? WHERE email = ?");
            $stmt->bindParam(1, $cliente->getNome());
            $stmt->bindParam(2, $cliente->getTelefone());
            $stmt->bindParam(3, $cliente->getEmail());
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            throw new Exception("Erro ao editar o cliente: " . $e->getMessage());
        }
    }


}
