<?php

use app\controller\PedidoController;
use app\controller\ItemPedidoController;
use app\controller\ProdutoController;
use app\utils\helpers\Logger;

$itemPedidoController = new ItemPedidoController();
$produtoController = new ProdutoController();
$pedidoController = new PedidoController();

if (isset($_POST['addPedido'])) {
    $produtosArray = $_POST["produtosSelecionados"] ?? [];
    $quantidadesArray = $_POST['quantidades']  ?? [];

    $valorTotal = 0.0;

    foreach ($produtosArray as $produtoId) {
        $quantidade = $quantidadesArray[$produtoId] ?? 1;
        $produto = $produtoController->selecionarProdutoPorID($produtoId);
        if ($produto) {
            $valorTotal += $produto->getPreco() * $quantidade;
        }
    }

    $valorTotal += $_POST["valorFrete"] ?? 0;


    $dadosPedido = [
        'idCliente' => $_POST["idCliente"] ?? 0,
        'idEndereco' => $_POST["idEndereco"] ?? null,
        'tipoFrete' => isset($_POST["ckbIsDelivery"]) ? 1 : 0,
        'valorTotal' => $valorTotal,
        'frete' => $_POST["valorFrete"] ?? 0,
        'meioDePagamento' => $_POST["meioPagamento"] ?? null,
        'trocoPara' => null
    ];

    $idPedido = $pedidoController->criarPedido($dadosPedido);

    foreach ($produtosArray as $produtoId) {
        $quantidade = $quantidadesArray[$produtoId] ?? 1;

        $dadosItensPedido = [
            'idPedido' => $idPedido,
            'idProduto' => $produtoId,
            'quantidade' => $quantidade
        ];
        $itemPedidoController->criarPedido($dadosItensPedido);
    }

    $_POST = [];
    header("Location: pedidos.php");
    exit();
}
