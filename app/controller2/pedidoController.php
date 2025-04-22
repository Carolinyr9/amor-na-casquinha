<?php 
namespace app\comtroller2;

use app\repository\PedidoRepository;
use app\repository\ClienteController;
use app\model\Pedido;
use app\config\Logger;

class PedidoController {
    private $repositorio;

    public function __construct() {
        $this->repostorio = new PedidoRepository();
    }
    
    private function logAndReturnFalse($mensagem) {
        Logger::logError($mensagem);
        return false;
    }  

    public function listarPedidoPorEmailCliente($email) {
        try{
            $clienteController = new ClienteController();
            $dados = $clienteController->listarClientePorEmail($email);

            if(!$dados) {
                return logAndReturnFalse("Erro ao listar pedido: Cliente não encontrado!");
            }
            
            $pedidos = $this->repositorio->listarPedidoPorIdCliente($id);
                
            if(!$pedidos) {
                return logAndReturnFalse("Erro ao listar pedido: Nenhum pedido encontrado!");
            }

            return $pedidos;
        } catch(Exception $e) {
            Logger::logError("Erro ao listar pedido: " . $e->getMessage());
            return false;
        }
    }
    
    public function listarPedidoPorId($idPedido) {
        try {
            if(!isset($idPedido) || empty($idPedido)) {
                return logAndReturnFalse("Erro ao listar pedido: ID do pedido não fornecido!");
            }
    
            $pedido = $this->repositorio->listarPedidoPorId($idPedido);
            
            if(!$pedido) {
                return logAndReturnFalse("Erro ao listar pedido: Nenhum pedido encontrado!");
            }

            return $pedido;
        } catch(Exception $e) {
            Logger::logError("Erro ao listar pedido: " . $e->getMessage());
            return false;
        }
    }

    public function listarInformacoesPedido($idPedido) {
        try {
            if(!isset($idPedido) || empty($idPedido)) {
                return $this->logAndReturnFalse("Erro ao listar informações do pedido: ID do pedido não fornecido!");
            }
    
            $pedido = $this->repositorio->listarInformacoesPedido($idPedido);

            if(!$pedido) {
                return $this->logAndReturnFalse("Erro ao listar informações do pedido: Nenhum pedido encontrado!");
            }

            return $pedido;
        } catch (Exception $e) {
            return Logger::logError("Erro ao listar informações do pedido: " . $e->getMessage());
        }
    }

    public function criarPedido($emailCliente, $dadosPedido) {
        try {
            $clienteController = new ClienteController();
            $cliente = $clienteController->listarClientePorEmail($emailCliente);
    
            if (!$cliente) {
                return $this->logAndReturnFalse("Erro ao criar pedido: Cliente não encontrado!");
            }
    
            $idCliente = $cliente['idCliente'];
            $idEndereco = $cliente['idEndereco'];
    
            $camposObrigatorios = ['tipoFrete', 'valorTotal', 'frete', 'meioDePagamento'];
    
            foreach ($camposObrigatorios as $campo) {
                if (empty($dadosPedido[$campo])) {
                    return $this->logAndReturnFalse("Erro ao criar pedido: Campo obrigatório '$campo' não fornecido!");
                }
            }
    
            if (
                $dadosPedido['meioDePagamento'] === 'Dinheiro' &&
                (empty($dadosPedido['trocoPara']) || !isset($dadosPedido['trocoPara']))
            ) {
                return $this->logAndReturnFalse("Erro ao criar pedido: Troco para não fornecido!");
            }
    
            $trocoPara = $dadosPedido['meioDePagamento'] === 'Dinheiro' ? $dadosPedido['trocoPara'] : null;
    
            $pedido = new Pedido(
                0,
                $idCliente,
                0,
                date('Y-m-d H:i:s'),
                null,
                $dadosPedido['tipoFrete'],
                $idEndereco,
                $dadosPedido['valorTotal'],
                null,
                null,
                'Aguardando Confirmação',
                0,
                $dadosPedido['frete'],
                $dadosPedido['meioDePagamento'],
                $trocoPara
            );
    
            $resultado = $this->repositorio->criarPedido(
                $pedido->getIdCliente(),
                $pedido->getDtPedido(),
                $pedido->getTipoFrete(),
                $pedido->getIdEndereco(),
                $pedido->getValorTotal(),
                $pedido->getStatusPedido(),
                $pedido->getFrete(),
                $pedido->getMeioPagamento(),
                $pedido->getTrocoPara()
            );
    
            if (!$resultado) {
                return $this->logAndReturnFalse("Erro ao criar pedido: Falha ao persistir pedido.");
            }
    
            Logger::logInfo("Pedido criado com sucesso! ID do pedido: " . $resultado);
            return true;
    
        } catch (Exception $e) {
            return $this->logAndReturnFalse("Erro ao criar pedido: " . $e->getMessage());
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

            if(!$pedidos) {
                return logAndReturnFalse("Erro ao listar pedidos: Nenhum pedido encontrado!");
            }

            return $pedidos;
        } catch(Exception $e) {
            return Logger::logError("Erro ao listar pedidos: " . $e->getMessage());
        }
    }

