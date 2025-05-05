<div id="addressDiv">
    <p>
        <?= 
            htmlspecialchars($endereco->getRua()) . ', ' .
            htmlspecialchars($endereco->getNumero()) . ', ' .
            (!empty($endereco->getComplemento()) ? htmlspecialchars($endereco->getComplemento()) . ', ' : '') .
            htmlspecialchars($endereco->getBairro()) . ', ' .
            htmlspecialchars($endereco->getCidade()) . ', ' .
            htmlspecialchars($endereco->getEstado()) . ', ' .
            htmlspecialchars($endereco->getCep());
        ?>
    </p>
</div>