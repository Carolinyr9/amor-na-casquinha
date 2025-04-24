<?php
$paginaAtual = basename($_SERVER['SCRIPT_NAME']);
?>

<div class="c1 p-3 my-3 d-flex flex-column rounded-4">
    <div class="head-box d-flex flex-row justify-content-between">
        <div class="img-box" style="width: 40%; height: auto;">
            <img class="imagem m-2 rounded-4 w-100 h-auto" src="../images/<?= htmlspecialchars($produto->getFoto()) ?>" alt="<?= htmlspecialchars($produto->getNome()) ?>">
        </div>
        <div class="d-flex flex-column justify-content-center">
            <h3 class="titulo mt-2 fs-6 fw-bold pl-2 px-2 text-wrap"><?= htmlspecialchars($produto->getNome()) ?></h3>
            <div class="preco d-flex justify-content-end px-2 mt-3 pl-2">
                <span>R$ <?= htmlspecialchars($produto->getPreco()) ?></span>
            </div>
        </div>
    </div>
    <div class="botao text-center d-flex justify-content-evenly mt-3">
        <?php if ($paginaAtual === 'gerenciarProdutos.php'): ?>
            <?php 
                $redirectToExcluir = 'excluirSabor.php?produto=' . urlencode($produto->getId());
                $redirectToEditar  = 'editarSabor.php?idProduto=' . urlencode($produto->getId()) . '&idCategoria=' . urlencode($produto->getCategoria());
            ?>
            <button id="excl" class="rounded-3 border-0">
                <a class="text-decoration-none" href="<?= $redirectToExcluir ?>">Excluir</a>
            </button>                        
            <button id="edit" class="rounded-3 border-0">
                <a class="text-decoration-none" href="<?= $redirectToEditar ?>">Editar</a>
            </button>
        <?php else: ?>
            <button class="add border-0 rounded-4">
                <a class="text-decoration-none fs-5" href="carrinho.php?add=<?= urlencode($produto->getId()) ?>">Adicionar ao Carrinho</a>
            </button>
        <?php endif; ?>
    </div>
</div>
