<?php
$paginaAtual = basename($_SERVER['SCRIPT_NAME']);

if (!empty($pedidos)): ?>
    <?php foreach ($pedidos as $pedido): 
        $redirectToInformacao = ($paginaAtual === 'pedidos.php') ? 
                'informacoesPedido.php?idPedido='.$pedido->getIdPedido() : 
                'informacoesPedidoCliente.php?idPedido=' . $pedido->getIdPedido(); ?>
        <div class="cards-pedido d-flex align-items-center flex-column rounded-3 text-center p-3">
            <span class="fs-5 mb-4 fw-bold fst-italic">Número do Pedido: <?= htmlspecialchars($pedido->getIdPedido()); ?></span>
            <p><strong>Data do Pedido:</strong> <?= htmlspecialchars((new DateTime($pedido->getDtPedido()))->format('d/m/Y \à\s H:i')); ?></p>
            <p><strong>Total:</strong> R$ <?= number_format($pedido->getValorTotal(), 2, ',', '.'); ?></p>
            <p><strong>Tipo de Frete:</strong> <?= ($pedido->getTipoFrete() == 1 ? 'É para entrega!' : 'É para buscar na sorveteria!'); ?></p>
            <p><strong>Pagamento:</strong> <?= htmlspecialchars($pedido->getMeioPagamento()); ?></p>
            <p><strong>Status:</strong> <?= htmlspecialchars($pedido->getStatusPedido()); ?></p>
            
            <a href="<?= $redirectToInformacao; ?>" class="botao botao-primary">Ver Informações</a>
            <?php if ($paginaAtual === 'pedidos.php' && ($pedido->getTipoFrete() == 1 && $pedido->getIdEntregador() == NULL)): ?>
                        <a class="card__btn--Entregador mt-3 text-decoration-none text-black" href="atribuirEntregador.php?idPedido=<?= $pedido->getIdPedido(); ?>">Atribuir Entregador ao Pedido</a>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p>Nenhum pedido encontrado.</p>
<?php endif; ?>

