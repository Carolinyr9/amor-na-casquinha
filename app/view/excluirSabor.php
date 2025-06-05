<?php
session_start();
require_once '../config/blockURLAccess.php';
require_once '../../vendor/autoload.php';
require_once '../utils/produto/excluirProdutos.php';

use app\controller\ProdutoController;

$produtoController = new ProdutoController();
$produtoId = $_GET['idProduto'] ?? null;
$categoriaId = $_GET['idCategoria'] ?? null;
$produto = $produtoController->selecionarProdutoPorID($produtoId);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir Sabor</title>
    <?php include_once '../utils/links/styleLinks.php'; ?>
    <link rel="stylesheet" href="style/CabecalhoRodape.css">
    <link rel="stylesheet" href="style/excluirProduto.css">
</head>
<body>

    <?php include_once 'components/header.php'; ?>

    <main class="d-flex flex-column justify-content-center align-items-center">
        <h1 class="titulo">Excluir Sabor</h1>

        <div class="container-info container d-flex align-items-center flex-column justify-content-center mx-auto my-4 p-4 rounded-4">
        <h4 class="subtitulo text-center">Tem certeza que deseja excluir esse sabor?</h4>

            <?php if ($produto): ?>
                <div class="d-flex align-items-center flex-column">
                    <picture>
                        <img src="../images/<?= htmlspecialchars($produto->getFoto()) ?>"
                             alt="<?= htmlspecialchars($produto->getNome()) ?>"
                             class="imagem">
                    </picture>
                    <div class="d-flex align-items-center flex-column">
                        <h4 class="subtitulo"><?= htmlspecialchars($produto->getNome()) ?></h4>
                        <p>Número de Identificação: <?= htmlspecialchars($produto->getId()) ?></p>
                        <p>Preço: R$ <?= htmlspecialchars(number_format($produto->getPreco(), 2, ',', '.')) ?></p>
                    </div>
                </div>

                <form action="" method="POST" id="formulario" class="formulario">
                    <input type="hidden" name="idProdutoExcl" value="<?= htmlspecialchars($produto->getId()) ?>">
                    <button type="submit" class="botao botao-alerta">Excluir</button>
                </form>
            <?php else: ?>
                <p>Produto não encontrado.</p>
            <?php endif; ?>
        </div>

        <a class="botao botao-secondary" href="gerenciarProdutos.php?categoria=<?= htmlspecialchars($categoriaId) ?>">Voltar</a>
        
    </main>

    <?php include_once 'components/footer.php'; ?>
</body>
</html>
