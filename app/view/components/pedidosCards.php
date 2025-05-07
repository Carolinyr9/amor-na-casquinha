<?php if (!empty($pedidos)): ?>
    <?php foreach ($pedidos as $pedido): 
        $redirectToInformacao = 'informacoesPedidoCliente.php?idPedido=' . $pedido->getIdPedido(); ?>
        <div class="cards-pedido d-flex align-items-center flex-column rounded-3 text-center p-3">
        <span class="fs-5 mb-4 fw-bold fst-italic">Número do Pedido: <?= htmlspecialchars($pedido->getIdPedido()); ?></span>
            <p><strong>Data do Pedido:</strong> <?= htmlspecialchars((new DateTime($pedido->getDtPedido()))->format('d/m/Y \à\s H:i')); ?></p>
            <p><strong>Total:</strong> R$ <?= number_format($pedido->getValorTotal(), 2, ',', '.'); ?></p>
            <p><strong>Tipo de Frete:</strong> <?= ($pedido->getTipoFrete() == 1 ? 'É para entrega!' : 'É para buscar na sorveteria!'); ?></p>
            <p><strong>Pagamento:</strong> <?= htmlspecialchars($pedido->getMeioPagamento()); ?></p>
            <p><strong>Status:</strong> <?= htmlspecialchars($pedido->getStatusPedido()); ?></p>
            
            <a href="<?= $redirectToInformacao; ?>" class="botao botao-primary">Ver Informações</a>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p>Nenhum pedido encontrado.</p>
<?php endif; ?>
