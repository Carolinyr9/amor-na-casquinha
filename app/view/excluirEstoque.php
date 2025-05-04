<?php
session_start();
require_once '../config/blockURLAccess.php';
require_once '../../vendor/autoload.php';
require_once '../utils/excluirProdutoEstoque.php';

use app\controller2\EstoqueController;
use app\controller2\CategoriaProdutoController;
use app\controller2\ProdutoController;

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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">  
    <link rel="stylesheet" href="style/CabecalhoRodape.css">
    <link rel="stylesheet" href="style/excluirEstoque-style.css">
    <title>Excluir Produto do Estoque</title>
</head>
<body>
    <?php include_once 'components/header.php'; ?>

    <main class="d-flex flex-column justify-content-center align-items-center">
        <h1 class="m-auto text-center pt-4 pb-4">Excluir produto do estoque</h1>
        <p class="text-center text-wrap w-50">
            Tem certeza que deseja excluir esse produto? Ele também será excluído dos produtos em visualização para compra de clientes.
        </p>

        <div class="dashboard w-25 mx-auto my-4 p-4 rounded-4 d-flex align-items-center flex-column justify-content-center">
            <?php if (isset($estoqueProduto) && $estoqueProduto): ?>
                <form action="" method="POST" id="formulario" class="d-flex justify-content-center flex-column">
                    <?php 
                        $categoria = $categoriaController->buscarCategoriaPorID($estoqueProduto->getIdCategoria());
                        $produto = $produtoController->selecionarProdutoPorID($estoqueProduto->getIdProduto());
                    ?>

                    <div class="d-flex align-items-center flex-column">
                        <p>Categoria: <?= $categoria ? $categoria->getNome() : 'Categoria não encontrada' ?></p>
                        <p>Produto: <?= $produto ? $produto->getNome() : 'Produto não encontrado' ?></p>
                        <p>Lote: <?= $estoqueProduto->getLote() ?></p>

                        <div class="img-box" style="width: 40%; height: auto;">
                            <img class="imagem m-2 rounded-4 w-100 h-auto"
                                 src="../images/<?= htmlspecialchars($produto->getFoto()) ?>"
                                 alt="<?= htmlspecialchars($produto->getNome()) ?>">
                        </div>

                        <input type="hidden" name="idEstoque" value="<?= htmlspecialchars($estoqueProduto->getIdEstoque()) ?>">
                        <input type="hidden" name="idProduto" value="<?= htmlspecialchars($estoqueProduto->getIdProduto()) ?>">
                    </div>

                    <input type="submit" name="excluirSubmit" class="input-excluir border-0 rounded-4 px-3 fw-bold mx-auto" value="Excluir" />
                </form>
            <?php else: ?>
                <p>Produto não encontrado.</p>
            <?php endif; ?>
        </div>

        <button class="b-voltar m-auto border-0 rounded-4 fw-bold px-3">
            <a class="text-decoration-none color-black" href="editarProdutos.php">Voltar</a>
        </button>
    </main>

    <?php include_once 'components/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js"
            integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
            crossorigin="anonymous"></script>
</body>
</html>
