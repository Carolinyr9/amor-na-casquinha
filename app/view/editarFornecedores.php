<?php
session_start();
require_once '../config/blockURLAccess.php';
require_once '../../vendor/autoload.php';
require_once '../utils/editarFornecedor.php';

use app\controller\FornecedorController;

$fornecedorController = new FornecedorController();

if(isset($_GET['fornEmail'])) {
    
    $emailForn = $_GET['fornEmail'];
    $dadosForn = $fornecedorController->buscarFornecedorPorEmail($emailForn);
} else {
    echo "<p class='text-center text-danger'>Fornecedor não encontrado.</p>";
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Fornecedor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="style/CabecalhoRodape.css">
    <link rel="stylesheet" href="style/editFuncS.css">
    <link rel="shortcut icon" href="images/iceCreamIcon.ico" type="image/x-icon">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <?php
        include_once 'components/header.php';
    ?>
    <main>
        <h1 class="m-auto text-center pt-4 pb-4">Editar Fornecedor</h1>
        <div class="conteiner">
            <div class="conteiner1">
                
                <div class="c1">
                    <div class="c2">
                    <form action="editarFornecedores.php" method="POST" id="formulario" class="formulario">
                        <input type="hidden" name="emailFornAtual" value="<?= $dadosForn->getEmail(); ?>">
                        <label for="nome2">Nome:</label>
                        <input type="text" id="nome2" name="nomeFornEdt" value="<?= $dadosForn->getNome(); ?>" required>
                        <label for="email2">Email:</label>
                        <input type="text" id="email2" name="emailFornEdt" value="<?= $dadosForn->getEmail(); ?>" required>
                        <label for="telefone2">Telefone:</label>
                        <input type="text" id="telefone2" name="telefoneFornEdt" value="<?= $dadosForn->getTelefone(); ?>" required>
                        <input type="submit" value="Atualizar" name="btnAtualizar">
                    </form>
                    </div>
                </div>
            </div>
            <button class="voltar"><a href="gerenciarFornecedores.php">Voltar</a></button>
        </div>
    </main>
    <?php
        include_once 'components/footer.php';
    ?>
</body>
</html>