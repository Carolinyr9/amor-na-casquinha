<?php
session_start();
require_once '../config/blockURLAccess.php';
require_once '../../vendor/autoload.php';
require_once '../utils/estoque/excluirProdutoEstoque.php';

use app\controller\EstoqueController;
use app\controller\CategoriaProdutoController;
use app\controller\ProdutoController;

$estoqueController = new EstoqueController();
$produtoController = new ProdutoController();
$categoriaController = new CategoriaProdutoController();

if (isset($_GET['idEstoque']) && !empty($_GET['idEstoque'])) {
    $estoqueProduto = $estoqueController->selecionarProdutoEstoquePorID($_GET['idEstoque']);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include_once '../utils/links/styleLinks.php'; ?>
    <link rel="stylesheet" href="style/excluirProduto.css">
    
    <title>Excluir Produto do Estoque</title>
</head>
<body>
    <?php include_once 'components/header.php'; ?>

    <main class="d-flex flex-column justify-content-center align-items-center">
        <h1 class="titulo">Excluir produto do estoque</h1>
        <p class="text-center text-wrap w-50">
            Tem certeza que deseja excluir esse produto? Ele também será excluído dos produtos em visualização para compra de clientes.
        </p>

        <div class="container-info container d-flex align-items-center flex-column justify-content-center mx-auto my-4 p-4 rounded-4">
            <?php if (isset($estoqueProduto) && $estoqueProduto): ?>
                <form action="" method="POST" id="formulario" class="d-flex justify-content-center align-items-center flex-column">
                    <?php 
                        $categoria = $categoriaController->buscarCategoriaPorID($estoqueProduto->getIdCategoria());
                        $produto = $produtoController->selecionarProdutoPorID($estoqueProduto->getIdProduto());
                    ?>

                    <div class="d-flex align-items-center flex-column">
                        <p>Categoria: <?= $categoria ? $categoria->getNome() : 'Categoria não encontrada' ?></p>
                        <p>Produto: <?= $produto ? $produto->getNome() : 'Produto não encontrado' ?></p>
                        <p>Lote: <?= $estoqueProduto->getLote() ?></p>

                        <picture>
                            <img class="rounded-4"
                                    src="../images/<?= htmlspecialchars($produto->getFoto()) ?>"
                                    alt="<?= htmlspecialchars($produto->getNome()) ?>">
                        </picture>

                        <input type="hidden" name="idEstoque" value="<?= htmlspecialchars($estoqueProduto->getIdEstoque()) ?>">
                        <input type="hidden" name="idProduto" value="<?= htmlspecialchars($estoqueProduto->getIdProduto()) ?>">
                    </div>

                    <input type="submit" name="excluirSubmit" class="botao botao-alerta mx-auto mt-4" value="Excluir" />
                </form>
            <?php else: ?>
                <p>Produto não encontrado.</p>
            <?php endif; ?>
        </div>

        <a class="botao botao-secondary mt-4" href="telaEstoque.php">Voltar</a>
    </main>

    <?php include_once 'components/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js"
            integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
            crossorigin="anonymous"></script>
</body>
</html>
