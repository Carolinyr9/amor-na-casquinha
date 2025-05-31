<?php 
foreach ($clientes as $cliente): ?>
    <option value="<?= htmlspecialchars($cliente->getId()) ?>"><?= htmlspecialchars($cliente->getNome()) ?></option>
<?php endforeach; ?>
