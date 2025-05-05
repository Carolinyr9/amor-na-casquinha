<?php
use app\controller2\PedidoController;
use app\controller2\CarrinhoController;

$carrinhoController = new CarrinhoController();
$pedidoController = new PedidoController();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["btnSubmit"])) {
        $frete = (isset($_POST["frete"]) && is_numeric($_POST["frete"])) ? $_POST["frete"] : null;
        $tipoFrete = (isset($_POST["ckbIsDelivery"]) && $_POST["ckbIsDelivery"] === '1') ? 1 : 0;

        $dados = [
            'idCliente' => isset($_POST["idCliente"]) ? $_POST["idCliente"] : NULL,
            'idEndereco' => isset($_POST["idEndereco"]) ? $_POST["idEndereco"] : NULL,
            'tipoFrete' => $tipoFrete,
            'valorTotal' => isset($_POST["totalComFrete"]) ? $_POST["totalComFrete"] : NULL,
            'frete' => $frete,
            'meioDePagamento' => isset($_POST["meioDePagamento"]) ? $_POST["meioDePagamento"] : NULL,
            'trocoPara' => isset($_POST["trocoPara"]) && is_numeric($_POST["trocoPara"]) ? (float) $_POST["trocoPara"] : NULL
        ];

        $pedidoController->criarPedido($dados);
        unset($_POST);

        $carrinhoController->limparCarrinho();
        header("Location: sobre.php");
        exit();     
    }
}