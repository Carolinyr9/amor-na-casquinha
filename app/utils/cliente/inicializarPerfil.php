<?php
session_start();
require_once '../../vendor/autoload.php';
require_once(__DIR__ . '/../pedido/criarPedidos.php');
require_once(__DIR__ . '/../cliente/alterarCliente.php');
require_once(__DIR__ . '/../cliente/excluirPerfil.php');
require_once(__DIR__ . '/../../config/config.php');
require_once(__DIR__ .'/../pedido/paginacaoPedidos.php');

use app\controller\ClienteController;
use app\controller\PedidoController;
use app\controller\CarrinhoController;
use app\controller\EnderecoController;

$pedidoController = new PedidoController(); 
$clienteController = new ClienteController();
$carrinho = new CarrinhoController();
$enderecoController = new EnderecoController();

$clienteData = $clienteController->listarClientePorEmail($_SESSION["userEmail"]);
$endereco = $enderecoController->listarEnderecoPorId($clienteData->getIdEndereco());
$pedidos = $pedidoController->listarPedidoPorIdCliente($clienteData->getId());

$pedidoController = new PedidoController();
$paginaAtual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$pedidos = $pedidoController->listarPedidoPorIdCliente($clienteData->getId());

$resultadoPaginado = paginarArray($pedidos, 8, $paginaAtual);
$pedidos = $resultadoPaginado['dados'];
$totalPaginas = $resultadoPaginado['total_paginas'];
$paginaAtual = $resultadoPaginado['pagina_atual'];