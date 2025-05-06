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
        <section>
            <h1 class="titulo">Perfil</h1>
            <div class="container-section container d-flex align-items-center flex-column text-center rounded-4 w-75 p-4 my-3">
                <div class="w-100">
                    <div id="dados">
                        <p class="fs-5">Nome: <?= htmlspecialchars($clienteData->getNome()); ?></p>
                        <p class="fs-5">Email: <?= htmlspecialchars($clienteData->getEmail()); ?></p>
                        <p class="fs-5">Telefone: <?= htmlspecialchars($clienteData->getTelefone()); ?></p>
                        <p class="fs-5">Endereço: </p>
                        <?php include 'components/enderecoCard.php'; ?>
                        <button id="edit" class="botao botao-secondary">Editar</button>
                    </div>
                        <form action="" class="formEditar flex-column justify-content-center w-auto " method="POST" id="formulario">
                            <p class="subtitulo">Usuário</p>
                            <div class="d-flex flex-row flex-wrap justify-content-center gap-4 mb-4">
                                <div class="form-group">
                                    <input type="text" name="nome" class="form-control" placeholder="Nome" value="<?= htmlspecialchars($clienteData->getNome() ?? ''); ?>" readonly>
                                </div>
                                <div class="form-group">
                                    <input type="email" class="form-control" name="email" placeholder="Email" value="<?= htmlspecialchars($clienteData->getNome() ?? ''); ?>" readonly>
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="senha" placeholder="Senha" readonly>
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="telefone" placeholder="Telefone" pattern="^\(\d{2}\) \d{4,5}-\d{4}$" title="O telefone deve seguir o formato (XX) XXXX-XXXX ou (XX) XXXXX-XXXX" maxlength="15" value="<?= htmlspecialchars($clienteData->getTelefone() ?? ''); ?>" required>
                                </div>
                            </div>

                            <span class="subtitulo mb-3">Endereço</span>
                            <div class="d-flex flex-row flex-wrap justify-content-center gap-4">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="rua" placeholder="Rua" value="<?= htmlspecialchars($endereco->getRua() ?? ''); ?>" pattern="^[A-Za-zÀ-ÿ\s]+$" title="A rua deve conter apenas letras e espaços" maxlength="50" required>
                                </div>
                                <div class="form-group">
                                    <input type="number" class="form-control" value="<?= htmlspecialchars($endereco->getNumero() ?? ''); ?>" name="numero" placeholder="Número" min="1" required>
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" value="<?= htmlspecialchars($endereco->getComplemento() ?? ''); ?>" name="complemento" placeholder="Complemento" maxlength="50">
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="cep" value="<?= htmlspecialchars($endereco->getCep() ?? ''); ?>" placeholder="CEP" pattern="^\d{5}-\d{3}$" title="O CEP deve seguir o formato XXXXX-XXX" required>
                                </div>
                                
                                <div class="form-group">
                                    <input type="text" class="form-control" name="bairro" value="<?= htmlspecialchars($endereco->getBairro() ?? ''); ?>" placeholder="Bairro" pattern="^[A-Za-zÀ-ÿ\s]+$" title="O bairro deve conter apenas letras e espaços" maxlength="50" required>
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="cidade" value="<?= htmlspecialchars($endereco->getCidade() ?? ''); ?>" placeholder="Cidade" pattern="^[A-Za-zÀ-ÿ\s]+$" title="A cidade deve conter apenas letras e espaços" maxlength="50" required>
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="estado" value="<?= htmlspecialchars($endereco->getEstado() ?? ''); ?>" placeholder="Estado" pattern="^[A-Za-zÀ-ÿ\s]+$" title="O estado deve conter apenas letras e espaços" maxlength="50" required>
                                </div>
                            </div>

                        <input type="hidden" name="idEndereco" value="<?= htmlspecialchars($endereco->getIdEndereco() ?? ''); ?>">
                        <button type="submit" name="btnAlterarCliente" class="botao botao-primary mt-4" style="width: 100px;">Salvar</button>
                    </form>
                </div>
            </div>
        </section>

        <section>
            <h1 class="titulo">Meus pedidos</h1>
            <div class="d-flex flex-row gap-5 mt-5"><?php include 'components/pedidosCards.php'; ?></div>
        </section>
    </main>
    <?php include_once 'components/footer.php'; ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="script/editar.js"></script>
</body>
</html>
