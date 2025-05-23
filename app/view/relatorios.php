<?php
session_start();
require_once '../config/blockURLAccess.php';
require_once '../../vendor/autoload.php';
require_once '../utils/pedido/geraResumoVendas.php';
use app\controller\PedidoController;
use app\controller\ItemPedidoController;
use app\controller\ProdutoController;
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
    <link rel="stylesheet" href="style/components/botao.css">
    <link rel="stylesheet" href="style/base/variables.css">
    <link rel="stylesheet" href="style/base/global.css">
    <link rel="stylesheet" href="style/relatorios-style.css">
    <link rel="shortcut icon" href="../images/iceCreamIcon.ico" type="image/x-icon">
</head>
<body>
    <?php include_once 'components/header.php'; ?>

    <main class="container my-5 text-center flex flex-column justify-content-center">
        <h1 class="titulo mb-3">Relatórios</h1>

        <h3 class="subtitulo">Selecionar Período</h3>
        <form method="POST" class="d-flex flex-row justify-content-center gap-4 mb-4">
            <input type="date" class="form-control w-auto" name="data-inicio" id="data-inicio" required>
            <input type="date" class="form-control w-auto" name="data-fim" id="data-fim" required>
            <button type="submit" class="botao botao-primary">Gerar Relatório</button>
        </form>

        <?php if ($totalVendas > 0) { ?>
            <h2 class="subtitulo">Resumo</h2>
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
            <h2 class="subtitulo">Sabores Mais Vendidos</h2>
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
