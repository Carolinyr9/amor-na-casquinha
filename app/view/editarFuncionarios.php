<?php
session_start();
require_once '../config/blockURLAccess.php';
require_once '../../vendor/autoload.php';
use app\controller\FuncionarioController;

$func = new FuncionarioController();

if(isset($_GET['funcEmail'])) {
    $dadosFunc = $func->listarFuncionarioEmail($_GET['funcEmail']);
}

if (isset($_POST['btnAtualizar'])) {
    $func->atualizarFunc($_POST['emailFunAtual'], $_POST['nomeFunEdt'], $_POST['emailFunEdt'], $_POST['telefoneFunEdt']);
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Funcionário</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="style/CabecalhoRodape.css">
    <link rel="stylesheet" href="style/editFuncS.css">
    <link rel="shortcut icon" href="images/iceCreamIcon.ico" type="image/x-icon">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <?php
        include_once 'components/header.php';
    ?>
    <main>
        <h1 class="m-auto text-center pt-4 pb-4">Editar Funcionários</h1>
        <div class="conteiner">
            <div class="conteiner1">
                
                <div class="c1">
                    <div class="c2">
                <?php foreach($dadosFunc as $dadosFunc){?>
                    <form action="editarFuncionarios.php" method="POST" id="formulario" class="formulario">
                        <input type="hidden" name="emailFunAtual" value="<?= $dadosFunc['email']; ?>">
                        <label for="nome2">Nome:</label>
                        <input type="text" id="nome2" name="nomeFunEdt" placeholder="Nome" value="<?= $dadosFunc['nome']; ?>" required>
                        <label for="email2">Email:</label>
                        <input type="text" id="email2" name="emailFunEdt" placeholder="Email" value="<?= $dadosFunc['email']; ?>" required>
                        <label for="telefone2">Telefone:</label>
                        <input type="text" id="telefone2" name="telefoneFunEdt" placeholder="(11) 955555555" value="<?= $dadosFunc['telefone']; ?>" required>
                        <input type="submit" value="Atualizar" name="btnAtualizar">
                    </form>
                <?php } ?>
                    </div>
                </div>
            </div>
            <button class="voltar"><a href="sessaoFuncionarios.php">Voltar</a></button>
        </div>
    </main>
    <?php
        include_once 'components/footer.php';
    ?>
</body>
</html>