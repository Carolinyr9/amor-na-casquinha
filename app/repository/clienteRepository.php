<?php 
namespace app\repository;

use app\config\DataBase;
use PDO;
use PDOException;
use app\utils\helpers\Logger;
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

    public function criarCliente($nome, $email, $senha, $telefone, $idEndereco, $perfil = 'CLIE', $desativado = 0) {
        try {
            $stmt = $this->conn->prepare("
                INSERT INTO cliente
                (nome, email, senha, telefone, perfil, idEndereco, desativado) 
                VALUES 
                (:nome, :email, :senha, :telefone, :perfil, :idEndereco, :desativado)
            ");
    
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':senha', $senha);
            $stmt->bindParam(':telefone', $telefone);
            $stmt->bindParam(':perfil', $perfil);
            $stmt->bindParam(':idEndereco', $idEndereco);
            $stmt->bindParam(':desativado', $desativado, PDO::PARAM_INT);
    
            $stmt->execute();
    
            return $this->conn->lastInsertId();
    
        } catch (PDOException $e) {
            Logger::logError("Erro ao criar cliente: " . $e->getMessage());
            return false; 
        }
    }
    

    public function listarClientePorEmail($email) {
        try {
            $stmt = $this->conn->prepare(" SELECT * FROM cliente WHERE email = ?");
            $stmt->bindParam(1, $email);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC) ?: false;
        } catch (PDOException $e) {
            Logger::logError("Erro ao listar o cliente: " . $e->getMessage());
        }
    }

    public function editarCliente($nome, $telefone, $email, $emailAntigo) {
        try {
            $stmt = $this->conn->prepare(" UPDATE cliente SET nome = ?, telefone = ?, email = ? WHERE email = ?");
            $stmt->bindParam(1, $nome);
            $stmt->bindParam(2, $telefone);
            $stmt->bindParam(3, $email);
            $stmt->bindParam(4, $emailAntigo);
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            Logger::logError("Erro ao editar o cliente: " . $e->getMessage());
        }
    }

    public function editarSenha($senhaNova, $senhaAtual, $idCliente) {
        try {
            $stmt = $this->conn->prepare("SELECT senha FROM cliente WHERE idCliente = ?");
            $stmt->bindParam(1, $idCliente);
            $stmt->execute();
            $senhaHash = $stmt->fetchColumn();

            if (!$senhaHash || !password_verify($senhaAtual, $senhaHash)) {
                return false; 
            }

            $novaSenhaHash = password_hash($senhaNova, PASSWORD_DEFAULT);
            $stmt = $this->conn->prepare("UPDATE cliente SET senha = ? WHERE idCliente = ?");
            $stmt->bindParam(1, $novaSenhaHash);
            $stmt->bindParam(2, $idCliente);
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            Logger::logError("Erro ao editar senha: " . $e->getMessage());
            return false;
        }
    }

    public function desativarPerfil($email) {
        try {
            $stmt = $this->conn->prepare("UPDATE cliente SET desativado = 1 WHERE email = :email");
            $stmt->bindParam(":email", $email, PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            Logger::logError("Erro ao desativar o cliente: " . $e->getMessage());
        }
    }

    public function listarClientes() {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM cliente WHERE desativado = 0");
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: false;
        } catch (PDOException $e) {
            Logger::logError("Erro ao listar clientes: " . $e->getMessage());
        }
    }

}
