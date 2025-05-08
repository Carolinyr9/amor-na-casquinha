<?php
use app\controller\ProdutoController;
use app\controller\EstoqueController;
use app\utils\Logger;
require_once '../utils/fotoHandler.php';
$foto = fotoHandler();

Logger::logError($foto);

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
