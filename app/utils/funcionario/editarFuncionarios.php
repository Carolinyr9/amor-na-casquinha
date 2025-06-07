<?php
use app\controller\FuncionarioController;
use app\controller\EnderecoController;

if (isset($_POST['btnAtualizar'])) {
    $funcionarioController = new FuncionarioController();
    $enderecoController = new EnderecoController();

    $dados = [
        'emailAntigo' => $_POST['emailFunAtual'],
        'nome' => $_POST['nomeFunEdt'],
        'email' => $_POST['emailFunEdt'],
        'telefone' => $_POST['telefoneFunEdt']
    ];

    $funcionarioController->editarFuncionario($dados);

    $dadosEndereco = [
        'rua' => $_POST["rua"],
        'numero' => $_POST["numero"],
        'complemento' => $_POST["complemento"],
        'cep' => $_POST["cep"],
        'bairro' => $_POST["bairro"],
        'estado' => $_POST["estado"],
        'cidade' => $_POST["cidade"],
        'idEndereco' => $_POST["idEndereco"]
    ];

    $enderecoController->editarEndereco($dadosEndereco);

    header("Location: editarFuncionarios.php?funcEmail=" . urlencode($_POST['emailFunEdt']));
    exit;
}