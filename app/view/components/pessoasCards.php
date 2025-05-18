<?php foreach ($listaFuncionarios as $funcionario): ?>
    <?php
        $email = $funcionario->getEmail();
        $nome = $funcionario->getNome();
        $telefone = $funcionario->getTelefone();
        $perfilImg = $funcionario->getPerfil();
        $editarUrl = "editarFuncionarios.php?funcEmail={$email}";
        $excluirUrl = "gerenciarFuncionarios.php?exclFunc={$email}";
    ?>
    <div class="cards-pessoa d-flex flex-column rounded-3 p-3">
        <div class="d-flex flex-column">
            <div>
                <h3 class="subtitulo"><?= $nome ?></h3>
                <div class="px-3">
                    <p>Email: <?= $email ?></p>
                    <p>Celular: <?= $telefone ?></p>
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-evenly mt-3">
            <a class="botao botao-primary" href="<?= $editarUrl ?>">Editar</a>
            <a class="botao botao-alerta" href="<?= $excluirUrl ?>">Excluir</a>
        </div>
    </div>
<?php endforeach; ?>