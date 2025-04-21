<?php 
namespace app\comtroller2;

use app\repository\PedidoRepository;
use app\repository\ClienteController;
use app\model\Pedido;

class PedidoController {
    private $repositorio;

    public function __construct() {
        $this->repostorio = new PedidoRepository();
    }

    public function listarPedidoPorEmailCliente($email) {
        $clienteController = new ClienteController();
        $dados = $clienteController->listarClientePorEmail($email);
        if(isset($dados['error'])) {
            return $dados['error'];
        } else {
            $pedidos = $this->repositorio->listarPedidoPorIdCliente($id);
            return $pedidos ?: Logger::logError("Erro ao listar pedido: Nenhum pedido encontrado!");
        }
    }
    
    public function listarPedidoPorId($idPedido) {
        if(!isset($idPedido) || empty($idPedido)) {
            return Logger::logError("Erro ao listar pedido: ID do pedido não fornecido!");
        }

        $pedido = $this->repositorio->listarPedidoPorId($idPedido);
        return $pedido ?: Logger::logError("Erro ao listar pedido: Nenhum pedido encontrado!");
    }

    public function listarInformacoesPedido($idPedido) {
        try {
            if(!isset($idPedido) || empty($idPedido)) {
                return Logger::logError("Erro ao listar informações do pedido: ID do pedido não fornecido!");
            }
    
            $pedido = $this->repositorio->listarInformacoesPedido($idPedido);

            return $pedido ?: Logger::logError("Erro ao listar informações do pedido: Nenhum item encontrado para o pedido!");
        } catch (Exception $e) {
            return Logger::logError("Erro ao listar informações do pedido: " . $e->getMessage());
        }
    }

    public function criarPedido($emailCliente, $dadosPedido) {
        try {
            if(isset($_POST)) {
                $clienteController = new ClienteController();
                $dados = $clienteController->listarClientePorEmail($emailCliente);
    
                if(isset($dados['error'])) {
                    return $dados['error'];
                } else {
                    $idCliente = $dados['idCliente'];
                }
                
                if(!isset($dadosPedido['tipoFrete']) || empty($dadosPedido['tipoFrete'])) {
                    Logger::logError("Erro ao criar pedido: Tipo de frete não fornecido!");
                    return false;
                }
        
                if(!isset($dadosPedido['valorTotal']) || empty($dadosPedido['valorTotal'])) {
                    Logger::logError("Erro ao criar pedido: Valor total não fornecido!");
                    return false;
                }
        
                if(!isset($dadosPedido['frete']) || empty($dadosPedido['frete'])) {
                    Logger::logError("Erro ao criar pedido: Frete não fornecido!");
                    return false;
                }
                
                if(!isset($dadosPedido['meioDePagamento']) || empty($dadosPedido['meioDePagamento'])) {
                    Logger::logError("Erro ao criar pedido: Meio de pagamento não fornecido!");
                    return false;
                }

                if($dados['meioDePagamento'] == 'Dinheiro' && (!isset($dadosPedido['trocoPara']) || empty($dadosPedido['trocoPara']))) {
                    Logger::logError("Erro ao criar pedido: Troco para não fornecido!");
                    return false;
                }
                
                $pedido = new Pedido(0, $idCliente, 0, date('Y-m-d H:i:s'), null, $dadosPedido['tipoFrete'], $dados['idEndereco'], $dadosPedido['valorTotal'], null, null, 'Aguardando Confirmação', 0, $dadosPedido['frete'], $dadosPedido['meioDePagamento'], $dadosPedido['trocoPara']);
                
                $resumoado = $this->repositorio->criarPedido($pedido->getIdCliente(), $pedido->getDtPedido(), $pedido->getTipoFrete(), $pedido->getIdEndereco(), $pedido->getValorTotal(), $pedido->getStatusPedido(), $pedido->getFrete(), $pedido->getMeioPagamento(), $pedido->getTrocoPara());

                return $resutado ? Logger::logInfo("Pedido criado com sucesso!") : Logger::logError("Erro ao criar pedido: Nenhum pedido encontrado!");
            }
        } catch(Exception $e) {
            return Logger::logError("Erro ao criar pedido: " . $e->getMessage());
        }
    }

