<?php
use app\controller2\EntregadorController;

if(isset($_POST['submitBtn'])) {
    $entregadorController = new EntregadorController();
    
    $dados = [
        'nome' => $_POST['nomeEntr'],
        'email' => $_POST['emailEntr'],
        'telefone' => $_POST['telefoneEntr'],
        'cnh' => $_POST['cnhEntr'],
        'senha' => password_hash($_POST['senhaEntr'], PASSWORD_DEFAULT),
    ];

    $entregadorController->criarEntregador($dados);

    header("Location: gerenciarEntregadores.php");
    exit;
}