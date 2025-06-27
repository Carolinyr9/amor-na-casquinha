<?php
use app\controller\PedidoController;
$pedidoController = new PedidoController();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['mudarStatus'])) {
    $statusAntigo = $_POST['statusAntigo'] ?? '';
    $tipoFrete = $_POST['tipoFrete'] ?? 0;

    switch ($statusAntigo) {
        case 'Aguardando Confirmação':
            $statusPedido = 'Preparando pedido';
            break;
        case 'Preparando pedido':
            $statusPedido = $tipoFrete == 0 ? 'Aguardando Retirada' : 'Aguardando Envio';
            break;
        case 'Aguardando Retirada':
            $statusPedido = 'Concluído';
            break;
        case 'Aguardando Envio':
            $statusPedido = 'A Caminho';
            break;
        case 'A Caminho':
            $statusPedido = 'Entregue';
            break;
        default:
            Logger::logError("Erro ao mudar status do pedido.");
            $statusPedido = $statusAntigo; 
    }

    $pedidoId = $_GET['idPedido'] ?? null;

    $dados = [
        'idPedido' => $pedidoId,
        'statusPedido' => $statusPedido
    ];

    $pedidoController->mudarStatus($dados);
    header("Location: informacoesPedido.php?idPedido=$pedidoId");
    exit();
}

if (isset($_POST['cancelarPedido'])) {

    $pedidoId = $_GET['idPedido'] ?? null;
    $dados = [
        'idPedido' => $pedidoId,
        'motivoCancelamento' => $_POST['motivoCancelamento'] ?? null
    ];
    $pedidoController->cancelarPedido($dados);
    header("Location: informacoesPedido.php?idPedido=$pedidoId");
    exit();
}
