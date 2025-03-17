<?php
session_start();
require_once '../config/blockURLAccess.php';
require_once '../../vendor/autoload.php';
use app\controller\FuncionarioController;

$func = new FuncionarioController();

$listaFuncionarios = $func->listarFunc();

if (isset($_POST['submitBtn'])) {
    $nome = $_POST['nomeFun'];
    $email = $_POST['emailFun'];
    $telefone = $_POST['telefoneFun'];
    $senha = $_POST['senhaFun'];
    $adm = $_POST['admFun'];

    $result = $func->inserirFunc($nome, $email, $telefone, $senha, $adm);
}

if(isset($_GET['exclFunc'])) {
    $func->deletarFunc($_GET['exclFunc']);
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Funcionários</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="style/CabecalhoRodape.css">
    <link rel="stylesheet" href="style/editarFuncionariosS.css">
    <link rel="shortcut icon" href="images/iceCreamIcon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.7.1/jquery-confirm.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>
<body>
    <?php
        include_once 'components/header.php';
    ?>
    <main>
        <h1 class="m-auto text-center pt-4 pb-4">Funcionários</h1>
        <div class="conteiner">
            <button class="add">Adicionar Funcionário</button>
            <form action="sessaoFuncionarios.php" method="POST" id="addFormulario">
                <label for="nome1" required>Nome:</label>
                <input type="text" id="nome1" name="nomeFun" placeholder="Nome">
                <label for="email2">Email:</label>
                <input type="email" id="email1" name="emailFun" placeholder="Email" required>
                <label for="telefone1">Telefone:</label>
                <input type="text" id="telefone1" name="telefoneFun" placeholder="(11) 955555555" required>
                <label for="senha1">Senha:</label>
                <input type="password" id="senha1" name="senhaFun" placeholder="Senha" required>
                <label for="adm1"><input type="radio" id="adm1" name="admFun" value=1> Administrador</label>
                <input type="submit" name="submitBtn" value="Adicionar">
            </form>
            <div class="conteiner1">
                
                    <?php
                        foreach ($listaFuncionarios as $row) {
                            $redirectToEditar = 'editarFuncionarios.php?funcEmail='.$row['email'];
                            $redirectToExcluir = 'sessaoFuncionarios.php?exclFunc='.$row['email'];
                            echo '<div class="c1">
                                    <div class="c2">
                                        <!-- <div class="c3">
                                            <picture>
                                            <source media="(min-width: 768px)" srcset="images/'.$row["perfil"].'.png">
                                            <img src="images/'.$row["perfil"].'.png" alt="'.$row["nome"].'">
                                            </picture>
                                        </div> -->
                                        <div class="d-flex flex-column">
                                            <div id="dados">
                                                <h3 class="titulo px-3">'.$row["nome"].'</h3>
                                                <div class="px-3">
                                                    <p>Email: '.$row["email"].'</p>
                                                    <p>Celular: '.$row["telefone"].'</p>
                                                </div>
                                            </div>
                                        </div>        
                                    </div>
                                    <div class="botao text-center d-flex justify-content-evenly mt-3">
                                        <button id="excl"><a href="'.$redirectToEditar.'">Editar</a></button>        
                                        <button id="edit"><a href="'.$redirectToExcluir.'">Excluir</a></button>        
                                    </div>
                                </div>';
                        }
                    ?>

            </div>
        </div>
    </main>
    
    <?php
        include_once 'components/footer.php';
    ?>
    <script src="script/adicionar.js"></script>
</body>
</html>