<?php 
namespace app\repository;

use app\config\DataBase;
use app\model2\Fornecedor;
use PDO;
use PDOException;
use Exception;

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
            $stmt = $this->conn->prepare("SELECT * FROM fornecedores WHERE desativado = 0");
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
            $stmt = $this->conn->prepare("SELECT * FROM fornecedores WHERE email = :email AND desativado = 0 LIMIT 1");
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

    public function criarFornecedor($nome, $email, $telefone, $cnpj) {
        try {
            $stmt = $this->conn->prepare("
                INSERT INTO fornecedores 
                (nome, email, telefone, cnpj) 
                VALUES 
                (:nome, :email, :telefone, :cnpj)
            ");
    
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':telefone', $telefone);
            $stmt->bindParam(':cnpj', $cnpj);
    
            $stmt->execute();
    
            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
            throw new Exception("Erro ao inserir o fornecedor: " . $e->getMessage());
        }
    }

    public function editarFornecedor($emailAntigo, $nome, $email, $telefone) {
        try {
            $stmt = $this->conn->prepare("
                UPDATE fornecedores 
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
            $stmt = $this->conn->prepare("UPDATE fornecedores SET desativado = 1 WHERE email = :email");
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Erro ao desativar o fornecedor: " . $e->getMessage());
        }
    }
}
