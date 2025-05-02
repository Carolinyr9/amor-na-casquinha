<?php 
namespace app\controller;

use app\model2\Login;

class LoginController{
    public function __construct(){
        $this->login = new Login();
    }

    public function login($email, $senha){
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo '<script>alert("E-mail inválido.");</script>';
            return;
        }
        $this->login->login($email, $senha);
    }


    public function registrar($nome, $email, $senha, $celular, $rua, $numero, $bairro, $complemento, $cep, $cidade, $estado){
        if (!$email) {
            echo '<script>alert("E-mail inválido.");</script>';
            echo '<script>window.location.href = "../view/registro.php";</script>';
        }
    
        if (!preg_match("/^\(\d{2}\) \d{4,5}-\d{4}$/", $celular)) {
            echo '<script>alert("celular inválido. Coloque no formato (XX) 99999-9999");</script>';
            echo '<script>window.location.href = "../view/registro.php";</script>';
        }
    
        if (!preg_match("/^\d{5}-?\d{3}$/", $cep)) {
            echo '<script>alert("CEP inválido. Coloque no formato 99999-999");</script>';
            echo '<script>window.location.href = "../view/registro.php";</script>';
        }
    
        if (strlen($senha) < 8 || 
                !preg_match('/[A-Z]/', $senha) || 
                !preg_match('/[a-z]/', $senha) || 
                !preg_match('/\d/', $senha) || 
                !preg_match('/[\W_]/', $senha)) {
            echo '<script>alert("A senha deve ter pelo menos 8 caracteres, incluindo uma letra maiúscula, uma minúscula, um número e um caractere especial.");</script>';
            echo '<script>window.location.href = "../view/registro.php";</script>';
        }

        $this->login->registrar($nome, $email, $senha, $celular, $rua, $numero, $bairro, $complemento, $cep, $cidade, $estado);

    }
}

?>