<?php
use app\controller2\FuncionarioController;

if (isset($_POST['btnAtualizar'])) {
    $funcionarioController = new FuncionarioController();
    $dados = [
        'emailAntigo' => $_POST['emailFunAtual'],
        'nome' => $_POST['nomeFunEdt'],
        'email' => $_POST['emailFunEdt'],
        'telefone' => $_POST['telefoneFunEdt']
    ];

    $funcionarioController->editarFuncionario($dados);

    header("Location: editarFuncionarios.php?funcEmail=" . urlencode($_POST['emailFunEdt']));
    exit;
}