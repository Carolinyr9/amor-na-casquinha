<?php
session_start();
require_once '../config/blockURLAccess.php';
require_once '../../vendor/autoload.php';
require_once '../utils/editarFuncionarios.php';

use app\controller\FuncionarioController;

$func = new FuncionarioController();
$dadosFunc = null;

if (isset($_GET['funcEmail'])) {
    $dadosFunc = $func->buscarFuncionarioPorEmail($_GET['funcEmail']);
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Funcionário</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">  
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="style/CabecalhoRodape.css">
    <link rel="stylesheet" href="style/editFuncS.css">
    <link rel="shortcut icon" href="images/iceCreamIcon.ico" type="image/x-icon">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <?php include_once 'components/header.php'; ?>

    <main>
        <h1 class="text-center pt-4 pb-4">Editar Funcionário</h1>
        <div class="conteiner">
            <div class="conteiner1">
                <div class="c1">
                    <div class="c2">
                        <?php if ($dadosFunc): ?>
                            <form action="editarFuncionarios.php" method="POST" id="formulario" class="formulario">
                                <input type="hidden" name="emailFunAtual" value="<?= htmlspecialchars($dadosFunc->getEmail()); ?>">

                                <label for="nome2">Nome:</label>
                                <input type="text" id="nome2" name="nomeFunEdt" placeholder="Nome" value="<?= htmlspecialchars($dadosFunc->getNome()); ?>" required>

                                <label for="email2">Email:</label>
                                <input type="email" id="email2" name="emailFunEdt" placeholder="Email" value="<?= htmlspecialchars($dadosFunc->getEmail()); ?>" required>

                                <label for="telefone2">Telefone:</label>
                                <input type="text" id="telefone2" name="telefoneFunEdt" placeholder="(11) 95555-5555" value="<?= htmlspecialchars($dadosFunc->getTelefone()); ?>" required>

                                <input type="submit" value="Atualizar" name="btnAtualizar">
                            </form>
                        <?php else: ?>
                            <p class="text-danger">Funcionário não encontrado.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="text-center mt-4">
                <button class="voltar"><a href="gerenciarFuncionarios.php">Voltar</a></button>
            </div>
        </div>
    </main>

    <?php include_once 'components/footer.php'; ?>
</body>
</html>
