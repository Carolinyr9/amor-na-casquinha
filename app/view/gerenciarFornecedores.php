<?php
session_start();

require_once '../config/blockURLAccess.php';
require_once '../../vendor/autoload.php';
require_once '../utils/fornecedor/adicionarFornecedor.php';
require_once '../utils/fornecedor/excluirFornecedor.php';

use app\controller\FornecedorController;

$fornecedorController = new FornecedorController();
$listaPessoas = $fornecedorController->listarFornecedor();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fornecedores</title>
    <?php include_once '../utils/links/styleLinks.php'; ?>
    <link rel="stylesheet" href="style/formulario.css">
    <script src="script\exibirFormulario.js"></script>
</head>
<body>
    <?php include_once 'components/header.php'; ?>

    <main>
        <h1 class="titulo">Fornecedores</h1>

        <section>
            <div class="conteiner d-flex flex-column justify-content-center align-items-center">
                <button id="addFornecedor" class="botao botao-primary mx-auto mt-4">Adicionar Fornecedor</button>

                <div id="formulario" class="container" style="display: none;">
                    <form action="gerenciarFornecedores.php" method="POST" class="formulario d-flex flex-column justify-content-center mx-auto my-4 border rounded-4 p-4">
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
                                <input class="form-control" type="text" id="cnpj" name="cnpj" placeholder="12.345.678/0001-95" required>
                            </div>
                            <div class="form-group mb-3">
                                <input class="form-control" type="text" id="rua" name="rua" placeholder="Rua" required>
                            </div>
                            <div class="form-group mb-3">
                                <input class="form-control" type="number" id="numero" name="numero" placeholder="Número" required>
                            </div>
                            <div class="form-group mb-3">
                                <input class="form-control" type="text" id="complemento" name="complemento" placeholder="Complemento">
                            </div>
                            <div class="form-group mb-3">
                                <input class="form-control" type="text" id="cep" name="cep" placeholder="CEP" pattern="\d{5}-\d{3}" title="Formato esperado: XXXXX-XXX" required>
                            </div>
                            <div class="form-group mb-3">
                                <input class="form-control" type="text" id="bairro" name="bairro" placeholder="Bairro" required>
                            </div>
                            <div class="form-group mb-3">
                                <input class="form-control" type="text" id="cidade" name="cidade" placeholder="Cidade" required>
                            </div>
                            <div class="form-group mb-3">
                                <input class="form-control" type="text" id="estado" name="estado" placeholder="Estado" required>
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

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#telefone').mask('(00) 00000-0000');
            $('#cnpj').mask('00.000.000/0000-00');
            $('#cep').mask('00000-000');
        });
    </script>
</body>
</html>