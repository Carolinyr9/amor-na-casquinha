<?php
use app\controller2\PedidoController;
use app\controller2\CarrinhoController;
use app\controller2\ItemPedidoController;

$carrinhoController = new CarrinhoController();
$itemPedidoController = new ItemPedidoController();
$pedidoController = new PedidoController();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["btnSubmit"])) {
        $frete = (isset($_POST["frete"]) && is_numeric($_POST["frete"])) ? $_POST["frete"] : null;
        $tipoFrete = (isset($_POST["ckbIsDelivery"]) && $_POST["ckbIsDelivery"] === '1') ? 1 : 0;

        $dadosPedido = [
            'idCliente' => $_POST["idCliente"] ?? null,
            'idEndereco' => $_POST["idEndereco"] ?? null,
            'tipoFrete' => $tipoFrete,
            'valorTotal' => $_POST["totalComFrete"] ?? null,
            'frete' => $frete,
            'meioDePagamento' => $_POST["meioDePagamento"] ?? null,
            'trocoPara' => (isset($_POST["trocoPara"]) && is_numeric($_POST["trocoPara"])) ? (float) $_POST["trocoPara"] : null
        ];

        $idPedido = $pedidoController->criarPedido($dadosPedido);

        $itensCarrinho = $carrinhoController->listarCarrinho();

        foreach ($itensCarrinho as $item) {
            $dadosItensPedido = [
                'idPedido' => $idPedido,
                'idProduto' => $item['id'],
                'quantidade' => $item['quantidades']
            ];
            $itemPedidoController->criarPedido($dadosItensPedido);
        }

        $carrinhoController->limparCarrinho();
        header("Location: perfil.php");
        exit();
    }
}
