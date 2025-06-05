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
</head>
<body>
    <?php include_once 'components/header.php'; ?>
    
    <main class="container flex-column my-5 text-center d-flex justify-content-center">
        <h1 class="titulo">Pedidos</h1>

        <div class="container d-flex flex-column align-items-center justify-content-center">
            <button class="botao botao-primary" id="addPedido">Adicionar Pedido</button>

            <div class="container justify-content-center mt-4" id="formulario" style="display: none;">
                <form action="" method="POST" class="container d-flex flex-wrap gap-4 border rounded-4 py-4">
                    <input type="hidden" name="addPedido" value="1">

                    <div class="d-flex flex-row flex-wrap justify-content-center gap-5 w-100">
                        <div class="mb-3">
                            <label for="idCliente" class="form-label">Selecione o cliente:</label>
                            <select name="idCliente" id="idCliente">
                                <?php include_once 'components/clientesOpcoes.php'; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="idEndereco" class="form-label">Selecione o endereço:</label>
                            <select name="idEndereco" id="idEndereco">
                                <?php include_once 'components/enderecosOpcoes.php'; ?>
                            </fieldset>
                        </div>
                        <div class="mb-3">
                            <fieldset>
                                <legend>Produtos Pedidos:</legend>
                                <?php include_once 'components/produtosOpcoes.php'; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="valorFrete" class="form-label">Valor do Frete:</label>
                            <input type="text" id="valorFrete" name="valorFrete" class="form-control" placeholder="Ex.: 15.00">
                        </div>
                        <div class="mb-3 form-check d-flex">
                            <input name="ckbIsDelivery" id="ckbIsDelivery" type="checkbox" class="form-check-input flex-grow-1">
                            <label for="ckbIsDelivery" class="form-check-label ms-2">O pedido é para entrega!</label>
                        </div>
                        <div class="mb-3">
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
                        
                    </div>

                    <button type="submit" class="botao botao-primary m-auto" >Salvar Pedido</button>
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

</body>
</html>
