<?php 
    require_once '../config/database.php';

    class Employee{
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

    }

    public function createEmployee($login, $senha, $nome, $telefone){
        try{
            $value = "FUNC";
            $cmd = $conn->prepare("CALL SP_FuncionarioCreate(?, ?, ?, ?, ?)");
            $cmd->bindParam(1, $_POST['emailFunAdd']);
            $cmd->bindParam(2, $_POST['senhaFunAdd']);
            $cmd->bindParam(3, $_POST['nomeFunAdd']);
            $cmd->bindParam(4, $_POST['telefoneFunAdd']);
            $cmd->bindParam(5, $value);
            
            $cmd->execute();
        } catch (PDOException $e) {
            echo "Erro no banco de dados: " . $e->getMessage();
        } catch (PDOException $e) {
            echo "Erro genérico: " . $e->getMessage();
        }
        
        echo '<script>';
        echo 'window.location.href = "";';
        echo '</script>';
        exit;
    }

?>