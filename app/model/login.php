<?php
require_once '../config/database.php';

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

    private function autenticarUsuario($email, $senha) {
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

    public function login() {
        if ($this->estaLogado()) {
            $this->redirecionarPara('../sobre.php');
        }

        if (!isset($_POST["email"]) || !isset($_POST["senha"])) {
            $this->redirecionarPara('../view/index.php');
        }

        $email = $_POST["email"];
        $senha = $_POST["senha"];

        $resultado = $this->autenticarUsuario($email, $senha);

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

            if ($senha == $linha["senha"]) {
                $this->iniciarNovaSessao($linha);
                $this->redirecionarPorPerfil($perfil);
            } else {
                echo '<script>
                    alert("Senha errada!");
                    location.replace("../view/login.php");
                </script>';
                exit();
            }
        }
    }

    private function redirecionarPorPerfil($perfil) {
        switch ($perfil) {
            case 'FUNC':
                $this->redirecionarPara('../view/editarProdutos.php');
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
}
?>
