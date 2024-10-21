<?php
require_once '../config/blockURLAccess.php';
session_start();
require_once '../controller/carrinhoController.php';
$carrinhoController = new carrinhoController();
$carrinhoController->atualizarCarrinho();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Fiscal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="style/CabecalhoRodape.css">
    <link rel="stylesheet" href="style/notaFiscalS.css">
    <link rel="shortcut icon" href="images/iceCreamIcon.ico" type="image/x-icon">
</head>
<body>
    <?php
        include_once 'components/header.php';
    ?>
    <main>
        <div class="conteiner1 container d-flex flex-column align-items-center">
            <h3>Confirmar Pedido?</h3>
            <?php
                $carrinhoController->getTotal();
                if (isset($_SESSION["userEmail"]) && !empty($_SESSION["userEmail"])) {
                    echo '
                    <form action="sobre.php" method="post">
                        <input name="ckbIsDelivery" id="ckbIsDelivery" type="checkbox" checked=true>
                        <label for="ckbIsDelivery" id="labelForCkbIsDelivery">O pedido será entregue no seu endereço!</label>
                        <div id="addressDiv">'; 
                    echo   '</div>
                        <input type="hidden" name="notaFiscal" value="1">
                        <input name="btnSubmit" id="btnSubmit" type="submit" value="Concluir Pedido" class="btn">
                    </form>';
                }
                else{
                    echo '
                    <button id="btnGoToLogin" class="btn">Fazer Login para Concluir Pedido</button>
                    ';
                }
            ?>
        </div>
    </main>
    <?php
        include_once 'components/footer.php';
    ?>
    <script src="script/notaFiscal.js"></script>
</body>
</html>
