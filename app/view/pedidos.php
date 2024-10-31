<?php
require_once '../config/blockURLAccess.php';
session_start();
require_once '../controller/pedidoController.php';

$pedidoController = new PedidoController();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Pedidos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style/CabecalhoRodape.css">
    <link rel="stylesheet" href="style/pedidosS.css">
</head>
<body>
    <?php include_once 'components/header.php'; ?>
    <main class="container my-5 text-center">
        <h1 class="mb-4">Meus Pedidos</h1>
        <?php
        $pedidos = $pedidoController->listarPedidos(); 
        
        if (!empty($pedidos)) {
            foreach ($pedidos as $pedido) {
                $redirectAtribuirEntregador = 'atribuirEntregador.php?idPedido=' . $pedido['idPedido'];
                $redirectToInformacao = 'informacoesPedido.php?idPedido=' . $pedido['idPedido'];
                ?>
                <div class="card mb-4">
                    <div class="card-body text-center">
                        <h3 class="titulo mt-3">Número do Pedido: <?= htmlspecialchars($pedido['idPedido']); ?></h3>
                        <p>Realizado em: <?= htmlspecialchars($pedido['dtPedido']); ?></p>
                        <p>Total: R$ <?= number_format($pedido['valorTotal'], 2, ',', '.'); ?></p>
                        <p><?= ($pedido['tipoFrete'] == 1 ? 'É para entrega!' : 'É para buscar na sorveteria!'); ?></p>
                        <p>Status: <?= htmlspecialchars($pedido['statusPedido']); ?></p>
                        <?php
                        if ($pedido['tipoFrete'] == 1 && is_null($pedido['idEntregador'])) {
                            ?>
                            <button id="vari" class="btn btn-primary"><a class="text-white" href="<?= $redirectAtribuirEntregador; ?>">Atribuir Entregador</a></button>
                            <?php
                        } else if ($pedido['tipoFrete'] == 1) {
                            ?>
                            <p>Entregador <?= htmlspecialchars($pedido['idEntregador']); ?> atribuído ao pedido</p>
                            <?php
                        }
                        ?>
                        <button id="vari" class="btn btn-secondary"><a class="text-white" href="<?= $redirectToInformacao; ?>">Ver Informações</a></button>
                    </div>
                </div>
                <?php
            }
        } else {
            ?>
            <div class="alert alert-warning">Nenhum pedido encontrado.</div>
            <?php
        }
        ?>
    </main>

    <?php include_once 'components/footer.php'; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
