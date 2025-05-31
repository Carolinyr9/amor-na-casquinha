<?php
namespace app\repository;

use app\config\DataBase;
use PDO;
use PDOException;
use app\utils\helpers\Logger;
use Exception;

class EnderecoRepository {
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

    public function listarEnderecoPorId($idEndereco) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM enderecos WHERE idEndereco = ?");
            $stmt->bindParam(1, $idEndereco);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                return null;
            }
        } catch (PDOException $e) {
            Logger::logError("Erro ao listar o endereço: " . $e->getMessage());
        }
    }

    public function editarEndereco($rua, $numero, $complemento, $cep, $bairro, $estado, $cidade, $idEndereco) {
        try {
            $stmt = $this->conn->prepare("UPDATE enderecos SET rua = ?, numero = ?, cep = ?, bairro = ?, cidade = ?, estado = ?, complemento = ? WHERE idEndereco = ?");
            $stmt->bindParam(1, $rua);
            $stmt->bindParam(2, $numero);
            $stmt->bindParam(3, $cep);
            $stmt->bindParam(4, $bairro);
            $stmt->bindParam(5, $cidade);
            $stmt->bindParam(6, $estado);
            $stmt->bindParam(7, $complemento);
            $stmt->bindParam(8, $idEndereco);
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            Logger::logError("Erro ao editar o endereço: " . $e->getMessage());
        }
    }

    public function criarEndereco($rua, $numero, $cep, $bairro, $cidade, $estado, $complemento){
        try {
            $stmt = $this->conn->prepare("
                INSERT INTO enderecos
                (rua, numero, cep, bairro, cidade, estado, complemento) 
                VALUES 
                (:rua, :numero, :cep, :bairro, :cidade, :estado, :complemento)
            ");

            $stmt->bindParam(':rua', $rua);
            $stmt->bindParam(':numero', $numero);
            $stmt->bindParam(':cep', $cep);
            $stmt->bindParam(':bairro', $bairro);
            $stmt->bindParam(':cidade', $cidade);
            $stmt->bindParam(':estado', $estado);
            $stmt->bindParam(':complemento', $complemento);

            $stmt->execute();

            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
            Logger::logError("Erro ao inserir o endereço: " . $e->getMessage());
        }
    }

    public function listarEnderecos() {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM enderecos");
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: false;
        } catch (PDOException $e) {
            Logger::logError("Erro ao listar endereços: " . $e->getMessage());
        }
    }

}