<?php
use app\controller\LoginController;

session_start();
require_once '../../vendor/autoload.php';
require_once '../config/blockURLAccess.php';

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $loginCtl = new LoginController();
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $loginCtl->login($email, $senha);
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="style/CabecalhoRodape.css">
    <link rel="stylesheet" href="style/components/botao.css">
    <link rel="stylesheet" href="style/base/global.css">
    <link rel="stylesheet" href="style/base/variables.css">
</head>
<body style="height: calc(100vh - 200px);">
    <?php
        include_once 'components/header.php';
    ?>
    <main>
        <h3 class="subtitulo">Login</h3>
        <div class="container d-flex justify-content-center align-items-center w-75">
            <form action="login.php" method="POST" class="d-flex flex-column justify-content-center align-items-center formu">
                <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control"   name="email" placeholder="E-mail" required>
                </div>
                <div class="form-group">
                        <label for="senha">Senha:</label>
                        <input type="password" class="form-control"   name="senha" placeholder="Senha" required>
                </div>
                <button type="submit" class="btn btn-primary">Login</button>
                <a class="link mt-4 text-center" href="registro.php">É cliente e não possui login? Clique para criar conta!</a>
            </form>
        </div>
    </main>
    <?php
        include_once 'components/footer.php';
    ?>
</body>
</html>
