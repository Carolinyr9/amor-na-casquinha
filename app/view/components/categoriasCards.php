<?php
$paginaAtual = basename($_SERVER['SCRIPT_NAME']);
?>

<div class="produto p-2 rounded-4 my-3 d-flex justify-content-center w-25">
    <div class="produto__card container d-flex flex-row justify-content-between align-items-center">
        
        <picture class="w-50">
            <img src="../images/<?= htmlspecialchars($categoria->getFoto()) ?>" alt="<?= htmlspecialchars($categoria->getNome()) ?>" class="img-fluid rounded">
        </picture>
        <div class="card__botao d-flex flex-column justify-content-center align-items-center w-50">

            <?php if ($paginaAtual === 'gerenciarCategorias.php'): ?>
                <?php
                    $id = $categoria->getId();
                    $redirectToVariacao = 'gerenciarProdutos.php?categoria=' . urlencode($id);
                    $redirectToEditar = 'editarCategoria.php?categoria=' . urlencode($id);
                    $redirectToExcluir = 'excluirCategorias.php?categoria=' . urlencode($id);
                ?>
                <a class="botao__link--verSabores text-decoration-none border-0 rounded-3 m-1 text-black" href="<?= htmlspecialchars($redirectToVariacao) ?>">Ver Sabores</a>     
                <a class="botao__link--editar text-decoration-none border-0 rounded-3 m-1 text-black" href="<?= htmlspecialchars($redirectToEditar) ?>">Editar</a>      
                <a class="botao__link--excluir text-decoration-none border-0 rounded-3 m-1 text-black" href="<?= htmlspecialchars($redirectToExcluir) ?>">Excluir</a>
            <?php else: ?>
                <h4 class="text-center"><?= htmlspecialchars($categoria->getNome()) ?></h4>
                <button class="border-0 rounded-4 fw-bold m-1"><a class="text-decoration-none text-body" href="sabores.php?categoria=<?= htmlspecialchars($categoria->getId()) ?>">ver</a></button>
            <?php endif; ?>

        </div>
    </div>
</div>


