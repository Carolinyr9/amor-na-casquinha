<?php
use app\controller\PedidoController;
use app\controller\CarrinhoController;
use app\controller\ItemPedidoController;
use app\controller\EstoqueController;
use app\utils\helpers\Logger;

$carrinhoController = new CarrinhoController();
$itemPedidoController = new ItemPedidoController();
$pedidoController = new PedidoController();
$estoqueController = new EstoqueController();

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["btnSubmit"])) {

    $itensCarrinho = $carrinhoController->listarCarrinho();

    foreach ($itensCarrinho as $item) {
        $dadosEstoque = [
            'idProduto' => $item['id'], 
            'quantidade' => $item['qntd']
        ];

        $resultado = $estoqueController->decrementarQuantidade($dadosEstoque);

        if (!$resultado) {
            echo "<script>
                    alert('Estamos sem estoque suficiente de algum produto do seu carrinho! Sentimos muito!');
                    window.location.href = 'carrinho.php';
                </script>";
            exit();
        }
    }

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
    
    foreach ($itensCarrinho as $item) {
        $dadosItensPedido = [
            'idPedido' => $idPedido,
            'idProduto' => $item['id'],
            'quantidade' => $item['qntd']
        ];
        $itemPedidoController->criarPedido($dadosItensPedido);
    }

    $carrinhoController->limparCarrinho();
    header("Location: perfil.php");
    exit();
}
