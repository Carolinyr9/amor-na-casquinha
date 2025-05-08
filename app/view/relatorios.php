<?php
session_start();
require_once '../config/blockURLAccess.php';
require_once '../../vendor/autoload.php';
require_once '../utils/geraResumoVendas.php';
use app\controller2\PedidoController;
use app\controller2\ItemPedidoController;
use app\controller2\ProdutoController;
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatórios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">  
    <link rel="stylesheet" href="style/CabecalhoRodape.css">
    <link rel="stylesheet" href="style/relatorios-style.css">
</head>
<body>
    <?php include_once 'components/header.php'; ?>

    <main class="container my-5 text-center flex flex-column justify-content-center">
        <h1 class="mb-4 my-4 text-center">Relatórios</h1>

        <h3>Selecionar Período</h3>
        <form method="POST" class="mb-4">
            <input type="date" name="data-inicio" id="data-inicio" required>
            <input type="date" name="data-fim" id="data-fim" required>
            <button type="submit" class="form__btn text-decoration-none border-0 rounded-3 m-1 text-black px-3">Gerar Relatório</button>
        </form>

        <?php if ($totalVendas > 0) { ?>
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
                            <td>R$ <?= number_format($totalVendas, 2, ',', '.') ?></td>
                            <td><?= $totalPedidos ?></td>
                            <td><?= $totalClientes ?></td>
                            <td><?= $totalProdutos ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        <?php } else { ?>
            <p>Selecione um período válido para acessar o rela 
                tório!</p>
        <?php } ?>


        <?php if (!empty($produtos)) { ?>
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
                        <?php 
                        $produtosLimitados = array_slice($produtos, 0, 3); // Limitar a 3 produtos mais vendidos
                        foreach ($produtosLimitados as $produto) { ?>
                            <tr>
                                <td><?= htmlspecialchars($produto['NomeProduto']) ?></td>
                                <td><?= htmlspecialchars($produto['quantidade']) ?></td>
                                <td>R$ <?= number_format($produto['Preco'], 2, ',', '.') ?></td>
                                <td><?= $produto['ProdutoDesativado'] ? 'Sim' : 'Não' ?></td>
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
