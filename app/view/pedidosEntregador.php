<?php
session_start();
require_once '../config/blockURLAccess.php';
require_once '../../vendor/autoload.php';
require_once '../utils/pedido/mudarStatusPedidoEntregador.php';

use app\controller\PedidoController;
use app\controller\EntregadorController;

$pedidoController = new PedidoController();
$entregadorController = new EntregadorController();

$entregador = $entregadorController->listarEntregadorPorEmail($_SESSION['userEmail']);
$pedidos = $pedidoController->listarPedidoPorIdEntregador($entregador->getId());
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedidos</title>
    <?php include_once '../utils/links/styleLinks.php'; ?>
    <link rel="stylesheet" href="style/pedidosEntregador.css">
</head>
<body>
    <?php include_once 'components/header.php'; ?>

    <main class="pedidos container my-5 text-center d-flex flex-column justify-content-center gap-5">
        <h1 class=" titulo mb-4">Pedidos</h1>

        <div class="container d-flex flex-row flex-wrap gap-5 justify-content-center mt-5">
            <?php include './components/pedidosCards.php' ?>
        </div>
            
    </main>

    <?php include_once 'components/footer.php'; ?>
</body>
</html>
