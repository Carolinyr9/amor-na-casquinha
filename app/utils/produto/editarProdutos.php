<?php
use app\controller\ProdutoController;
use app\utils\helpers\Logger;
require_once __DIR__ . '/../helpers/fotoHandler.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btnEditar'])) {
    
    if(isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
        $foto = fotoHandler();
    } else {
        $foto = $_POST['imagemSabEdt'];
    }

    $dados = [
        'id' => $_POST['idProduto'] ?? '',
        'nome' => $_POST['nomeProdEdt'] ?? '',
        'marca' => $_POST['marcaProdEdt'] ?? '',
        'preco'=> $_POST['precoSabEdt'] ?? '',
        'foto' => $foto,
    ];

    $idProduto = $_POST['idProduto'] ?? '';
    $idCategoria = $_POST['idCategoria'] ?? '';

    
    $produtoController = new ProdutoController();
    $produtoController->editarProduto($dados);

    header("Location: editarSabor.php?idProduto=$idProduto&idCategoria=$idCategoria");
    exit();
}