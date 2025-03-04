<?php
namespace app\model;

use app\config\DataBase;
use Cliente;
use PDO;
use PDOException;

class Login {
    private $conn;

    public function __construct(){
        $database = new DataBase();
        $this->conn = $database->getConnection();
    }

    private function estaLogado() {
        return isset($_SESSION["userEmail"]);
    }

    private function redirecionarPara($url) {
        header("Location: $url");
        exit();
    }

    private function usuarioDesativado($status) {
        return $status == 1;
    }

    private function autenticarUsuario($email) {
        $stmt = $this->conn->prepare("CALL Login(?)");
        $stmt->bindParam(1, $email);
        $stmt->execute();
        return $stmt->fetchAll();
    }


    private function iniciarNovaSessao($dadosUsuario) {
        session_unset(); 
        session_destroy(); 
        session_start(); 

        $_SESSION["userEmail"] = $dadosUsuario["email"];
        $_SESSION["userName"] = $dadosUsuario["nome"];
        $_SESSION["userTel"] = $dadosUsuario["telefone"];
        $_SESSION["userPerfil"] = $dadosUsuario["perfil"];
    }

    public function login($email, $senha) {
        if ($this->estaLogado()) {
            $this->redirecionarPara('../view/sobre.php');
        }

        if (!isset($_POST["email"]) || !isset($_POST["senha"])) {
            $this->redirecionarPara('../view/index.php');
        }

        $resultado = $this->autenticarUsuario($email);

        foreach ($resultado as $linha) {
            $perfil = $linha["perfil"];
            $desativado = $linha["desativado"];

            if ($this->usuarioDesativado($desativado)) {
                echo '<script>
                    alert("Este usuário não possui mais acesso, entre em contato com um administrador!");
                    window.location.replace("../view/login.php");
                </script>';
                exit();
            }

            if (password_verify($senha, $linha["senha"])) {
                $this->iniciarNovaSessao($linha);
                $this->redirecionarPorPerfil($perfil);
            } else {
                echo '<script>
                    alert("E-mail ou senha incorretos!");
                    location.replace("../view/login.php");
                </script>';
                exit();
            }
        }
    }

    private function redirecionarPorPerfil($perfil) {
        switch ($perfil) {
            case 'FUNC':
                $this->redirecionarPara('../view/relatorios.php');
                break;
            case 'CLIE':
                $this->redirecionarPara('../view/sobre.php');
                break;
            case 'ENTR':
                $this->redirecionarPara('../view/pedidosEntregador.php');
                break;
            default:
                echo '<script>
                    alert("Perfil desconhecido.");
                    location.replace("../view/login.php");
                </script>';
                exit();
        }
    }

    public function logout() {
        session_start();
        session_destroy();
        $this->redirecionarPara('../view/index.php');
    }

    public function registrar($nome, $email, $senha, $celular, $rua, $numero, $bairro, $complemento, $cep, $cidade, $estado) {
        if ($this->estaLogado()) {
            $this->redirecionarPara('../sobre.php');
        }

        try {
            $cliente = new Cliente();
            $resultado = $cliente->getCliente($email);
    
            if ($resultado['email'] == $email) {
                echo '<script>
                    alert("E-mail já cadastrado!");
                    window.location.href = "../view/registro.php";
                </script>';
                exit;
            }
    
            $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
    
            $stmt = $this->conn->prepare("CALL InserirCliente(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bindParam(1, $nome);
            $stmt->bindParam(2, $email);
            $stmt->bindParam(3, $senhaHash); 
            $stmt->bindParam(4, $celular);
            $stmt->bindParam(5, $rua);
            $stmt->bindParam(6, $numero);
            $stmt->bindParam(7, $complemento);
            $stmt->bindParam(8, $bairro);
            $stmt->bindParam(9, $cep);
            $stmt->bindParam(10, $cidade);
            $stmt->bindParam(11, $estado);
    
            $stmt->execute();
    
            echo '<script>
                alert("Cadastro realizado com sucesso!");
            </script>';

            $linha = array(
                "email" => $email,
                "nome" => $nome,
                "telefone" => $celular,
                "perfil" => "CLIE"
            );
            $perfil = $linha["perfil"];
            $this->iniciarNovaSessao($linha);
            $this->redirecionarPorPerfil($perfil);
    
        } catch (PDOException $e) {
            error_log("Erro ao registrar o cliente: " . $e->getMessage());
            echo '<script>
                alert("Erro ao cadastrar. Tente novamente mais tarde.");
                location.replace("../view/registro.php");
            </script>';
        }
    }
    
}
?>
