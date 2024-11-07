<?php
require_once '../config/blockURLAccess.php';
session_start();
require_once '../config/config.php';
require_once '../controller/clienteController.php';
require_once '../controller/pedidoController.php';
require_once '../controller/carrinhoController.php';

$pedidoController = new PedidoController();
$clienteController = new ClienteController();
$carrinho = new Carrinho();

$clienteData = $clienteController->getClienteData($_SESSION["userEmail"]);
$pedidos = $pedidoController->listarPedidoPorCliente($_SESSION["userEmail"]);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["btnSubmit"])) {
    $total = $carrinho->getTotal(); 
    if ($total > 0) {
        $pedidoController->criarPedido($_SESSION["userEmail"], $_POST["ckbIsDelivery"] ? 0 : 1, $total);
        unset($_POST);
        $carrinho->limparCarrinho(); 
        header("Location: sobre.php");
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="style/CabecalhoRodape.css">
    <link rel="stylesheet" href="style/sobreS.css">
    <link rel="shortcut icon" href="images/iceCreamIcon.ico" type="image/x-icon">
</head>
<body>
    <?php include_once 'components/header.php'; ?>
    <main>
        <div class="conteiner1 conteiner d-flex align-items-center flex-column w-75 p-4 my-3">
            <div class="c1">
                
                <?php if (isset($clienteData["error"])): ?>
                    <div class="alert alert-danger">
                        <?php echo $clienteData["error"]; ?>
                    </div>
                <?php else: ?>
                    <div id="dados">
                        
                        <p>Nome: <?= htmlspecialchars($clienteData['nome']); ?></p>
                        <p>Email: <?= htmlspecialchars($clienteData['email']); ?></p>
                        <p>Telefone: <?= htmlspecialchars($clienteData['telefone']); ?></p>
                        <p>Endereço: </p> 
                        <?php if (isset($clienteData['endereco']['rua'])): ?>
                            <div id="endereco">
                                <p>
                                    <?= 
                                    htmlspecialchars($clienteData['endereco']['rua']) . ', ' . 
                                    htmlspecialchars($clienteData['endereco']['numero']) . ', ' . 
                                    (isset($clienteData['endereco']['complemento']) ? htmlspecialchars($clienteData['endereco']['complemento']) . ', ' : '') . 
                                    htmlspecialchars($clienteData['endereco']['bairro']) . ', ' . 
                                    htmlspecialchars($clienteData['endereco']['cidade']) . ', ' . 
                                    htmlspecialchars($clienteData['endereco']['estado']) . ', ' . 
                                    htmlspecialchars($clienteData['endereco']['cep']); ?>
                                </p>
                            </div>
                        <?php else: ?>
                            <p>Endereço não encontrado!</p>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <form action="" method="POST" id="formulario">
                    <label for="nome">Nome:</label>
                    <input type="text" id="nome" placeholder="Nome completo">
                    <label for="endereco">Endereço:</label>
                    <input type="text" id="endereco" placeholder="Endereço">
                    <button type="submit" name="btnAlterar">Salvar</button>
                </form>
            </div>
            <button id="edit">Editar</button>
        </div>
        <br>
        <h3>Meus pedidos</h3>
        <?php if (empty($pedidos)): ?>
            <p>Nenhum pedido encontrado.</p>
        <?php else: ?>
            <?php foreach ($pedidos as $pedido): ?>
                <div class="conteiner1 conteiner d-flex align-items-center flex-column w-75 p-4 my-3">
                    <h5 class="titulo">Número do Pedido: <?= htmlspecialchars($pedido['idPedido']); ?></h5>
                    <p><strong>Data do Pedido:</strong> <?= htmlspecialchars($pedido['dtPedido']); ?></p>
                    <p><strong>Total:</strong> R$ <?= number_format($pedido['valorTotal'], 2, ',', '.'); ?></p>
                    <p><strong>Tipo de Frete:</strong> <?= ($pedido['tipoFrete'] == 1 ? 'É para entrega!' : 'É para buscar na sorveteria!'); ?></p>
                    <p><strong>Status:</strong> <?= htmlspecialchars($pedido['statusPedido']); ?></p>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </main>
    <?php include_once 'components/footer.php'; ?>
    <script src="script/editar.js"></script>
</body>
</html>
