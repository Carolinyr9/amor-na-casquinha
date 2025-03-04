<?php
session_start();
require_once '../config/blockURLAccess.php';
require_once '../../vendor/autoload.php';
use app\controller\EntregadorController;
use app\controller\PedidoController;

$entregadorController = new EntregadorController();
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
        $pedidoController->mudarStatus($pedidoId, $usuario, NULL);
        header("Location: informacoesPedido.php?idPedido=$pedidoId");
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
                        <p>Realizado em: <?= htmlspecialchars((new DateTime($pedido['dtPedido']))->format('d/m/Y \à\s H:i')); ?></p>
                        <p>Total: R$ <?= number_format($pedido['valorTotal'], 2, ',', '.') ?></p>
                        <p><?= $pedido['tipoFrete'] == 1 ? 'É para entrega!' : 'É para buscar na sorveteria!' ?></p>
                        <p>Status: <?= htmlspecialchars($pedido['statusPedido']) ?></p>
                        <p>Meio de Pagamento: <?= htmlspecialchars($pedido['meioPagamento']) ?></p>
                    </div>
                    
                    
                    <?php if ($pedido['tipoFrete'] == 1): ?>
                        <div class="box-pedido w-100 d-flex justify-content-center blue m-auto rounded-5 py-3">
                        <?php $entregador = $entregadorController->getEntregadorPorId($pedido['idEntregador']); ?>
                        <?php if ($entregador): ?>
                            <div class="card blue border-0 d-flex align-items-center mt-4">
                                <div class="d-flex align-items-center flex-column c2">
                                    <h3 class="titulo px-3"><?= htmlspecialchars($entregador[0]["nome"] ?? '') ?></h3>
                                    <div class="px-3">
                                        <p>Email: <?= htmlspecialchars($entregador[0]["email"] ?? '') ?></p>
                                        <p>Celular: <?= htmlspecialchars($entregador[0]["telefone"] ?? '') ?></p>
                                        <p>CNH: <?= htmlspecialchars($entregador[0]["cnh"] ?? '') ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    <?php endif; ?>
                    
                    <?php 
                    $statusPermitidos = ['Aguardando Confirmação', 'Preparando pedido', 'Aguardando Envio', 'Aguardando Retirada'];
                    if (in_array($pedido['statusPedido'], $statusPermitidos) || ($pedido['tipoFrete'] == 0 && $pedido['statusPedido'] == 'Entregue')): 
                    ?>
                        <form method="POST" action="">
                            <input type="hidden" name="mudarStatus" value="1">
                            <button type="submit" class="btnStatus px-3">Mudar Status</button>
                        </form>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="alert alert-danger">Pedido não encontrado.</div>
                <?php endif; ?>

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
            <button class="btn-yellow rounded-4 mt-5"><a href="pedidos.php">Voltar</a></button>
        </div>
    </main>
    <?php include_once 'components/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
