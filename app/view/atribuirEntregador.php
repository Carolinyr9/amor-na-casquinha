<?php
require_once '../config/blockURLAccess.php';
session_start();
require_once '../controller/EntregadorController.php';

require_once '../controller/PedidoController.php';
$pedidoController = new PedidoController();
$entregadorController = new EntregadorController();

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atribuir Entregador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
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
    }?>

    <main>
        <h1 class="m-auto text-center pt-4 pb-4">Atribuir Entregador</h1>
        <div class="container">
            <div class="container1">
                <div class="c1">
                    <div class="c2">
                        <?php
                        $idPedido = $_GET['idPedido'] ?? null;
                        $entregadores = $entregadorController->getEntregadores();

                        if (!empty($entregadores)) {
                            foreach ($entregadores as $row) {
                                $redirectAtribuirEntregador = 'atribuirEntregador.php?idEntregador=' . $row['idEntregador'] . '&idPedido=' . $idPedido;
                                echo '
                                    <div class="card categ d-flex align-items-center">
                                        <div class="d-flex align-items-center flex-column c2">
                                            <h3 class="titulo px-3">' . htmlspecialchars($row["nome"]) . '</h3>
                                            <div class="px-3">
                                                <p>Email: ' . htmlspecialchars($row["email"]) . '</p>
                                                <p>Celular: ' . htmlspecialchars($row["telefone"]) . '</p>
                                                <p>CNH: ' . htmlspecialchars($row["cnh"]) . '</p>
                                            </div>
                                            <button><a href="' . htmlspecialchars($redirectAtribuirEntregador) . '">Atribuir entregador</a></button>
                                        </div>
                                    </div>';
                            }
                        } else {
                            echo '<p>Nenhum entregador encontrado.</p>'; 
                        }
                        ?>
                    </div>
                </div>
            </div>
            <button class="voltar"><a href="pedidos.php">Voltar</a></button>
        </div>
    </main>

    <?php include_once 'components/footer.php'; ?>
</body>
</html>