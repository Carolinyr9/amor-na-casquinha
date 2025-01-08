<?php
session_start();
require_once '../config/blockURLAccess.php';
require_once '../controller/EntregadorController.php';
require_once '../controller/PedidoController.php';

$pedidoController = new PedidoController();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informações do Pedido</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style/CabecalhoRodape.css">
    <link rel="stylesheet" href="style/infoPedidos.css">
    <link rel="shortcut icon" href="images/iceCreamIcon.ico" type="image/x-icon">
</head>
<body>
    <?php
    include_once 'components/header.php';
    $pedidoId = $_GET['idPedido'] ?? null;
    $usuario = $_SESSION['userPerfil'] ?? null;

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['mudarStatus'])) {
        $pedidoController->mudarStatus($pedidoId, $usuario);
        header("Location: informacoesPedidoCliente.php?idPedido=$pedidoId");
        exit();
    }
    ?>
    <main class="container my-5 text-center flex flex-column justify-content-center">
        <h1 class="text-center mb-4">Informações do Pedido</h1>
        <div class="container text-center">
            <?php if ($pedidoId): ?>
                <?php 
                $pedido = $pedidoController->listarPedidoPorId($pedidoId);
                $produtos = $pedidoController->listarInformacoesPedido($pedidoId); 
                ?>
                <div class="box-pedido w-100 d-flex justify-content-center blue m-auto rounded-5 py-3">
                    <?php if ($pedido): ?>
                        <h3>Número do Pedido: <?= htmlspecialchars($pedido['idPedido']) ?></h3>
                        <p>Realizado em: <?= htmlspecialchars($pedido['dtPedido']) ?></p>
                        <p>Total: R$ <?= number_format($pedido['valorTotal'], 2, ',', '.') ?></p>
                        <p><?= $pedido['tipoFrete'] == 1 ? 'É para entrega!' : 'É para buscar na sorveteria!' ?></p>
                        <p>Status: <?= htmlspecialchars($pedido['statusPedido']) ?></p>
                        <p>Meio de Pagamento: <?= htmlspecialchars($pedido['meioPagamento']) ?></p>
                    </div>
                    
                    <div class="box-pedido w-100 d-flex justify-content-center blue m-auto rounded-5 py-3">
                        <?php if ($produtos): ?>
                            <h3>Itens do Pedido</h3>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Quantidade</th>
                                        <th>Preço</th>
                                        <th>Desativado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($produtos as $itens): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($itens['NomeProduto']) ?></td>
                                            <td><?= htmlspecialchars($itens['quantidade']) ?></td>
                                            <td>R$ <?= number_format($itens['Preco'], 2, ',', '.') ?></td>
                                            <td><?= $itens['ProdutoDesativado'] ? 'Sim' : 'Não' ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning">ID do pedido não fornecido.</div>
                <?php endif; ?>

                <?php 
                $statusPermitidos = ['Aguardando Pagamento', 'Aguardando Envio'];
                
                if ($pedido['statusPedido'] == 'A Caminho'): ?>
                    <form method="POST" action="">
                        <input type="hidden" name="mudarStatus" value="1">
                        <input type="hidden" name="idPedido" value="<?= $pedido['idPedido']; ?>">
                        <button type="submit" class="btnMudarStatus border-0 rounded-4 h-auto px-2">Mudar para: Entregue</button>
                    </form>
                <?php endif; ?>
                
                <?php if (in_array($pedido['statusPedido'], $statusPermitidos)): ?>
                    <form method="POST" action="">
                        <input type="hidden" name="mudarStatus" value="1">
                        <input type="hidden" name="idPedido" value="<?= $pedido['idPedido']; ?>">
                        <button type="submit" class="btnCancelaPedido rounded-4 border-0 fw-bold">Cancelar Pedido</button>
                    </form>
                <?php endif; ?>
                <button class="btn-yellow rounded-4 mt-5"><a href="sobre.php">Voltar</a></button>
            <?php endif; ?>
        </div>
    </main>
    <?php include_once 'components/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
