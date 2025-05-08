<?php if (!empty($produtos)): ?>
    <?php foreach ($produtos as $produto): ?>
        <div class="cards-carrinho d-flex flex-row align-items-center my-3 py-3 w-50">

            <picture class="d-flex align-items-center justify-content-center w-25">
                <img src="../images/<?= $produto['foto'] ?>" alt="<?= $produto['nome'] ?>" class="img-carrinho">
            </picture>                
                <div class="header-carrinho d-flex flex-column justify-content-justify w-50">
                    <span class="cards-titulo"><?= $produto['nome'] ?></span>
                    <form method="post" action="" class="form-carrinho d-flex justify-content-between align-items-center gap-4 mt-3 flex-row w-75">
                        <input type="hidden" name="id" value="<?= $produto['id'] ?>">
                        <div class="d-flex align-items-start">
                            <a href="?action=remove&item=<?= $produto['id'] ?>" class="botao botao-alerta">Excluir</a>
                        </div>
                        <div class="carrinho-select d-flex justify-content-between align-items-center border px-3 rounded-3">
                            <span class="menos-quantidade pointer text-danger fs-5">-</span>
                            <span class="quantidade fs-6"><?= $produto['qntd'] ?></span>
                            <span class="mais-quantidade pointer text-danger fs-5">+</span>
                            <input type="hidden" name="quantidade" value="0">
                        </div>
                    </form>

                </div>

                <div class="preco d-flex flex-row justify-content-between px-2">
                    <span>R$ <?= $produto['preco'] ?></span>
                </div>
        </div>
    <?php endforeach; ?>

    <div class="d-flex flex-column justify-content-between align-items-center w-75 my-4">
        <div class="d-flex flex-row justify-content-between w-50">
            <h4>Total</h4>
            <p id="totalValue">R$ <?= $total ?></p>
        </div>
        <form method="post" action="notaFiscal.php" class="d-flex flex-column align-items-center mt-3">
            <input type="hidden" name="cart" value="1">
            <input type="hidden" name="total" value="<?= htmlspecialchars($total); ?>">
            <input class="botao botao-primary fs-5 rounded-4" type="submit" value="Concluir"/>
        </form>
    </div>

<?php else: ?>
    <p>Carrinho está vazio!</p>
<?php endif; ?>
