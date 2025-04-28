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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="style/CabecalhoRodape.css">
    <link rel="shortcut icon" href="images/iceCreamIcon.ico" type="image/x-icon">
    <link rel="stylesheet" href="style/pessoas.css">
</head>
<body>
<?php
        include_once 'components/header.php';
    ?>
    <main>
        <section class="box-pessoas w-75 m-auto rounded-4 p-4">
            <div class="d-flex flex-column justify-content-center align-items-center gap-4">
                <h3 class="fw-bold">Gerenciamento de Pessoas</h3>
                <div class="box-link w-auto p-3 d-flex justify-content-center align-items-center rounded-4">
                    <a class="fs-5 fw-bold text-decoration-none text-dark" href="sessaoFuncionarios.php">Funcionários</a>
                </div>
                <div class="box-link w-auto p-3 d-flex justify-content-center align-items-center rounded-4">
                    <a class="fs-5 fw-bold text-decoration-none text-dark" href="gerenciarFornecedores.php">Fornecedores</a>
                </div>
                
            </div>
        </section>
    </main>
    <?php
        include_once 'components/footer.php';
    ?>
</body>
</html>
