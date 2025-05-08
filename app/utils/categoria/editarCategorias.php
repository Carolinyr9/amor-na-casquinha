<?php
use app\controller\CategoriaProdutoController;
use app\utils\helpers\Logger;
require_once __DIR__ . '/../helpers/fotoHandler.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btnEditar'])) {

    $id = $_GET['categoria'] ?? null;
    $foto = fotoHandler();
    $dados = [
        'id' => $id,
        'nome' => $_POST['nomeProdEdt'] ?? '',
        'marca' => $_POST['marcaProdEdt'] ?? '',
        'descricao'=> $_POST['descricaoProdEdt'] ?? '',
        'foto' => $foto,
    ];

    $categoriaController = new CategoriaProdutoController();
    $categoriaController->editarCategoria($dados);

   header("Location: editarCategoria.php?categoria=$id");
   exit();
}
?>