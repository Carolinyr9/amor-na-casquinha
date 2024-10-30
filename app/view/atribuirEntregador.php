<?php
require_once '../config/blockURLAccess.php';
session_start();

require_once '../controller/entregadorController.php';
$entregadorController = new entregadorController();

require_once '../controller/pedidoController.php';
$pedidoController = new pedidoController();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8"><meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atribuir Entregador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="style/CabecalhoRodape.css">
    <link rel="stylesheet" href="style/editFuncS.css">
    <link rel="shortcut icon" href="images/iceCreamIcon.ico" type="image/x-icon">
</head>
<body>
    <?php
        include_once 'components/header.php';

        if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['idPedido']) && isset($_GET['idEntregador'])) {
            $idPedido = $_GET['idPedido'];
            $idEntregador = $_GET['idEntregador'];

            $pedidoController->atribuirEntregador($idPedido, $idEntregador);
        }
    ?>
    <main>
        <h1 class="m-auto text-center pt-4 pb-4">Atribuir Entregador</h1>
        <div class="conteiner">
            <div class="conteiner1">
                
                <div class="c1">
                    <div class="c2">
                        <?php
                        $entregadorController->listarEntregadores($_GET['idPedido']);
                        ?>
                    </div>
                </div>
            </div>
            <button class="voltar"><a href="pedidos.php">Voltar</a></button>
        </div>
    </main>
    <?php
        include_once 'components/footer.php';
    ?>
</body>
</html>