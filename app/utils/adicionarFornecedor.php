<?php
use app\controller2\FornecedorController;
use app\controller2\EnderecoController;

if(isset($_POST['submitBtn'])) {

    $fornecedorController = new FornecedorController();
    $enderecoController = new EnderecoController();
    $dadosEndereco = [
        'rua' => $_POST['ruaForn'],
        'numero' => $_POST['numeroForn'],
        'bairro' => $_POST['bairroForn'],
        'complemento' => $_POST['complementoForn'],
        'cep' => $_POST['cepForn'],
        'cidade' => $_POST['cidadeForn'],
        'estado' => $_POST['estadoForn']
    ];

    $idEndereco = $enderecoController->criarEndereco($dadosEndereco);

    $dadosFornecedor = [
        'nome' => $_POST['nomeForn'],
        'email' => $_POST['emailForn'],
        'telefone' => $_POST['telefoneForn'],
        'cnpj' => $_POST['cnpjForn'],
        'idEndereco' => $idEndereco
    ];

    $fornecedorController->criarFornecedor($dadosFornecedor);

    header("Location: gerenciarFornecedores.php");
    exit;
}