    public function listarPedidosEntregador($emailEntregador) {
        try{
            if(!isset($emailEntregador) || empty($emailEntregador)) {
                return logAndReturnFalse("Erro ao listar pedidos: Email do entregador não fornecido!");
            }

            $entregadorController = new EntregadorController();
            $dadosEntregador = $entregadorController->listarEntregadorPorEmail($emailEntregador); 

            if(!$dadosEntregador) {
                return logAndReturnFalse("Erro ao listar pedidos: Entregador não encontrado!");
            }
            
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

            if(!$pedidos) {
                return logAndReturnFalse("Erro ao listar pedidos: Nenhum pedido encontrado!");
            }

            return $pedidos;
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
            $camposObrigatorios = ['idPedido', 'frete'];
            
            foreach($camposObrigatorios as $campo) {
                if (empty($dados[$campo])) {
                    return Logger::logError("Erro ao mudar status do pedido: Campo obrigatório '$campo' não fornecido!");
                }
            }

            $pedido = $this->listarPedidoPorId($dados['idPedido']);

            $novoStatus = $this->determinarNovoStatusPorUsuario($dados['usuario']);

            if(!isset($dados['motivoCancelamento']) || empty($dados['motivoCancelamento'])) {
                $resposta = $this->repositorio->mudarStatus($idPedido, $novoStatus);
            } else {
                $resposta = $this->repositorio->mudarStatusCancelamento($idPedido, $novoStatus, $motivoCancelamento);
            }

            if(!$resposta) {
                return Logger::logError("Erro ao mudar status do pedido: Nenhum pedido encontrado!");
            }

            Logger::logInfo("Status do pedido alterado com sucesso!");
            return true;
        } catch(Exception $e) {
            return Logger::logError("Erro ao mudar status do pedido: " . $e->getMessage());
        }
    }

    public function listarResumoVendas($dataInicio, $dataFim) {
        try {
            if(!isset($dataInicio) || empty($dataInicio)) {
                return logAndReturnFalse("Erro ao listar o resumo das vendas: Data de início não fornecida!");
            }

            if(!isset($dataFim) || empty($dataFim)) {
                return logAndReturnFalse("Erro ao listar o resumo das vendas: Data de fim não fornecida!");
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

            Logger::logInfo("Resumo das vendas listado com sucesso!");
            return $resumo;
        } catch(Exception $e) {
            return Logger::logError("Erro ao listar o resumo das vendas: " . $e->getMessage());
        }
    }

    public function atribuirEntregadorPedido($dados) {
        try {
            $camposObrigatorios = ['idPedido', 'idEntregador'];

            foreach($camposObrigatorios as $campo) {
                if (empty($dados[$campo])) {
                    return logAndReturnFalse("Erro ao atribuir entregador ao pedido: Campo obrigatório '$campo' não fornecido!");
                }
            }

            $resposta = $this->repositorio->atribuirEntregadorPedido($dados['idPedido'], $dados['idEntegador']);

            if(!$resposta) {
                return logAndReturnFalse("Erro ao atribuir entregador ao pedido: Nenhum pedido encontrado!");
            }

            Logger::logInfo("Entregador atribuído ao pedido com sucesso!");
            return true;
        } catch(Exception $e) {
            return Logger::logError("Erro ao atribuir entregador ao pedido: " . $e->getMessage());
        }
    }

    public function calcularFrete($cep) {
        try {
            $url = "http://localhost:8080/sorveteria/frete?cep=" . urlencode($cep);
            $ch = curl_init();
            $timeout = 5;

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);

            $data = curl_exec($ch);

            if (curl_errno($ch)) {
                echo 'Error:' . curl_error($ch);
                return Logger::logError("Erro ao calcular o frete.");
            }

            if ($data == 1) {
                return Logger::logError("Estamos muito distantes do seu endereço para fazer uma entrega!");
            }

            curl_close($ch);

            return $data;
        } catch(Exception $e) {
            return Logger::logError("Erro ao calcular o frete: " . $e->getMessage());
        }
    }
}