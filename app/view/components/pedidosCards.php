<?php if (empty($pedidos)): ?>
    <p>Nenhum pedido encontrado.</p>
<?php else: ?>
    <?php foreach ($pedidos as $pedido):
        $redirectToInformacao = 'informacoesPedidoCliente.php?idPedido=' . $pedido['idPedido'];?>
        <div class="cards-pedido d-flex align-items-center flex-column rounded-3 text-center p-3">
            <span class="fs-5 mb-4 fw-bold fst-italic">Número do Pedido: <?= htmlspecialchars($pedido['idPedido']); ?></span>
            <p><strong>Data do Pedido:</strong> <?= htmlspecialchars((new DateTime($pedido['dtPedido']))->format('d/m/Y \à\s H:i')); ?></p>
            <p><strong>Total:</strong> R$ <?= number_format($pedido['valorTotal'], 2, ',', '.'); ?></p>
            <p><strong>Tipo de Frete:</strong> <?= ($pedido['tipoFrete'] == 1 ? 'É para entrega!' : 'É para buscar na sorveteria!'); ?></p>
            <p><strong>Pagamento:</strong> <?= htmlspecialchars($pedido['meioPagamento']); ?></p>
            <p><strong>Status:</strong> <?= htmlspecialchars($pedido['statusPedido']); ?></p>

            <a href="<?= $redirectToInformacao; ?>" class="botao botao-primary">Ver Informações</a>
        </div>
    <?php endforeach; ?>
<?php endif; ?>