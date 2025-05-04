<?php
$paginaAtual = basename($_SERVER['SCRIPT_NAME']);
?>

<div class="cards-produtos d-flex flex-column justify-content-between align-items-center p-3 my-3 h-auto rounded-4" title="<?= htmlspecialchars($produto->getNome()) ?>">

    <div class="d-flex flex-column justify-content-center align-items-center gap-2 w-100" title="<?= htmlspecialchars($produto->getNome()) ?>">
        <picture class="h-auto d-flex justify-content-center align-items-center"><img src="../images/<?= htmlspecialchars($produto->getFoto()) ?>" alt="<?= htmlspecialchars($produto->getNome()) ?>"></picture>
        
        
        <p class="cards-titulo w-75" title="<?= htmlspecialchars($produto->getNome()) ?>"><?= htmlspecialchars($produto->getNome()) ?></p>
        
        <span>R$ <?= htmlspecialchars($produto->getPreco()) ?></span>
    </div>

    <div class="text-center d-flex justify-content-evenly mt-3">
        <?php if ($paginaAtual === 'gerenciarProdutos.php'): ?>
            <?php 
                $redirectToExcluir = 'excluirSabor.php?idProduto=' . urlencode($produto->getId()) . '&idCategoria=' . urlencode($produto->getCategoria());
                $redirectToEditar  = 'editarSabor.php?idProduto=' . urlencode($produto->getId()) . '&idCategoria=' . urlencode($produto->getCategoria());
            ?>
            <button id="excl" class="rounded-3 border-0">
                <a class="text-decoration-none" href="<?= $redirectToExcluir ?>">Excluir</a>
            </button>                        
            <button id="edit" class="rounded-3 border-0">
                <a class="text-decoration-none" href="<?= $redirectToEditar ?>">Editar</a>
            </button>
        <?php else: ?>
            <a class="botao botao-primary" href="carrinho.php?add=<?= urlencode($produto->getId()) ?>">Adicionar ao Carrinho</a>
        <?php endif; ?>
    </div>
</div>
