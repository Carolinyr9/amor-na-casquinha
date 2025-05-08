<?php require_once '../utils/pedido/inicializarNotaFiscal.php'; ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Fiscal</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">  
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="style/CabecalhoRodape.css">
    <link rel="stylesheet" href="style/notaFiscalS.css">
    <link rel="stylesheet" href="style/base/variables.css">
    <link rel="stylesheet" href="style/base/global.css">
    <link rel="stylesheet" href="style/components/botao.css">
    <link rel="shortcut icon" href="images/iceCreamIcon.ico" type="image/x-icon">
</head>
<body>
    <?php include_once 'components/header.php'; ?>
    <main>
        <h1 class="titulo">Confirmar Pedido?</h1>
        <div class="container-pedido container d-flex flex-column justify-content-center align-items-center rounded-4 w-50 p-4" style="background-color: var(--quaternary);">
            <?php if (isset($_SESSION["userEmail"])): ?>
                <form id="pedidoForm" name="pedidoForm" method="post" action="perfil.php" class="d-flex flex-column justify-content-center align-items-center">
                    <div>
                        <input type="hidden" name="isDelivery" id="isDelivery" value="<?= $isDelivery ? '1' : '0' ?>">
                        
                        <label for="ckbIsDelivery" id="labelForCkbIsDelivery" class="text-wrap"><input name="ckbIsDelivery" id="ckbIsDelivery" type="checkbox" value="1" <?= $isDelivery ? 'checked' : '' ?>>
                            O pedido será entregue no seu endereço!
                        </label>
                    </div>
                    <?php include 'components/enderecoCard.php'; ?>
                    
                    <div class="total-div d-flex flex-row align-items-center justify-content-between w-50 mt-3">
                        <h4>Subtotal</h4>
                        <p>R$ <?= number_format($subtotal, 2, ',', '.') ?></p>
                    </div>

                    <div class="frete-div flex-row align-items-center justify-content-between w-50" id="freteDiv" style="display: <?= is_numeric($frete) ? 'flex' : 'none' ?>;">
                        <h4>Frete</h4>
                        <p>
                            <?php if (is_numeric($frete)): ?>
                                R$ <?= number_format($frete, 2, ',', '.') ?>
                            <?php else: ?>
                                <?= htmlspecialchars($frete) ?>
                            <?php endif; ?>
                        </p>
                        <input type="hidden" name="frete" id="frete" value="<?= htmlspecialchars($frete); ?>">
                    </div>

                    <div class="total-com-frete d-flex flex-row align-items-center justify-content-between w-50 mb-3">
                        <h4>Total do Pedido</h4>
                        <p>R$ <?= number_format($total, 2, ',', '.') ?></p>
                        <input type="hidden" name="totalComFrete" id="totalComFrete" value="<?= htmlspecialchars($total); ?>">
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

                    <?php include 'components/troco.php'; ?>

                    <input type="hidden" name="totalPedido" value="<?= htmlspecialchars($total); ?>">
                    <input type="hidden" name="idCliente" value="<?= htmlspecialchars($clienteData->getId()); ?>">
                    <input type="hidden" name="idEndereco" value="<?= htmlspecialchars($endereco->getIdEndereco()); ?>">

                    <input type="hidden" name="notaFiscal" value="1">
                    <input name="btnSubmit" id="btnSubmit" type="submit" value="Concluir Pedido" class="botao botao-primary my-4">
                </form>
            <?php else: ?>
                <button id="btnGoToLogin" class="link" onclick="window.location.href='login.php'">Faça Login para Concluir seu Pedido</button>
            <?php endif; ?>
        </div>
    </main>
    <?php include_once 'components/footer.php'; ?>
    
    <script src="script/mostrarTroco.js"></script>
    <script src="script/atualizarFrete.js"></script>
</body>
</html>
