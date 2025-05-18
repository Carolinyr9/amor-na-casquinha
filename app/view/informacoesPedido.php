<?php
session_start();

require_once '../config/blockURLAccess.php';
require_once '../../vendor/autoload.php';
require_once '../utils/pedido/mudarStatusPedidoFuncionario.php';

use app\controller\EntregadorController;
use app\controller\PedidoController;
use app\controller\ItemPedidoController;
use app\controller\ProdutoController;

 
$itemPedidoController = new ItemPedidoController();
$pedidoController = new PedidoController();
$produtoController = new ProdutoController();
$entregadorController = new EntregadorController();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Informações do Pedido</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous" />
    <link rel="stylesheet" href="style/CabecalhoRodape.css" />
    <link rel="stylesheet" href="style/infoPedidos.css">
    <link rel="stylesheet" href="style/components/botao.css">
    <link rel="stylesheet" href="style/base/global.css">
    <link rel="stylesheet" href="style/base/variables.css">
    <link rel="shortcut icon" href="images/iceCreamIcon.ico" type="image/x-icon" />
</head>
<body>

<?php include_once 'components/header.php'; ?>

    <main class="container my-5 text-center">
        <h1 class="titulo">Informações do Pedido</h1>

        <section class="mx-auto mb-4 rounded-4 p-4 " style="background-color: var(--quaternary);">
                <?php if ($_GET['idPedido']): ?>
                    <?php
                        $pedido = $pedidoController->listarPedidoPorId($_GET['idPedido']);
                        $produtos = $itemPedidoController->listarInformacoesPedido($_GET['idPedido']);
                    ?>
                    <?php if ($pedido): ?>
                        <?php include 'components/infoPedidoCard.php'; ?>
                        <?php include 'components/itensPedidoTabela.php'; ?>
                        <?php if ($pedido->getTipoFrete() == 1): ?>
                            <?php
                                $entregador = $entregadorController->listarEntregadorPorId($pedido->getIdEntregador());
                            ?>
                            <?php if ($entregador): ?>
                                <div class="container w-50 rounded-4 p-4">
                                    <div class="d-flex align-items-center flex-column">
                                        <h3 class="subtitulo">Entregador</h3>
                                        <div class="px-3">
                                            <p><?= htmlspecialchars($entregador->getNome() ?? '') ?></p>
                                            <p>Email: <?= htmlspecialchars($entregador->getEmail() ?? '') ?></p>
                                            <p>Celular: <?= htmlspecialchars($entregador->getTelefone() ?? '') ?></p>
                                            <p>CNH: <?= htmlspecialchars($entregador->getCnh() ?? '') ?></p>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                        <?php
                            $statusPermitidos = ['Aguardando Confirmação', 'Preparando pedido', 'Aguardando Envio', 'Aguardando Retirada'];
                            $statusAtual = $pedido->getStatusPedido();
                            $tipoFrete = $pedido->getTipoFrete();
                        ?>
                        <?php if (in_array($statusAtual, $statusPermitidos) || ($tipoFrete == 0 && $statusAtual == 'Entregue')): ?>
                            <form method="POST" action="">
                                <input type="hidden" name="statusAntigo" value="<?= htmlspecialchars($statusAtual) ?>" />
                                <input type="hidden" name="tipoFrete" value="<?= htmlspecialchars($tipoFrete) ?>" />
                                <input type="hidden" name="mudarStatus" value="1" />
                                <button type="submit" class="botao botao-primary">Mudar Status</button>
                            </form>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="alert alert-danger">Pedido não encontrado.</div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="alert alert-warning">ID do pedido não fornecido.</div>
                <?php endif; ?>
            </section>
            <a class="botao botao-secondary" href="pedidos.php">Voltar</a>
    </main>

<?php include_once 'components/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
