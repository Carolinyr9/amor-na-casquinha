<?php foreach ($listaEstoque as $estoque): ?>
    <?php
        $produto = $produtoController->selecionarProdutoPorID($estoque->getIdProduto());
        $categoria = $produto ? $categoriaController->buscarCategoriaPorID($produto->getCategoria()) : null;

        $hoje = date('Y-m-d');
        $mensagem = '';

        if ($estoque->getDtVencimento() < $hoje) {
            $mensagem = 'Produto vencido!';
        } elseif ($estoque->getQuantidade() == 0) {
            $mensagem = 'Estoque zerado!';
        }
    ?>
    <tr <?= $mensagem ? 'style="background-color: #ffe0e0;"' : '' ?>>
        <td><?= $produto ? htmlspecialchars($produto->getNome()) : 'Produto Desativado' ?></td>
        <td><?= $categoria ? htmlspecialchars($categoria->getNome()) : 'Categoria Desativada' ?></td>
        <td><?= htmlspecialchars($estoque->getDtEntrada()) ?></td>
        <td><?= htmlspecialchars($estoque->getQuantidade()) ?></td>
        <td><?= htmlspecialchars($estoque->getDtFabricacao()) ?></td>
        <td><?= htmlspecialchars($estoque->getDtVencimento()) ?></td>
        <td><?= htmlspecialchars($estoque->getLote()) ?></td>
        <td><?= htmlspecialchars($estoque->getPrecoCompra()) ?></td>
        <td><?= htmlspecialchars($estoque->getQtdMinima()) ?></td>
        <td><?= htmlspecialchars($estoque->getQtdVendida()) ?></td>
        <td><?= htmlspecialchars($estoque->getOcorrencia()) ?></td>
        <td><?= htmlspecialchars($estoque->getQtdOcorrencia()) ?></td>
        <td>
            <?php if ($mensagem): ?>
                <span style="color: red; font-weight: bold;"><?= $mensagem ?></span>
                <a class="botao botao-primary" href="editarEstoque.php?idEstoque=<?= htmlspecialchars($estoque->getIdEstoque()) ?>">Adicionar Lote</a>
            <?php else: ?>
                <a class="botao botao-primary" href="editarEstoque.php?idEstoque=<?= htmlspecialchars($estoque->getIdEstoque()) ?>">Editar</a>
            <?php endif; ?>
        </td>
        <td>
            <a class="botao botao-alerta" href="excluirEstoque.php?idEstoque=<?= htmlspecialchars($estoque->getIdEstoque()) ?>">Excluir</a>
        </td>
    </tr>
<?php endforeach; ?>
