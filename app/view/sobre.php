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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["btnSubmit"])) {
        $total = $carrinho->getTotal();
        if ($total > 0) {
            $pedidoController->criarPedido($_SESSION["userEmail"], isset($_POST["ckbIsDelivery"]) ? 1 : 0, $total);
            unset($_POST);
            $carrinho->limparCarrinho();
            header("Location: sobre.php");
        }
    }

    if (isset($_POST['mudarStatus'])) {
        $pedidoId = $_GET['idPedido'] ?? null;
        $usuario = $_SESSION['perfil'] ?? null;
        $pedidoController->mudarStatus($pedidoId, $usuario);
        header("Location: sobre.php");
        exit();
    }

    if (isset($_POST["btnAlterarCliente"])) {
        $clienteController->editarCliente(
            $_SESSION["userEmail"],
            $_POST["idEndereco"],
            $_POST["nome"],
            $_POST["telefone"],
            $_POST["rua"],
            $_POST["cep"],
            $_POST["numero"],
            $_POST["bairro"],
            $_POST["cidade"],
            $_POST["estado"],
            $_POST["complemento"]
            
        );
        header("Location: sobre.php");
        exit();
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
                        <?php echo htmlspecialchars($clienteData["error"]); ?>
                    </div>
                <?php else: ?>
                    <div id="dados">
                        <p>Nome: <?= htmlspecialchars($clienteData['nome']); ?></p>
                        <p>Email: <?= htmlspecialchars($clienteData['email']); ?></p>
                        <p>Telefone: <?= htmlspecialchars($clienteData['telefone']); ?></p>
                        <p>Endereço: </p>
                        
                        <?php
                        if (isset($clienteData['endereco']['rua'])): ?>
                            <div id="endereco">
                                <p>
                                    <?= htmlspecialchars($clienteData['endereco']['rua']) . ', ' . 
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
                    <p>Usuário</p>
                    <label for="nome">Nome:</label>
                    <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($clienteData['nome'] ?? ''); ?>" placeholder="Nome completo">
                    
                    <label for="telefone">Telefone:</label>
                    <input type="text" id="telefone" name="telefone" value="<?= htmlspecialchars($clienteData['telefone'] ?? ''); ?>" placeholder="Telefone">
                    
                    <br>
                    <p>Endereço</p>
                    
                    <label for="cep">CEP:</label>
                    <input type="text" id="cep" name="cep" value="<?= htmlspecialchars($clienteData['endereco']['cep'] ?? ''); ?>" placeholder="CEP">
                    
                    <label for="rua">Rua:</label>
                    <input type="text" id="rua" name="rua" value="<?= htmlspecialchars($clienteData['endereco']['rua'] ?? ''); ?>" placeholder="Rua">
                    
                    <label for="numero">Número:</label>
                    <input type="number" id="numero" name="numero" value="<?= htmlspecialchars($clienteData['endereco']['numero'] ?? ''); ?>" placeholder="Número">
                    
                    <label for="complemento">Complemento:</label>
                    <input type="text" id="complemento" name="complemento" value="<?= htmlspecialchars($clienteData['endereco']['complemento'] ?? ''); ?>" placeholder="Complemento">
                    
                    <label for="bairro">Bairro:</label>
                    <input type="text" id="bairro" name="bairro" value="<?= htmlspecialchars($clienteData['endereco']['bairro'] ?? ''); ?>" placeholder="Bairro">
                    
                    <label for="cidade">Cidade:</label>
                    <input type="text" id="cidade" name="cidade" value="<?= htmlspecialchars($clienteData['endereco']['cidade'] ?? ''); ?>" placeholder="Cidade">
                    
                    <label for="estado">Estado:</label>
                    <input type="text" id="estado" name="estado" value="<?= htmlspecialchars($clienteData['endereco']['estado'] ?? ''); ?>" placeholder="Estado">

                    <input type="hidden" name="idEndereco" value="<?= htmlspecialchars($clienteData['endereco']['idEndereco'] ?? ''); ?>">

                    <button type="submit" name="btnAlterarCliente">Salvar</button>
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
                    
                    <?php 
                    $statusPermitidos = ['Aguardando Pagamento', 'Aguardando Envio'];
                    
                    if ($pedido['statusPedido'] == 'A Caminho'): ?>
                        <form method="POST" action="">
                            <input type="hidden" name="mudarStatus" value="1">
                            <button type="submit" class="btn btn-primary">Mudar para: Entregue</button>
                        </form>
                    <?php endif; ?>
                    
                    <?php 
                    if (in_array($pedido['statusPedido'], $statusPermitidos)): ?>
                        <form method="POST" action="">
                            <input type="hidden" name="mudarStatus" value="1">
                            <button type="submit" class="btn btn-primary">Cancelar Pedido</button>
                        </form>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </main>
    <?php include_once 'components/footer.php'; ?>
    <script src="script/editar.js"></script>
</body>
</html>
