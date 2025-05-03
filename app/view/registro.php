<?php
session_start();
require_once '../config/blockURLAccess.php';
require_once '../controller/loginController.php';

if(isset($_POST['resgistrar'])){
    $login = new LoginController();

    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $celular = $_POST['celular'];
    $rua = $_POST['rua'];
    $numero = $_POST['numero'];
    $bairro = $_POST['bairro'];
    $complemento = $_POST['complemento'];
    $cep = $_POST['cep'];
    $cidade = $_POST['cidade'];
    $estado = $_POST['estado'];
    $login->registrar($nome, $email, $senha, $celular, $rua, $numero, $bairro, $complemento, $cep, $cidade, $estado);
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="style/CabecalhoRodape.css">
    <link rel="stylesheet" href="style/components/botao.css">
    <link rel="stylesheet" href="style/base/global.css">
    <link rel="stylesheet" href="style/base/variables.css">
    <link rel="stylesheet" href="style/registro.css">
</head>
<body>
    <?php
        include_once 'components/header.php';
    ?>
    <main>
    <h3 class="subtitulo">Login</h3>
    <div class="d-flex justify-content-center align-items-center">
        <form action="registro.php" method="post" class="d-flex flex-row flex-wrap justify-content-center align-items-center gap-4 rounded-4 p-4">
            <div class="form-group">
                <label for="nome">Nome:</label>
                <input type="text" class="form-control" name="nome" placeholder="Nome" pattern="^[A-Za-zÀ-ÿ\s]+$"  title="O nome deve conter apenas letras e espaços" maxlength="50" required>
            </div>

            <div class="form-group">
                <label for="email">E-mail:</label>
                <input type="email" class="form-control" name="email" placeholder="E-mail" maxlength="60" required>
            </div>

                <div class="form-group">
                <label for="celular">Celular:</label>
                <input type="tel" class="form-control" name="celular" maxlength="25" placeholder="(XX) XXXXX-XXXX" pattern="^\(\d{2}\) \d{5}-\d{4}$" title="oque no formato (XX) XXXXX-XXXX" required>
            </div>

            <div class="form-group">
                <label for="rua">Rua:</label>
                <input type="text" class="form-control" name="rua" maxlength="100" placeholder="Rua" required>
            </div>
            
            <div class="form-group">
                <label for="numero">Número:</label>
                <input type="number" class="form-control" name="numero" placeholder="Número" min="1" required>
            </div>

            <div class="form-group">
                <label for="complemento">Complemento:</label>
                <input type="text" class="form-control" name="complemento" maxlength="15" placeholder="Complemento">
            </div>

            <div class="form-group">
                <label for="cep">CEP:</label>
                <input type="text" class="form-control" name="cep" maxlength="20" placeholder="CEP" pattern="^\d{5}-?\d{3}$" title="oque no formato 99999-999"  required>
            </div>

            <div class="form-group">
                <label for="bairro">Bairro:</label>
                <input type="text" class="form-control" name="bairro" maxlength="45" placeholder="Bairro" required>
            </div>

            <div class="form-group">
                <label for="cidade">Cidade:</label>
                <input type="text" class="form-control" name="cidade" maxlength="45" placeholder="Cidade" required>
            </div>

            <div class="form-group">
                <label for="estado">Estado:</label>
                <input type="text" class="form-control" name="estado" maxlength="45" placeholder="Estado" required>
            </div>

            <div class="form-group">
                <label for="senha">Senha:</label>
                <input type="password" class="form-control" name="senha" maxlength="255" placeholder="Senha" minlength="8" pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}" title="A senha deve ter pelo menos 8 caracteres, incluindo uma letra maiúscula, uma minúscula, um número e um caractere especial." required>
            </div>
            <div class="form-group d-flex align-items-center justify-content-center w-100 "><input class="btn btn-primary" type="submit" value="Registre-se" name="resgistrar" class="btn btn-primary mt-3"></div>
        </form>
</div>
    </main>

   <?php
        include_once 'components/footer.php';
    ?>
<script src="script/header.js"></script>
</body>
</html>
