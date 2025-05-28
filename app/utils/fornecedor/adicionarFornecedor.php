<?php
use app\controller\FornecedorController;
use app\controller\EnderecoController;

if(isset($_POST['submitBtn'])) {

    $fornecedorController = new FornecedorController();
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

    $dadosFornecedor = [
        'nome' => $_POST['nome'],
        'email' => $_POST['email'],
        'telefone' => $_POST['telefone'],
        'cnpj' => $_POST['cnpj'],
        'idEndereco' => $idEndereco
    ];

    $fornecedorController->criarFornecedor($dadosFornecedor);

    header("Location: gerenciarFornecedores.php");
    exit;
}