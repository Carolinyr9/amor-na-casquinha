<?php 
namespace app\controller;
use app\model\Usuario;
use app\repository\UsuarioRepository;

class UsuarioController {
    private $usuarioRepository;

    public function __construct() {
        $this->usuarioRepository = new UsuarioRepository();
    }

    private function redirecionarPara($url) {
        header("Location: $url");
        exit();
    }

    private function estaLogado() {
        return isset($_SESSION["userEmail"]);
    }

    private function usuarioDesativado($status) {
        return $status == 1;
    }
    private function iniciarNovaSessao($dados) {
        session_unset(); 
        session_destroy(); 
        session_start(); 

        $_SESSION["userEmail"] = $dados["email"];
        $_SESSION["userName"] = $dados["nome"];
        $_SESSION["userPerfil"] = $dados["perfil"];
    }

    public function verificarUsuarioPorEmail($email) {
        return $this->usuarioRepository->verificarUsuarioPorEmail($email);
    }

    public function logar($email, $senha) {
        if(empty($email) || empty($senha)) {
            $this->redirecionarPara('../view/index.php');
        }

        if ($this->estaLogado()) {
            $this->redirecionarPara('../view/sobre.php');
        }

        $dados = $this->verificarUsuarioPorEmail($email);

        if($dados) {
            if ($this->usuarioDesativado($dados["desativado"])) {
                echo '<script>
                    alert("Este usuário não possui mais acesso, entre em contato com um administrador!");
                    window.location.replace("../view/login.php");
                </script>';
                exit();
            }

            if (password_verify($senha, $dados["senha"])) {
                $this->iniciarNovaSessao($dados);
                $this->redirecionarPorPerfil($dados["perfil"]);
            } else {
                echo '<script>
                    alert("E-mail ou senha incorretos!");
                    location.replace("../view/login.php");
                </script>';
                exit();
            }
        }
    }

    public function logout() {
        session_start();
        session_destroy();
        $this->redirecionarPara('../view/index.php');
    }
}