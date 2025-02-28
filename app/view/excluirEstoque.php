<?php
session_start();
require_once '../config/blockURLAccess.php';
require_once '../controller/estoqueController.php';
require_once '../controller/produtoVariacaoController.php';
require_once '../controller/produtoController.php';

$estoque = new EstoqueController();
$variacao = new ProdutoVariacaoController();
$produto = new ProdutoController();
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

if((isset($_POST) || !empty($_POST)) && isset($_POST["excluirSubmit"])){
    foreach ($dados as $produto) {
        $estoque->excluirProdutoEstoque($produto['idEstoque'], $produto['idVariacao']);
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
    <link rel="stylesheet" href="style/excluirEstoque-style.css">
    <title>Edição de Estoque</title>
</head>
<body>
<?php include_once 'components/header.php'; ?>

<main class="d-flex flex-column justify-content-center align-items-center">
        <h1 class="m-auto text-center pt-4 pb-4">Excluir produto do estoque</h1>
            <p class="text-center text-wrap w-50">Tem certeza que deseja excluir esse produto? Ele também será excluido de seu produtos em visualização na compra dos cliente</p>
                <div class="dashboard w-25 mx-auto my-4 p-4 rounded-4 d-flex align-items-center flex-column justify-content-center">
                    <?php if ($dados): ?>
                        <form action="" method="POST" id="formulario" class="d-flex justify-content-center flex-column">
                        <?php foreach($dados as $row){
                            $dadosVariacao = $variacao->selecionarProdutoPorID(intval($row['idVariacao']));
                            if ($dadosVariacao) {
                                $dadosProdutos = $produto->selecionarProdutoPorID(intval($dadosVariacao['idProduto']));
                            } else {
                                $dadosProdutos = false;
                            } ?>
                            <div class="d-flex align-items-center flex-column">
                                <p>Produto: <?=  $dadosProdutos ? $dadosProdutos['nome'] : 'Produto não encontrado'?></p>
                                <p>Variação: <?=  $dadosVariacao ? $dadosVariacao['nomeVariacao'] : 'Variação não encontrada'?></p>
                                <p>Lote: <?=  $row['lote']?></p>
                            </div>
                        <?php } ?>
                            <input type="submit" name="excluirSubmit" class="input-excluir border-0 rounded-4 px-3 fw-bold mx-auto" value="Excluir"/>
                        </form>
                    <?php else: ?>
                        <p>Produto não encontrado.</p>
                    <?php endif; ?>
                </div>
            <button class="b-voltar m-auto border-0 rounded-4 fw-bold px-3"><a class="text-decoration-none color-black" href="editarProdutos.php">Voltar</a></button>
    </main>

<?php include_once 'components/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script
  src="https://code.jquery.com/jquery-3.7.1.js"
  integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
  crossorigin="anonymous"></script>
</body>
</html>