<?php
session_start();
require_once '../config/blockURLAccess.php';
require_once '../../vendor/autoload.php';
require_once '../utils/pedido/criarPedidosFuncionario.php';
require_once '../utils/pedido/paginacaoPedidos.php';

use app\controller\PedidoController;

$pedidoController = new PedidoController();
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
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style/CabecalhoRodape.css">
    <link rel="stylesheet" href="style/pedidosS.css">
    <link rel="stylesheet" href="style/components/botao.css">
    <link rel="stylesheet" href="style/components/cards.css">
    <link rel="stylesheet" href="style/base/global.css">
    <link rel="stylesheet" href="style/base/variables.css">
    <link rel="shortcut icon" href="../images/iceCreamIcon.ico" type="image/x-icon">
    <script src="script\exibirFormulario.js"></script>
</head>
<body>
    <?php include_once 'components/header.php'; ?>
    
    <main class="container flex-column my-5 text-center d-flex justify-content-center">
        <h1 class="titulo">Pedidos</h1>

        <div class="pedido__formulario container d-flex flex-column align-items-center justify-content-center">
            <button class="botao botao-primary" id="addPedido">Adicionar Categoria</button>

            <div class="container justify-content-center mt-4" id="formulario" style="display: none;">
                <form action="" method="POST" class="container d-flex flex-wrap gap-4 border rounded-4 py-4">
                    <input type="hidden" name="addPedido" value="1">

                    <div class="d-flex flex-row flex-wrap justify-content-center gap-5 w-100">
                        <div class="mb-3">
                            <label for="userEmail" class="form-label">Email do cliente:</label>
                            <input type="text" id="userEmail" name="userEmail" class="form-control" placeholder="Se não possuir, não preencher">
                        </div>
                        <div class="mb-3">
                            <label for="produtosPedidos" class="form-label">Produtos Pedidos:</label>
                            <input type="text" id="produtosPedidos" name="produtosPedidos" class="form-control" placeholder="Ex.: 1;2;3;4;5" required>
                        </div>
                        <div class="mb-3">
                            <label for="quantidadeProdutosPedidos" class="form-label">Quantidade dos Produtos Pedidos:</label>
                            <input type="text" id="quantidadeProdutosPedidos" name="quantidadeProdutosPedidos" class="form-control" placeholder="Ex.: 1;2;3;4;5" required>
                        </div>
                        <div class="mb-3">
                            <label for="valorFrete" class="form-label">Valor do Frete:</label>
                            <input type="text" id="valorFrete" name="valorFrete" class="form-control" placeholder="Ex.: 15.00">
                        </div>
                        <div class="mb-3">
                            <label for="valorTotal" class="form-label">Valor Total:</label>
                            <input type="text" id="valorTotal" name="valorTotal" class="form-control" placeholder="Ex.: 150.00" required>
                        </div>
                        <div class="mb-3">
                            <label for="meioPagamento" class="form-label">Meio de Pagamento:</label>
                            <div>
                                <input type="radio" id="pagamentoDebito" name="meioPagamento" value="Cartão de Débito" class="form-check-input" required>
                                <label for="pagamentoDebito" class="form-check-label">Cartão de Débito</label>
                            </div>
                            <div>
                                <input type="radio" id="pagamentoCredito" name="meioPagamento" value="Cartão de Crédito" class="form-check-input">
                                <label for="pagamentoCredito" class="form-check-label">Cartão de Crédito</label>
                            </div>
                            <div>
                                <input type="radio" id="pagamentoDinheiro" name="meioPagamento" value="Dinheiro" class="form-check-input">
                                <label for="pagamentoDinheiro" class="form-check-label">Dinheiro</label>
                            </div>
                        </div>
                        <div class="mb-3 form-check d-flex">
                            <input name="ckbIsDelivery" id="ckbIsDelivery" type="checkbox" class="form-check-input flex-grow-1">
                            <label for="ckbIsDelivery" class="form-check-label ms-2">O pedido é para entrega!</label>
                        </div>
                    </div>

                    <button type="submit" class="botao botao-primary m-auto" >Salvar Pedido</button>
                </form>
            </div>
        </div>

        <div class="container d-flex flex-row flex-wrap gap-5 justify-content-center mt-5">
            <?php include './components/pedidosCards.php' ?>
        </div>

        <?php include_once 'components/paginacaoPedidos.php'; ?>
    </main>

    <?php include_once 'components/footer.php'; ?>

</body>
</html>
