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
    <title>Editar Produto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="style/CabecalhoRodape.css">
    <link rel="stylesheet" href="style/editFuncS.css">
    <link rel="shortcut icon" href="images/iceCreamIcon.ico" type="image/x-icon">
</head>
<body>
    <?php
    require_once '../controller/produtoController.php';
    
    $produtoController = new ProdutoController();
    $produtoId = $_GET['produto'] ?? null;
    $produto = $produtoId ? $produtoController->obterProdutoPorID($produtoId) : null;
    include_once 'components/header.php';

    if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['produto']) && isset($_GET['marcaProdEdt'])) {
        $id = $_GET['produto'];
        $nomeProduto = $_GET['nomeProdEdt'] ?? '';
        $marca = $_GET['marcaProdEdt'] ?? '';
        $descricao = $_GET['descricaoProdEdt'] ?? '';
        $imagemProduto = $_GET['imagemProdEdt'] ?? '';
    
        $produtoController->editarProduto($id, $nomeProduto, $marca, $descricao, $imagemProduto);
        header("Location: editProd.php?produto=$produtoId");
    }
    ?>
    <main>
        <h1 class="m-auto text-center pt-4 pb-4">Editar Produto</h1>
        <div class="conteiner">
            <div class="conteiner1">
                <div class="c1">
                    <div class="c2">
                        <?php
                        if ($produto) {
                            echo '
                            <form action="' . htmlspecialchars($_SERVER["PHP_SELF"] . '?produto=' . $produto['idProduto']) . '" method="GET" id="formulario" class="formulario">
                                <input type="hidden" name="produto" value="' . htmlspecialchars($produto['idProduto']) . '">
                                <label for="nome2">Nome:</label>
                                <input type="text" id="nome2" name="nomeProdEdt" placeholder="Nome" value="' . htmlspecialchars($produto['nome']) . '">
                                <label for="marca2">Marca:</label>
                                <input type="text" id="marca2" name="marcaProdEdt" placeholder="Marca" value="' . htmlspecialchars($produto['marca']) . '">
                                <label for="descricao2">Descrição:</label>
                                <input type="text" id="descricao2" name="descricaoProdEdt" placeholder="Descrição" value="' . htmlspecialchars($produto['descricao']) . '">
                                <label for="foto2">Nome do arquivo de imagem:</label>
                                <input type="text" id="imagem2" name="imagemProdEdt" placeholder="imagem.png" value="' . htmlspecialchars($produto['foto']) . '">
                                <button type="submit">Salvar</button>
                            </form>';
                        } else {
                            echo '<p>Produto não encontrado.</p>';
                        }
                        ?>
                    </div>
                </div>
            </div>
            <button class="voltar"><a href="editarProdutos.php">Voltar</a></button>
        </div>
    </main>
    <?php include_once 'components/footer.php'; ?>
</body>
</html>
