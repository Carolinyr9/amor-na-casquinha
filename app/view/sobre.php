<?php
session_start();
require_once '../config/blockURLAccess.php';
require_once '../config/config.php';
require_once '../controller/clienteController.php';
require_once '../controller/pedidoController.php';
require_once '../controller/carrinhoController.php';

$pedidoController = new PedidoController();
$clienteController = new ClienteController();
$carrinho = new Carrinho();

$clienteData = $clienteController->getClienteData($_SESSION["userEmail"]);
$pedidos = $pedidoController->listarPedidoPorCliente($_SESSION["userEmail"]);
$itensCarrinho = $carrinho->listarCarrinho();


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["btnSubmit"])) {
        
        $pedidoController->criarPedido($_SESSION["userEmail"], isset($_POST["ckbIsDelivery"]) ? 1 : 0, $_POST["totalComFrete"], isset($_POST["frete"]) ? $_POST["frete"] : NULL, isset($_POST["meioDePagamento"]) ? $_POST["meioDePagamento"] : NULL, $itensCarrinho);
        unset($_POST);
        
        $carrinho->limparCarrinho();
        header("Location: sobre.php");
        
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
        <div class="conteiner1 rounded-4 text-center d-flex align-items-center flex-column w-75 p-4 my-3">
            <div class="c1 w-100">
                <?php if (isset($clienteData["error"])): ?>
                    <div class="alert alert-danger">
                        <?php echo htmlspecialchars($clienteData["error"]); ?>
                    </div>
                <?php else: ?>
                    <div id="dados">
                        <p class="fs-5">Nome: <?= htmlspecialchars($clienteData['nome']); ?></p>
                        <p class="fs-5">Email: <?= htmlspecialchars($clienteData['email']); ?></p>
                        <p class="fs-5">Telefone: <?= htmlspecialchars($clienteData['telefone']); ?></p>
                        <p class="fs-5">Endereço: </p>
                        
                        <?php if (isset($clienteData['endereco']['rua'])): ?>
                            <div id="endereco">
                                <p class="fs-5">
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

                <form action="" class="w-100 formEditar" method="POST" id="formulario">
                    <p class="fs-5">Usuário</p>
                    <label for="nome">Nome:</label>
                    <input class="rounded-3 border-0 mr-4" type="text" id="nome" name="nome" value="<?= htmlspecialchars($clienteData['nome'] ?? ''); ?>" placeholder="Nome completo">
                    
                    <label for="telefone">Telefone:</label>
                    <input class="rounded-3 border-0 mr-4" type="text" id="telefone" name="telefone" value="<?= htmlspecialchars($clienteData['telefone'] ?? ''); ?>" placeholder="Telefone">
                    
                    <br>
                    <p class="fs-5">Endereço</p>
                    
                    <div class="container">
                        <div class="row my-2">
                            <div class="col">
                                <label for="rua">Rua:</label>
                                <input class="rounded-3 border-0 mr-4" type="text" id="rua" name="rua" value="<?= htmlspecialchars($clienteData['endereco']['rua'] ?? ''); ?>" placeholder="Rua">
                            </div>

                            <div class="col">
                                <label for="numero">Número:</label>
                                <input class="rounded-3 border-0 mr-4" type="number" id="numero" name="numero" value="<?= htmlspecialchars($clienteData['endereco']['numero'] ?? ''); ?>" placeholder="Número">
                            </div>
                        </div>
                        
                        <div class="row my-2">
                            <div class="col">
                                <label for="complemento">Complem.:</label>
                                <input class="rounded-3 border-0 mr-4" type="text" id="complemento" name="complemento" value="<?= htmlspecialchars($clienteData['endereco']['complemento'] ?? ''); ?>" placeholder="Complemento">
                            </div>

                            <div class="col">
                                <label for="cep">CEP:</label>
                                <input class="rounded-3 border-0 mr-4" type="text" id="cep" name="cep" value="<?= htmlspecialchars($clienteData['endereco']['cep'] ?? ''); ?>" placeholder="CEP">
                            </div>
                        </div>
                        
                        <div class="row my-2">
                            <div class="col">
                                <label for="bairro">Bairro:</label>
                                <input class="rounded-3 border-0 mr-4" type="text" id="bairro" name="bairro" value="<?= htmlspecialchars($clienteData['endereco']['bairro'] ?? ''); ?>" placeholder="Bairro">
                            </div>
                            
                            <div class="col">
                                <label for="cidade">Cidade:</label>
                                <input class="rounded-3 border-0 mr-4" type="text" id="cidade" name="cidade" value="<?= htmlspecialchars($clienteData['endereco']['cidade'] ?? ''); ?>" placeholder="Cidade">
                            </div>
                        </div>
                        
                        <label for="estado">Estado:</label>
                        <input class="rounded-3 border-0 mr-4" type="text" id="estado" name="estado" value="<?= htmlspecialchars($clienteData['endereco']['estado'] ?? ''); ?>" placeholder="Estado">
                    </div>

                    <input type="hidden" name="idEndereco" value="<?= htmlspecialchars($clienteData['endereco']['idEndereco'] ?? ''); ?>">

                    <button type="submit" name="btnAlterarCliente" class="rounded-4 border-0 fs-5 mt-4">Salvar</button>
                </form>
            </div>
            <button id="edit" class="btnEditar rounded-4 m-auto border-0 fs-5">Editar</button>
        </div>

        <br>
        <h3>Meus pedidos</h3>
        <?php if (empty($pedidos)): ?>
            <p>Nenhum pedido encontrado.</p>
        <?php else: ?>
            <?php foreach ($pedidos as $pedido): 
                $redirectToInformacao = 'informacoesPedidoCliente.php?idPedido=' . $pedido['idPedido'];?>
                <div class="conteiner-pedidos rounded-4 text-center d-flex align-items-center flex-column w-75 p-4 my-3">
                    <h5 class="titulo">Número do Pedido: <?= htmlspecialchars($pedido['idPedido']); ?></h5>
                    <p><strong>Data do Pedido:</strong> <?= htmlspecialchars($pedido['dtPedido']); ?></p>
                    <p><strong>Total:</strong> R$ <?= number_format($pedido['valorTotal'], 2, ',', '.'); ?></p>
                    <p><strong>Tipo de Frete:</strong> <?= ($pedido['tipoFrete'] == 1 ? 'É para entrega!' : 'É para buscar na sorveteria!'); ?></p>
                    <p><strong>Pagamento:</strong> <?= htmlspecialchars($pedido['meioPagamento']); ?></p>
                    <p><strong>Status:</strong> <?= htmlspecialchars($pedido['statusPedido']); ?></p>
                    
                    <button class="btnVerInfos mt-3"><a href="<?= $redirectToInformacao; ?>">Ver Informações</a></button>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </main>
    <?php include_once 'components/footer.php'; ?>
    <script src="script/editar.js"></script>
</body>
</html>