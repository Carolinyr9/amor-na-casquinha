<?php
session_start();
require_once '../config/blockURLAccess.php';
require_once '../../vendor/autoload.php';
require_once '../utils/funcionario/editarFuncionarios.php';

use app\controller\FuncionarioController;
use app\controller\EnderecoController;

$func = new FuncionarioController();
$dadosFunc = null;
$enderecoController = new EnderecoController();

if (isset($_GET['funcEmail'])) {
    $dadosFunc = $func->buscarFuncionarioPorEmail($_GET['funcEmail']);

    if ($dadosFunc) {
        $endereco = $enderecoController->listarEnderecoPorId($dadosFunc->getEndereco());
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Funcionário</title>
    <?php include_once '../utils/links/styleLinks.php'; ?>
    <link rel="stylesheet" href="style/editarPessoas.css">
</head>
<body>
    <?php include_once 'components/header.php'; ?>

    <main>
        <section class="d-flex flex-column align-items-center justify-content-center">
            <h1 class="titulo">Editar Funcionário</h1>
            <div class="container-form container rounded-4 p-4">
                <?php if ($dadosFunc): ?>
                    <form action="editarFuncionarios.php" method="POST" class="m-auto">
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
                            <input type="text" id="rua" name="rua" class="form-control" placeholder="Rua" value="<?= htmlspecialchars($endereco->getRua()); ?>" required>
                        </div>

                        <div class="form-group mb-3">
                            <input type="text" id="numero" name="numero" class="form-control" placeholder="Número" value="<?= htmlspecialchars($endereco->getNumero()); ?>" required>
                        </div>

                        <div class="form-group mb-3">
                            <input type="text" id="complemento" name="complemento" class="form-control" placeholder="Complemento" value="<?= htmlspecialchars($endereco->getComplemento()); ?>">
                        </div>

                        <div class="form-group mb-3">
                            <input type="text" id="bairro" name="bairro" class="form-control" placeholder="Bairro" value="<?= htmlspecialchars($endereco->getBairro()); ?>" required>
                        </div>

                        <div class="form-group mb-3">
                            <input type="text" id="cidade" name="cidade" class="form-control" placeholder="Cidade" value="<?= htmlspecialchars($endereco->getCidade()); ?>" required>
                        </div>

                        <div class="form-group mb-3">
                            <input type="text" id="estado" name="estado" class="form-control" placeholder="Estado" value="<?= htmlspecialchars($endereco->getEstado()); ?>" required>
                        </div>

                        <div class="form-group mb-3">
                            <input type="text" id="cep" name="cep" class="form-control" placeholder="CEP" value="<?= htmlspecialchars($endereco->getCep()); ?>" pattern="\d{5}-\d{3}" title="Formato esperado: 12345-678" required>
                        </div>

                        <input type="hidden" name="idEndereco" value="<?= htmlspecialchars($endereco->getIdEndereco() ?? ''); ?>">

                        <div class="m-auto w-25 d-flex justify-content-center align-items-center"><input type="submit" value="Atualizar" name="btnAtualizar" class="botao botao-primary m-auto"></div>
                    </form>
                <?php else: ?>
                    <p class="text-danger">Funcionário não encontrado.</p>
                <?php endif; ?>
            </div>
            <a href="gerenciarFuncionarios.php" class="botao botao-secondary mt-4">Voltar</a>
        </section>
    </main>

    <?php include_once 'components/footer.php'; ?>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#telefone').mask('(00) 00000-0000');
            $('#cnpj').mask('00.000.000/0000-00');
            $('#cep').mask('00000-000');
        });
    </script>
</body>
</html>
