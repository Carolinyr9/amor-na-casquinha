<?php
require_once '../config/blockURLAccess.php';
session_start();
require_once '../config/config.php';
require_once '../controller/clienteController.php';
require_once '../controller/pedidoController.php';

$pedidoController = new pedidoController();
$clienteController = new clienteController();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="style/CabecalhoRodape.css">
    <link rel="stylesheet" href="style/sobreS.css">
    <link rel="shortcut icon" href="images/iceCreamIcon.ico" type="image/x-icon">
</head>
<body>
    <?php include_once 'components/header.php'; ?>
    <main>
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["btnSubmit"])) {
            $pedidoController->criarPedido($_SESSION["userEmail"], $_POST["ckbIsDelivery"] ? 0 : 1, 6);
        } else {
            echo '<div class="alert alert-warning" role="alert">Nenhum pedido foi criado. Preencha o formulário e tente novamente.</div>';
        }
        ?>
        
        <div class="conteiner1 conteiner d-flex align-items-center flex-column w-75 p-4 my-3">
            <div class="c1">
                <div class="d-flex justify-content-center m-2">
                    <img src="images/funcionario1.png" alt="">
                </div>
                <?php 
                $clienteController->getCliente($_SESSION["userEmail"]);
                ?>  
                <form action="" method="POST" id="formulario">
                    <label for="nome">Nome:</label>
                    <input type="text" id="nome" placeholder="Nome completo">
                    <label for="endereco">Endereço:</label>
                    <input type="text" id="endereco" placeholder="Endereço">
                    <button type="submit" name="btnSubmit">Salvar</button>
                </form>
            </div>
            <button id="edit">Editar</button>
        </div>

        <div class="conteiner1 conteiner d-flex align-items-center flex-column w-75 p-4 my-3">
            <h3>Meus pedidos</h3>
            <?php
            $pedidoController->listarPedidoPorCliente($_SESSION["userEmail"]);
            ?>
        </div>
    </main>
    <?php include_once 'components/footer.php'; ?>
    <script src="script/editar.js"></script>
</body>
</html>
