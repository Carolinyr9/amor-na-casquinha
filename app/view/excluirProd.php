<?php
require_once '../config/blockURLAccess.php';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir Produto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="style/CabecalhoRodape.css">
    <link rel="stylesheet" href="style/excluirProdS.css">
    <link rel="shortcut icon" href="images/iceCreamIcon.ico" type="image/x-icon">
</head>
<body>
    <?php
    include_once 'components/header.php';
    require_once '../controller/produtoController.php';
    $produtoController = new produtoController();

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['idProdutoExcl'])) {
        $idProduto = $_POST['idProdutoExcl'];
        $produtoController->removerProduto($idProduto);
    }
    ?>
    <main>
        <h1 class="m-auto text-center pt-4 pb-4">Excluir Produto</h1>
        <div class="container">
            <h3>Tem certeza que deseja excluir esse produto?</h3>
            <div class="container1">   
                <div class="c1">
                    <?php
                    if (isset($_GET['produto'])) {
                        $idProduto = $_GET['produto'];
                        $produtoController->selecionarProdutosPorID($idProduto);
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
            </div>
            <button class="voltar"><a href="editarProdutos.php">Voltar</a></button>
        </div>
    </main>
    <?php
    include_once 'components/footer.php';
    ?>
</body>
</html>
