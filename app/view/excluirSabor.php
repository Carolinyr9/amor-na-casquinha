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
    <title>Excluir Sabor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="style/CabecalhoRodape.css">
    <link rel="stylesheet" href="style/excluirSaborS.css">
    <link rel="shortcut icon" href="images/iceCreamIcon.ico" type="image/x-icon">
</head>
<body>
    
    <?php
        include_once 'components/header.php';
        require_once '../controller/produtoVariacaoController.php';
        $produtoVariacaoController = new produtoVariacaoController();

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['idProdutoExcl'])) {
            $idProduto = $_POST['idProdutoExcl'];
            $produtoVariacaoController->removerProduto($idProduto);
        }
    ?>

    <main>
        <h1>Desativar Sabor</h1>
    
        <div class="conteiner">
            <h3>Tem certeza que deseja desativar esse Sabor?</h3>
            
            <div class="conteiner1">   
            <div class="c1">
                    <?php
                    if (isset($_GET['idVariacao'])) {
                        $idProduto = $_GET['idVariacao'];
                        $produtoVariacaoController->selecionarProdutosPorID($idProduto);
                        echo '
                        <form action="" method="POST" id="formulario" class="formulario">
                            <input type="hidden" name="idProdutoExcl" value="' . htmlspecialchars($idProduto) . '">
                            <button type="submit" class="btn btn-danger">Excluir</button>
                        </form>';
                    } else {
                        echo '<p>Produto não encontrado.</p>';
                    }
                    ?>
                </div>
            <button class="voltar"><a href="editarProdutos.php">Voltar</a></button>
        </div>
    </main>
    
    <?php
        include_once 'components/footer.php';
    ?>
    
    <script src="script/header.js"></script>
</body>
</html>