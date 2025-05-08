<?php
use app\controller\CategoriaProdutoController;
use app\utils\Logger;
require_once '../utils/fotoHandler.php';

$foto = fotoHandler();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dados = [
        'nome'      => $_POST['nomeProAdd'] ?? '',
        'marca'     => $_POST['marcaProAdd'] ?? '',
        'descricao' => $_POST['descricaoProAdd'] ?? '',
        'fornecedor'=> $_POST['fornecedor'] ?? '',
        'foto'      => $foto,
    ];

    $categoriaController = new CategoriaProdutoController();
    $categoriaController->criarCategoria($dados);

    header("Location: editarCategorias.php");
    exit;
}
?>