<?php
session_start();
require_once '../../vendor/autoload.php';

use app\controller\EstoqueController;
use app\controller\ProdutoController;
use app\controller\CategoriaProdutoController;

require_once '../config/blockURLAccess.php';

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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">  
    <link rel="stylesheet" href="style/CabecalhoRodape.css">
    <link rel="stylesheet" href="style/estoque-style.css">
    <link rel="stylesheet" href="style/alertas-style.css">
</head>
<body>

<?php include_once 'components/header.php'; ?>

<main>
    <div class="d-flex justify-content-between m-auto w-25">
        <a href="#" id="editarProdutoEstoque" class="rounded-3 px-3 pb-1 text-decoration-none text-black">Editar</a>
        <a href="#" id="excluirProdutoEstoque" class="rounded-3 px-3 pb-1 text-decoration-none text-black">Excluir</a>
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
