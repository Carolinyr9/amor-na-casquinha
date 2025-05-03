<?php
$paginaAtual = basename($_SERVER['SCRIPT_NAME']);
?>

<div class="cards d-flex flex-row justify-content-between align-items-center p-3 my-3 h-auto rounded-4">
        <picture class="h-auto d-flex justify-content-center align-items-center">
            <img src="../images/<?= htmlspecialchars($categoria->getFoto()) ?>" alt="<?= htmlspecialchars($categoria->getNome()) ?>" class="img-fluid rounded">
        </picture>
        <div class="d-flex flex-column justify-content-center align-items-center gap-2">

            <?php if ($paginaAtual === 'gerenciarCategorias.php'): ?>
                <?php
                    $id = $categoria->getId();
                    $redirectToVariacao = 'gerenciarProdutos.php?categoria=' . urlencode($id);
                    $redirectToEditar = 'editarCategoria.php?categoria=' . urlencode($id);
                    $redirectToExcluir = 'excluirCategorias.php?categoria=' . urlencode($id);
                ?>
                <a class="btn btn-primary" href="<?= htmlspecialchars($redirectToVariacao) ?>">Ver Sabores</a>     
                <a class="btn btn-secondary" href="<?= htmlspecialchars($redirectToEditar) ?>">Editar</a>      
                <a class="btn btn-alerta" href="<?= htmlspecialchars($redirectToExcluir) ?>">Excluir</a>
            <?php else: ?>
                <h4 class="text-center"><?= htmlspecialchars($categoria->getNome()) ?></h4>
                <a class="btn btn-primary" href="sabores.php?categoria=<?= htmlspecialchars($categoria->getId()) ?>">ver</a>
            <?php endif; ?>

        </div>
</div>


