<?php
use app\controller2\FornecedorController;

if (isset($_POST['btnAtualizar'])) {
    $fornecedorController = new FornecedorController();

    $dados = [
        'nome' => $_POST['nomeFornEdt'],
        'emailAntigo' => $_POST['emailFornAtual'],
        'email' => $_POST['emailFornEdt'],
        'telefone' => $_POST['telefoneFornEdt'],

    ];

    $fornecedorController->editarFornecedor($dados);

    header("Location: editarFornecedores.php?fornEmail=" . urlencode($_POST['emailFornEdt']));
    exit;
}