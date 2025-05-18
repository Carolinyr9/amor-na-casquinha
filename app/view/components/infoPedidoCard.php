<?php if ($pedido): ?>
<div class="w-100 d-flex justify-content-center blue m-auto rounded-5 py-3">
    <div class="w-100">
        <h3 class="subtitulo">Número do Pedido: <?= htmlspecialchars($pedido->getIdPedido()) ?></h3>
        <p>Realizado em: <?= htmlspecialchars((new DateTime($pedido->getDtPedido()))->format('d/m/Y \à\s H:i')) ?></p>
        <p>Total: R$ <?= number_format($pedido->getValorTotal(), 2, ',', '.') ?></p>
        <p><?= $pedido->getTipoFrete() == 1 ? 'É para entrega!' : 'É para buscar na sorveteria!' ?></p>
        <p>Status: <?= htmlspecialchars($pedido->getStatusPedido()) ?></p>
        <p>Meio de Pagamento: <?= htmlspecialchars($pedido->getMeioPagamento()) ?></p>
    </div>
</div>
<?php endif; ?>
