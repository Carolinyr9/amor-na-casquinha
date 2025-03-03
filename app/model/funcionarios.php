<?php 
    require_once '../config/DataBase.php';

    class Funcionarios{
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

        public function listarFunc(){
            try{
                $cmd = $this->conn->prepare("CALL ListarFuncionarios()");
                $cmd->execute();
                $funcionarios = $cmd->fetchAll();
                return $funcionarios;
            } catch (PDOException $e) {
                echo "Erro no banco de dados: " . $e->getMessage();
            }

        }

        public function listarFuncionarioEmail($email){
            try{
                $cmd = $this->conn->prepare("CALL ListarFuncionarioPorEmail(?)");
                $cmd->bindParam(1, $email);
                $cmd->execute();
                $funcionarios = $cmd->fetchAll();
                return $funcionarios;
            } catch (PDOException $e) {
                echo "Erro no banco de dados: " . $e->getMessage();
            }
 
        }

        public function inserirFunc($nome, $email, $telefone, $senha, $adm){
            $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
            try{
                $cmd = $this->conn->prepare("CALL InserirFuncionario(?, ?, ?, ?, ?)");
                $cmd->bindParam(1, $nome);
                $cmd->bindParam(2, $email);
                $cmd->bindParam(3, $telefone);
                $cmd->bindParam(4, $senhaHash);
                $cmd->bindParam(5, $adm);
                $cmd->execute();
            } catch (PDOException $e) {
                echo "Erro no banco de dados: " . $e->getMessage();
            } 
            
            echo '<script>window.location.href = "../view/sessaoFuncionarios.php";</script>';
            exit;
        }

        public function atualizarFunc($emailAntigo, $nome, $email, $telefone){
            try{
                $cmd = $this->conn->prepare("CALL EditarFuncionarioPorEmail(?, ?, ?, ?)");
                $cmd->bindParam(1, $emailAntigo);
                $cmd->bindParam(2, $email);
                $cmd->bindParam(3, $nome);
                $cmd->bindParam(4, $telefone);
                $cmd->execute();

                echo '<script>window.location.href = "../view/sessaoFuncionarios.php";</script>';
                exit;
            } catch (PDOException $e) {
                echo "Erro no banco de dados: " . $e->getMessage();
            }
        }

        public function deletarFunc($email){
            try{
                $cmd = $this->conn->prepare("CALL DesativarFuncionarioPorEmail(?)");
                $cmd->bindParam(1, $email);
                $cmd->execute();

                echo '<script>window.location.href = "../view/sessaoFuncionarios.php";</script>';
                exit;
            } catch (PDOException $e) {
                echo "Erro no banco de dados: " . $e->getMessage();
            }
        }

    }


?>