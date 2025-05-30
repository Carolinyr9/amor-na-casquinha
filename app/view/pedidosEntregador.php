<?php
session_start();
require_once '../config/blockURLAccess.php';
require_once '../../vendor/autoload.php';
require_once '../utils/pedido/mudarStatusPedidoEntregador.php';
require_once '../utils/pedido/paginacaoPedidos.php';

use app\controller\PedidoController;
use app\controller\EntregadorController;

$pedidoController = new PedidoController();
$entregadorController = new EntregadorController();

$entregador = $entregadorController->listarEntregadorPorEmail($_SESSION['userEmail']);
$pedidos = $pedidoController->listarPedidoPorIdEntregador($entregador->getId());

$paginaAtual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$pedidos = $pedidoController->listarPedidos();

$resultadoPaginado = paginarArray($pedidos, 8, $paginaAtual);
$pedidos = $resultadoPaginado['dados'];
$totalPaginas = $resultadoPaginado['total_paginas'];
$paginaAtual = $resultadoPaginado['pagina_atual'];
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedidos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="style/CabecalhoRodape.css">
    <link rel="stylesheet" href="style/entregador-pedidos-style.css">
    <link rel="shortcut icon" href="../images/iceCreamIcon.ico" type="image/x-icon">
    <link rel="stylesheet" href="style/components/botao.css">
    <link rel="stylesheet" href="style/components/cards.css">
    <link rel="stylesheet" href="style/base/global.css">
    <link rel="stylesheet" href="style/base/variables.css">
</head>
<body>
    <?php include_once 'components/header.php'; ?>

    <main class="pedidos container text-center d-flex flex-column justify-content-center">
        <h1 class=" titulo mb-4">Pedidos</h1>

        <div class="container d-flex flex-row flex-wrap gap-5 justify-content-center mt-5">
            <?php include './components/pedidosCards.php' ?>
        </div>
            
    </main>

    <?php include_once 'components/footer.php'; ?>
</body>
</html>
