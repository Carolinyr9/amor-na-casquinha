<?php foreach ($listaEstoque as $estoque): ?>
    <?php
        $produto = $produtoController->selecionarProdutoPorID($estoque->getIdProduto());
        $categoria = $produto ? $categoriaController->buscarCategoriaPorID($produto->getCategoria()) : null;
    ?>
    <tr>
        <td><?= $produto ? htmlspecialchars($produto->getNome()) : 'Produto não encontrado' ?></td>
        <td><?= $categoria ? htmlspecialchars($categoria->getNome()) : 'Categoria não encontrada' ?></td>
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
        <td><a class="botao botao-primary" href="editarEstoque.php?idEstoque=<?= htmlspecialchars($estoque->getIdEstoque()) ?>">Editar</a></td>
        <td><a class="botao botao-alerta" href="excluirEstoque.php?idEstoque=<?= htmlspecialchars($estoque->getIdEstoque()) ?>">Excluir</a></td>
    </tr>
<?php endforeach; ?>
