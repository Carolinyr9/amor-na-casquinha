<?php
use app\controller\PedidoController;

$pedidoController = new PedidoController();
$pedidoId = $_GET['idPedido'] ?? null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['mudarStatus'])) {
        $dados = [
            'idPedido' => $pedidoId,
            'statusPedido' => 'Entregue'
        ];
        $pedidoController->mudarStatus($dados);
        header("Location: informacoesPedidoCliente.php?idPedido=$pedidoId");
        exit();
    }

    if (isset($_POST['cancelarPedido'])) {
        $dados = [
            'idPedido' => $pedidoId,
            'motivoCancelamento' => $_POST['motivoCancelamento'] ?? null
        ];
        $pedidoController->cancelarPedido($dados);
        header("Location: informacoesPedidoCliente.php?idPedido=$pedidoId");
        exit();
    }
}
