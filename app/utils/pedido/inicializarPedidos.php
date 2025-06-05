<?php
session_start();
require_once '../config/blockURLAccess.php';
require_once '../../vendor/autoload.php';
require_once '../utils/pedido/criarPedidosFuncionario.php';
require_once '../utils/pedido/paginacaoPedidos.php';

use app\controller\PedidoController;
use app\controller\ClienteController;
use app\controller\EnderecoController;
use app\controller\ProdutoController;

$pedidoController = new PedidoController();
$clienteController = new ClienteController();
$enderecoController = new EnderecoController;
$produtoController = new ProdutoController();

$paginaAtual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$pedidos = $pedidoController->listarPedidos();
$clientes = $clienteController->listarClientes();
$enderecos = $enderecoController->listarEnderecos();
$produtos = $produtoController->selecionarProdutos();

$resultadoPaginado = paginarArray($pedidos, 8, $paginaAtual);
$pedidos = $resultadoPaginado['dados'];
$totalPaginas = $resultadoPaginado['total_paginas'];
$paginaAtual = $resultadoPaginado['pagina_atual'];
?>