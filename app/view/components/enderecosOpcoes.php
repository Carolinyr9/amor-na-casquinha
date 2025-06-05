<?php foreach ($enderecos as $endereco): ?>
    <option value="<?= htmlspecialchars($endereco->getIdEndereco()) ?>">
        <?= htmlspecialchars($endereco->getRua()) ?>
        <?= $endereco->getNumero() ? ', ' . htmlspecialchars($endereco->getNumero()) : '' ?>
    </option>
<?php endforeach; ?>
