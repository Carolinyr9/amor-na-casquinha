<?php
session_start();
require_once '../config/blockURLAccess.php';
require_once '../../vendor/autoload.php';
use app\controller\PedidoController;

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

$pedidosPorPagina = 8;
$paginaAtual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($paginaAtual - 1) * $pedidosPorPagina;

$pedidos = $pedidoController->listarPedidos();
$totalPedidos = count($pedidos);
$totalPaginas = ceil($totalPedidos / $pedidosPorPagina);

$pedidosPagina = array_slice($pedidos, $offset, $pedidosPorPagina);
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
            <button id="toggleFormButton" class="btn btn-primary border-0 rounded-4 my-3 fw-bold fs-5 px-3">
                Adicionar Pedido
            </button>
            
            <div id="addPedidoForm">
                <form action="" method="POST">
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

                    <button type="submit" class="btn btn-success">Salvar Pedido</button>
                </form>
            </div>
        </div>

        <?php if (!empty($pedidosPagina)): ?>
            <?php foreach ($pedidosPagina as $pedido): ?>
                <div class="container0">
                    <div class="container1">
                        <h3 class="titulo mt-3">Número do Pedido: <?= htmlspecialchars($pedido['idPedido']); ?></h3>
                        <p>Realizado em: <?= htmlspecialchars((new DateTime($pedido['dtPedido']))->format('d/m/Y \à\s H:i')); ?></p>
                        <p>Total: R$ <?= number_format($pedido['valorTotal'], 2, ',', '.'); ?></p>
                        <p>Status: <?= htmlspecialchars($pedido['statusPedido']); ?></p>
                        <button class="btnVerInfos mt-3"><a href="informacoesPedido.php?idPedido=<?= $pedido['idPedido']; ?>">Ver Informações</a></button>
                        <?php if ($pedido['tipoFrete'] == 1 && $pedido['idEntregador'] == NULL): ?>
                            <button class="btnVerInfos mt-3"><a href="atribuirEntregador.php?idPedido=<?= $pedido['idPedido']; ?>">Atribuir Entregador ao Pedido</a></button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="alert alert-warning">Nenhum pedido encontrado.</div>
        <?php endif; ?>

        <nav aria-label="Navegação de página">
            <ul class="pagination justify-content-center mt-4">
                <li class="page-item <?= $paginaAtual == 1 ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?pagina=<?= $paginaAtual - 1; ?>" tabindex="-1">Anterior</a>
                </li>
                <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                    <li class="page-item <?= $i == $paginaAtual ? 'active' : ''; ?>">
                        <a class="page-link" href="?pagina=<?= $i; ?>"><?= $i; ?></a>
                    </li>
                <?php endfor; ?>
                <li class="page-item <?= $paginaAtual == $totalPaginas ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?pagina=<?= $paginaAtual + 1; ?>">Próxima</a>
                </li>
            </ul>
        </nav>
    </main>

    <?php include_once 'components/footer.php'; ?>
    
    <script src="script/exibirFormulario.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
