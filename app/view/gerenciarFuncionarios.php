<?php
session_start();

require_once '../config/blockURLAccess.php';
require_once '../../vendor/autoload.php';
require_once '../utils/adicionarFuncionario.php';
require_once '../utils/excluirFuncionario.php';

use app\controller2\FuncionarioController;

$func = new FuncionarioController();
$listaFuncionarios = $func->listarFuncionario();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Funcionários</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="style/CabecalhoRodape.css">
    <link rel="stylesheet" href="style/editarFuncionariosS.css">
    <link rel="shortcut icon" href="images/iceCreamIcon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.7.1/jquery-confirm.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>
<body>
    <?php include_once 'components/header.php'; ?>

    <main>
        <h1 class="text-center pt-4 pb-4">Funcionários</h1>

        <div class="conteiner">
            <button class="add">Adicionar Funcionário</button>

            <form action="gerenciarFuncionarios.php" method="POST" id="addFormulario">
                <label for="nome1">Nome:</label>
                <input type="text" id="nome1" name="nomeFun" placeholder="Nome" required>

                <label for="email1">Email:</label>
                <input type="email" id="email1" name="emailFun" placeholder="Email" required>

                <label for="telefone1">Telefone:</label>
                <input type="text" id="telefone1" name="telefoneFun" placeholder="(11) 95555-5555" required>

                <label for="senha1">Senha:</label>
                <input type="password" id="senha1" name="senhaFun" placeholder="Senha" required>

                <label><input type="radio" name="admFun" value="1"> Administrador</label>

                <input type="submit" name="submitBtn" value="Adicionar">
            </form>

            <div class="conteiner1">
                <?php foreach ($listaFuncionarios as $funcionario): ?>
                    <?php
                        $email = $funcionario->getEmail();
                        $nome = $funcionario->getNome();
                        $telefone = $funcionario->getTelefone();
                        $perfilImg = $funcionario->getPerfil();
                        $editarUrl = "editarFuncionarios.php?funcEmail={$email}";
                        $excluirUrl = "gerenciarFuncionarios.php?exclFunc={$email}";
                    ?>
                    <div class="c1">
                        <div class="c2 d-flex flex-column">
                            <div id="dados">
                                <h3 class="titulo px-3"><?= $nome ?></h3>
                                <div class="px-3">
                                    <p>Email: <?= $email ?></p>
                                    <p>Celular: <?= $telefone ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="botao text-center d-flex justify-content-evenly mt-3">
                            <button id="excl"><a href="<?= $editarUrl ?>">Editar</a></button>
                            <button id="edit"><a href="<?= $excluirUrl ?>">Excluir</a></button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </main>

    <?php include_once 'components/footer.php'; ?>
    <script src="script/adicionar.js"></script>
</body>
</html>
