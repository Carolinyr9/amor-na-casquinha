<?php
require_once '../config/blockURLAccess.php';
session_start();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Sabor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="style/CabecalhoRodape.css">
    <link rel="stylesheet" href="style/editaSaborS.css">
    <link rel="shortcut icon" href="images/iceCreamIcon.ico" type="image/x-icon">
</head>
<body>
<?php
require_once '../controller/produtoVariacaoController.php';
$produtoVariacaoController = new produtoVariacaoController();
include_once 'components/header.php';

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['btnEditar'])) {
    $idProduto = $_GET['idProduto'];
    $idVariacao = $_GET['idVariacao'];
    $nomeProduto = isset($_GET['nomeSabEdt']) ? $_GET['nomeSabEdt'] : '';
    $preco = isset($_GET['precoSabEdt']) ? $_GET['precoSabEdt'] : '';
    $imagemProduto = isset($_GET['imagemSabEdt']) ? $_GET['imagemSabEdt'] : '';
    
    echo $idVariacao, $idProduto, $nomeProduto, $preco, $imagemProduto;
    $produtoVariacaoController->editarProduto($idVariacao, $idProduto, $nomeProduto, $preco, $imagemProduto);
}
?>
<main>
    <div class="conteiner2">
        <h2>Editar Sabor</h2>
        <?php
        $produtoVariacaoController->selecionarProdutosPorID($_GET['idVariacao']);
        ?>
        <button class="voltar"><a href="editarProdutos.php">Voltar</a></button>
    </div>
</main>
<?php
include_once 'components/footer.php';
?>
<script src="script/header.js"></script>
<script src="script/adicionar.js"></script>
</body>
</html>
