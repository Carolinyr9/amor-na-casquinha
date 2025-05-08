<?php
use app\controller\CategoriaProdutoController;
require_once __DIR__ . '/../helpers/fotoHandler.php';

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