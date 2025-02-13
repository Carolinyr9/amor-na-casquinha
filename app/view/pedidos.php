<?php
session_start();
require_once '../config/blockURLAccess.php';
require_once '../controller/pedidoController.php';

$pedidoController = new PedidoController();

if (isset($_POST['addPedido'])) {
    $produtosArray = explode(";", $_POST["produtosPedidos"] ?? "");
    $quantidadesArray = explode(";", $_POST["quantidadeProdutosPedidos"] ?? "");
    $itensPedido = [];

    foreach ($produtosArray as $index => $produto) {
        $itensPedido[] = [
            'id' => $produto,
            'qntd' => $quantidadesArray[$index] ?? 1
        ];
    }

    echo 'user: "' . $_POST["userEmail"] . '"';

    $pedidoController->criarPedido(
        !empty($_POST["userEmail"]) ? $_POST["userEmail"] : 'desconhecido',
        isset($_POST["ckbIsDelivery"]) ? 1 : 0,
        $_POST["valorTotal"] ?? "0.00",
        $_POST["valorFrete"] ?? NULL,
        $_POST["meioPagamento"] ?? NULL,
        NULL,
        $itensPedido
    );

    $_POST = [];
    header("Location: pedidos.php");
}
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
    
    <main class="container my-5 text-center d-flex flex-column justify-content-center">
        <h1 class="mb-4">Pedidos</h1>

        <div class="d-flex flex-column align-items-center justify-content-center">
            <button class="add border-0 rounded-4 my-3 fw-bold fs-5 px-3">Adicionar Pedido</button>
            
            <div>
                <form action="" method="POST" id="addPedido">
                    <input type="hidden" name="addPedido" value="1">

                    <div class="mb-3">
                        <label for="userEmail" class="form-label">Email do cliente:</label>
                        <input type="text" id="userEmail" name="userEmail" class="form-control" placeholder="Se não possuir, não preencher">
                    </div>

                    <div class="mb-3">
                        <label for="produtosPedidos" class="form-label">Produtos Pedidos:</label>
                        <input type="text" id="produtosPedidos" name="produtosPedidos" class="form-control" placeholder="Ex.: 1;2;3;4;5" required>
                    </div>

                    <div class="mb-3">
                        <label for="quantidadeProdutosPedidos" class="form-label">Quantidade dos Produtos Pedidos:</label>
                        <input type="text" id="quantidadeProdutosPedidos" name="quantidadeProdutosPedidos" class="form-control" placeholder="Ex.: 1;2;3;4;5" required>
                    </div>

                    <div class="mb-3 form-check">
                        <input name="ckbIsDelivery" id="ckbIsDelivery" type="checkbox" class="form-check-input">
                        <label for="ckbIsDelivery" class="form-check-label">O pedido é para entrega!</label>
                    </div>

                    <div class="mb-3">
                        <label for="valorFrete" class="form-label">Valor do Frete:</label>
                        <input type="text" id="valorFrete" name="valorFrete" class="form-control" placeholder="Ex.: 15.00">
                    </div>

                    <div class="mb-3">
                        <label for="meioPagamento" class="form-label">Meio de Pagamento:</label>
                        <div>
                            <input type="radio" id="pagamentoDebito" name="meioPagamento" value="Cartão de Débito" class="form-check-input" required>
                            <label for="pagamentoDebito" class="form-check-label">Cartão de Débito</label>
                        </div>
                        <div>
                            <input type="radio" id="pagamentoCredito" name="meioPagamento" value="Cartão de Crédito" class="form-check-input">
                            <label for="pagamentoCredito" class="form-check-label">Cartão de Crédito</label>
                        </div>
                        <div>
                            <input type="radio" id="pagamentoDinheiro" name="meioPagamento" value="Dinheiro" class="form-check-input">
                            <label for="pagamentoDinheiro" class="form-check-label">Dinheiro</label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="valorTotal" class="form-label">Valor Total:</label>
                        <input type="text" id="valorTotal" name="valorTotal" class="form-control" placeholder="Ex.: 150.00" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Salvar</button>
                </form>
            </div>
        </div>

        <?php
        $pedidos = $pedidoController->listarPedidos();
        if (!empty($pedidos)) {
            foreach ($pedidos as $pedido) {
                $redirectToInformacao = 'informacoesPedido.php?idPedido=' . $pedido['idPedido'];
                $redirectToAtribuir = 'atribuirEntregador.php?idPedido=' . $pedido['idPedido'];
                ?>
                <div class="container0">
                    <div class="container1">
                        <h3 class="titulo mt-3">Número do Pedido: <?= htmlspecialchars($pedido['idPedido']); ?></h3>
                        <p>Realizado em: <?= htmlspecialchars((new DateTime($pedido['dtPedido']))->format('d/m/Y \à\s H:i')); ?></p>
                        <p>Total: R$ <?= number_format($pedido['valorTotal'], 2, ',', '.'); ?></p>
                        <p>Status: <?= htmlspecialchars($pedido['statusPedido']); ?></p>
                        <button class="btnVerInfos mt-3"><a href="<?= $redirectToInformacao; ?>">Ver Informações</a></button>
                        
                        <?php if ($pedido['tipoFrete'] == 1 && $pedido['idEntregador'] == NULL): ?>
                            <button class="btnVerInfos mt-3"><a href="<?= $redirectToAtribuir; ?>">Atribuir Entregador ao Pedido</a></button>
                        <?php endif; ?>
                    </div>
                </div>
                <?php
            }
        } else {
            echo '<div class="alert alert-warning">Nenhum pedido encontrado.</div>';
        }
        ?>
    </main>

    <?php include_once 'components/footer.php'; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
