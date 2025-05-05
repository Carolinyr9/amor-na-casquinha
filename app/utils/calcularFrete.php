<?php
use app\controller2\FreteController;

$isDelivery = isset($_POST['isDelivery']) && $_POST['isDelivery'] === '1';

if ($isDelivery && $endereco && $endereco->getCep()) {
    $cep = $endereco->getCep();
    $freteController = new FreteController();
    $frete = $freteController->calcularFrete($cep);

    if (!is_numeric($frete)) {
        $isDelivery = 0;
    } else {
        $totalComFrete += $frete;
    }
}