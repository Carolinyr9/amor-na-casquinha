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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">  
    <link rel="stylesheet" href="style/CabecalhoRodape.css">
    <link rel="stylesheet" href="style/carrinhoS.css">
    <link rel="stylesheet" href="style/components/botao.css">
    <link rel="stylesheet" href="style/components/cards.css">
    <link rel="stylesheet" href="style/base/global.css">
    <link rel="stylesheet" href="style/base/variables.css">
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
