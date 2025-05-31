<?php
$paginaAtual = basename($_SERVER['SCRIPT_NAME']);
?>

<div class="cards-categoria d-flex flex-row justify-content-justify align-items-center p-3 my-3 h-auto rounded-4 w-md-75">
        <picture class="h-auto d-flex justify-content-center align-items-center mx-2">
            <img src="../images/<?= htmlspecialchars($categoria->getFoto()) ?>" alt="<?= htmlspecialchars($categoria->getNome()) ?>">
        </picture>

        <div class="d-flex flex-column justify-content-center align-items-center gap-2">

            <?php if ($paginaAtual === 'gerenciarCategorias.php'): ?>
                <?php
                    $id = $categoria->getId();
                    $redirectToVariacao = 'gerenciarProdutos.php?categoria=' . urlencode($id);
                    $redirectToEditar = 'editarCategoria.php?categoria=' . urlencode($id);
                    $redirectToExcluir = 'excluirCategorias.php?categoria=' . urlencode($id);
                ?>
                <p class="cards-titulo w-75 text-center" title="<?= htmlspecialchars($categoria->getNome()) ?>"><?= htmlspecialchars($categoria->getNome()) ?></p>
                <p class="cards-titulo w-75 text-center" title="<?= htmlspecialchars($categoria->getId()) ?>"><?= htmlspecialchars($categoria->getId()) ?></p>
                <a class="botao botao-primary" href="<?= htmlspecialchars($redirectToVariacao) ?>">Ver Sabores</a>     
                <a class="botao botao-secondary" href="<?= htmlspecialchars($redirectToEditar) ?>">Editar</a>      
                <a class="botao botao-alerta" href="<?= htmlspecialchars($redirectToExcluir) ?>">Excluir</a>
            <?php else: ?>
                <h4 class="cards-titulo fs-5"><?= htmlspecialchars($categoria->getNome()) ?></h4>
                <a class="botao botao-primary" href="sabores.php?categoria=<?= htmlspecialchars($categoria->getId()) ?>">ver</a>
            <?php endif; ?>

        </div>
</div>


