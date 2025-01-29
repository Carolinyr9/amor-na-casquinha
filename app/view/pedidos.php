<?php
session_start();
require_once '../config/blockURLAccess.php';
require_once '../controller/pedidoController.php';

$pedidoController = new PedidoController();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedidos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style/CabecalhoRodape.css">
    <link rel="stylesheet" href="style/pedidosS.css">
</head>
<body>
    <?php include_once 'components/header.php'; ?>
    
    <main class="container my-5 text-center d-flex flex-column justify-content-center">
        <h1 class="mb-4">Pedidos</h1>

        <div class="d-flex flex-column align-items-center justify-content-center">
            <button class="add border-0 rounded-4 my-3 fw-bold fs-5 px-3">Adicionar Pedido</button>
            
            <div>
                <form action="" method="POST" id="addFormulario">
                    <label for="dataPedido">Data do Pedido:</label>
                    <input type="date" id="dataPedido" name="dataPedido" placeholder="Data do pedido" required>

                    <label for="idCliente">Id do Cliente:</label>
                    <input type="number" id="idCliente" name="idCliente" placeholder="Se não possuir, preencher com 0" required>

                    <label for="idEnderecoCliente">Id do Endereço do Cliente:</label>
                    <input type="number" id="idEnderecoCliente" name="idEnderecoCliente" placeholder="Se não possuir, preencher com 0">

                    <label for="produtosPedidos">Produtos Pedidos:</label>
                    <input type="text" id="produtosPedidos" name="produtosPedidos" placeholder="Ex.: 1;2;3;4;5" required>

                    <label for="quantidadeProdutosPedidos">Quantidade dos Produtos Pedidos:</label>
                    <input type="text" id="quantidadeProdutosPedidos" name="quantidadeProdutosPedidos" placeholder="Ex.: 1;2;3;4;5" required>

                    <label for="tipoFrete">Tipo de Frete:</label>
                    <div>
                        <input type="radio" id="freteLoja" name="tipoFrete" value="Retirada" required>
                        <label for="freteLoja">Retirada na loja</label>
                    </div>
                    <div>
                        <input type="radio" id="freteMotoboy" name="tipoFrete" value="Motoboy">
                        <label for="freteMotoboy">Entrega em Domicilio</label>
                    </div>

                    <label for="valorFrete">Valor do Frete:</label>
                    <input type="text" id="valorFrete" name="valorFrete" placeholder="Ex.: 15,00">

                    <label for="statusPedido">Status do Pedido:</label>
                    <div>
                        <input type="radio" id="statusAguardando" name="statusPedido" value="Aguardando Confirmação" required>
                        <label for="statusAguardando">Aguardando Confirmação</label>
                    </div>
                    <div>
                        <input type="radio" id="statusEnvio" name="statusPedido" value="Aguardando Envio">
                        <label for="statusEnvio">Aguardando Envio</label>
                    </div>
                    <div>
                        <input type="radio" id="statusCaminho" name="statusPedido" value="A Caminho">
                        <label for="statusCaminho">A Caminho</label>
                    </div>
                    <div>
                        <input type="radio" id="statusEntregue" name="statusPedido" value="Entregue">
                        <label for="statusEntregue">Entregue</label>
                    </div>
                    <div>
                        <input type="radio" id="statusCancelado" name="statusPedido" value="Cancelado">
                        <label for="statusCancelado">Cancelado</label>
                    </div>

                    <label for="meioPagamento">Meio de Pagamento:</label>
                    <div>
                        <input type="radio" id="pagamentoDebito" name="meioPagamento" value="Cartão de Débito" required>
                        <label for="pagamentoDebito">Cartão de Débito</label>
                    </div>
                    <div>
                        <input type="radio" id="pagamentoCredito" name="meioPagamento" value="Cartão de Crédito">
                        <label for="pagamentoCredito">Cartão de Crédito</label>
                    </div>
                    <div>
                        <input type="radio" id="pagamentoDinheiro" name="meioPagamento" value="Dinheiro">
                        <label for="pagamentoDinheiro">Dinheiro</label>
                    </div>

                    <label for="valorTotal">Valor Total:</label>
                    <input type="text" id="valorTotal" name="valorTotal" placeholder="Ex.: 150,00" required>

                    <button type="submit">Salvar</button>
                </form>
            </div>
        </div>

        <?php
        $pedidos = $pedidoController->listarPedidos();
        
        if (!empty($pedidos)) {
            foreach ($pedidos as $pedido) {
                $redirectAtribuirEntregador = 'atribuirEntregador.php?idPedido=' . $pedido['idPedido'];
                $redirectToInformacao = 'informacoesPedido.php?idPedido=' . $pedido['idPedido'];
                ?>
                <div class="conteiner0">
                    <div class="conteiner1">
                        <h3 class="titulo mt-3">Número do Pedido: <?= htmlspecialchars($pedido['idPedido']); ?></h3>
                        <p>Realizado em: <?= htmlspecialchars((new DateTime($pedido['dtPedido']))->format('d/m/Y \à\s H:i')); ?></p>
                        <p>Total: R$ <?= number_format($pedido['valorTotal'], 2, ',', '.'); ?></p>
                        <p><?= $pedido['tipoFrete'] == 1 ? 'É para entrega!' : 'É para buscar na sorveteria!'; ?></p>
                        <p>Status: <?= htmlspecialchars($pedido['statusPedido']); ?></p>
                        <?php if ($pedido['tipoFrete'] == 1 && is_null($pedido['idEntregador'])) { ?>
                            <button class="btnAtribuir"><a href="<?= $redirectAtribuirEntregador; ?>">Atribuir Entregador</a></button>
                        <?php } else if ($pedido['tipoFrete'] == 1) { ?>
                            <p>Entregador <?= htmlspecialchars($pedido['idEntregador']); ?> atribuído ao pedido</p>
                        <?php } ?>
                        <button class="btnVerInfos mt-3"><a href="<?= $redirectToInformacao; ?>">Ver Informações</a></button>
                    </div>
                </div>
                <?php
            }
        } else {
            echo '<div class="alert alert-warning">Nenhum pedido encontrado.</div>';
        }
        ?>
    </main>

    <?php include_once 'components/footer.php'; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
