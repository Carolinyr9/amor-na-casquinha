<?php
$nomePaginaAtual = basename($_SERVER['SCRIPT_NAME']);

if (!empty($pedidos)): ?>
    <?php foreach ($pedidos as $pedido): ?>
        <?php if ($nomePaginaAtual === 'pedidosEntregador.php'): ?>
            <?php if (!in_array($pedido->getStatusPedido(), ['Concluído', 'Cancelado']) && $pedido->getTipoFrete() == 1): ?>
                <div class="cards-pedido d-flex align-items-center flex-column rounded-3 text-center p-3">
                    <h3 class="subtitulo mt-3">Número do Pedido: <?= htmlspecialchars($pedido->getIdPedido()) ?></h3>
                    <p>Realizado em: <?= htmlspecialchars((new DateTime($pedido->getDtPedido()))->format('d/m/Y \à\s H:i')) ?></p>
                    <p>Total: R$ <?= number_format($pedido->getValorTotal(), 2, ',', '.') ?></p>
                    <p>Status: <?= htmlspecialchars($pedido->getStatusPedido()) ?></p>

                    <a href="rotasEntregador.php?idEndereco=<?= htmlspecialchars($pedido->getIdEndereco()) ?>" class="botao botao-primary mt-3 rounded-3 w-50 m-auto mb-3">
                        Ver Rotas
                    </a>

                    <?php if ($pedido->getStatusPedido() === 'Aguardando Envio'): ?>
                        <form method="POST" class="mb-2">
                            <input type="hidden" name="idPedido" value="<?= htmlspecialchars($pedido->getIdPedido()) ?>">
                            <input type="hidden" name="mudarStatus" value="Entrega Falhou">
                            <button type="submit" class="botao botao-alerta mt-2">
                                Entrega Falhou
                            </button>
                        </form>

                        <form method="POST">
                            <input type="hidden" name="idPedido" value="<?= htmlspecialchars($pedido->getIdPedido()) ?>">
                            <input type="hidden" name="mudarStatus" value="Entregue">
                            <button type="submit" class="botao botao-secondary mt-2">
                                Entregue
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php else: ?>
     
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

                    <?php if ($nomePaginaAtual === 'pedidos.php' && ($pedido->getTipoFrete() == 1 && $pedido->getIdEntregador() == NULL)): ?>
                                <a class="card__btn--Entregador mt-3 text-decoration-none text-black" href="atribuirEntregador.php?idPedido=<?= $pedido->getIdPedido(); ?>">Atribuir Entregador ao Pedido</a>
                    <?php endif; ?>

                </div>

        <?php endif; ?>

    <?php endforeach; ?>
<?php else: ?>
    <p>Nenhum pedido encontrado.</p>
<?php endif; ?>

