<?php
use app\controller2\CarrinhoController;
use app\controller2\ProdutoController;

$carrinhoController = new CarrinhoController();
$produtoController = new ProdutoController();

if (isset($_GET["add"])) {
    $produto = $produtoController->selecionarProdutoPorID($_GET["add"]);
    $carrinhoController->adicionarProduto($produto);
    header("Location: carrinho.php"); 
    exit();
}

if (isset($_GET["action"]) && $_GET["action"] === 'remove' && isset($_GET["item"])) {
    $carrinhoController->removerProduto($_GET["item"]);
    header("Location: carrinho.php"); 
    exit();
}