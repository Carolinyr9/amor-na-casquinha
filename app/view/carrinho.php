<?php
session_start();
require_once '../config/blockURLAccess.php';
require_once '../../vendor/autoload.php';
require_once '../utils/carrinho/carrinhoFuncoes.php';
use app\controller\CarrinhoController;
use app\controller\ProdutoController;

$carrinhoController = new CarrinhoController();
$produtoController = new ProdutoController();

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
    <?php include_once '../utils/links/styleLinks.php'; ?>
    <link rel="stylesheet" href="style/carrinho.css">
    
</head>
<body>
    <?php include_once 'components/header.php'; ?>
    <main>
        <section>
            <h1 class="titulo mx-auto">Carrinho</h1>
            <div class="container d-flex flex-column align-items-center">
                <?php include 'components/carrinhoCards.php'; ?>
                <a class="botao botao-secondary mt-5" href="index.php">Voltar</a>
            </div>
        </section>
    </main>
    <?php include_once 'components/footer.php'; ?>
    <script src="script/atualizaQuantidade.js"></script>
</body>
</html>
