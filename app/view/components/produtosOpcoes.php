<?php foreach ($produtos as $produto): 
    $id = htmlspecialchars($produto->getId());
    $nome = htmlspecialchars($produto->getNome());
?>
    <div class="d-flex flex-column">
        <div>
            <input
                type="checkbox"
                id="produto<?= $id ?>"
                name="produtosSelecionados[]"
                value="<?= $id ?>"
                onchange="toggleQuantidade(<?= $id ?>)"
            >
            <label for="produto<?= $id ?>"><?= $nome ?></label>
        </div>

        <div id="quantidade-wrapper-<?= $id ?>" style="display:none;" class="mt-2 mb-4">
            <button type="button" onclick="alterarQuantidade(<?= $id ?>, -1)" class="botao botao-primary">−</button>
            <input 
                type="number" 
                name="quantidades[<?= $id ?>]" 
                id="quantidade-<?= $id ?>" 
                value="1" 
                min="1" 
                style="width: 50px; text-align: center;"
            >
            <button type="button" onclick="alterarQuantidade(<?= $id ?>, 1)" class="botao botao-primary">+</button>
        </div>
    </div>
<?php endforeach; ?>
