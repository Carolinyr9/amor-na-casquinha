<?php

use app\controller\CategoriaProdutoController;
use app\utils\helpers\Logger;

if (isset($_GET['categoriaAtivar'])) {
    $idCategoria = $_GET['categoriaAtivar'];
    $categoriaController = new CategoriaProdutoController();
    
    $categoriaController->ativarCategoria($idCategoria);
    
    header("Location: gerenciarCategorias.php");
    exit();
} else {
    echo "Categoria não especificada.";
}
?>
