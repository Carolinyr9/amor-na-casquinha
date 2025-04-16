<?php
namespace app\view;
use app\controller2\ProdutoController;

session_start();
include_once 'components/header.php';

$produtoController = new ProdutoController();
$categoriaId = $_GET['categoria'] ?? null;
$produtos = $categoriaId ? $produtoController->selecionarProdutosAtivos($categoriaId) : [];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Sabores</title>
    <link rel="stylesheet" href="style/CabecalhoRodape.css">
    <link rel="stylesheet" href="style/saboresS.css">
    <link rel="shortcut icon" href="images/iceCreamIcon.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<main>
    <h1 class="m-auto text-center pt-4 pb-4">Sabores</h1>
    <div class="container d-flex flex-column align-items-center justify-content-center">
        <div class="container1 d-flex flex-row flex-wrap justify-content-center">
            <?php
                if (is_array($produtos) && count($produtos) > 0) {
                    foreach ($produtos as $produto) {
                        include 'components/produtosCards.php';
                    }
                } else {
                    echo '<p>Nenhum produto encontrado.</p>';
                }
            ?>
        </div>
        <button class="voltar d-flex justify-content-center align-items-center fs-4 fw-bold rounded-4 mt-5 border-0">
            <a class="text-decoration-none fs-5" href="index.php">Voltar</a>
        </button>
    </div>
</main>
<?php include_once 'components/footer.php'; ?>
</body>
</html>
