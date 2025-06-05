<?php
session_start();
require_once '../config/blockURLAccess.php';
require_once '../../vendor/autoload.php';
require_once '../utils/estoque/editarEstoque.php';

use app\controller\EstoqueController;
use app\controller\ProdutoController;

$estoqueController = new EstoqueController();
$produtoController = new ProdutoController();

$produtosEstoque = [];

if (isset($_GET['idEstoque']) && !empty($_GET['idEstoque'])) {
    $estoqueProduto = $estoqueController->selecionarProdutoEstoquePorID($_GET['idEstoque']);
    if ($estoqueProduto) {
        $produtosEstoque[] = $estoqueProduto;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include_once '../utils/links/styleLinks.php'; ?>
    <link rel="stylesheet" href="style/editarEstoque.css">
    <title>Edição de Estoque</title>
</head>

<body>
    <?php include_once 'components/header.php'; ?>

    <main>
        <div class="d-flex flex-column justify-content-center align-items-center">
            <h2 class="titulo">Edição</h2>
            <form action="editarEstoque.php" method="post"
                class="formulario container m-auto p-3 rounded-4 d-flex flex-row flex-wrap gap-4 justify-content-center">
                <?php foreach ($produtosEstoque as $produtoEstoque) {
                    $produto = $produtoController->selecionarProdutoPorID($produtoEstoque->getIdProduto());
                ?>
            
                <h3 class="subtitulo w-100"><?= $produto->getNome() ?></h3>
                <input type="hidden" name="produtos[<?= $produtoEstoque->getIdEstoque() ?>][idEstoque]"
                    value="<?= $produtoEstoque->getIdEstoque() ?>">
                <div class="form-group w-25">
                    <label for="lote_">Lote</label>
                    <input type="text" class="form-control" id="lote_<?= $produtoEstoque->getIdEstoque() ?>"
                        name="produtos[<?= $produtoEstoque->getIdEstoque() ?>][lote]" value="<?= $produtoEstoque->getLote() ?>"
                        required>
                </div>
                <div class="form-group w-25">
                    <label for="precoCompra_">Valor de compra</label>
                    <input type="text" class="form-control" id="precoCompra_<?= $produtoEstoque->getIdEstoque() ?>"
                        name="produtos[<?= $produtoEstoque->getIdEstoque() ?>][precoCompra]"
                        value="<?= $produtoEstoque->getPrecoCompra() ?>" required>
                </div>
                <div class="form-group w-25">
                    <label for="quantidade_">Quantidade</label>
                    <input type="number" class="form-control" id="quantidade_<?= $produtoEstoque->getIdEstoque() ?>"
                        name="produtos[<?= $produtoEstoque->getIdEstoque() ?>][quantidade]"
                        value="<?= $produtoEstoque->getQuantidade() ?>" required>
                </div>
                <div class="form-group w-25">
                    <label for="dtEntrada_">Entrada</label>
                    <input type="date" class="form-control" id="dtEntrada_<?= $produtoEstoque->getIdEstoque() ?>"
                        name="produtos[<?= $produtoEstoque->getIdEstoque() ?>][dtEntrada]"
                        value="<?= $produtoEstoque->getDtEntrada() ?>" required>
                </div>
                <div class="form-group w-25">
                    <label for="dtFabricacao_">Fabricação</label>
                    <input type="date" class="form-control" id="dtFabricacao_<?= $produtoEstoque->getIdEstoque() ?>"
                        name="produtos[<?= $produtoEstoque->getIdEstoque() ?>][dtFabricacao]"
                        value="<?= $produtoEstoque->getDtFabricacao() ?>" required>
                </div>
                <div class="form-group w-25">
                    <label for="dtVencimento_">Vencimento</label>
                    <input type="date" class="form-control" id="dtVencimento_<?= $produtoEstoque->getIdEstoque() ?>"
                        name="produtos[<?= $produtoEstoque->getIdEstoque() ?>][dtVencimento]"
                        value="<?= $produtoEstoque->getDtVencimento() ?>" required>
                </div>
                <div class="form-group w-25">
                    <label for="qtdMinima_">Quantidade Mínima</label>
                    <input type="text" class="form-control" id="qtdMinima_<?= $produtoEstoque->getIdEstoque() ?>"
                        name="produtos[<?= $produtoEstoque->getIdEstoque() ?>][qtdMinima]"
                        value="<?= $produtoEstoque->getQtdMinima() ?>" required>
                </div>
                <div class="form-group w-25">
                    <label for="qtdOcorrencia_">Quantidade Ocorrida</label>
                    <input type="number" class="form-control" id="qtdOcorrencia_<?= $produtoEstoque->getIdEstoque() ?>"
                        name="produtos[<?= $produtoEstoque->getIdEstoque() ?>][qtdOcorrencia]"
                        value="<?= $produtoEstoque->getQtdOcorrencia() ?>" required>
                </div>
                <div class="form-group w-100">
                    <label for="ocorrencia_">Ocorrência</label>
                    <textarea class="form-control" name="produtos[<?= $produtoEstoque->getIdEstoque() ?>][ocorrencia]"
                        id="ocorrencia_<?= $produtoEstoque->getIdEstoque() ?>" required><?= $produtoEstoque->getOcorrencia() ?></textarea>
                </div>
                <?php } ?>
                <input type="submit" value="Editar" name="editarsubmit" class="botao botao-primary m-auto">
            </form>
            <a class="botao botao-secondary mt-4" href="telaEstoque.php">Voltar</a>
        </div>

    </main>

    <?php include_once 'components/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
</body>

</html>
