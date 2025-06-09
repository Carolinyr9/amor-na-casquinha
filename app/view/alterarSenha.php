<?php
session_start();
require_once '../config/blockURLAccess.php';
require_once '../../vendor/autoload.php';
require_once '../utils/cliente/alterarSenha.php';

use app\controller\ClienteController;
$clienteController = new ClienteController();
$clienteData = $clienteController->listarClientePorEmail($_SESSION["userEmail"]);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar Senha</title>
    <?php include_once '../utils/links/styleLinks.php'; ?>
    <link rel="stylesheet" href="style/perfil.css">
    <script src="script/exibirFormulario.js"></script>
</head>
<body>
    <?php include_once 'components/header.php'; ?>
    <main>
        <section>
            <h1 class="subtitulo">Alterar Senha</h1>
            <div class="container-section container d-flex align-items-center flex-column text-center rounded-4 p-4 my-3">
                
                <form action="" class="formEditarSenha" method="POST" id="formularioSenha">
                        <p class="subtitulo">Confirme sua senha</p>
                        <div class="d-flex flex-row flex-wrap justify-content-center gap-4 mb-4">
                            <div class="form-group">
                                <input type="password" name="senhaAtual" class="form-control" placeholder="Senha atual" required>
                            </div>
                            <div class="form-group">
                                <input class="form-control" type="password" id="senhaNova" name="senhaNova" placeholder="Senha nova" pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}" title="A senha deve conter pelo menos 8 caracteres, incluindo uma letra maiúscula, uma minúscula, um número e um caractere especial." required>
                            </div>
                            
                        </div>
                        <input type="hidden" name="idCliente" value="<?= htmlspecialchars($clienteData->getId() ?? ''); ?>">  
                        <button type="submit" name="btnAlterarSenha" class="botao botao-primary mt-4" style="width: 100px;">Salvar</button>
                </form>
            </div>
            <a href="perfil.php" class="botao botao-secondary mt-4">Voltar</a>
        </section>


    </main>
    <?php include_once 'components/footer.php'; ?>
</body>
</html>
