<?php
session_start();

require_once '../../vendor/autoload.php';
require_once '../config/blockURLAccess.php';
require_once '../utils/autenticacao/login.php';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar</title>
    <?php include_once '../utils/links/styleLinks.php'; ?>
    <link rel="stylesheet" href="style/registro.css">
</head>
<body>
    <?php include_once 'components/header.php'; ?>

    <main>
        <h3 class="subtitulo">Registro</h3>
        <div class="d-flex justify-content-center align-items-center">
            <form action="registro.php" method="post"
                  class="d-flex flex-row flex-wrap justify-content-center align-items-center gap-4 rounded-4 p-4">

                <div class="form-group">
                    <label for="nome">Nome:</label>
                    <input type="text" class="form-control" name="nome" placeholder="Nome"
                           pattern="^[A-Za-zÀ-ÿ\s]+$" title="O nome deve conter apenas letras e espaços"
                           maxlength="50" required>
                </div>

                <div class="form-group">
                    <label for="email">E-mail:</label>
                    <input type="email" class="form-control" name="email" placeholder="E-mail"
                           maxlength="60" required>
                </div>

                <div class="form-group">
                    <label for="celular">Celular:</label>
                    <input type="tel" class="form-control" name="celular" id="celular"
                           placeholder="(XX) XXXXX-XXXX" maxlength="25"
                           pattern="^\(\d{2}\) \d{5}-\d{4}$"
                           title="Formato esperado: (XX) XXXXX-XXXX" required>
                </div>

                <div class="form-group">
                    <label for="rua">Rua:</label>
                    <input type="text" class="form-control" name="rua" placeholder="Rua"
                           maxlength="100" required>
                </div>

                <div class="form-group">
                    <label for="numero">Número:</label>
                    <input type="number" class="form-control" name="numero" placeholder="Número"
                           min="1" required>
                </div>

                <div class="form-group">
                    <label for="complemento">Complemento:</label>
                    <input type="text" class="form-control" name="complemento" placeholder="Complemento"
                           maxlength="15">
                </div>

                <div class="form-group">
                    <label for="cep">CEP:</label>
                    <input type="text" class="form-control" name="cep" placeholder="CEP" id="cep"
                           pattern="^\d{5}-?\d{3}$" title="Formato esperado: 99999-999"
                           maxlength="20" required>
                </div>

                <div class="form-group">
                    <label for="bairro">Bairro:</label>
                    <input type="text" class="form-control" name="bairro" placeholder="Bairro"
                           maxlength="45" required>
                </div>

                <div class="form-group">
                    <label for="cidade">Cidade:</label>
                    <input type="text" class="form-control" name="cidade" placeholder="Cidade"
                           maxlength="45" required>
                </div>

                <div class="form-group">
                    <label for="estado">Estado:</label>
                    <input type="text" class="form-control" name="estado" placeholder="Estado"
                           maxlength="45" required>
                </div>

                <div class="form-group">
                    <label for="senha">Senha:</label>
                    <input type="password" class="form-control" name="senha" placeholder="Senha"
                           minlength="8" maxlength="255"
                           pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}"
                           title="A senha deve conter pelo menos 8 caracteres, incluindo uma letra maiúscula, uma minúscula, um número e um caractere especial."
                           required>
                </div>

                <div class="form-group d-flex align-items-center justify-content-center w-100">
                    <input class="botao botao-primary" type="submit" value="Registre-se" name="registrar">
                </div>
            </form>
        </div>
    </main>

    <?php include_once 'components/footer.php'; ?>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#celular').mask('(00) 00000-0000');
            $('#cep').mask('00000-000');
        });
    </script>
</body>
</html>
