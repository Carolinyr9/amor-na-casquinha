<?php
use app\controller2\EstoqueController;
use app\utils\Logger;

$estoqueController = new EstoqueController();

if ((isset($_POST) || !empty($_POST)) && isset($_POST["editarsubmit"])) {
    foreach ($_POST['produtos'] as $produtoEditado) {
        $estoqueController->editarProdutoEstoque($produtoEditado);
    }

    $ids = array_keys($_POST['produtos']);
    $idEstoque = $ids[0];

    header("Location: editarEstoque.php?idEstoque=$idEstoque");
    exit;
}