<?php 
namespace app\model;

use app\config\DataBase;
use PDO;
use PDOException;

    class Fornecedores{
        private $id;
        private $nome;
        private $tipo;
        private $telefone;
        private $login;
        private $senha;
        private $conn;

        public function __construct(){
            $database = new DataBase;
            $this->conn = $database->getConnection();
        }

        public function listarForn(){
            try{
                $cmd = $this->conn->prepare("CALL ListarFornecedores()");
                $cmd->execute();
                $funcionarios = $cmd->fetchAll();
                return $funcionarios;
            } catch (PDOException $e) {
                echo "Erro no banco de dados: " . $e->getMessage();
            }

        }

        public function listarFornecedorEmail($email){
            try{
                $cmd = $this->conn->prepare("CALL ListarFornecedorPorEmail(?)");
                $cmd->bindParam(1, $email);
                $cmd->execute();
                $funcionarios = $cmd->fetch(PDO::FETCH_ASSOC);
                return $funcionarios;
            } catch (PDOException $e) {
                echo "Erro no banco de dados: " . $e->getMessage();
            }
 
        }

        public function inserirForn($nome, $email, $telefone, $cnpj, $rua, $numero, $bairro, $complemento, $cep, $cidade, $estado){
            try{
                $cmd = $this->conn->prepare("CALL InserirFornecedor(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $cmd->bindParam(1, $nome);
                $cmd->bindParam(2, $email);
                $cmd->bindParam(3, $telefone);
                $cmd->bindParam(4, $cnpj);
                $cmd->bindParam(5, $rua);
                $cmd->bindParam(6, $numero);
                $cmd->bindParam(7, $complemento);   
                $cmd->bindParam(8, $bairro);
                $cmd->bindParam(9, $cep);
                $cmd->bindParam(10, $cidade);
                $cmd->bindParam(11, $estado);
                $cmd->execute();
            } catch (PDOException $e) {
                echo "Erro no banco de dados: " . $e->getMessage();
            } 
            
            echo '<script>window.location.href = "../view/sessaoFornecedores.php";</script>';
            exit;
        }

        public function atualizarForn($emailAntigo, $nome, $email, $telefone){
            try{
                $cmd = $this->conn->prepare("CALL EditarFornecedorPorEmail(?, ?, ?, ?)");
                $cmd->bindParam(1, $emailAntigo);
                $cmd->bindParam(2, $email);
                $cmd->bindParam(3, $nome);
                $cmd->bindParam(4, $telefone);
                $cmd->execute();

                echo '<script>window.location.href = "../view/sessaoFornecedores.php";</script>';
                exit;
            } catch (PDOException $e) {
                echo "Erro no banco de dados: " . $e->getMessage();
            }
        }

        public function deletarForn($email){
            try{
                $cmd = $this->conn->prepare("CALL DesativarFornecedorPorEmail(?)");
                $cmd->bindParam(1, $email);
                $cmd->execute();

                echo '<script>window.location.href = "../view/sessaoFornecedores.php";</script>';
                exit;
            } catch (PDOException $e) {
                echo "Erro no banco de dados: " . $e->getMessage();
            }
        }

    }

    

?>