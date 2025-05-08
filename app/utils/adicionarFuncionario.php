<?php
use app\controller\FuncionarioController;

if (isset($_POST['submitBtn'])) {
    $funcionarioController = new FuncionarioController();
    $dados = [
        'nome' => $_POST['nomeFun'],
        'email' => $_POST['emailFun'],
        'telefone' => $_POST['telefoneFun'],
        'senha' => password_hash($_POST['senhaFun'], PASSWORD_DEFAULT),
        'adm' => $_POST['admFun']
    ];

    $funcionarioController->criarFuncionario($dados);
    header("Location: gerenciarFuncionarios.php");
    exit;
}