<?php 
use app\controller2\PedidoController;
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['idPedido']) && isset($_GET['idEntregador'])) {
    $pedidoController = new PedidoController();
    $dados= [
        'idPedido' => $_GET['idPedido'],
        'idEntregador' => $_GET['idEntregador']
    ];

    $pedidoController->atribuirEntregadorPedido($dados);
    header("Location: pedidos.php");
    exit();
}