<?php
session_start();
require_once '../config/blockURLAccess.php';
require_once '../controller/pedidoController.php';

$pedidoController = new PedidoController();

$vendas = [];
$itens = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['data-inicio'], $_POST['data-fim'])) {
    $dataInicio = $_POST['data-inicio'];
    $dataFim = $_POST['data-fim'];

    if (!empty($dataInicio) && !empty($dataFim)) {
        $vendas = $pedidoController->listarResumo();
        $itens = $pedidoController->listarTodosItensPedidos();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatórios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style/CabecalhoRodape.css">
    <link rel="stylesheet" href="style/pedidosS.css">
</head>
<body>
    <?php include_once 'components/header.php'; ?>

    <main class="container my-5 text-center flex flex-column justify-content-center">
        <h1 class="mb-4">Relatórios</h1>

        <h3>Selecionar Período</h3>
        <form method="POST" class="mb-4">
            <input type="date" name="data-inicio" id="data-inicio" required>
            <input type="date" name="data-fim" id="data-fim" required>
            <button type="submit" class="btn btn-primary">Gerar Relatório</button>
        </form>

        <?php if (!empty($vendas)) { ?>
            <h2>Resumo</h2>
            <div class="box-pedido w-100 d-flex justify-content-center blue m-auto rounded-5 py-3">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Valor Total Vendido</th>
                            <th>Total de Pedidos Realizados</th>
                            <th>Clientes Diferentes</th>
                            <th>Total de Produtos Vendidos</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>R$ <?= number_format($vendas['totalVendas'], 2, ',', '.') ?></td>
                            <td><?= htmlspecialchars($vendas['pedidosFeitos']) ?></td>
                            <td><?= htmlspecialchars($vendas['totalPedidosClientes']) ?></td>
                            <td><?= htmlspecialchars($vendas['totalProdutos']) ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        <?php } ?>

        <?php if (!empty($itens)) { ?>
            <h2>Sabores Mais Vendidos</h2>
            <div class="box-pedido w-100 d-flex justify-content-center blue m-auto rounded-5 py-3">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Vendidos</th>
                            <th>Preço Unitário</th>
                            <th>Foi Desativado?</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($itens as $item) { ?>
                            <tr>
                                <td><?= htmlspecialchars($item['NomeProduto']) ?></td>
                                <td><?= htmlspecialchars($item['quantidade']) ?></td>
                                <td>R$ <?= number_format($item['Preco'], 2, ',', '.') ?></td>
                                <td><?= $item['ProdutoDesativado'] ? 'Sim' : 'Não' ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        <?php } ?>
    </main>

    <?php include_once 'components/footer.php'; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
