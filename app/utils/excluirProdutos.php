<?php
use app\controller2\ProdutoController;
use app\utils\Logger;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['idProdutoExcl'])) {
    $idProduto = $_POST['idProdutoExcl'];
    $produtoController = new produtoController();
    $produtoController->desativarProduto($idProduto);
    header("Location: gerenciarCategorias.php");
    exit();
}