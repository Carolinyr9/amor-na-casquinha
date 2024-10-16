<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="style/CabecalhoRodape.css">
    <link rel="stylesheet" href="style/carrinhoS.css">
    <link rel="shortcut icon" href="images/iceCreamIcon.ico" type="image/x-icon">
</head>
<body>
    <?php
        include_once 'components/header.php';
        require_once '../controller/carrinhoController.php';
        $carrinhoController = new carrinhoController();
    ?>
    <main>
        <h1 class="m-auto text-center pt-4 pb-4">Carrinho</h1>
        <div class="conteiner d-flex flex-column align-items-center">
            <form method="post" action="notaFiscal.php" class="container-fluid d-flex flex-column align-items-center conteiner1">
                <input type="hidden" name="cart" value="1">
                <?php
                    if (isset($_GET["add"])) {
                        $carrinhoController->addProduto($_GET["add"]);
                    }
                    if (isset($_GET["action"]) && $_GET["action"] === 'remove' && isset($_GET["item"])) {
                        $carrinhoController->removeProduto($_GET["item"]);
                    }
                    $carrinhoController->listarCarrinho();
                ?>
            </form>
            <button class="voltar"><a href="index.php">Voltar</a></button>
        </div>
    </main>
    <?php
        include_once 'components/footer.php';
    ?>
</body>
</html>