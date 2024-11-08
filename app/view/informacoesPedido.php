<?php
require_once '../config/blockURLAccess.php';
session_start();

require_once '../controller/EntregadorController.php';
require_once '../controller/PedidoController.php';

$entregadorController = new EntregadorController();
$pedidoController = new PedidoController();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informações do Pedido</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style/CabecalhoRodape.css">
    <link rel="stylesheet" href="style/infoPedidos.css">
    <link rel="shortcut icon" href="images/iceCreamIcon.ico" type="image/x-icon">
</head>
<body>
    <?php include_once 'components/header.php'; ?>
    <main class="container my-5 text-center flex flex-column justify-content-center">
        <h1 class="text-center mb-4">Informações do Pedido</h1>
        
        <div class="w-50 d-flex justify-content-center blue m-auto rounded-5 pt-3">
            <div class="card-body text-center">
                <?php
                $idPedido = $_GET['idPedido'] ?? null;

                if ($idPedido) {
                    $pedido = $pedidoController->listarPedidoPorId($idPedido);

                    if ($pedido) {
                        echo '<h3>Número do Pedido: ' . htmlspecialchars($pedido['idPedido']) . '</h3>';
                        echo '<p>Realizado em: ' . htmlspecialchars($pedido['dtPedido']) . '</p>';
                        echo '<p>Total: R$ ' . number_format($pedido['valorTotal'], 2, ',', '.') . '</p>';
                        echo '<p>' . ($pedido['tipoFrete'] == 1 ? 'É para entrega!' : 'É para buscar na sorveteria!') . '</p>';
                        echo '<p>Status: ' . htmlspecialchars($pedido['statusPedido']) . '</p>';
                        
                        if ($pedido['tipoFrete'] == 1) {
                            $entregador = $entregadorController->getEntregadorPorId($pedido['idEntregador']);
                            if ($entregador) {
                                echo '
                                    <div class="card categ d-flex align-items-center">
                                        <div class="d-flex align-items-center flex-column c2">
                                            <h3 class="titulo px-3">' . htmlspecialchars($entregador[0]["nome"] ?? '') . '</h3>
                                            <div class="px-3">
                                                <p>Email: ' . htmlspecialchars($entregador[0]["email"] ?? '') . '</p>
                                                <p>Celular: ' . htmlspecialchars($entregador[0]["telefone"] ?? '') . '</p>
                                                <p>CNH: ' . htmlspecialchars($entregador[0]["cnh"] ?? '') . '</p>
                                            </div>
                                            <button><a href="atribuirEntregador.php?idEntregador=' . htmlspecialchars($entregador[0]['idEntregador'] ?? '') . '&idPedido=' . htmlspecialchars($idPedido) . '">Atribuir entregador</a></button>
                                        </div>
                                    </div>';
                            } else {
                                echo '<p>Nenhum entregador atribuído para esse pedido.</p>';
                            }
                        }
                    } else {
                        echo '<div class="alert alert-danger">Pedido não encontrado.</div>';
                    }
                } else {
                    echo '<div class="alert alert-warning">ID do pedido não fornecido.</div>';
                }
                ?>
            </div>
        </div>
        
        <button class="btn-yellow rounded-4 mt-5"><a href="pedidos.php">Voltar</a></button>
    </main>
    <?php include_once 'components/footer.php'; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
