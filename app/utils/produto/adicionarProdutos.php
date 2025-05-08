<?php
use app\controller\ProdutoController;
use app\controller\EstoqueController;
use app\utils\helpers\Logger;
require_once __DIR__ . '/../helpers/fotoHandler.php';
$foto = fotoHandler();

if ($_SERVER['REQUEST_METHOD'] == 'POST' || isset($_POST['inserirSaborSubmit'])) {

    $dadosProduto = [
        'categoria' => $_POST["idProduto"] ?? '',
        'nome'     => $_POST['nomeSabAdd'] ?? '',
        'preco' => $_POST['precoSabAdd'] ?? '',
        'foto'      => $foto,
    ];

    $produtoController = new ProdutoController();
    $idProduto = $produtoController->criarProduto($dadosProduto);

    $dadosEstoque = [
        'idCategoria' => $_POST["idProduto"] ?? '',
        'idProduto' => $idProduto,
        'lote' => $_POST['lote'] ?? '',
        'dtEntrada' => $_POST['dataEntrada'] ?? '',
        'dtFabricacao' => $_POST['dataFabricacao'] ?? '',
        'dtVencimento' => $_POST['dataVencimento'] ?? '',
        'qtdMinima' => $_POST['quantidadeMinima'] ?? '',
        'quantidade' => $_POST['quantidade'] ?? '',
        'precoCompra' => $_POST['valor'] ?? '',
    ];

    $estoqueController = new EstoqueController();
    $estoqueController->criarProdutoEstoque($dadosEstoque);

    header("Location: gerenciarProdutos.php?categoria=" . $_POST["idProduto"]);
    exit;
}
