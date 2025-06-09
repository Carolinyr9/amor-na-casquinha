<?php
require_once '../utils/cliente/inicializarPerfil.php';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil</title>
    <?php include_once '../utils/links/styleLinks.php'; ?>
    <link rel="stylesheet" href="style/perfil.css">
    <script src="script/exibirFormulario.js"></script>
</head>
<body>
    <?php include_once 'components/header.php'; ?>
    <main>
        <section>
            <h1 class="titulo">Perfil</h1>
            <div class="container-section container d-flex align-items-center flex-column text-center rounded-4 p-4 my-3">
                <div  class="fechar"><span>x</span></div>
                    <div id="dadosPerfil" class="w-50">
                        <p class="fs-5">Nome: <?= htmlspecialchars($clienteData->getNome()); ?></p>
                        <p class="fs-5">Email: <?= htmlspecialchars($clienteData->getEmail()); ?></p>
                        <p class="fs-5">Telefone: <?= htmlspecialchars($clienteData->getTelefone()); ?></p>
                        <p class="fs-5">Endereço: </p>
                        <?php include 'components/enderecoCard.php'; ?>
                        <button id="editPerfil" class="botao botao-secondary">Editar</button>
                    </div>
                    <form action="" class="formEditar flex-column justify-content-center w-auto" method="POST" id="formularioPerfil">
                            <p class="subtitulo">Usuário</p>
                            <div class="d-flex flex-row flex-wrap justify-content-center gap-4 mb-4">
                                <div class="form-group">
                                    <input type="text" name="nome" class="form-control" placeholder="Nome" value="<?= htmlspecialchars($clienteData->getNome() ?? ''); ?>" >
                                </div>
                                <div class="form-group">
                                    <input type="email" class="form-control" name="email" placeholder="Email" value="<?= htmlspecialchars($clienteData->getEmail() ?? ''); ?>">
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="telefone" placeholder="Telefone" pattern="^\(\d{2}\) \d{4,5}-\d{4}$" title="O telefone deve seguir o formato (XX) XXXX-XXXX ou (XX) XXXXX-XXXX" maxlength="15" value="<?= htmlspecialchars($clienteData->getTelefone() ?? ''); ?>" >
                                </div>
                            </div>

                            <span class="subtitulo mb-3">Endereço</span>
                            <div class="d-flex flex-row flex-wrap justify-content-center gap-4">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="rua" placeholder="Rua" value="<?= htmlspecialchars($endereco->getRua() ?? ''); ?>" pattern="^[A-Za-zÀ-ÿ\s]+$" title="A rua deve conter apenas letras e espaços" maxlength="50" required>
                                </div>
                                <div class="form-group">
                                    <input type="number" class="form-control" value="<?= htmlspecialchars($endereco->getNumero() ?? ''); ?>" name="numero" placeholder="Número" min="1" required>
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" value="<?= htmlspecialchars($endereco->getComplemento() ?? ''); ?>" name="complemento" placeholder="Complemento" maxlength="50">
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="cep" value="<?= htmlspecialchars($endereco->getCep() ?? ''); ?>" placeholder="CEP" pattern="^\d{5}-\d{3}$" title="O CEP deve seguir o formato XXXXX-XXX" required>
                                </div>
                                
                                <div class="form-group">
                                    <input type="text" class="form-control" name="bairro" value="<?= htmlspecialchars($endereco->getBairro() ?? ''); ?>" placeholder="Bairro" pattern="^[A-Za-zÀ-ÿ\s]+$" title="O bairro deve conter apenas letras e espaços" maxlength="50" required>
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="cidade" value="<?= htmlspecialchars($endereco->getCidade() ?? ''); ?>" placeholder="Cidade" pattern="^[A-Za-zÀ-ÿ\s]+$" title="A cidade deve conter apenas letras e espaços" maxlength="50" required>
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="estado" value="<?= htmlspecialchars($endereco->getEstado() ?? ''); ?>" placeholder="Estado" pattern="^[A-Za-zÀ-ÿ\s]+$" title="O estado deve conter apenas letras e espaços" maxlength="50" required>
                                </div>
                            </div>

                            <input type="hidden" name="idEndereco" value="<?= htmlspecialchars($endereco->getIdEndereco() ?? ''); ?>">
                            <button type="submit" name="btnAlterarSenha" class="botao botao-primary mt-4" style="width: 100px;">Salvar</button>
                    </form>
            </div>
        </section>

        <section>
            <h1 class="subtitulo">Alterar Senha</h1>
            <div class="container-section container d-flex align-items-center flex-column text-center rounded-4 p-4 my-3">
                
                <form action="" class="formEditarSenha" method="POST" id="formularioSenha">
                        <p class="subtitulo">Confirme sua senha</p>
                        <div class="d-flex flex-row flex-wrap justify-content-center gap-4 mb-4">
                            <div class="form-group">
                                <input type="password" name="senhaAtual" class="form-control" placeholder="Senha atual" required>
                            </div>
                            <div class="form-group">
                                <input class="form-control" type="password" id="senhaNova" name="senhaNova" placeholder="Senha nova" pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}" title="A senha deve conter pelo menos 8 caracteres, incluindo uma letra maiúscula, uma minúscula, um número e um caractere especial." required>
                            </div>

                            
                        </div>
                        <input type="hidden" name="idEndereco" value="<?= htmlspecialchars($clienteData->getId() ?? ''); ?>">  
                        <button type="submit" name="btnAlterarCliente" class="botao botao-primary mt-4" style="width: 100px;">Salvar</button>
                </form>
            </div>
        </section>

        <section>
            <h1 class="titulo">Meus pedidos</h1>
            <div class="container d-flex flex-row flex-wrap justify-content-center gap-5 mt-5"><?php include 'components/pedidosCards.php'; ?></div>
        </section>

        <section>
            <h1 class="titulo">Excluir perfil</h1>
            <div class="container-section container d-flex align-items-center flex-column text-center rounded-4 p-4 my-3">
                <p class="fs-5">Se você deseja excluir seu perfil, clique no botão abaixo. Esta ação é irreversível.</p>
                <form action="" method="POST" class="formExcluirPerfil" id="formularioPerfil">
                    <?php foreach ($pedidos as $pedido): ?>
                        <input type="hidden" name="statusPedidos[]" value="<?= $pedido->getStatusPedido(); ?>">
                    <?php endforeach; ?>
                    <input type="hidden" name="emailCliente" value="<?= $clienteData->getEmail(); ?>">
                    <button type="submit" name="btnExcluirPerfil" class="botao botao-alerta mt-4">Excluir</button>
                </form>
        </section>
    </main>
    <?php include_once 'components/footer.php'; ?>
</body>
</html>
