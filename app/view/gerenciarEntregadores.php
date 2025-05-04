<?php

//  depois alterar tabela e colocar auto increment no id do entregador 
session_start();

require_once '../config/blockURLAccess.php';
require_once '../../vendor/autoload.php';
require_once '../utils/adicionarEntregador.php';
require_once '../utils/excluirEntregador.php';

use app\controller2\EntregadorController;

$entregadorController = new EntregadorController();
$entregadores = $entregadorController->listarEntregadores();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Entregadores</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">  
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet">
    <link href="style/CabecalhoRodape.css" rel="stylesheet">
    <link href="style/editarFuncionariosS.css" rel="stylesheet">
    <link rel="shortcut icon" href="images/iceCreamIcon.ico" type="image/x-icon">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.7.1/jquery-confirm.min.css" rel="stylesheet">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>
<body>
    <?php include_once 'components/header.php'; ?>

    <main>
        <h1 class="m-auto text-center pt-4 pb-4">Entregadores</h1>

        <div class="conteiner">
            <button class="add">Adicionar Entregadores</button>

            <form action="gerenciarEntregadores.php" method="POST" id="addFormulario">
                <label for="nome1">Nome:</label>
                <input type="text" id="nome1" name="nomeEntr" placeholder="Nome" required>

                <label for="email1">Email:</label>
                <input type="email" id="email1" name="emailEntr" placeholder="Email" required>

                <label for="telefone1">Telefone:</label>
                <input type="text" id="telefone1" name="telefoneEntr" placeholder="(11) 95555-5555" required>

                <label for="senha1">Senha:</label>
                <input type="text" id="senha1" name="senhaEntr" placeholder="Senha para acesso primário" required>

                <label for="estado1">CNH:</label>
                <input type="text" id="cnh1" name="cnhEntr" placeholder="86930603333" required>

                <input type="submit" name="submitBtn" value="Adicionar">
            </form>

            <div class="conteiner1">
                <?php foreach ($entregadores as $entregador): 
                    $redirectToEditar = 'editarEntregadores.php?entrEmail=' . urlencode($entregador->getEmail());
                    $redirectToExcluir = 'gerenciarEntregadores.php?exclEntr=' . urlencode($entregador->getEmail());
                ?>
                    <div class="c1">
                        <div class="c2">
                            <div class="d-flex flex-column">
                                <div id="dados">
                                    <h3 class="titulo px-3"><?php echo htmlspecialchars($entregador->getNome()); ?></h3>
                                    <div class="px-3">
                                        <p>Email: <?php echo htmlspecialchars($entregador->getEmail()); ?></p>
                                        <p>Celular: <?php echo htmlspecialchars($entregador->getTelefone()); ?></p>
                                        <p>CNH: <?php echo htmlspecialchars($entregador->getCnh()); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="botao text-center d-flex justify-content-evenly mt-3">
                            <button id="edit"><a href="<?php echo $redirectToEditar; ?>">Editar</a></button>
                            <button id="excl"><a href="<?php echo $redirectToExcluir; ?>">Excluir</a></button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </main>

    <?php include_once 'components/footer.php'; ?>

    <script src="script/adicionar.js"></script>
</body>
</html>
