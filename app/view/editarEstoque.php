<?php
session_start();
require_once '../config/blockURLAccess.php';
require_once '../controller/estoqueController.php';
require_once '../controller/produtoVariacaoController.php';

$estoque = new EstoqueController();
$variacao = new ProdutoVariacaoController();
$produtos = [];
$vari = 0;

if (isset($_GET['idsArray']) && !empty($_GET['idsArray'])) {
    $_SESSION['idsArray'] = explode(',', $_GET['idsArray']);
    
    foreach ($_SESSION['idsArray'] as $id) {
        $dados = $estoque->selecionarProdutoEstoquePorID($id);
        if ($dados) { 
            $produtos[] = $dados;
        }
    }
} else {
    echo "Nenhum produto foi selecionado.";
}

if((isset($_POST) || !empty($_POST)) && isset($_POST["editarsubmit"])){
    foreach ($_POST['produtos'] as $produto) {
        $estoque->editarProdutoEstoque($produto);
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style/CabecalhoRodape.css">
    <link rel="stylesheet" href="style/editarEstoque-style.css">
    <title>Edição de Estoque</title>
</head>
<body>
<?php include_once 'components/header.php'; ?>

<main>
    <h2 class="text-center mb-3">Edição</h2>
    <form action="editarEstoque.php" method="post" class="formulario w-50 m-auto p-3 rounded-4 d-flex flex-row flex-wrap gap-4 justify-content-center">
        <?php foreach ($produtos as $produto) {  
            $vari = $variacao->selecionarProdutoPorID($produto[0]["idVariacao"]); ?>
        <h3 class="w-100 text-center mt-4"><?= $vari['nomeVariacao'] ?></h3> 
        <input type="hidden" name="produtos[<?= $produto[0]["idEstoque"]?>][idEstoque]" value="<?= $produto[0]["idEstoque"]?>">
        <div class="form-group w-25">
            <label for="lote_">Lote</label>
            <input type="text" class="form-control" id="lote_<?= $produto[0]["idEstoque"]?>" name="produtos[<?= $produto[0]["idEstoque"]?>][lote]" value="<?= $produto[0]["lote"] ?>" required>
        </div>
        <div class="form-group w-25">
            <label for="valor_">Valor</label>
            <input type="text" class="form-control" id="valor_<?= $produto[0]["idEstoque"]?>" name="produtos[<?= $produto[0]["idEstoque"]?>][valor]" value="<?= $produto[0]["precoCompra"] ?>" required>
        </div>
        <div class="form-group w-25">
            <label for="quantidade_">Quantidade</label>
            <input type="number" class="form-control" id="quantidade_<?= $produto[0]["idEstoque"]?>" name="produtos[<?= $produto[0]["idEstoque"]?>][quantidade]" value="<?= $produto[0]["quantidade"] ?>" required>
        </div>
        <div class="form-group w-25">
            <label for="dataFabricacao_">Entrada</label>
            <input type="date" class="form-control" id="dataEntrada_<?= $produto[0]["idEstoque"]?>" name="produtos[<?= $produto[0]["idEstoque"]?>][dataEntrada]" value="<?= $produto[0]["dtEntrada"] ?>" required>
        </div>
        <div class="form-group w-25">
            <label for="dataFabricacao_">Fabricação</label>
            <input type="date" class="form-control" id="dataFabricacao_<?= $produto[0]["idEstoque"]?>" name="produtos[<?= $produto[0]["idEstoque"]?>][dataFabricacao]" value="<?= $produto[0]["dtFabricacao"] ?>" required>
        </div>
        <div class="form-group w-25">
            <label for="dataVencimento_">Vencimento</label>
            <input type="date" class="form-control" id="dataVencimento_<?= $produto[0]["idEstoque"]?>" name="produtos[<?= $produto[0]["idEstoque"]?>][dataVencimento]" value="<?= $produto[0]["dtVencimento"] ?>" required>
        </div>
        <div class="form-group w-25">
            <label for="quantidadeMinima_">Quantidade Mínima</label>
            <input type="text" class="form-control" id="quantidadeMinima_<?= $produto[0]["idEstoque"]?>" name="produtos[<?= $produto[0]["idEstoque"]?>][quantidadeMinima]" value="<?= $produto[0]["qtdMinima"] ?>" required>
        </div>
        <div class="form-group w-25">
            <label for="quantidadeOcorrencia_">Quantidade ocorrida</label>
            <input type="number" class="form-control" id="quantidadeOcorrencia_<?= $produto[0]["idEstoque"]?>" name="produtos[<?= $produto[0]["idEstoque"]?>][quantidadeOcorrencia]" value="<?= $produto[0]["qtdOcorrencia"] ?>">
        </div>
        <div class="form-group w-100">
            <label for="ocorrencia_">Ocorrência</label>
            <textarea class="form-control" name="produtos[<?= $produto[0]["idEstoque"]?>][ocorrencia]" id="ocorrencia_<?= $produto[0]["idEstoque"]?>" value="<?= $produto[0]["ocorrencia"] ?>"></textarea>
        </div>
        <?php }?>
        <input type="submit" value="Editar" name="editarsubmit" class="editarProduto rounded-3 px-4 text-decoration-none border-0">
    </form>
</main>

<?php include_once 'components/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script
  src="https://code.jquery.com/jquery-3.7.1.js"
  integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
  crossorigin="anonymous"></script>
</body>
</html>