    public function listarPedidos() {
        try{
            $pedidos = $this->repositorio->listarPedidos();

            usort($pedidos, function($a, $b) {
                $prioridades = [
                    'Aguardando Confirmação' => 1,
                    'Preparando pedido' => 2,
                    'Aguardando Retirada' => 3,
                    'Aguardando Envio' => 4,
                    'A Caminho' => 5,
                    'Entregue' => 6,
                    'Concluído' => 7,
                    'Cancelado' => 8,
                    'Entrega Falhou' => 9
                ];
    
                $prioridadeA = $prioridades[$a['statusPedido']] ?? 999;
                $prioridadeB = $prioridades[$b['statusPedido']] ?? 999;
                return $prioridadeA - $prioridadeB;
            });

            return $pedidos ?: Logger::logError("Erro ao listar pedidos: Nenhum pedido encontrado!");
        } catch(Exception $e) {
            return Logger::logError("Erro ao listar pedidos: " . $e->getMessage());
        }
    }

    public function listarPedidosEntregador($emailEntregador) {
        try{
            if(!isset($emailEntregador) || empty($emailEntregador)) {
                return Logger::logError("Erro ao listar pedidos: Email do entregador não fornecido!");
            }

            $entregadorController = new EntregadorController();
            $dadosEntregador = $entregadorController->listarEntregadorPorEmail($emailEntregador); 

            if(isset($dadosEntregador['error'])) {
                return Logger::logError("Erro ao listar pedidos: " . $dadosEntregador['error']);
            } else {
                $pedidos = $this->repositorio->listarPedidosEntregador($dadosEntregador['idEntregador']);
    
                usort($pedidos, function($a, $b) {
                    $prioridades = [
                        'A Caminho' => 1,
                        'Aguardando Confirmação' => 2,
                        'Preparando pedido' => 3,
                        'Aguardando Retirada' => 4,
                        'Aguardando Envio' => 5,
                        'Entregue' => 6,
                        'Concluído' => 7,
                        'Cancelado' => 8,
                        'Entrega Falhou' => 9
                    ];
        
                    $prioridadeA = $prioridades[$a['statusPedido']] ?? 999;
                    $prioridadeB = $prioridades[$b['statusPedido']] ?? 999;
                    return $prioridadeA - $prioridadeB;
                });

                return $pedidos ?: Logger::logError("Erro ao listar pedidos: Nenhum pedido encontrado!");
            }
        } catch(Exception $e) {
            return Logger::logError("Erro ao listar pedidos: " . $e->getMessage());
        }
    }

    private function determinarNovoStatusPorUsuario($usuario, $pedido) {
        switch($usuario) {    
            case 'CLIE':
                return $this->determinarNovoStatusCliente($pedido);
    
            case 'FUNC':
                return $this->determinarNovoStatusFuncionario($pedido);
    
            default:
                return $pedido['statusPedido'];
        }
    }

    private function determinarNovoStatusFuncionario($pedido) {
        return $this->determinarNovoStatus($pedido['statusPedido'], [
            'Aguardando Confirmação' => 'Preparando pedido',
            'Preparando pedido' => $pedido['tipoFrete'] == 0 ? 'Aguardando Retirada' : 'Aguardando Envio',
            'Aguardando Retirada' => 'Concluído',
            'Aguardando Envio' => 'A Caminho',
            'Entregue' => 'Entregue'
        ]);
    }
    
    private function determinarNovoStatusCliente($pedido) {
        return $this->determinarNovoStatus($pedido['statusPedido'], [
            'Entregue' => 'Concluído',
            'Aguardando Confirmação' => 'Cancelado',
            'Preparando pedido' => 'Cancelado',
            'Aguardando Envio' => 'Cancelado'
        ]);
    }
    
    
    private function determinarNovoStatus($statusAtual, $mudancas) {
        return $mudancas[$statusAtual] ?? $statusAtual;
    }

