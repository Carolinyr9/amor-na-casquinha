<?php
require_once '../config/blockURLAccess.php';
session_start();
require_once '../controller/produtoController.php';

$produtoController = new produtoController();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Produtos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="style/CabecalhoRodape.css">
    <link rel="stylesheet" href="style/editarProdutosS.css">
    <link rel="shortcut icon" href="images/iceCreamIcon.ico" type="image/x-icon">
</head>
<body>
    <?php include_once 'components/header.php'; 
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $nomeProduto = isset($_POST['nomeProAdd']) ? $_POST['nomeProAdd'] : '';
        $marca = isset($_POST['marcaProAdd']) ? $_POST['marcaProAdd'] : '';
        $descricao = isset($_POST['descricaoProAdd']) ? $_POST['descricaoProAdd'] : '';
        $idFornecedor = isset($_POST['fornecedor']) ? $_POST['fornecedor'] : '';
        $imagemProduto = isset($_POST['imagemProAdd']) ? $_POST['imagemProAdd'] : '';

        $produtoController->adicionarProduto($nomeProduto, $marca, $descricao, $idFornecedor, $imagemProduto);
    } ?>
    
    <main>
        <h1>Produtos</h1>
        <div class="d-flex flex-column align-items-center justify-content-center">
            <button class="add">Adicionar Produto</button>
            <div>
                <form action="" method="POST" id="addFormulario">
                    <label for="nome1">Nome:</label>
                    <input type="text" id="nomeProduto" name="nomeProAdd" placeholder="Nome do produto">
                    <label for="marca">Marca:</label>
                    <input type="text" id="marcaProAdd" name="marcaProAdd" placeholder="Marca do produto">
                    <label for="descricaoProduto">Descrição:</label>
                    <input type="text" id="descricaoProduto" name="descricaoProAdd" placeholder="Descrição do produto">
                    <label for="fornecedor">ID do fornecedor:</label>
                    <input type="text" id="fornecedor" name="fornecedor" placeholder="1234">
                    <label for="nomeImagem">Nome do arquivo de imagem:</label>
                    <input type="text" id="imagemProduto" name="imagemProAdd" placeholder="imagem.png">
                    <button type="submit">Salvar</button>
                </form>
            </div>
            <div class="conteiner1">
                <?php $produtoController->selecionarProdutosFunc(); ?>
            </div>
        </div>
    </main>
    <?php include_once 'components/footer.php'; ?>
    <script src="script/adicionar.js"></script>
    <script src="script/editar.js"></script>
</body>
</html>
