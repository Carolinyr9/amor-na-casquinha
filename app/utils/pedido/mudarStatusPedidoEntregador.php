<?php
use app\controller\PedidoController;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mudarStatus'], $_POST['idPedido'])) {
    $pedidoController = new PedidoController();
    $dados = [
        'idPedido' => $_POST['idPedido'],
        'statusPedido' => $_POST['mudarStatus']
    ];

    $pedidoController->mudarStatus($dados);
    header("Location: pedidosEntregador.php");
    exit();
}