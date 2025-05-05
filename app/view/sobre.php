<?php
session_start();
require_once '../../vendor/autoload.php';
require_once '../utils/criarPedidos.php';
require_once '../utils/alterarCliente.php';
require_once '../config/config.php';

use app\controller2\ClienteController;
use app\controller2\PedidoController;
use app\controller2\CarrinhoController;
use app\controller2\EnderecoController;

$pedidoController = new PedidoController();
$clienteController = new ClienteController();
$carrinho = new CarrinhoController();
$enderecoController = new EnderecoController();

$clienteData = $clienteController->listarClientePorEmail($_SESSION["userEmail"]);
$endereco = $enderecoController->listarEnderecoPorId($clienteData->getIdEndereco());
$pedidos = $pedidoController->listarPedidoPorIdCliente($clienteData->getId());
$itensCarrinho = $carrinho->listarCarrinho();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">  
    <link rel="stylesheet" href="style/CabecalhoRodape.css">
    <link rel="stylesheet" href="style/sobreS.css">
    <link rel="shortcut icon" href="images/iceCreamIcon.ico" type="image/x-icon">
    <link rel="stylesheet" href="style/components/botao.css">
    <link rel="stylesheet" href="style/components/cards.css">
    <link rel="stylesheet" href="style/base/global.css">
    <link rel="stylesheet" href="style/base/variables.css">
</head>
<body>
    <?php include_once 'components/header.php'; ?>
    <main>
        <div class="conteiner1 rounded-4 text-center d-flex align-items-center flex-column w-75 p-4 my-3">
            <div class="c1 w-100">
                <div id="dados">
                    <p class="fs-5">Nome: <?= htmlspecialchars($clienteData->getNome()); ?></p>
                    <p class="fs-5">Email: <?= htmlspecialchars($clienteData->getEmail()); ?></p>
                    <p class="fs-5">Telefone: <?= htmlspecialchars($clienteData->getTelefone()); ?></p>
                    <p class="fs-5">Endereço: </p>
                    <?php include 'components/enderecoCard.php'; ?>
                        
                <form action="" class="w-100 formEditar" method="POST" id="formulario">
                    <p class="fs-5">Usuário</p>
                    <label for="nome">Nome:</label>
                    <input class="rounded-3 border-0 mr-4" type="text" id="nome" name="nome" value="<?= htmlspecialchars($clienteData->getNome() ?? ''); ?>" placeholder="Nome completo">
                    
                    <label for="telefone">Telefone:</label>
                    <input class="rounded-3 border-0 mr-4" type="text" id="telefone" name="telefone" value="<?= htmlspecialchars($clienteData->getTelefone() ?? ''); ?>" placeholder="Telefone">

                    <br>
                    <p class="fs-5">Endereço</p>

                    <div class="container">
                        <div class="row my-2">
                            <div class="col">
                                <label for="rua">Rua:</label>
                                <input class="rounded-3 border-0 mr-4" type="text" id="rua" name="rua" value="<?= htmlspecialchars($endereco->getRua() ?? ''); ?>" placeholder="Rua">
                            </div>

                            <div class="col">
                                <label for="numero">Número:</label>
                                <input class="rounded-3 border-0 mr-4" type="number" id="numero" name="numero" value="<?= htmlspecialchars($endereco->getNumero() ?? ''); ?>" placeholder="Número">
                            </div>
                        </div>

                        <div class="row my-2">
                            <div class="col">
                                <label for="complemento">Complem.:</label>
                                <input class="rounded-3 border-0 mr-4" type="text" id="complemento" name="complemento" value="<?= htmlspecialchars($endereco->getComplemento() ?? ''); ?>" placeholder="Complemento">
                            </div>

                            <div class="col">
                                <label for="cep">CEP:</label>
                                <input class="rounded-3 border-0 mr-4" type="text" id="cep" name="cep" value="<?= htmlspecialchars($endereco->getCep() ?? ''); ?>" placeholder="CEP">
                            </div>
                        </div>

                        <div class="row my-2">
                            <div class="col">
                                <label for="bairro">Bairro:</label>
                                <input class="rounded-3 border-0 mr-4" type="text" id="bairro" name="bairro" value="<?= htmlspecialchars($endereco->getBairro() ?? ''); ?>" placeholder="Bairro">
                            </div>

                            <div class="col">
                                <label for="cidade">Cidade:</label>
                                <input class="rounded-3 border-0 mr-4" type="text" id="cidade" name="cidade" value="<?= htmlspecialchars($endereco->getCidade() ?? ''); ?>" placeholder="Cidade">
                            </div>
                        </div>

                        <label for="estado">Estado:</label>
                        <input class="rounded-3 border-0 mr-4" type="text" id="estado" name="estado" value="<?= htmlspecialchars($endereco->getEstado() ?? ''); ?>" placeholder="Estado">
                    </div>

                    <input type="hidden" name="idEndereco" value="<?= htmlspecialchars($endereco->getIdEndereco() ?? ''); ?>">

                    <button type="submit" name="btnAlterarCliente" class="rounded-4 border-0 fs-5 mt-4">Salvar</button>
                </form>
            </div>
            <button id="edit" class="btnEditar rounded-4 m-auto border-0 fs-5">Editar</button>
        </div>

        <br>
        <h3>Meus pedidos</h3>
        <?php include 'components/pedidoCard.php'; ?>
    </main>
    <?php include_once 'components/footer.php'; ?>
    <script src="script/editar.js"></script>
</body>
</html>
