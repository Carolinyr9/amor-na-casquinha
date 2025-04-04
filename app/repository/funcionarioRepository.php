<?php 
namespace app\repository;

use app\config\DataBase;
use PDO;
use PDOException;

class FuncionarioRepository {
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

    public function buscarFuncionarios(): array {
        try {
            $stmt = $this->conn->prepare("SELECT idFuncionario, desativado, adm, nome, telefone, email, senha, idEndereco FROM funcionarios WHERE desativado = 0");
            $stmt->execute();

            $funcionarios = [];

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $funcionario = new Funcionario(
                    $row['idFuncionario'],
                    $row['desativado'],
                    $row['adm'],
                    $row['nome'],
                    $row['telefone'],
                    $row['email'],
                    $row['senha'],
                    $row['idEndereco']
                );
                $funcionarios[] = $funcionario;
            }

            return $funcionarios;
        } catch (PDOException $e) {
            throw new Exception("Erro ao buscar funcionários: " . $e->getMessage());
        }
    }

    public function buscarFuncionarioPorEmail(string $email): ?Funcionario {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM funcionarios WHERE email = :email LIMIT 1");
            $stmt->bindParam(":email", $email);
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                return new Funcionario(
                    $row['idFuncionario'],
                    $row['desativado'],
                    $row['adm'],
                    $row['nome'],
                    $row['telefone'],
                    $row['email'],
                    $row['senha'],
                    $row['idEndereco']
                );
            }
            return null;
        } catch (PDOException $e) {
            throw new Exception("Erro ao buscar funcionário por email: " . $e->getMessage());
        }
    }

    public function criarFuncionario($nome, $email, $telefone, $senha, $adm): int {
        try {
            $stmt = $this->conn->prepare("
                INSERT INTO funcionarios (
                    nome, email, telefone, senha, adm, perfil, desativado
                ) VALUES (
                    :nome, :email, :telefone, :senha, :adm, 'FUNC', 0
                )
            ");

            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':telefone', $telefone);
            $stmt->bindParam(':senha', $senha);
            $stmt->bindParam(':adm', $adm);

            $stmt->execute();
            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
            throw new Exception("Erro ao inserir o funcionário: " . $e->getMessage());
        }
    }

    public function editarFuncionario($emailAntigo, $nome, $emailNovo, $telefone): bool {
        try {
            $stmt = $this->conn->prepare("
                UPDATE funcionarios SET
                    nome = :nome,
                    email = :emailNovo,
                    telefone = :telefone
                WHERE email = :emailAntigo
            ");

            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':emailNovo', $emailNovo);
            $stmt->bindParam(':telefone', $telefone);
            $stmt->bindParam(':emailAntigo', $emailAntigo);

            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Erro ao editar o funcionário: " . $e->getMessage());
        }
    }

    public function desativarFuncionario($email): bool {
        try {
            $stmt = $this->conn->prepare("
                UPDATE funcionarios SET
                    desativado = 1,
                    senha = NULL
                WHERE email = :email
            ");

            $stmt->bindParam(':email', $email);

            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Erro ao desativar o funcionário: " . $e->getMessage());
        }
    }
}