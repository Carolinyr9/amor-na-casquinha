<?php
require_once '../config/blockURLAccess.php';
session_start();

require_once '../controller/carrinhoController.php';
require_once '../controller/clienteController.php';
require_once '../controller/PedidoController.php';

// Instância dos controllers
$carrinhoController = new CarrinhoController();
$clienteController = new ClienteController();
$pedidoController = new PedidoController();

// Atualizar carrinho
$carrinhoController->atualizarCarrinho();
$pedidoData = $carrinhoController->getPedidoData();
$clienteData = isset($_SESSION["userEmail"]) 
    ? $clienteController->getClienteData($_SESSION["userEmail"]) 
    : null;

$total = $carrinhoController->calcularTotal();
$frete = 0.0;
$totalComFrete = $total;

// Verifica se o cliente está logado e se o frete deve ser calculado
$isDelivery = isset($_POST['isDelivery']) && $_POST['isDelivery'] === '1';

if ($isDelivery && $clienteData && isset($clienteData['endereco']['cep'])) {
    $cep = $clienteData['endereco']['cep'];
    $frete = $pedidoController->calcularFrete($cep);
    if (is_numeric($frete)) {
        $totalComFrete += $frete;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Fiscal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="style/CabecalhoRodape.css">
    <link rel="stylesheet" href="style/notaFiscalS.css">
    <link rel="shortcut icon" href="images/iceCreamIcon.ico" type="image/x-icon">
</head>
<body>
    <?php include_once 'components/header.php'; ?>
    <main>
        <div class="conteiner1 container d-flex flex-column align-items-center">
            <h3>Confirmar Pedido?</h3>

            <?php if (isset($_SESSION["userEmail"])): ?>
                <form id="pedidoForm" name="pedidoForm" method="post" action="sobre.php">
                    <input type="hidden" name="isDelivery" id="isDelivery" value="<?= $isDelivery ? '1' : '0' ?>">
                    <input type="hidden" name="totalComFrete" id="totalComFrete" value="<?= htmlspecialchars($totalComFrete); ?>">

                    <input 
                        name="ckbIsDelivery" 
                        id="ckbIsDelivery" 
                        type="checkbox" 
                        <?= $isDelivery ? 'checked' : '' ?>>
                    <label for="ckbIsDelivery" id="labelForCkbIsDelivery">
                        O pedido será entregue no seu endereço!
                    </label>
                    <div id="addressDiv">
                        <p>
                            <?= htmlspecialchars($clienteData['endereco']['rua']) . ', ' . 
                                htmlspecialchars($clienteData['endereco']['numero']) . ', ' . 
                                (isset($clienteData['endereco']['complemento']) ? htmlspecialchars($clienteData['endereco']['complemento']) . ', ' : '') . 
                                htmlspecialchars($clienteData['endereco']['bairro']) . ', ' . 
                                htmlspecialchars($clienteData['endereco']['cidade']) . ', ' . 
                                htmlspecialchars($clienteData['endereco']['estado']) . ', ' . 
                                htmlspecialchars($clienteData['endereco']['cep']); ?>
                        </p>
                    </div>

                    <div class="total-div">
                        <h4>Subtotal</h4>
                        <p>R$ <?= number_format($total, 2, ',', '.') ?></p>
                    </div>

                    <div class="frete-div" id="freteDiv" style="display: <?= $isDelivery ? 'block' : 'none' ?>;">
                        <h4>Frete</h4>
                        <p>R$ <?= number_format($frete, 2, ',', '.') ?></p>
                    </div>

                    <div class="total-com-frete">
                        <h4>Total do Pedido</h4>
                        <p>R$ <?= number_format($totalComFrete, 2, ',', '.') ?></p>
                    </div>

                    <input type="hidden" name="totalPedido" value="<?= htmlspecialchars($totalComFrete); ?>">
                    <input type="hidden" name="notaFiscal" value="1">
                    <input name="btnSubmit" id="btnSubmit" type="submit" value="Concluir Pedido" class="btn">
                </form>
            <?php else: ?>
                <button id="btnGoToLogin" class="btn" onclick="window.location.href='login.php'">Fazer Login para Concluir Pedido</button>
            <?php endif; ?>
        </div>
    </main>
    <?php include_once 'components/footer.php'; ?>
    <script src="script/atualizarFrete.js"></script>
</body>
</html>
