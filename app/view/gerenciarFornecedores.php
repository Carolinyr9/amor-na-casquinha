<?php
session_start();

require_once '../config/blockURLAccess.php';
require_once '../../vendor/autoload.php';
require_once '../utils/fornecedor/adicionarFornecedor.php';
require_once '../utils/fornecedor/excluirFornecedor.php';

use app\controller\FornecedorController;

$fornecedorController = new FornecedorController();
$fornecedores = $fornecedorController->listarFornecedor();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Fornecedores</title>
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
        <h1 class="m-auto text-center pt-4 pb-4">Fornecedores</h1>

        <div class="conteiner">
            <button class="add">Adicionar Fornecedores</button>

            <form action="gerenciarFornecedores.php" method="POST" id="addFormulario">
                <label for="nome1">Nome:</label>
                <input type="text" id="nome1" name="nomeForn" placeholder="Nome" required>

                <label for="email1">Email:</label>
                <input type="email" id="email1" name="emailForn" placeholder="Email" required>

                <label for="telefone1">Telefone:</label>
                <input type="text" id="telefone1" name="telefoneForn" placeholder="(11) 95555-5555" pattern="\(\d{2}\) \d{5}-\d{4}" title="Formato esperado: (69) 97955-6487" required>

                <label for="cnpj1">CNPJ:</label>
                <input type="text" id="cnpj1" name="cnpjForn" placeholder="12.345.678/0001-95" required>

                <label for="rua1">Rua:</label>
                <input type="text" id="rua1" name="ruaForn" placeholder="Rua" required>

                <label for="bairro1">Bairro:</label>
                <input type="text" id="bairro1" name="bairroForn" placeholder="Bairro" required>

                <label for="numero1">Número:</label>
                <input type="text" id="numero1" name="numeroForn" placeholder="Número" required>

                <label for="complemento1">Complemento:</label>
                <input type="text" id="complemento1" name="complementoForn" placeholder="Complemento">

                <label for="cep1">CEP:</label>
                <input type="text" id="cep1" name="cepForn" placeholder="00000-000" required>

                <label for="cidade1">Cidade:</label>
                <input type="text" id="cidade1" name="cidadeForn" placeholder="Cidade" required>

                <label for="estado1">Estado:</label>
                <input type="text" id="estado1" name="estadoForn" placeholder="Estado" required>

                <input type="submit" name="submitBtn" value="Adicionar">
            </form>

            <div class="conteiner1">
                <?php foreach ($fornecedores as $fornecedor): 
                    $redirectToEditar = 'editarFornecedores.php?fornEmail=' . urlencode($fornecedor->getEmail());
                    $redirectToExcluir = 'gerenciarFornecedores.php?exclForn=' . urlencode($fornecedor->getEmail());
                ?>
                    <div class="c1">
                        <div class="c2">
                            <div class="d-flex flex-column">
                                <div id="dados">
                                    <h3 class="titulo px-3"><?php echo htmlspecialchars($fornecedor->getNome()); ?></h3>
                                    <div class="px-3">
                                        <p>Email: <?php echo htmlspecialchars($fornecedor->getEmail()); ?></p>
                                        <p>Celular: <?php echo htmlspecialchars($fornecedor->getTelefone()); ?></p>
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
            <button class="b-voltar m-auto border-0 rounded-4 fw-bold px-3">
                <a class="text-decoration-none color-black" href="pessoas.php">Voltar</a>
            </button>
        </div>
    </main>

    <?php include_once 'components/footer.php'; ?>

    <script src="script/adicionar.js"></script>
</body>
</html>
