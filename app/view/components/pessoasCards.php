<?php 

$paginaAtual = basename($_SERVER['SCRIPT_NAME']);
foreach ($listaPessoas as $pessoa): ?>
    <?php
        $email = $pessoa->getEmail();
        $nome = $pessoa->getNome();
        $telefone = $pessoa->getTelefone();

        switch ($paginaAtual) {
            case 'gerenciarFornecedores.php':
                $editarUrl = "editarFornecedores.php?fornEmail={$email}";
                $excluirUrl = "gerenciarFornecedores.php?exclForn={$email}";
                break;
            case 'gerenciarEntregadores.php':
                $editarUrl = "editarEntregadores.php?entrEmail={$email}";
                $excluirUrl = "gerenciarEntregadores.php?exclEntr={$email}";;
                break;
            case 'gerenciarFuncionarios.php':
                $editarUrl = "editarFuncionarios.php?funcEmail={$email}";
                $excluirUrl = "gerenciarFuncionarios.php?exclFunc={$email}";
                $perfilImg = $pessoa->getPerfil();
                break;
        }
       
    ?>
    <div class="cards-pessoa d-flex flex-column rounded-3 p-3">
        <div class="d-flex flex-column">
            <div>
                <h3 class="subtitulo"><?= $nome ?></h3>
                <div class="px-3">
                    <p>Email: <?= $email ?></p>
                    <p>Celular: <?= $telefone ?></p>

                   <?php if($paginaAtual === 'gerenciarEntregadores.php'): ?>
                        <p>CNH: <?= $pessoa->getCnh() ?></p>
                    <?php endif; ?>

                </div>
            </div>
        </div>
        <div class="d-flex justify-content-evenly mt-3">
            <a class="botao botao-primary" href="<?= $editarUrl ?>">Editar</a>
            <a class="botao botao-alerta" href="<?= $excluirUrl ?>">Excluir</a>
        </div>
    </div>
<?php endforeach; ?>