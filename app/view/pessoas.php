<?php
    session_start();
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
    <link rel="stylesheet" href="style/indexS.css">
</head>
<body>
<?php
        include_once 'components/header.php';
    ?>
    <main>
        <section>
            <div class="conteiner1 d-flex flex-column justify-content-center align-items-center">
                <h3>Gerenciamento de Pessoas</h3>
                <div class="c1">
                    <div class="card categ d-flex align-items-center">
                        <div class="d-flex align-items-center flex-column c2">
                            <h4>Funcionários</h4>
                            <button><a href="sessaoFuncionarios.php">ver</a></button>
                        </div>
                    </div>
                </div>
                <div class="c1">
                    <div class="card categ d-flex align-items-center">
                        <div class="d-flex align-items-center flex-column c2">
                            <h4>Fornecedores</h4>
                            <button><a href="sessaoFornecedores.php">ver</a></button>
                        </div>
                    </div>
                </div>
                
            </div>
        </section>
    </main>
    <?php
        include_once 'components/footer.php';
    ?>
</body>
</html>
