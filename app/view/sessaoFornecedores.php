<?php
session_start();
require_once '../config/blockURLAccess.php';
use app\controller\ProdutoVariacaoController;

$forn = new FornecedorController();

$listaFornecedores = $forn->listarForn();

if(isset($_POST['submitBtn'])) {
    $nome = $_POST['nomeForn'];
    $email = $_POST['emailForn'];
    $telefone = $_POST['telefoneForn'];
    $cnpj = $_POST['cnpjForn'];
    $rua = $_POST['ruaForn'];
    $numero = $_POST['numeroForn'];
    $bairro = $_POST['bairroForn'];
    $complemento = $_POST['complementoForn'];
    $cep = $_POST['cepForn'];
    $cidade = $_POST['cidadeForn'];
    $estado = $_POST['estadoForn'];
    $forn->inserirForn($nome, $email, $telefone, $cnpj, $rua, $numero, $bairro, $complemento, $cep, $cidade, $estado);
}

if(isset($_GET['exclForn'])) {
    $forn->deletarForn($_GET['exclForn']);
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fornecedores</title>
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
        <h1 class="m-auto text-center pt-4 pb-4">Fornecedores</h1>
        <div class="conteiner">
            <button class="add">Adicionar Fornecedores</button>
            <form action="sessaoFornecedores.php" method="POST" id="addFormulario">
                <label for="nome1" required>Nome:</label>
                <input type="text" id="nome1" name="nomeForn" placeholder="Nome" required>
                <label for="email1">Email:</label>
                <input type="email" id="email1" name="emailForn" placeholder="Email" required>
                <label for="telefone1">Telefone:</label>
                <input type="text" id="telefone1" name="telefoneForn" placeholder="(11) 955555555" required>
                <label for="cnpj1">CNPJ:</label>
                <input type="text" id="cnpj1" name="cnpjForn" placeholder="CNPJ" required>
                <label for="rua1">Rua:</label>
                <input type="text" id="rua1" name="ruaForn" placeholder="Rua" required>
                <label for="bairro1">Bairro:</label>
                <input type="text" id="bairro1" name="bairroForn" placeholder="Bairro" required>    
                <label for="numero1">Número:</label>
                <input type="text" id="numero1" name="numeroForn" placeholder="Número" required>
                <label for="complemento1">Complemento:</label>
                <input type="text" id="complemento1" name="complementoForn" placeholder="Complemento">
                <label for="cep1">CEP:</label>
                <input type="text" id="cep1" name="cepForn" placeholder="CEP" required>
                <label for="cidade1">Cidade:</label>
                <input type="text" id="cidade1" name="cidadeForn" placeholder="Cidade" required>
                <label for="estado1">Estado:</label>
                <input type="text" id="estado1" name="estadoForn" placeholder="Estado" required>
                <input type="submit" name="submitBtn" value="Adicionar">
            </form>
            <div class="conteiner1">
                    <?php
                        foreach ($listaFornecedores as $row) {
                            $redirectToEditar = 'editarFornecedores.php?fornEmail='.$row['email'];
                            $redirectToExcluir = 'sessaoFornecedores.php?exclForn='.$row['email'];
                            echo '<div class="c1">
                                    <div class="c2">
                                       
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
                                        <button id="edit"><a href="'.$redirectToEditar.'">Editar</a></button>        
                                        <button id="excl"><a href="'.$redirectToExcluir.'">Excluir</a></button>        
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