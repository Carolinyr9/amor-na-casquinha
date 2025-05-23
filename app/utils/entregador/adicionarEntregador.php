<?php
use app\controller\EntregadorController;

if(isset($_POST['submitBtn'])) {
    $entregadorController = new EntregadorController();
    
    $dados = [
        'nome' => $_POST['nome'],
        'email' => $_POST['email'],
        'telefone' => $_POST['telefone'],
        'cnh' => $_POST['cnh'],
        'senha' => password_hash($_POST['senha'], PASSWORD_DEFAULT),
    ];

    $entregadorController->criarEntregador($dados);

    header("Location: gerenciarEntregadores.php");
    exit;
}