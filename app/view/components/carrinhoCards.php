<?php if (!empty($produtos)): ?>
    <?php foreach ($produtos as $produto): ?>
        <div class="borders my-3 py-3 card-width">
            <div class="row">
                <div class="col col-4 c2 d-flex align-items-center justify-content-center">
                    <img src="../images/<?= $produto['foto'] ?>" alt="<?= $produto['nome'] ?>" class="imagem">
                </div>
                <div class="col c3">
                    <h3><?= $produto['nome'] ?></h3>
                    <div class="preco d-flex flex-row justify-content-between px-2">
                        <p>Preço</p>
                        <span>R$ <?= $produto['preco'] ?></span>
                    </div>
                </div>
            </div>

            <form method="post" action="carrinho.php" class="botao text-center d-flex justify-content-evenly mt-3 flex-row">
                <input type="hidden" name="id" value="<?= $produto['id'] ?>">
                <div class="col col-3 d-flex align-items-start">
                    <a href="?action=remove&item=<?= $produto['id'] ?>" class="btn-excluir rounded-3 text-decoration-none">Excluir</a>
                </div>
                <div class="col d-flex align-items-start col-7" style="margin-left: 13px;">
                    <p>Quantid.</p>
                    <select class="ms-2 border-0" name="quantidade" onchange="this.form.submit()">
                        <?= $produto['quantidades'] ?>
                    </select>
                </div>
            </form>
        </div>
    <?php endforeach; ?>

    <div class="d-flex flex-row justify-content-between w-75 my-3">
        <h4>Total</h4>
        <p id="totalValue">R$ <?= $total ?></p>
    </div>

    <form method="post" action="notaFiscal.php" class="d-flex flex-column align-items-center">
        <input type="hidden" name="cart" value="1">
        <input type="hidden" name="total" value="<?= htmlspecialchars($total); ?>">
        <input class="btn-concluir fs-5 rounded-4" type="submit" value="Concluir"/>
    </form>

<?php else: ?>
    <p>Carrinho está vazio!</p>
<?php endif; ?>
