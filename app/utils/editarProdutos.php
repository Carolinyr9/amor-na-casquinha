<?php
use app\controller2\ProdutoController;
use app\utils\Logger;
require_once '../utils/fotoHandler.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btnEditar'])) {
    $dados = [
        'id' => $_POST['idProduto'] ?? '',
        'nome' => $_POST['nomeProdEdt'] ?? '',
        'marca' => $_POST['marcaProdEdt'] ?? '',
        'preco'=> $_POST['precoSabEdt'] ?? '',
        'foto' => fotoHandler(),
    ];

    $idProduto = $_POST['idProduto'] ?? '';
    $idCategoria = $_POST['idCategoria'] ?? '';

    
    $produtoController = new ProdutoController();
    $produtoController->editarProduto($dados);

    header("Location: editarSabor.php?idProduto=$idProduto&idCategoria=$idCategoria");
    exit();
}