<?php 
namespace app\controller2;

use app\repository\UsuarioRepository;
use app\model2\Usuario;

class UsuarioController {
    private $repository;

    public function __construct() {
        $this->repository = new UsuarioRepository();
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
        $_SESSION["userTel"] = $dados["telefone"] ?? '';
        $_SESSION["userPerfil"] = $dados["perfil"];
    }

    private function redirecionarPorPerfil($perfil) {
        switch ($perfil) {
            case 'FUNC':
                $this->redirecionarPara('../view/gerenciarCategorias.php');
                break;
            case 'CLIE':
                $this->redirecionarPara('../view/perfil.php');
                break;
            case 'ENTR':
                $this->redirecionarPara('../view/pedidosEntregador.php');
                break;
            default:
                echo '<script>alert("Perfil desconhecido."); location.replace("../view/login.php");</script>';
                exit();
        }
    }

    public function login($email, $senha) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo '<script>alert("E-mail inválido.");</script>';
            return;
        }

        if ($this->estaLogado()) {
            $this->redirecionarPara('../view/perfil.php');
        }

        $dados = $this->repository->verificarUsuarioPorEmail($email);

        if (!$dados || isset($dados['Status'])) {
            echo '<script>alert("E-mail ou senha incorretos!"); location.replace("../view/login.php");</script>';
            return;
        }

        if ($this->usuarioDesativado($dados["desativado"] ?? 0)) {
            echo '<script>alert("Este usuário não possui mais acesso, entre em contato com um administrador!"); location.replace("../view/login.php");</script>';
            return;
        }

        if (password_verify($senha, $dados["senha"])) {
            $this->iniciarNovaSessao($dados);
            $this->redirecionarPorPerfil($dados["perfil"]);
        } else {
            echo '<script>alert("E-mail ou senha incorretos!"); location.replace("../view/login.php");</script>';
        }
    }

    public function validarDados($email, $senha, $celular, $cep) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo '<script>alert("E-mail inválido."); window.location.href = "../view/registro.php";</script>';
            return;
        }

        if (!preg_match("/^\(\d{2}\) \d{4,5}-\d{4}$/", $celular)) {
            echo '<script>alert("Celular inválido. Use o formato (XX) 99999-9999"); window.location.href = "../view/registro.php";</script>';
            return;
        }

        if (!preg_match("/^\d{5}-?\d{3}$/", $cep)) {
            echo '<script>alert("CEP inválido. Use o formato 99999-999"); window.location.href = "../view/registro.php";</script>';
            return;
        }

        if (!$this->senhaValida($senha)) {
            echo '<script>alert("A senha deve ter pelo menos 8 caracteres, incluindo uma letra maiúscula, minúscula, número e símbolo."); window.location.href = "../view/registro.php";</script>';
            return;
        }

        return true;
    }

    public function registrarCliente($email, $nome, $celular){
        $dados = [
            "email" => $email,
            "nome" => $nome,
            "telefone" => $celular,
            "perfil" => "CLIE"
        ];
        $this->iniciarNovaSessao($dados);
        $this->redirecionarPorPerfil("CLIE");
    }

    private function senhaValida($senha) {
        return strlen($senha) >= 8 &&
               preg_match('/[A-Z]/', $senha) &&
               preg_match('/[a-z]/', $senha) &&
               preg_match('/\d/', $senha) &&
               preg_match('/[\W_]/', $senha);
    }

    public function logout() {
        session_start();
        session_destroy();
        $this->redirecionarPara('../view/index.php');
    }
}
