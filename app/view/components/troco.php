<div class="troco-div" id="trocoDiv" style="display: none;">
    <h4>Precisa de troco?</h4>
    <?php
    $valoresTroco = [5, 10, 20, 50, 100, 200, 500];
    foreach ($valoresTroco as $value):
        if ($total <= $value && $value <= $total * 5):
    ?>
            <div><input name="trocoPara" type="radio" value="<?= $value ?>"> Troco para <?= $value ?></div>
    <?php endif; endforeach; ?>
    <div><input name="trocoPara" type="radio" value="NULL"> Não preciso de troco</div>
</div>
