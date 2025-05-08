<?php
session_start();
require_once '../config/blockURLAccess.php';
require_once '../../vendor/autoload.php';
require_once 'calcularFrete.php';

use app\controller\CarrinhoController;
use app\controller\PedidoController;
use app\controller\ClienteController;
use app\controller\EnderecoController;
use app\controller\FreteController;

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


