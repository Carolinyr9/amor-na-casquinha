<?php
session_start();

require_once '../config/blockURLAccess.php';
require_once '../../vendor/autoload.php';
require_once '../utils/entregador/atribuirEntregador.php';

use app\controller\EntregadorController;
use app\controller\PedidoController;

$pedidoController = new PedidoController();
$entregadorController = new EntregadorController();
$idPedido = $_GET['idPedido'] ?? null;
$entregadores = $entregadorController->listarEntregadores();
?>

<!DOCTYPE html>
<html lang="pt-br"> 
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atribuir Entregador</title>
    <?php include_once '../utils/links/styleLinks.php'; ?>
    <link rel="stylesheet" href="style/atribuirEntregador.css">
</head>
<body>
    <?php include_once 'components/header.php'; ?>

    <main>
        <h1 class="m-auto text-center pt-4 pb-4">Atribuir Entregador</h1>
        <div class="container d-flex flex-column justify-content-center align-items-center">

            <div class="box-entregador d-flex flex-column justify-content-center align-items-center gap-4 py-4 w-50 rounded-4">

                <?php if (!empty($entregadores)): ?>
                    <?php foreach ($entregadores as $entregador): 
                        $link = 'atribuirEntregador.php?idEntregador=' . $entregador->getId() . '&idPedido=' . $idPedido;
                    ?>
                        <div class="border-0 box-card w-75 d-flex justify-content-center align-items-center">
                            <div class="d-flex align-items-center flex-column">
                                <h3 class="titulo px-3"><?= htmlspecialchars($entregador->getNome()) ?></h3>
                                <div class="px-3">
                                    <p>Email: <?= htmlspecialchars($entregador->getEmail()) ?></p>
                                    <p>Celular: <?= htmlspecialchars($entregador->getTelefone()) ?></p>
                                    <p>CNH: <?= htmlspecialchars($entregador->getCnh()) ?></p>
                                </div>
                                <a href="<?= htmlspecialchars($link) ?>" class="btn-addEntregador px-3 text-decoration-none text-dark">
                                    Atribuir entregador
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Nenhum entregador encontrado.</p>
                <?php endif; ?>

            </div>

            <a href="pedidos.php" class="voltar mt-3 fs-5 fw-bold rounded-4 border-0 text-dark text-decoration-none">
                Voltar
            </a>

        </div>
    </main>

    <?php include_once 'components/footer.php'; ?>
</body>
</html>
``
