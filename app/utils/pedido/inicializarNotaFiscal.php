<?php
session_start();
require_once '../config/blockURLAccess.php';
require_once '../../vendor/autoload.php';

use app\controller\CarrinhoController;
use app\controller\PedidoController;
use app\controller\ClienteController;
use app\controller\EnderecoController;
use app\controller\FreteController;

$carrinhoController = new CarrinhoController();
$clienteController = new ClienteController();
$pedidoController = new PedidoController();
$enderecoController = new EnderecoController();
$freteController = new FreteController();

$carrinhoController->atualizarCarrinho();

if (isset($_SESSION["userEmail"])) {
    $clienteData = $clienteController->listarClientePorEmail($_SESSION["userEmail"]);
    $endereco = $enderecoController->listarEnderecoPorId($clienteData->getIdEndereco());

    $isDelivery = isset($_POST['isDelivery']) && $_POST['isDelivery'] === '1';
    $trocoPara = isset($_POST["trocoPara"]) ? $_POST["trocoPara"] : null;
    $trocoPara = is_numeric($trocoPara) ? floatval($trocoPara) : null;
    $subtotal = $carrinhoController->calcularTotal();

    if ($isDelivery && $endereco !== null) {
        $frete = $freteController->calcularFrete($endereco->getCep());
        $freteValor = $frete['valor'];
        $freteDescricao = $frete['descricao'] ?? '';
    } else {
        $freteValor = 0.00;
        $freteDescricao = 'Retirada no local';
    }

    $total = $subtotal + $freteValor;
}
