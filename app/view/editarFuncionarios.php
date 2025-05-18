<?php
session_start();
require_once '../config/blockURLAccess.php';
require_once '../../vendor/autoload.php';
require_once '../utils/funcionario/editarFuncionarios.php';

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
    <link rel="stylesheet" href="style/components/botao.css">
    <link rel="stylesheet" href="style/base/global.css">
    <link rel="stylesheet" href="style/base/variables.css">
    <link rel="shortcut icon" href="../images/iceCreamIcon.ico" type="image/x-icon">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <?php include_once 'components/header.php'; ?>

    <main>
        <h1 class="titulo">Editar Funcionário</h1>
        <section>
            <div class="container" style="background-color: var(--quaternary);">
                <?php if ($dadosFunc): ?>
                    <form action="editarFuncionarios.php" method="POST" id="formulario" class="formulario">
                        <input type="hidden" name="emailFunAtual" value="<?= htmlspecialchars($dadosFunc->getEmail()); ?>">

                        <div class="form-group mb-3">
                            <input type="text" id="nome" name="nomeFunEdt" class="form-control" placeholder="Nome" value="<?= htmlspecialchars($dadosFunc->getNome()); ?>" required> 
                        </div>
                        
                        <div class="form-group mb-3">
                            <input type="email" id="email" name="emailFunEdt" class="form-control" placeholder="Email" value="<?= htmlspecialchars($dadosFunc->getEmail()); ?>" required>
                        </div>
                        
                        <div class="form-group mb-3">
                            <input type="text" id="telefone" name="telefoneFunEdt" class="form-control" placeholder="(11) 95555-5555" value="<?= htmlspecialchars($dadosFunc->getTelefone()); ?>" pattern="\(\d{2}\) \d{5}-\d{4}" title="Formato esperado: (69) 97955-6487" required>
                        </div>
                        
                        <div class="form-group mb-3">
                            <input type="text" id="rua" name="ruaFunEdt" class="form-control" placeholder="Rua" value="<?= htmlspecialchars($dadosFunc->getRua()); ?>" required>
                        </div>
                        
                        <div class="form-group mb-3">
                            <input type="text" id="numero" name="numeroFunEdt" class="form-control" placeholder="Número" value="<?= htmlspecialchars($dadosFunc->getNumero()); ?>" required>
                        </div>
                        
                        <div class="form-group mb-3">
                            <input type="text" id="complemento" name="complementoFunEdt" class="form-control" placeholder="Complemento" value="<?= htmlspecialchars($dadosFunc->getComplemento()); ?>">
                        </div>
                        
                        <div class="form-group mb-3">
                            <input type="text" id="bairro" name="bairroFunEdt" class="form-control" placeholder="Bairro" value="<?= htmlspecialchars($dadosFunc->getBairro()); ?>" required>
                        </div>
                        
                        <div class="form-group mb-3">
                            <input type="text" id="cidade" name="cidadeFunEdt" class="form-control" placeholder="Cidade" value="<?= htmlspecialchars($dadosFunc->getCidade()); ?>" required>
                        </div>
                        
                        <div class="form-group mb-3">
                            <input type="text" id="estado" name="estadoFunEdt" class="form-control" placeholder="Estado" value="<?= htmlspecialchars($dadosFunc->getEstado()); ?>" required>
                        </div>
                        
                        <div class="form-group mb-3">
                            <input type="text" id="cep" name="cepFunEdt" class="form-control" placeholder="CEP" value="<?= htmlspecialchars($dadosFunc->getCep()); ?>" pattern="\d{5}-\d{3}" title="Formato esperado: 12345-678" required>
                        </div>

                        <input type="submit" value="Atualizar" name="btnAtualizar">
                    </form>
                <?php else: ?>
                    <p class="text-danger">Funcionário não encontrado.</p>
                <?php endif; ?>
            </div>
        </section>
        <button class="voltar"><a href="gerenciarFuncionarios.php">Voltar</a></button>
    </main>

    <?php include_once 'components/footer.php'; ?>
</body>
</html>
