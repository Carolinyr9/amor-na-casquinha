<?php foreach ($produtos as $produto): ?>
    <input 
        type="checkbox" 
        id="produto<?= htmlspecialchars($produto->getId()) ?>" 
        name="produtosSelecionados[]" 
        value="<?= htmlspecialchars($produto->getId()) ?>"
    >
    <label for="produto<?= htmlspecialchars($produto->getId()) ?>">
        <?= htmlspecialchars($produto->getNome()) ?>
    </label><br>
<?php endforeach; ?>