<?php
use app\controller\FuncionarioController;
use app\controller\EnderecoController;

if (isset($_POST['submitBtn'])) {
    $funcionarioController = new FuncionarioController();
    $enderecoController = new EnderecoController();
    $dadosEndereco = [
        'rua' => $_POST['rua'],
        'numero' => $_POST['numero'],
        'bairro' => $_POST['bairro'],
        'complemento' => $_POST['complemento'],
        'cep' => $_POST['cep'],
        'cidade' => $_POST['cidade'],
        'estado' => $_POST['estado']
    ];

    $idEndereco = $enderecoController->criarEndereco($dadosEndereco);

    $dados = [
        'nome' => $_POST['nome'],
        'email' => $_POST['email'],
        'telefone' => $_POST['telefone'],
        'senha' => password_hash($_POST['senha'], PASSWORD_DEFAULT),
        'adm' => $_POST['admFun'],
        'idEndereco' => $idEndereco
    ];

    ;;$funcionarioController->criarFuncionario($dados);
    header("Location: gerenciarFuncionarios.php");
    exit;
}