    public function mudarStatus($dados) {
        try{
            if(!isset($dados['idPedido']) || empty($dados['idPedido'])) {
                return Logger::logError("Erro ao mudar status do pedido: ID do pedido não fornecido!");
            }

            if(!isset($dados['$usuario']) || empty($dados['$usuario'])) {
                return Logger::logError("Erro ao mudar status do pedido: Usuário não fornecido!");
            }

            $pedido = $this->listarPedidoPorId($dados['idPedido']);

            $novoStatus = $this->determinarNovoStatusPorUsuario($dados['usuario']);

            if(!isset($dados['motivoCancelamento']) || empty($dados['motivoCancelamento'])) {
                $resposta = $this->repositorio->mudarStatus($idPedido, $novoStatus);
            } else {
                $resposta = $this->repositorio->mudarStatusCancelamento($idPedido, $novoStatus, $motivoCancelamento);
            }

            return $resposta ? Logger::logInfo("Status do pedido alterado com sucesso!") : Logger::logError("Erro ao mudar status do pedido: Nenhum pedido encontrado!");
        } catch(Exception $e) {
            return Logger::logError("Erro ao mudar status do pedido: " . $e->getMessage());
        }
    }

    public function listarResumoVendas($dataInicio, $dataFim) {
        try {
            if(!isset($dataInicio) || empty($dataInicio)) {
                return Logger::logError("Erro ao listar o resumo das vendas: Data de início não fornecida!");
            }

            if(!isset($dataFim) || empty($dataFim)) {
                return Logger::logError("Erro ao listar o resumo das vendas: Data de fim não fornecida!");
            }
            
            $resposta = $this->repositorio->listarResumoVendas($dataInicio, $dataFim);

            if($resposta) {
                $auxClientes = [];
                $auxProdutos = [];
                $auxPedidos = [];
                $resumo = [
                    'totalVendas' => 0,
                    'totalPedidosClientes' => 0,
                    'totalProdutos' => 0,
                    'pedidosFeitos' => 0
                ];

                foreach ($resposta as $row) {
                    $idCliente = $row['idCliente'];
                    $idProduto = $row['idProduto'];
                    $idPedido = $row['pedidoId'];
    
                    if (!isset($auxPedidos[$idPedido])) {
                        $auxPedidos[$idPedido] = true;
                        $resumo['totalVendas'] += $row['valorTotal'];
                        $resumo['pedidosFeitos'] += 1;
                    }
                    
                    if (!isset($auxClientes[$idCliente])) {
                        $auxClientes[$idCliente] = true;
                        $resumo['totalPedidosClientes'] += 1; 
                    }
                    
                    if (!isset($auxProdutos[$idProduto])) {
                        $auxProdutos[$idProduto] = true;
                        $resumo['totalProdutos'] += 1;
                    }
                }
            }

            return $resumo ?: Logger::logError("Erro ao resumir vendas");
        } catch(Exception $e) {
            return Logger::logError("Erro ao listar o resumo das vendas: " . $e->getMessage());
        }
    }

    public function atribuirEntregadorPedido($dados) {
        try {
            if(!isset($dados['idPedido']) || empty($dados['idPedido'])) {
                return Logger::logError("Erro ao atribuir entregador ao pedido: ID do pedido não fornecido!");
            }

            if(!isset($dados['idEntregador']) || empty($dados['idEntregador'])) {
                return Logger::logError("Erro ao atribuir entregador ao pedido: Entregador não fornecido!");
            }

            $resposta = $this->repositorio->atribuirEntregadorPedido($dados['idPedido'], $dados['idEntegador']);

            return $resposta ? Logger::logInfo("Entregador atribuído ao pedido com sucesso!") : Logger::logError("Erro ao atribuir entregador ao pedido");
        } catch(Exception $e) {
            return Logger::logError("Erro ao atribuir entregador ao pedido: " . $e->getMessage());
        }
    }
}