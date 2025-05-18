<?php if ($produtos): ?>
<div class="d-flex justify-content-center w-100 m-auto py-3">
    <div>
        <h3 class="subtitulo">Itens do Pedido</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Quantidade</th>
                    <th>Preço</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($produtos as $itens): 
                    $produto = $produtoController->selecionarProdutoPorID($itens->getIdProduto()); ?>
                    <tr>
                        <td><?= htmlspecialchars($produto->getNome()) ?></td>
                        <td><?= htmlspecialchars($itens->getQuantidade()) ?></td>
                        <td>R$ <?= number_format($produto->getPreco(), 2, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>
