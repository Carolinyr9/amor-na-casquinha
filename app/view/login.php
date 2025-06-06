<?php
use app\controller\UsuarioController;

session_start();

require_once '../../vendor/autoload.php';
require_once '../config/blockURLAccess.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';

    $usuarioController = new UsuarioController();
    $usuarioController->login($email, $senha);
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <?php include_once '../utils/links/styleLinks.php'; ?>
    <link rel="stylesheet" href="style/login.css">
    
</head>
<body>
    <?php include_once 'components/header.php'; ?>

    <main>
        <h3 class="subtitulo">Login</h3>
        <div class="container d-flex justify-content-center align-items-center w-75">
            <form action="login.php" method="POST" class="d-flex flex-column justify-content-center align-items-center formu">
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" name="email" placeholder="E-mail" required>
                </div>
                <div class="form-group">
                    <label for="senha">Senha:</label>
                    <input type="password" class="form-control" name="senha" placeholder="Senha" required>
                </div>
                <button type="submit" class="botao botao-primary mt-4">Login</button>
                <a class="link mt-4 text-center" href="registro.php">É cliente e não possui login? Clique para criar conta!</a>
            </form>
        </div>
    </main>

    <?php include_once 'components/footer.php'; ?>
</body>
</html>
