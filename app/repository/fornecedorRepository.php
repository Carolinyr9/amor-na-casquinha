<?php 
namespace app\repository;

use app\config\DataBase;
use app\model2\Fornecedor;
use PDO;
use PDOException;
use Exception;
use app\utils\Logger;

class FornecedorRepository {
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

    public function listarFornecedor() {
        $fornecedores = [];
        try {
            $stmt = $this->conn->prepare("SELECT * FROM fornecedor WHERE desativado = 0");
            $stmt->execute();
    
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $fornecedores[] = [
                    'idFornecedor' => $row['idFornecedor'],
                    'nome' => $row['nome'],  
                    'telefone' => $row['telefone'],  
                    'email' => $row['email'],
                    'cnpj' => $row['cnpj'],  
                    'desativado' => $row['desativado'], 
                    'idEndereco' => $row['idEndereco'],
                ];
            }
        } catch (PDOException $e) {
            throw new Exception("Erro ao buscar fornecedores: " . $e->getMessage());
        }
        return $fornecedores;
    }

    public function buscarFornecedorPorEmail($email) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM fornecedor WHERE email = :email AND desativado = 0 LIMIT 1");
            $stmt->bindParam(":email", $email, PDO::PARAM_STR);
            $stmt->execute();
            $dados = $stmt->fetch(PDO::FETCH_ASSOC);
    
            return $dados ? new Fornecedor(
                $dados['idFornecedor'],
                $dados['nome'],  
                $dados['telefone'],  
                $dados['email'],
                $dados['cnpj'],  
                $dados['desativado'] == 0, 
                $dados['idEndereco']
            ) : null;
        } catch (PDOException $e) {
            throw new Exception("Erro ao buscar fornecedor por e-mail: " . $e->getMessage());
        }
    }

    public function criarFornecedor($nome, $email, $telefone, $cnpj, $idEndereco, $desativado = 0) {
        try {
            $stmt = $this->conn->prepare("
                INSERT INTO fornecedor
                (nome, email, telefone, cnpj, idEndereco, desativado) 
                VALUES 
                (:nome, :email, :telefone, :cnpj, :idEndereco, :desativado)
            ");
            
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':telefone', $telefone);
            $stmt->bindParam(':cnpj', $cnpj);
            $stmt->bindParam(':idEndereco', $idEndereco);
            $stmt->bindParam(':desativado', $desativado);
    
    
            $stmt->execute();
    
            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
            throw new Exception("Erro ao inserir o fornecedor: " . $e->getMessage());
        }
    }

    public function editarFornecedor($emailAntigo, $nome, $email, $telefone) {
        try {
            $stmt = $this->conn->prepare("
                UPDATE fornecedor
                SET nome = :nome, email = :email, telefone = :telefone 
                WHERE email = :emailAntigo
            ");
    
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':telefone', $telefone);
            $stmt->bindParam(':emailAntigo', $emailAntigo);
    
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Erro ao editar o fornecedor: " . $e->getMessage());
        }
    }

    public function desativarFornecedor($email) {
        try {
            $stmt = $this->conn->prepare("UPDATE fornecedor SET desativado = 1 WHERE email = :email");
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Erro ao desativar o fornecedor: " . $e->getMessage());
        }
    }
}
