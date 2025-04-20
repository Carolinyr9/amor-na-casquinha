<?php
session_start();
require_once '../config/blockURLAccess.php';
require_once '../../vendor/autoload.php';
use app\controller2\CarrinhoController;
use app\controller2\ProdutoController;

$carrinhoController = new CarrinhoController();
$produtoController = new ProdutoController();

if (isset($_GET["add"])) {
    $produto = $produtoController->selecionarProdutoPorID($_GET["add"]);
    $carrinhoController->adicionarProduto($produto);
}

if (isset($_GET["action"]) && $_GET["action"] === 'remove' && isset($_GET["item"])) {
    $carrinhoController->removerProduto($_GET["item"]);
}

$produtos = $carrinhoController->listarCarrinho();
$total = $carrinhoController->calcularTotal();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']) && isset($_POST['quantidade'])) {
    $dados = [ 'id' => $_POST['id'], 'quantidade' => $_POST['quantidade']];
    $carrinhoController->atualizarQuantidade($dados);
    header("Location: carrinho.php"); 
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style/CabecalhoRodape.css">
    <link rel="stylesheet" href="style/carrinhoS.css">
</head>
<body>
    <?php include_once 'components/header.php'; ?>
    <main>
        <h1 class="m-auto text-center pt-4 pb-4">Carrinho</h1>
        <div class="container d-flex flex-column align-items-center">
            <?php include 'components/carrinhoCards.php'; ?>
            <button class="voltar fs-5 fw-bold mt-5 border-0 rounded-4">
                <a class="text-decoration-none" href="index.php">Voltar</a>
            </button>
        </div>
    </main>
    <?php include_once 'components/footer.php'; ?>
</body>
</html>
