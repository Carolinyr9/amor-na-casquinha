<?php
session_start();
require_once '../../vendor/autoload.php';
require_once '../config/blockURLAccess.php';

use app\controller\EstoqueController;
use app\controller\ProdutoController;
use app\controller\CategoriaProdutoController;

$estoqueController = new EstoqueController();
$produtoController = new ProdutoController();
$categoriaController = new CategoriaProdutoController();

$listaEstoque = $estoqueController->listarEstoque();
$estoqueController->verificarQuantidadeMinima();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estoque</title>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style/CabecalhoRodape.css">
    <link rel="stylesheet" href="style/components/botao.css">
    <link rel="stylesheet" href="style/base/variables.css">
    <link rel="stylesheet" href="style/base/global.css">
    <link rel="stylesheet" href="style/estoque-style.css">
    <link rel="stylesheet" href="style/alertas-style.css">
    <link rel="shortcut icon" href="../images/iceCreamIcon.ico" type="image/x-icon">
</head>
<body>

<?php include_once 'components/header.php'; ?>

<main>
    <div class="d-flex justify-content-between m-auto w-25">
        <a href="#" id="editarProdutoEstoque" class="botao botao-primary">Editar</a>
        <a href="#" id="excluirProdutoEstoque" class="botao botao-secondary">Excluir</a>
    </div>

    <div class="lista m-auto p-3">
        <table class="table table-striped table-hover text-nowrap">
            <thead>
                <tr>
                    <th scope="col"></th>
                    <th scope="col">Produto</th>
                    <th scope="col">Categoria</th>
                    <th scope="col">Data de Entrada</th>
                    <th scope="col">Quantidade</th>
                    <th scope="col">Data de Fabricação</th>
                    <th scope="col">Data de Vencimento</th>
                    <th scope="col">Lote</th>
                    <th scope="col">Preço de Compra</th>
                    <th scope="col">Qtd. Mínima</th>
                    <th scope="col">Qtd. Vendida</th>
                    <th scope="col">Ocorrência</th>
                    <th scope="col">Qtd. Ocorrência</th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                <?php if ($listaEstoque): ?>
                    <?php include 'components/estoqueTabela.php'; ?>
                <?php else: ?>
                    <tr><td colspan="15" class="text-center">Nenhum item no estoque.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<?php include_once 'components/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
<script src="script/telaEstoqueScript.js"></script>
<script src="script/alertas-script.js"></script>

</body>
</html>
