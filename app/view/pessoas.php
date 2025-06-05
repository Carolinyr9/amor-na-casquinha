<?php
session_start();
require_once '../config/blockURLAccess.php';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciador de Pessoas</title>
    <?php include_once '../utils/links/styleLinks.php'; ?>
    <link rel="stylesheet" href="style/pessoas.css">
</head>
<body>
<?php
        include_once 'components/header.php';
    ?>
    <main>
        <section class="">
            <h3 class="titulo">Gerenciamento de Pessoas</h3>
            <div class="box-pessoas d-flex flex-column justify-content-center align-items-center gap-4 m-auto rounded-4 p-4">
                <div class="botao botao-primary w-auto p-3 d-flex justify-content-center align-items-center rounded-4">
                    <a class="fs-5 fw-bold text-decoration-none text-dark" href="gerenciarFuncionarios.php">Funcionários</a>
                </div>
                <div class="botao botao-primary w-auto p-3 d-flex justify-content-center align-items-center rounded-4">
                    <a class="fs-5 fw-bold text-decoration-none text-dark" href="gerenciarFornecedores.php">Fornecedores</a>
                </div>
                <div class="botao botao-primary w-auto p-3 d-flex justify-content-center align-items-center rounded-4">
                    <a class="fs-5 fw-bold text-decoration-none text-dark" href="gerenciarEntregadores.php">Entregadores</a>
                </div>
                
            </div>
        </section>
    </main>
    <?php
        include_once 'components/footer.php';
    ?>
</body>
</html>
