<?php
use app\controller\CategoriaProdutoController;
use app\utils\helpers\Logger;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['idCategoriaExcl'], $_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {
    $idCategoria = $_POST['idCategoriaExcl'];
    $categoriaController = new CategoriaProdutoController();
    
    $categoriaController->removerCategoria($idCategoria);
    
    header("Location: gerenciarCategorias.php");
    exit();
}
?>
