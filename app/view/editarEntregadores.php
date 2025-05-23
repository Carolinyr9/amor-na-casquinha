<?php
session_start();
require_once '../config/blockURLAccess.php';
require_once '../../vendor/autoload.php';
require_once '../utils/entregador/editarEntregador.php';

use app\controller\EntregadorController;
use app\controller\EnderecoController;

$entregadorController = new EntregadorController();
$dadosEntr = null;

if(isset($_GET['entrEmail'])) {
    $dadosEntr = $entregadorController->listarEntregadorPorEmail($_GET['entrEmail']);
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Entregador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">  
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="style/CabecalhoRodape.css">
    <link rel="stylesheet" href="style/editarPessoas.css">
    <link rel="stylesheet" href="style/components/botao.css">
    <link rel="stylesheet" href="style/base/global.css">
    <link rel="stylesheet" href="style/base/variables.css">
    <link rel="shortcut icon" href="../images/iceCreamIcon.ico" type="image/x-icon">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <?php include_once 'components/header.php'; ?>

    <main>
        <section class="d-flex flex-column align-items-center justify-content-center">
            <h1 class="titulo">Editar Entregador</h1>
            <div class="container-form container rounded-4 p-4">
                <?php if ($dadosEntr): ?>
                    <form action="editarEntregadores.php" method="POST" class="m-auto">
                        <input type="hidden" name="emailFunAtual" value="<?= htmlspecialchars($dadosEntr->getEmail()); ?>">

                        <div class="form-group mb-3">
                            <input type="text" id="nome" name="nomeEntrEdt" class="form-control" placeholder="Nome" value="<?= htmlspecialchars($dadosEntr->getNome()); ?>" required> 
                        </div>

                        <div class="form-group mb-3">
                            <input type="email" id="email" name="emailEntrEdt" class="form-control" placeholder="Email" value="<?= htmlspecialchars($dadosEntr->getEmail()); ?>" required>
                        </div>

                        <div class="form-group mb-3">
                            <input type="text" id="telefone" name="telefoneEntrEdt" class="form-control" placeholder="(11) 95555-5555" value="<?= htmlspecialchars($dadosEntr->getTelefone()); ?>" pattern="\(\d{2}\) \d{5}-\d{4}" title="Formato esperado: (69) 97955-6487" required>
                        </div>

                        <div class="form-group mb-3">
                            <input type="text" id="cnh" name="cnhEntrEdt" class="form-control" placeholder="CNH" value="<?= htmlspecialchars($dadosEntr->getCnh()); ?>" required>
                        </div>

                        <div class="m-auto w-25 d-flex justify-content-center align-items-center"><input type="submit" value="Atualizar" name="btnAtualizar" class="botao botao-primary m-auto"></div>
                    </form>
                <?php else: ?>
                    <p class="text-danger">Entregador não encontrado.</p>
                <?php endif; ?>
            </div>
            <a href="gerenciarEntregadores.php" class="botao botao-secondary mt-4">Voltar</a>
        </section>
    </main>
    <?php
        include_once 'components/footer.php';
    ?>
</body>
</html>