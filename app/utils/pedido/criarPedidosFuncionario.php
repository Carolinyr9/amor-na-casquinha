<?php

use app\controller\PedidoController;
use app\controller\ItemPedidoController;
use app\controller\ProdutoController;
use app\utils\helpers\Logger;

require_once '../utils/helpers/logger.php';

$itemPedidoController = new ItemPedidoController();
$produtoController = new ProdutoController();
$pedidoController = new PedidoController();

if (isset($_POST['addPedido'])) {

    $produtosArray = $_POST["produtosSelecionados"] ?? [];

    if($produtosArray == null || $produtosArray == []){
        if ($produtosArray == null || $produtosArray == []) {
        Logger::logError("Nenhum produto selecionado para o pedido.");
        echo "<script>
            alert('Nenhum produto selecionado para o pedido.');
            window.history.back(); // Volta para a página anterior
        </script>";
        exit();
        }
    }
    
    $quantidadesArray = $_POST['quantidades']  ?? [];

    $valorTotal = 0.0;

    foreach ($produtosArray as $produtoId) {
        $quantidade = $quantidadesArray[$produtoId] ?? 1;
        $produto = $produtoController->selecionarProdutoPorID($produtoId);
        if ($produto) {
            $valorTotal += $produto->getPreco() * $quantidade;
        }
    }

    $valorFrete = isset($_POST["valorFrete"]) ? floatval(str_replace(',', '.', $_POST["valorFrete"])) : 0.0;
    $valorTotal += $valorFrete;

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
