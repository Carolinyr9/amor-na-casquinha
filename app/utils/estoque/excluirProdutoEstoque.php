<?php
use app\controller\ProdutoController;
use app\controller\EstoqueController;

if ((isset($_POST) || !empty($_POST)) && isset($_POST["excluirSubmit"])) {
    if (isset($_POST['idEstoque']) && !empty($_POST['idEstoque'])) {
        $idEstoque = $_POST['idEstoque'] ?? null;
        $idProduto = $_POST['idProduto'] ?? null;

        $estoqueController = new EstoqueController();
        $produtoController = new ProdutoController();

        $estoqueController->desativarProdutoEstoque($idEstoque);
        $produtoController->desativarProduto($idProduto);
        
        header("Location: telaEstoque.php");
        exit();
    }
}