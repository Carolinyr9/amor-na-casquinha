<div class="c1 d-flex flex-column rounded-4">
    <div class="c2">
        <div>
            <img src="../images/<?= htmlspecialchars($produto->getFoto()) ?>" alt="<?= htmlspecialchars($produto->getNome()) ?>" class="imagem">
        </div>
        <div class="c3">
            <h3 class="titulo px-2"><?= htmlspecialchars($produto->getNome()) ?></h3>
            <div class="preco d-flex flex-row justify-content-between px-2">
                <p>Preço</p>
                <span>R$ <?= htmlspecialchars($produto->getPreco()) ?></span>
            </div>
        </div>
    </div>
    <!-- quero adicionar um jeito de tirar esse trecho daqui, para que o funcionario possa reutilizar esse componente com outras opcoes!-->
    <div class="botao text-center d-flex justify-content-evenly mt-3">
        <button class="add border-0 rounded-4">
            <a class="text-decoration-none fs-5" href="carrinho.php?add=<?= htmlspecialchars($produto->getId()) ?>">Adicionar ao Carrinho</a>
        </button>
    </div>
</div>
