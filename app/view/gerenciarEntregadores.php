<?php
session_start();

require_once '../config/blockURLAccess.php';
require_once '../../vendor/autoload.php';
require_once '../utils/entregador/excluirEntregador.php';
require_once '../utils/entregador/adicionarEntregador.php';

use app\controller\EntregadorController;

$entregadorController = new EntregadorController();
$listaPessoas = $entregadorController->listarEntregadores();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entregadores</title>
    <?php include_once '../utils/links/styleLinks.php'; ?>
    <link rel="stylesheet" href="style/editarEstoque.css">
    <script src="script\exibirFormulario.js"></script>
</head>
<body>
    <?php include_once 'components/header.php'; ?>

    <main>
        <h1 class="titulo">Entregadores</h1>

        <section>
            <div class="conteiner d-flex flex-column justify-content-center align-items-center">
                <button id="addEntregador" class="botao botao-primary mx-auto mt-4">Adicionar Entregador</button>

                <div id="formulario" class="container" style="display: none;">
                    <form action="gerenciarEntregadores.php" method="POST" class="formulario d-flex flex-column justify-content-center mx-auto my-4 border rounded-4 p-4">
                        <div class="d-flex flex-row flex-wrap justify-content-center align-items-center gap-4">
                            <div class="form-group mb-3">
                                <input class="form-control" type="text" id="nome" name="nome" placeholder="Nome" required>
                            </div>
                            <div class="form-group mb-3">
                                <input class="form-control" type="email" id="email" name="email" placeholder="Email" required>
                            </div>
                            <div class="form-group mb-3">
                                <input class="form-control" type="text" id="telefone" name="telefone" placeholder="(11) 95555-5555" pattern="\(\d{2}\) \d{5}-\d{4}" title="Formato esperado: (69) 97955-6487" required>
                            </div>
                            <div class="form-group mb-3">
                                <input class="form-control" type="text" id="cnh" name="cnh" placeholder="99999900000" required>
                            </div>
                            <div class="form-group mb-3">
                                <input class="form-control" type="password" id="senha1" name="senha" placeholder="Senha" pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}" title="A senha deve conter pelo menos 8 caracteres, incluindo uma letra maiúscula, uma minúscula, um número e um caractere especial." required>
                            </div>
                        </div>

                        <input type="submit" name="submitBtn" value="Adicionar" class="botao botao-primary mx-auto">
                    </form>
                </div>

                <div class="container d-flex flex-row flex-wrap justify-content-center gap-5 my-5">
                    <?php include './components/pessoasCards.php'; ?>
                </div>

                <a class="botao botao-secondary" href="pessoas.php">Voltar</a>
            </div>
        </section>
    </main>

    <?php include_once 'components/footer.php'; ?>
</body>
</html>
