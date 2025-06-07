<?php
use app\controller\FornecedorController;
use app\controller\EnderecoController;

if (isset($_POST['btnAtualizar'])) {
    $fornecedorController = new FornecedorController();
    $enderecoController = new EnderecoController();

    $dados = [
        'nome' => $_POST['nomeFornEdt'],
        'emailAntigo' => $_POST['emailFornAtual'],
        'email' => $_POST['emailFornEdt'],
        'telefone' => $_POST['telefoneFornEdt'],
        'cnpj' => $_POST['cnpjFornEdt']
    ];

    $fornecedorController->editarFornecedor($dados);

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

    header("Location: editarFornecedores.php?fornEmail=" . urlencode($_POST['emailFornEdt']));
    
    exit;
}