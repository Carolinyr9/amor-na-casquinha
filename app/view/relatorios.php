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
    <title>Relatórios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style/CabecalhoRodape.css">
    <link rel="stylesheet" href="style/pedidosS.css">
</head>
<body>
    <?php include_once 'components/header.php'; ?>
    <main class="container my-5 text-center flex flex-column justify-content-center">
        <h1 class="mb-4">Relatórios</h1>
        
        <?php
        $itens = $pedidoController->listarTodosItensPedidos(); 
        
        if (!empty($itens)) {
        ?>
        <h2>Sabores mais vendidos</h2>
        <div class="box-pedido w-100 d-flex justify-content-center blue m-auto rounded-5 py-3">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Vendidos</th>
                        <th>Preço Unitário</th>
                        <th>Foi desativado?</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($itens as $item) {
                        if($item['quantidade'] > 1){ ?>
                            <tr>
                                <td><?= htmlspecialchars($item['NomeProduto']) ?></td>
                                <td><?= htmlspecialchars($item['quantidade']) ?></td>
                                <td>R$ <?= number_format($item['Preco'], 2, ',', '.') ?></td>
                                <td><?= $item['ProdutoDesativado'] ? 'Sim' : 'Não' ?></td>
                            </tr>
                    <?php }} ?>
                </tbody>
            </table>
        <?php
        } else {
        ?>
            <div class="alert alert-warning">Nenhum pedido encontrado.</div>
        <?php
        }
        ?>
        </div>


        <?php
        if (!empty($itens)) {
        ?>
        <h2>Sabores menos vendidos</h2>
        <div class="box-pedido w-100 d-flex justify-content-center blue m-auto rounded-5 py-3">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Vendidos</th>
                        <th>Preço Unitário</th>
                        <th>Foi desativado?</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($itens as $item) {
                        if($item['quantidade'] <= 1){ ?>
                            <tr>
                                <td><?= htmlspecialchars($item['NomeProduto']) ?></td>
                                <td><?= htmlspecialchars($item['quantidade']) ?></td>
                                <td>R$ <?= number_format($item['Preco'], 2, ',', '.') ?></td>
                                <td><?= $item['ProdutoDesativado'] ? 'Sim' : 'Não' ?></td>
                            </tr>
                    <?php }} ?>
                </tbody>
            </table>
        <?php
        } else {
        ?>
            <div class="alert alert-warning">Nenhum pedido encontrado.</div>
        <?php
        }
        ?>
        </div>

        <!-- relatorio contabil por data (enviar uma data no padrão mes/ano)
        
        vendas totais
        produto mais vendido
        gastos em produtos
        lucro total no periodo
        Ticket médio(Valor médio gasto por cliente no período, Fórmula: Vendas Totais / Número de Pedidos.)
        numero de pedidos cancelados

        !-->
    </main>

    <?php include_once 'components/footer.php'; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
