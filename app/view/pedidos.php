<?php require_once '../utils/pedido/inicializarPedidos.php'; ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedidos</title>
    <?php include_once '../utils/links/styleLinks.php'; ?>
    <link rel="stylesheet" href="style/pedidos.css">
    <script src="script/exibirFormulario.js"></script>
</head>
<body>
    <?php include_once 'components/header.php'; ?>
    
    <main class="container flex-column my-5 text-center d-flex justify-content-center">
        <h1 class="titulo">Pedidos</h1>

        <div class="container d-flex flex-column align-items-center justify-content-center">
            <button class="botao botao-primary" id="addPedido">Adicionar Pedido</button>

            <div class="container justify-content-center mt-4" id="formulario" style="display: none">
                <form action="" method="POST" class="container d-flex flex-column justify-content-center align-items-center flex-wrap gap-4 border rounded-4 py-4">
                    <input type="hidden" name="addPedido" value="1">
                        <div class="mb-3 mx-auto">
                            <label for="idCliente" class="form-label">Selecione o cliente:</label>
                            <select name="idCliente" id="idCliente" class="form-control">
                                <?php include_once 'components/clientesOpcoes.php'; ?>
                            </select>
                        </div>
                        <div class="mb-3 mx-auto">
                            <label for="idEndereco" class="form-label">Selecione o endereço:</label>
                            <select name="idEndereco" id="idEndereco" class="form-control">
                                <?php include_once 'components/enderecosOpcoes.php'; ?>
                            </select>
                        </div>

                        <div class="w-100">
                            <fieldset>
                                <legend>Produtos Pedidos:</legend>
                                <?php include_once 'components/produtosOpcoes.php'; ?>
                            </fieldset>
                        </div>
                        <div>
                            <div class="mb-3">
                                <input name="ckbIsDelivery" id="ckbIsDelivery" type="checkbox" class="form-check-input mx-auto">
                                <label for="ckbIsDelivery" class="form-check-label">O pedido é para entrega!</label>
                            </div>
                            <div class="mb-3">
                                <label for="valorFrete" class="form-label">Valor do Frete:</label>
                                <input type="text" id="valorFrete" name="valorFrete" class="form-control mx-auto" placeholder="Ex.: 15.00" disabled>
                            </div>
                            <label for="meioPagamento" class="form-label">Meio de Pagamento:</label>
                            <div>
                                <input type="radio" id="pagamentoDebito" name="meioPagamento" value="Cartão de Débito" class="form-check-input" required>
                                <label for="pagamentoDebito" class="form-check-label">Cartão de Débito</label>
                            </div>
                            <div>
                                <input type="radio" id="pagamentoCredito" name="meioPagamento" value="Cartão de Crédito" class="form-check-input">
                                <label for="pagamentoCredito" class="form-check-label">Cartão de Crédito</label>
                            </div>
                            <div>
                                <input type="radio" id="pagamentoDinheiro" name="meioPagamento" value="Dinheiro" class="form-check-input">
                                <label for="pagamentoDinheiro" class="form-check-label">Dinheiro</label>
                            </div>
                        </div>
                      
                    <button type="submit" class="botao botao-primary  mx-auto" >Salvar Pedido</button>
                </form>
            </div>
        </div>

        <div class="container d-flex flex-row flex-wrap gap-5 justify-content-center mt-5">
            <?php include './components/pedidosCards.php' ?>
        </div>

        <?php include_once 'components/paginacaoPedidos.php'; ?>

    </main>

    <?php include_once 'components/footer.php'; ?>
    <script src="script/atualizarQtddPedidos.js"></script>
    <script src="script/habilitarInputFrete.js"></script>

</body>
</html>
