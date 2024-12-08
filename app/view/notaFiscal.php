<?php
session_start();
require_once '../config/blockURLAccess.php';
require_once '../controller/carrinhoController.php';
require_once '../controller/clienteController.php';
require_once '../controller/PedidoController.php';

$carrinhoController = new CarrinhoController();
$clienteController = new ClienteController();
$pedidoController = new PedidoController();

$carrinhoController->atualizarCarrinho();
$pedidoData = $carrinhoController->getPedidoData();
$clienteData = isset($_SESSION["userEmail"]) 
    ? $clienteController->getClienteData($_SESSION["userEmail"]) 
    : null;

$total = $carrinhoController->calcularTotal();
$frete = 0.0;
$totalComFrete = $total;

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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="style/CabecalhoRodape.css">
    <link rel="stylesheet" href="style/notaFiscalS.css">
    <link rel="shortcut icon" href="images/iceCreamIcon.ico" type="image/x-icon">
</head>
<body>
    <?php include_once 'components/header.php'; ?>
    <main>
        <div class="conteiner1 container d-flex flex-column align-items-center rounded-4">
            <h3 class="mt-3">Confirmar Pedido?</h3>

            <?php if (isset($_SESSION["userEmail"])): ?>
                <form id="pedidoForm" name="pedidoForm" method="post" action="sobre.php">
                    <input type="hidden" name="isDelivery" id="isDelivery" value="<?= $isDelivery ? '1' : '0' ?>">

                    <input name="ckbIsDelivery" id="ckbIsDelivery" type="checkbox">
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

                    <div class="frete-div" id="freteDiv" style="display: none;">
                        <h4>Frete</h4>
                        <p>R$ <?= number_format($frete, 2, ',', '.') ?></p>
                        <input type="hidden" name="frete" id="frete" value="<?= htmlspecialchars($frete); ?>">
                    </div>

                    <div class="total-com-frete">
                        <h4>Total do Pedido</h4>
                        <p>R$ <?= number_format($totalComFrete, 2, ',', '.') ?></p>
                        <input type="hidden" name="totalComFrete" id="totalComFrete" value="<?= htmlspecialchars($totalComFrete); ?>">
                    </div>

                    <div class="meio-de-pagamento">
                        <h4>Selecione o Meio de Pagamento</h4>
                        <div>
                            <input name="meioDePagamento" id="meioDePagamentoDebito" type="radio" value="Cartão de Débito" required>
                            <label for="meioDePagamentoDebito">Cartão de Débito</label>
                        </div>
                        <div>
                            <input name="meioDePagamento" id="meioDePagamentoCredito" type="radio" value="Cartão de Crédito">
                            <label for="meioDePagamentoCredito">Cartão de Crédito</label>
                        </div>
                        <div>
                            <input name="meioDePagamento" id="meioDePagamentoDinheiro" type="radio" value="Dinheiro">
                            <label for="meioDePagamentoDinheiro">Dinheiro</label>
                        </div>
                    </div>

                    <input type="hidden" name="totalPedido" value="<?= htmlspecialchars($totalComFrete); ?>">
                    <input type="hidden" name="notaFiscal" value="1">
                    <input name="btnSubmit" id="btnSubmit" type="submit" value="Concluir Pedido" class="btnConcluir border-0 px-3 mb-3 rounded-4">
                </form>
            <?php else: ?>
                <button id="btnGoToLogin" class="btnGoToLogin w-75 text-wrap h-auto rounded-3 mb-3 border-0 p-2" onclick="window.location.href='login.php'">Faça Login para Concluir seu Pedido</button>
            <?php endif; ?>
        </div>
    </main>
    <?php include_once 'components/footer.php'; ?>
    <script src="script/atualizarFrete.js"></script>
</body>
</html>
