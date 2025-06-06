<?php foreach ($produtos as $produto): 
    $id = htmlspecialchars($produto->getId());
    $nome = htmlspecialchars($produto->getNome());
?>
    <div class="produto-item">
        <input 
            type="checkbox" 
            id="produto<?= $id ?>" 
            name="produtosSelecionados[]" 
            value="<?= $id ?>" 
            onchange="toggleQuantidade(<?= $id ?>)"
        >
        <label for="produto<?= $id ?>"><?= $nome ?></label>

        <div id="quantidade-wrapper-<?= $id ?>" style="display:none; margin-top: 5px;">
            <button type="button" onclick="alterarQuantidade(<?= $id ?>, -1)">−</button>
            <input 
                type="number" 
                name="quantidades[<?= $id ?>]" 
                id="quantidade-<?= $id ?>" 
                value="1" 
                min="1" 
                style="width: 50px; text-align: center;"
            >
            <button type="button" onclick="alterarQuantidade(<?= $id ?>, 1)">+</button>
        </div>
    </div>
<?php endforeach; ?>
