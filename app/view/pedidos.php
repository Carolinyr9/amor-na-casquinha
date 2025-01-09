<?php
session_start();
require_once '../config/blockURLAccess.php';
require_once '../controller/pedidoController.php';

$pedidoController = new PedidoController();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedidos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style/CabecalhoRodape.css">
    <link rel="stylesheet" href="style/pedidosS.css">
</head>
<body>
    <?php include_once 'components/header.php'; ?>
    <main class="container my-5 text-center flex flex-column justify-content-center">
        <h1 class="mb-4">Pedidos</h1>
        <?php
        $pedidos = $pedidoController->listarPedidos(); 
        
        if (!empty($pedidos)) {
            foreach ($pedidos as $pedido) {
                $redirectAtribuirEntregador = 'atribuirEntregador.php?idPedido=' . $pedido['idPedido'];
                $redirectToInformacao = 'informacoesPedido.php?idPedido=' . $pedido['idPedido'];
                ?>
                <div class="conteiner0">
                    <div class="conteiner1">
                        <h3 class="titulo mt-3">Número do Pedido: <?= htmlspecialchars($pedido['idPedido']); ?></h3>
                        <p>Realizado em: <?= htmlspecialchars($pedido['dtPedido']); ?></p>
                        <p>Total: R$ <?= number_format($pedido['valorTotal'], 2, ',', '.'); ?></p>
                        <p><?= ($pedido['tipoFrete'] == 1 ? 'É para entrega!' : 'É para buscar na sorveteria!'); ?></p>
                        <p>Status: <?= htmlspecialchars($pedido['statusPedido']); ?></p>
                        <?php
                        if ($pedido['tipoFrete'] == 1 && is_null($pedido['idEntregador'])) {
                            ?>
                            <button class="btnAtribuir"><a href="<?= $redirectAtribuirEntregador; ?>">Atribuir Entregador</a></button>
                            <?php
                        } else if ($pedido['tipoFrete'] == 1) {
                            ?>
                            <p>Entregador <?= htmlspecialchars($pedido['idEntregador']); ?> atribuído ao pedido</p>
                            <?php
                        }
                        ?>
                        <button class="btnVerInfos mt-3"><a href="<?= $redirectToInformacao; ?>">Ver Informações</a></button>
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
