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
    <link rel="stylesheet" href="style/loginS.css">
</head>
<body>
    <?php
        include_once 'components/header.php';
    ?>
    <main>
    <h3 class="m-auto fw-bold text-center">Login</h3>
    <div class="d-flex justify-content-center align-items-center">
        <form action="registro.php" method="post" class="d-flex flex-column my-4 formu">
            <input class="rounded-4 border-0 fw-bold pl-3" type="text" name="nome" maxlength="50" placeholder="Nome" pattern="^[A-Za-zÀ-ÿ\s]+$" title="O nome deve conter apenas letras e espaços" required>
            <input class="rounded-4 border-0 fw-bold pl-3" type="email" name="email" maxlength="60" placeholder="Email" required>
            <input class="rounded-4 border-0 fw-bold pl-3" type="tel" name="celular" maxlength="25" placeholder="(XX) XXXXX-XXXX" pattern="^\(\d{2}\) \d{5}-\d{4}$" title="Coloque no formato (XX) XXXXX-XXXX" required>
            <input class="rounded-4 border-0 fw-bold pl-3" type="text" name="cep" maxlength="20" placeholder="CEP" pattern="^\d{5}-?\d{3}$" title="Coloque no formato 99999-999"  required>
            <input class="rounded-4 border-0 fw-bold pl-3" type="text" name="rua" maxlength="100" placeholder="Rua" required>
            <input class="rounded-4 border-0 fw-bold pl-3" type="text" name="bairro" maxlength="45" placeholder="Bairro" required>
            <input class="rounded-4 border-0 fw-bold pl-3" type="number" name="numero" placeholder="Número" min="1" required>
            <input class="rounded-4 border-0 fw-bold pl-3" type="text" name="complemento" maxlength="15" placeholder="Complemento">
            <input class="rounded-4 border-0 fw-bold pl-3" type="text" name="cidade" maxlength="45" placeholder="Cidade" required>
            <input class="rounded-4 border-0 fw-bold pl-3" type="text" name="estado" maxlength="45" placeholder="Estado" required>
            <input class="rounded-4 border-0 fw-bold pl-3" type="password" name="senha" maxlength="255" placeholder="Senha" minlength="8" pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}" title="A senha deve ter pelo menos 8 caracteres, incluindo uma letra maiúscula, uma minúscula, um número e um caractere especial." required>
            <input class="rounded-4 border-0 fw-bold m-auto" type="submit" value="Registre-se" name="resgistrar" class="btn btn-primary mt-3">
        </form>
</div>
    </main>

   <?php
        include_once 'components/footer.php';
    ?>
<script src="script/header.js"></script>
</body>
</html>
