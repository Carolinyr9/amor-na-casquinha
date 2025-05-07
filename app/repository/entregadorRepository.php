<?php
namespace app\repository;

use app\model\Entregador;
use app\config\DataBase;
use PDO;
use PDOException;
use Exception;
use app\utils\Logger;

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

    public function criarEntregador($nome, $email, $telefone, $cnh, $senha, $desativado = 0, $perfil = 'ENTR'){
        try {
            $stmt = $this->conn->prepare("
                INSERT INTO entregador
                (nome, email, telefone, cnh, senha, perfil, desativado) 
                VALUES 
                (:nome, :email, :telefone, :cnh, :senha, :perfil, :desativado)
            ");

            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':telefone', $telefone);
            $stmt->bindParam(':cnh', $cnh);
            $stmt->bindParam(':senha', $senha);
            $stmt->bindParam(':perfil', $perfil);
            $stmt->bindParam(':desativado', $desativado);
    
    
            $stmt->execute();
    
            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
            Logger::logError("Erro ao criar entregador: " . $e->getMessage());
        }
    }

    public function listarEntregadores() {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM entregador WHERE desativado = 0");
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: false;
        } catch (PDOException $e) {
            Logger::logError("Erro ao listar entregadores: " . $e->getMessage());
        }
    }

    public function listarEntregadorPorId($idEntregador) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM entregador WHERE idEntregador = ? AND desativado = 0");
            $stmt->bindParam(1, $idEntregador);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC) ?: false;
        } catch (PDOException $e) {
            Logger::logError("Erro ao listar entregador: " . $e->getMessage());
        }
    }

    public function listarEntregadorPorEmail($email) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM entregador WHERE email = ? AND desativado = 0");
            $stmt->bindParam(1, $email);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC) ?: false;
        } catch (PDOException $e) {
            Logger::logError("Erro ao listar entregador: " . $e->getMessage());
        }
    }

    public function editarEntregador($emailAntigo, $nome, $email, $telefone) {
        try {
            $stmt = $this->conn->prepare("
                UPDATE entregador
                SET nome = :nome, email = :email, telefone = :telefone
                WHERE email = :emailAntigo
            ");
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':telefone', $telefone);
            $stmt->bindParam(':emailAntigo', $emailAntigo);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC) ?: false;
        } catch (PDOException $e) {
            Logger::logError("Erro ao listar entregador: " . $e->getMessage());
        }
    }

    public function desativarEntregador($email) {
        try {
            $stmt = $this->conn->prepare("UPDATE entregador SET desativado = 1 WHERE email = :email");
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            return $stmt->execute();
        } catch (PDOException $e) {
            Logger::logError("Erro ao desativar o entregador: " . $e->getMessage());
        }
    }
}