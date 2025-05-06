<?php
use app\controller2\FreteController;

function calcularFreteSeAplicavel($isDelivery, $endereco, $subtotal) {
    $frete = 0.0;
    $totalComFrete = $subtotal;

    if ($isDelivery && $endereco && $endereco->getCep()) {
        $cep = $endereco->getCep();
        $freteController = new FreteController();
        $frete = $freteController->calcularFrete($cep);

        if (!is_numeric($frete)) {
            $isDelivery = false;
        } else {
            $totalComFrete += $frete;
        }
    }

    return [$frete, $totalComFrete];
}
