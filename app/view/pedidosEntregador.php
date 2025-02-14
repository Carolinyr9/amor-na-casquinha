<?php
session_start();
require_once '../config/blockURLAccess.php';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedidos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="style/CabecalhoRodape.css">
    <link rel="stylesheet" href="style/pedidosS.css">
</head>
<body>
    <?php
    include_once 'components/header.php';
    require_once '../controller/PedidoController.php';
    $pedidoController = new PedidoController();
    $usuario = $_SESSION['userPerfil'] ?? null;
    $pedidos = $pedidoController->listarPedidosEntregador($_SESSION['userEmail']);
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mudarStatus'], $_POST['idPedido'])) {
        $pedidoId = $_POST['idPedido'];
        $status = $_POST['mudarStatus'];
        $pedidoController->mudarStatusEntregador($pedidoId, $status);
        header("Location: pedidosEntregador.php");
        exit();
    }
    ?>
    
    <main class="container my-5 text-center flex flex-column justify-content-center">
        <h1 class="mb-4">Pedidos</h1>
        <?php if (!empty($pedidos)) : ?>
            <?php foreach ($pedidos as $pedido) : ?>
                <?php if ($pedido['statusPedido'] !== "Concluído" && $pedido['statusPedido'] !== "Cancelado") : ?>
                    <div class="conteiner0">
                        <div class="conteiner1">
                            <h3 class="titulo mt-3">Número do Pedido: <?= htmlspecialchars($pedido['idPedido']); ?></h3>
                            <p>Realizado em: <?= htmlspecialchars((new DateTime($pedido['dtPedido']))->format('d/m/Y \à\s H:i')); ?></p>
                            <p>Total: R$ <?= number_format($pedido['valorTotal'], 2, ',', '.'); ?></p>
                            <p><?= $pedido['tipoFrete'] == 1 ? 'É para entrega!' : 'É para buscar na sorveteria!'; ?></p>
                            <p>Status: <?= htmlspecialchars($pedido['statusPedido']); ?></p>
                            <button class="btnVerInfos mt-3"><a href="rotasEntregador.php?idEndereco=<?= $pedido['idEndereco']; ?>">Ver Rotas</a></button>
                            <?php if ($pedido['statusPedido'] === 'A Caminho') : ?>
                                <form method="POST" action="">
                                    <input type="hidden" name="idPedido" value="<?= htmlspecialchars($pedido['idPedido']); ?>">
                                    <input type="hidden" name="mudarStatus" value="Entrega Falhou">
                                    <button type="submit" class="btn btn-danger">Entrega Falhou</button>
                                </form>
                                <form method="POST" action="" class="mt-2">
                                    <input type="hidden" name="idPedido" value="<?= htmlspecialchars($pedido['idPedido']); ?>">
                                    <input type="hidden" name="mudarStatus" value="Entregue">
                                    <button type="submit" class="btn btn-success">Entregue</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php else : ?>
            <div class="alert alert-warning">Nenhum pedido encontrado.</div>
        <?php endif; ?>
    </main>
    <?php include_once 'components/footer.php'; ?>
</body>
</html>
