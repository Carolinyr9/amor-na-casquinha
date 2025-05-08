<?php
session_start();
require_once '../config/blockURLAccess.php';
require_once '../../vendor/autoload.php';
require_once '../utils/gerenciarStatusPedidoClientes.php';

use app\controller\PedidoController;
use app\controller\ItemPedidoController;
use app\controller\ProdutoController;

$itemPedidoController = new ItemPedidoController();
$pedidoController = new PedidoController();
$produtoController = new ProdutoController();

$pedidoId = $_GET['idPedido'] ?? null;
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informações do Pedido</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style/CabecalhoRodape.css">
    <link rel="stylesheet" href="style/infoPedidos.css">
    <link rel="shortcut icon" href="images/iceCreamIcon.ico" type="image/x-icon">
</head>
<body>
    <?php include_once 'components/header.php'; ?>

    <main class="container my-5 text-center">
        <h1 class="text-center mb-4">Informações do Pedido</h1>

        <?php if ($pedidoId): ?>
            <?php 
                $pedido = $pedidoController->listarPedidoPorId($pedidoId);
                $produtos = $itemPedidoController->listarInformacoesPedido($pedidoId);
            ?>

            <?php include 'components/infoPedidoCard.php'; ?>
            <?php include 'components/itensPedidoTabela.php'; ?>

            <?php 
            $statusPermitidos = ['Aguardando Confirmação', 'Preparando pedido', 'Aguardando Envio'];
            ?>

            <?php if ($pedido->getStatusPedido() == 'Entregue'): ?>
                <form method="POST" action="">
                    <input type="hidden" name="mudarStatus" value="1">
                    <input type="hidden" name="idPedido" value="<?= $pedido->getIdPedido(); ?>">
                    <button type="submit" class="btnMudarStatus border-0 rounded-4 h-auto px-2">
                        Eu recebi meu pedido
                    </button>
                </form>
            <?php endif; ?>

            <?php if (in_array($pedido->getStatusPedido(), $statusPermitidos)): ?>
                <button type="button" class="btnCancelaPedido rounded-4 border-0 fw-bold"
                        data-bs-toggle="modal" data-bs-target="#cancelModal">
                    Cancelar Pedido
                </button>
            <?php endif; ?>

            <button class="btn-yellow rounded-4 mt-5">
                <a href="perfil.php">Voltar</a>
            </button>
        <?php else: ?>
            <div class="alert alert-warning">ID do pedido não fornecido.</div>
        <?php endif; ?>
    </main>

    <div class="modal fade" id="cancelModal" tabindex="-1" aria-labelledby="cancelModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cancelModalLabel">Motivo do Cancelamento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="">
                        <input type="hidden" name="cancelarPedido" value="1">
                        <input type="hidden" name="idPedido" value="<?= $pedido->getIdPedido(); ?>">
                        <div class="mb-3">
                            <label for="motivoCancelamento" class="form-label">
                                Informe o motivo do cancelamento:
                            </label>
                            <textarea class="form-control" id="motivoCancelamento" name="motivoCancelamento"
                                      rows="3" required maxlength="100"></textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="botao botao-secondary" data-bs-dismiss="modal">Fechar</button>
                            <button type="submit" class="botao botao-danger">Confirmar Cancelamento</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php include_once 'components/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
