<?php
use app\controller\PedidoController;
use app\controller\ItemPedidoController;

$itemPedidoController = new ItemPedidoController();
$pedidoController = new PedidoController();

if (isset($_POST['addPedido'])) {
    $produtosArray = explode(";", $_POST["produtosPedidos"] ?? "");
    $quantidadesArray = explode(";", $_POST["quantidadeProdutosPedidos"] ?? "");
    
    $dadosPedido = [
        'idCliente' => $_POST["idCliente"] ?? null,
        'idEndereco' => $_POST["idEndereco"] ?? null,
        'tipoFrete' => isset($_POST["ckbIsDelivery"]) ? 1 : 0,
        'valorTotal' => $_POST["valorTotal"] ?? "0.00",
        'frete' => $_POST["valorFrete"] ?? NULL,
        'meioDePagamento' => $_POST["meioPagamento"] ?? NULL,
        'trocoPara' => NULL
    ];

    $idPedido = $pedidoController->criarPedido($dadosPedido);

    foreach ($produtosArray as $index => $produto) {
        $dadosItensPedido = [
            'idPedido' => $idPedido,
            'idProduto' => $produto,
            'quantidade' => $quantidadesArray[$index] ?? 1
        ];
        $itemPedidoController->criarPedido($dadosItensPedido);
    }

    $_POST = [];
    header("Location: pedidos.php");
    exit();
}