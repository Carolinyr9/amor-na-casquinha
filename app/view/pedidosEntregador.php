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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="style/CabecalhoRodape.css">
    <link rel="stylesheet" href="style/entregador-pedidos-style.css">
</head>
<body>
    <?php include_once 'components/header.php'; ?>

    <main class="pedidos container my-5 text-center d-flex flex-column justify-content-center gap-5">
        <h1 class="mb-4">Pedidos</h1>

        <?php foreach ($pedidos as $pedido): ?>
            <?php if (!in_array($pedido->getStatusPedido(), ['Concluído', 'Cancelado'])): ?>
                <div class="card d-flex flex-column justify-content-center p-3 w-25 m-auto">
                    <h3 class="titulo mt-3">Número do Pedido: <?= htmlspecialchars($pedido->getIdPedido()) ?></h3>
                    <p>Realizado em: <?= htmlspecialchars((new DateTime($pedido->getDtPedido()))->format('d/m/Y \à\s H:i')) ?></p>
                    <p>Total: R$ <?= number_format($pedido->getValorTotal(), 2, ',', '.') ?></p>
                    <p>Status: <?= htmlspecialchars($pedido->getStatusPedido()) ?></p>

                    <a href="rotasEntregador.php?idEndereco=<?= htmlspecialchars($pedido->getIdEndereco()) ?>" class="btn__rotas mt-3 rounded-3 w-50 m-auto mb-3">
                        Ver Rotas
                    </a>

                    <?php if ($pedido->getStatusPedido() === 'Entregue'): ?>
                        <form method="POST" class="mb-2">
                            <input type="hidden" name="idPedido" value="<?= htmlspecialchars($pedido->getIdPedido()) ?>">
                            <input type="hidden" name="mudarStatus" value="Entrega Falhou">
                            <button type="submit" class="btn__pedido--falha bg-none rounded-3 px-3">
                                Entrega Falhou
                            </button>
                        </form>

                        <form method="POST">
                            <input type="hidden" name="idPedido" value="<?= htmlspecialchars($pedido->getIdPedido()) ?>">
                            <input type="hidden" name="mudarStatus" value="Entregue">
                            <button type="submit" class="btn__pedido--concluido border-0 rounded-3 px-3">
                                Entregue
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </main>

    <?php include_once 'components/footer.php'; ?>
</body>
</html>
