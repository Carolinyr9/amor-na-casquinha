<div class="troco-div" id="trocoDiv" style="display: none;">
    <h4>Precisa de troco?</h4>
    <?php foreach ([5, 10, 20, 50, 100, 200, 500] as $value): ?>
        <?php if ($total >= $value): ?>
            <div><input name="trocoPara" type="radio" value="<?= $value ?>"> Troco para <?= $value ?></div>
        <?php endif; ?>
    <?php endforeach; ?>
    <div><input name="trocoPara" type="radio" value="NULL"> Não preciso de troco</div>
</div>
