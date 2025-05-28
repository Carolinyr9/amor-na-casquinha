<?php
session_start();

require_once '../config/blockURLAccess.php';
require_once '../../vendor/autoload.php';
require_once '../utils/funcionario/adicionarFuncionario.php';
require_once '../utils/funcionario/excluirFuncionario.php';
require_once '../utils/funcionario/adicionarFuncionario.php';

use app\controller\FuncionarioController;

$funcionarioController = new FuncionarioController();
$listaPessoas = $funcionarioController->listarFuncionario();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Funcionários</title>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style/CabecalhoRodape.css">
    <link rel="stylesheet" href="style/components/cards.css">
    <link rel="stylesheet" href="style/components/botao.css">
    <link rel="stylesheet" href="style/base/variables.css">
    <link rel="stylesheet" href="style/base/global.css">
    <link rel="shortcut icon" href="../images/iceCreamIcon.ico" type="image/x-icon">
    <script src="script\exibirFormulario.js"></script>
</head>
<body>
    <?php include_once 'components/header.php'; ?>

    <main>
        <h1 class="titulo">Funcionários</h1>

        <section>
            <div class="conteiner d-flex flex-column justify-content-center align-items-center">
                <button id="addFuncionario" class="botao botao-primary mx-auto mt-4">Adicionar Funcionário</button>

                <div id="formulario" class="container" style="display: none;">
                    <form action="gerenciarFuncionarios.php" method="POST" class="d-flex flex-column justify-content-center w-50 mx-auto my-4 border rounded-4 p-4">
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
                                    <input class="form-control" type="password" id="senha1" name="senha" placeholder="Senha" pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}" title="A senha deve conter pelo menos 8 caracteres, incluindo uma letra maiúscula, uma minúscula, um número e um caractere especial." required>
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
                                <div>
                                    <input type="checkbox" name="admFun" value="1">
                                    <label for="admFun">Administrador</label><br>
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
