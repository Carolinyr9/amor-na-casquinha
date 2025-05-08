<?php
use app\controller\EntregadorController;

if (isset($_POST['btnAtualizar'])) {
    $entregadorController = new EntregadorController();

    $dados = [
        'nome' => $_POST['nomeEntrEdt'],
        'emailAntigo' => $_POST['emailEntrAtual'],
        'email' => $_POST['emailEntrEdt'],
        'telefone' => $_POST['telefoneEntrEdt'],
    ];

    $entregadorController->editarEntregador($dados);

    header("Location: editarEntregadores.php?entrEmail=" . urlencode($_POST['emailEntrEdt']));
    exit;
}