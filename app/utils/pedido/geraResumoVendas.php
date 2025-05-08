<?php
use app\controller\PedidoController;
use app\controller\ItemPedidoController;
use app\controller\ProdutoController;
use app\utils\helpers\Logger;

$itemPedidoController = new ItemPedidoController();
$pedidoController = new PedidoController();
$produtoController = new ProdutoController();

$vendas = [];
$itens = [];
$produtos = [];
$totalVendas = 0;
$totalPedidos = 0;
$totalClientes = 0;
$totalProdutos = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['data-inicio'], $_POST['data-fim'])) {
    $dados = [
        'dataInicio' => $_POST['data-inicio'],
        'dataFim' => $_POST['data-fim']
    ];

    if (!empty($dados['dataInicio']) && !empty($dados['dataFim'])) {
        $vendas = $pedidoController->listarPedidosPorPeriodo($dados);
        
        if ($vendas && is_array($vendas)) {
            foreach ($vendas as $pedido) {
                $itens = $itemPedidoController->listarInformacoesPedido($pedido->getIdPedido());

                if ($itens && is_array($itens)) {
                    foreach ($itens as $item) {
                        $produto = $produtoController->selecionarProdutoPorID($item->getIdProduto());

                        $idProduto = $item->getIdProduto();

                        if (isset($produtos[$idProduto])) {
                            $produtos[$idProduto]['quantidade'] += $item->getQuantidade();
                        } else {
                            $produtos[$idProduto] = [
                                'idProduto' => $idProduto,
                                'quantidade' => $item->getQuantidade(),
                                'NomeProduto' => $produto->getNome(),
                                'Preco' => $produto->getPreco(),
                                'Foto' => $produto->getFoto(),
                                'ProdutoDesativado' => $produto->getDesativado()
                            ];
                        }
                        
                        $totalVendas += $item->getQuantidade() * $produto->getPreco();
                        $totalPedidos++;
                        $totalProdutos += $item->getQuantidade();
                    }
                } else {
                    Logger::logError("Erro ao obter itens do pedido.");
                }
            }

            usort($produtos, function ($a, $b) {
                return $b['quantidade'] <=> $a['quantidade'];
            });

            $clientes = array_unique(array_map(function($pedido) {
                return $pedido->getIdCliente();
            }, $vendas));
            $totalClientes = count($clientes);
        } else {
            Logger::logError("Erro ao listar pedidos no período.");
        }
    }
}
?>
