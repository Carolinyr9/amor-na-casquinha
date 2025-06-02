<?php
use app\controller\ProdutoController;

if (isset($_GET['produtoAtivar'])) {
    $idproduto = $_GET['produtoAtivar'];
    $produtoController = new ProdutoController();
    
    $produtoController->ativarProduto($idproduto);
    
    header("Location: gerenciarProdutos.php?categoria=" . $_GET['idCategoria']);
    exit();
} else {
    echo "Produto não especificado.";
}
?>
