<?php
session_start();
require_once '../config/blockURLAccess.php';
require_once '../../vendor/autoload.php';
require_once '../utils/calcularFrete.php';

use app\controller2\CarrinhoController;
use app\controller2\PedidoController;
use app\controller2\ClienteController;
use app\controller2\EnderecoController;
use app\controller2\FreteController;

$carrinhoController = new CarrinhoController();
$clienteController = new ClienteController();
$pedidoController = new PedidoController();
$enderecoController = new EnderecoController();

$carrinhoController->atualizarCarrinho();

if(isset($_SESSION["userEmail"])){
    $clienteData = $clienteController->listarClientePorEmail($_SESSION["userEmail"]);
    $endereco = $enderecoController->listarEnderecoPorId($clienteData->getIdEndereco());
    
    $isDelivery = isset($_POST['isDelivery']) && $_POST['isDelivery'] === '1';
    $trocoPara = isset($_POST["trocoPara"]) ? $_POST["trocoPara"] : null;
    $trocoPara = is_numeric($trocoPara) ? floatval($trocoPara) : null;
    $subtotal = $carrinhoController->calcularTotal();
    list($frete, $total) = calcularFreteSeAplicavel($isDelivery, $endereco, $subtotal);
}


