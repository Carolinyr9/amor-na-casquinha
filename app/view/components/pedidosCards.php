<?php if (!empty($pedidos)): ?>
    <?php foreach ($pedidos as $pedido): 
        $redirectToInformacao = 'informacoesPedidoCliente.php?idPedido=' . $pedido->getIdPedido(); ?>
        <div class="conteiner-pedidos rounded-4 text-center d-flex align-items-center flex-column w-75 p-4 my-3">
            <h5 class="titulo">Número do Pedido: <?= htmlspecialchars($pedido->getIdPedido()); ?></h5>
            <p><strong>Data do Pedido:</strong> <?= htmlspecialchars((new DateTime($pedido->getDtPedido()))->format('d/m/Y \à\s H:i')); ?></p>
            <p><strong>Total:</strong> R$ <?= number_format($pedido->getValorTotal(), 2, ',', '.'); ?></p>
            <p><strong>Tipo de Frete:</strong> <?= ($pedido->getTipoFrete() == 1 ? 'É para entrega!' : 'É para buscar na sorveteria!'); ?></p>
            <p><strong>Pagamento:</strong> <?= htmlspecialchars($pedido->getMeioPagamento()); ?></p>
            <p><strong>Status:</strong> <?= htmlspecialchars($pedido->getStatusPedido()); ?></p>
            
            <button class="btnVerInfos mt-3"><a href="<?= $redirectToInformacao; ?>">Ver Informações</a></button>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p>Nenhum pedido encontrado.</p>
<?php endif; ?>
