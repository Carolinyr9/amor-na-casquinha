<?php
session_start();
require_once '../config/blockURLAccess.php';
require_once '../../vendor/autoload.php';
require_once '../utils/criarPedidosFuncionario.php';
require_once '../utils/paginacaoPedidos.php';

use app\controller\PedidoController;

$pedidoController = new PedidoController();
$paginaAtual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$pedidos = $pedidoController->listarPedidos();

$resultadoPaginado = paginarArray($pedidos, 8, $paginaAtual);
$pedidosPagina = $resultadoPaginado['dados'];
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
    <script src="https://code.jquery.com/jquery-3.7.1.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="style/CabecalhoRodape.css">
    <link rel="stylesheet" href="style/pedidosS.css">
</head>
<body>
    <?php include_once 'components/header.php'; ?>
    
    <main class="pedido container flex-column my-5 text-center d-flex justify-content-center">
        <h1 class="mb-4">Pedidos</h1>

        <div class="pedido__formulario container d-flex flex-column align-items-center justify-content-center">
            <button id="toggleFormButton" class="formulario_btn--addPedido border-0 rounded-4 my-3 fw-bold fs-5 px-3">
                Adicionar Pedido
            </button>

            <div id="addPedidoForm" class="addPedidoForm container mx-auto justify-content-center">
                <form action="" method="POST" class="container mx-auto d-flex flex-row flex-wrap justify-content-center gap-4 w-75 p-4 border rounded-4">
                    <input type="hidden" name="addPedido" value="1">

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

                    <div class="mb-3 form-check d-flex">
                        <input name="ckbIsDelivery" id="ckbIsDelivery" type="checkbox" class="form-check-input flex-grow-1">
                        <label for="ckbIsDelivery" class="form-check-label">O pedido é para entrega!</label>
                    </div>

                    <div class="mb-3">
                        <label for="valorFrete" class="form-label">Valor do Frete:</label>
                        <input type="text" id="valorFrete" name="valorFrete" class="form-control" placeholder="Ex.: 15.00">
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

                    <div class="mb-3">
                        <label for="valorTotal" class="form-label">Valor Total:</label>
                        <input type="text" id="valorTotal" name="valorTotal" class="form-control" placeholder="Ex.: 150.00" required>
                    </div>

                    <button type="submit" class="btn--salvar border-0 rounded-3 px-3 m-auto">Salvar Pedido</button>
                </form>
            </div>
        </div>

        <div class="container d-flex flex-row flex-wrap gap-5 justify-content-center mt-5">
            <?php if (!empty($pedidosPagina)): ?>
                <?php foreach ($pedidosPagina as $pedido): ?>
                    <div>
                        <div class="card border-0 p-3">
                            <h3 class="titulo mt-3">Número do Pedido: <?= htmlspecialchars($pedido->getIdPedido()); ?></h3>
                            <p>Realizado em: <?= htmlspecialchars((new DateTime($pedido->getDtPedido()))->format('d/m/Y \à\s H:i')); ?></p>
                            <p>Total: R$ <?= number_format($pedido->getValorTotal(), 2, ',', '.'); ?></p>
                            <p>Status: <?= htmlspecialchars($pedido->getStatusPedido()); ?></p>
                            <a class="card__btn--VerInfos mt-3 text-decoration-none text-black" href="informacoesPedido.php?idPedido=<?= $pedido->getIdPedido(); ?>">Ver Informações</a>
                            <?php if ($pedido->getTipoFrete() == 1 && $pedido->getIdEntregador() == NULL): ?>
                                <a class="card__btn--Entregador mt-3 text-decoration-none text-black" href="atribuirEntregador.php?idPedido=<?= $pedido->getIdPedido(); ?>">Atribuir Entregador ao Pedido</a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="alert alert-warning">Nenhum pedido encontrado.</div>
            <?php endif; ?>
        </div>

        <?php include_once 'components/paginacaoPedidos.php'; ?>
    </main>

    <?php include_once 'components/footer.php'; ?>

    <script src="script/exibirFormulario.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>
