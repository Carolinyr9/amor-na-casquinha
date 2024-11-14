<?php
require_once '../config/blockURLAccess.php';
session_start();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedidos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="style/CabecalhoRodape.css">
    <link rel="stylesheet" href="style/pedidosS.css">
</head>
<body>
    <?php
        include_once 'components/header.php';
        require_once '../controller/PedidoController.php';
        $pedidoController = new PedidoController();
        
        $pedidos = $pedidoController->listarPedidosEntregador($_SESSION['userEmail']);
    ?>
    <main>
        <h1 class="m-auto text-center pt-4 pb-4">Pedidos</h1>
        <div class="conteiner">
            <div class="conteiner1">
                <div>
                    <?php
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
                                    
                                    <button class="btnVerInfos mt-3"><a href="<?= $redirectToInformacao; ?>">Ver Rotas</a></button>
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
                </div>
            </div>
        </div>
    </main>
    
    <?php
        include_once 'components/footer.php';
    ?>
</body>
</html>