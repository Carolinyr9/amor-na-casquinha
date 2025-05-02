<?php 
namespace app\controller2;

use app\repository\PedidoRepository;
use app\model2\Pedido;
use app\utils\Logger;
use Exception;

class PedidoController {
    private $repository;

    public function __construct(PedidoRepository $repository = null) {
        $this->repository = $repository ?? new PedidoRepository();
    }

    public function listarPedidoPorIdCliente($idCliente) {
        try{
            $pedidos = $this->repository->listarPedidoPorIdCliente($idCliente);
                
            if(!$pedidos) {
                Logger::logError("Pedido com ID {$idCliente} não encontrado");
                return false;
            }

            return $pedidos;
        } catch(Exception $e) {
            Logger::logError("Erro ao listar pedido por ID do cliente: " . $e->getMessage());
            return false;
        }
    }

    public function listarPedidoPorId($idPedido) {
        try{
            $pedidos = $this->repository->listarPedidoPorId($idPedido);
                
            if(!$pedidos) {
                Logger::logError("Pedido com ID {$dados['idPedido']} não encontrado");
                return false;
            }
            
            return $pedidos;
        } catch(Exception $e) {
            Logger::logError("Erro ao listar pedido por ID: " . $e->getMessage());
            return false;
        }
    }

    public function criarPedido($dadosPedido) {
        try {
            if (!$this->validarDadosPedido($dadosPedido)) {
                return false;
            }
    
            $idCliente = $dadosPedido['idCliente'];
            $idEndereco = $dadosPedido['idEndereco'];
            $dataAgora = date('Y-m-d H:i:s');
            $trocoPara = $dadosPedido['meioDePagamento'] === 'Dinheiro' ? $dadosPedido['trocoPara'] : null;
    
            $idPedido = $this->repository->criarPedido(
                $idCliente,
                $dataAgora,
                $dadosPedido['tipoFrete'],
                $idEndereco,
                $dadosPedido['valorTotal'],
                'Aguardando Confirmação',
                $dadosPedido['frete'],
                $dadosPedido['meioDePagamento'],
                $trocoPara
            );
    
            if (!$idPedido) {
                Logger::logError("Erro ao criar pedido: Falha ao persistir pedido.");
                return false;
            }
    
            $pedido = new Pedido(
                $idPedido,
                $idCliente,
                $dataAgora,
                null,
                $dadosPedido['tipoFrete'],
                $idEndereco,
                $dadosPedido['valorTotal'],
                null,
                null,
                'Aguardando Confirmação',
                null,
                $dadosPedido['frete'],
                $dadosPedido['meioDePagamento'],
                $trocoPara
            );
    
            Logger::logInfo("Pedido criado com sucesso! ID do pedido: $idPedido");
            return true;
    
        } catch (Exception $e) {
            Logger::logError("Erro ao criar pedido: " . $e->getMessage());
            return false;
        }
    }
    
    public function listarPedidos() {
        try {
            $dados = $this->repository->listarPedidos();
    
            if (empty($dados)) {
                Logger::logError("Erro ao listar pedidos: Nenhum pedido encontrado!");
                return false;
            }
    
            $dadosOrdenados = $this->ordenarPorPrioridadeStatus($dados);
    
            $pedidos = [];
    
            foreach ($dadosOrdenados as $pedido) {
                $pedidos[] = new Pedido(
                    $pedido['idPedido'],
                    $pedido['idCliente'],
                    $pedido['dtPedido'],
                    $pedido['dtPagamento'],
                    $pedido['tipoFrete'],
                    $pedido['idEndereco'],
                    $pedido['valorTotal'],
                    $pedido['dtCancelamento'],
                    $pedido['motivoCancelamento'],
                    $pedido['statusPedido'],
                    $pedido['idEntregador'],
                    $pedido['frete'],
                    $pedido['meioPagamento'],
                    $pedido['trocoPara']   
                );
            }

            return $pedidos;
    
        } catch (Exception $e) {
            Logger::logError("Erro ao listar pedidos: " . $e->getMessage());
            return false;
        }
    }

    public function listarPedidoPorIdEntregador($idEntregador) {
        try{
            $pedidos = $this->repository->listarPedidosEntregador($idEntregador);
                
            if(!$pedidos) {
                Logger::logError("Erro ao listar pedido por ID do entregador: Nenhum pedido encontrado!");
                return false;
            }
            
            return $pedidos;
        } catch(Exception $e) {
            Logger::logError("Erro ao listar pedido por ID do entregador: " . $e->getMessage());
            return false;
        }
    }

    public function atribuirEntregadorPedido($dados) {
        try {
            $pedido = $this->listarPedidoPorId($dados['idPedido']);

            if($pedido){
                $pedido->setIdEntregador($dados['idEntregador']);

                $resposta = $this->repository->atribuirEntregadorPedido($dados['idPedido'], $dados['idEntegador']);

                if(!$resposta) {
                    Logger::logError("Erro ao atribuir entregador ao pedido: Nenhum pedido encontrado!");
                    return false;
                }
    
                Logger::logInfo("Entregador atribuído ao pedido com sucesso!");
                return true;
            } else {
                Logger::logError("Pedido com ID {$dados['idPedido']} não encontrado");
                return false;
            }
        } catch(Exception $e) {
            return Logger::logError("Erro ao atribuir entregador ao pedido: " . $e->getMessage());
        }
    }

    public function mudarStatus($dados) {
        try{
            $pedido = $this->listarPedidoPorId($dados['idPedido']);
            if($pedido){
                $pedido->setStatusPedido($dados['statusPedido']);

                $resposta = $this->repository->mudarStatus($dados['idPedido'], $dados['statusPedido']);
                
                if(!$resposta) {
                    return Logger::logError("Erro ao mudar status do pedido: Nenhum pedido encontrado!");
                }

                Logger::logInfo("Status do pedido alterado com sucesso!");
                return true;
            } else {
                Logger::logError("Pedido não encontrado");
                return false;
            }
        } catch(Exception $e) {
            return Logger::logError("Erro ao mudar status do pedido: " . $e->getMessage());
        }
    }

    public function cancelarPedido($dados) {
        try{
            $pedido = $this->listarPedidoPorId($dados['idPedido']);
            if($pedido){
                $pedido->setStatusPedido('Cancelado');
                $pedido->setMotivoCancelamento($dados['motivoCancelamento']);

                $resposta = $this->repository->cancelarPedido(
                    $dados['idPedido'],
                    'Cancelado',
                    $dados['motivoCancelamento']
                );
                
                if(!$resposta) {
                    return Logger::logError("Erro ao cancelar pedido: Nenhum pedido encontrado!");
                }

                Logger::logInfo("Pedido cancelado com sucesso!");
                return true;
            } else {
                Logger::logError("Pedido não encontrado");
                return false;
            }
        } catch(Exception $e) {
            return Logger::logError("Erro ao mudar status do pedido: " . $e->getMessage());
        }
    }

    private function validarDadosPedido($dados) {
        $camposObrigatorios = ['idCliente', 'idEndereco', 'tipoFrete', 'valorTotal', 'frete', 'meioDePagamento'];
    
        foreach ($camposObrigatorios as $campo) {
            if (empty($dados[$campo])) {
                Logger::logError("Erro ao criar pedido: Campo obrigatório '$campo' não fornecido!");
                return false;
            }
        }
    
        if (
            $dados['meioDePagamento'] === 'Dinheiro' &&
            (empty($dados['trocoPara']) || !isset($dados['trocoPara']))
        ) {
            Logger::logError("Erro ao criar pedido: Troco para não fornecido!");
            return false;
        }
    
        return true;
    }

    private function ordenarPorPrioridadeStatus(array $pedidos): array {
        $prioridades = [
            'Aguardando Confirmação' => 1,
            'Preparando pedido'      => 2,
            'Aguardando Retirada'    => 3,
            'Aguardando Envio'       => 4,
            'A Caminho'              => 5,
            'Entregue'               => 6,
            'Concluído'              => 7,
            'Cancelado'              => 8,
            'Entrega Falhou'         => 9
        ];
    
        usort($pedidos, function ($a, $b) use ($prioridades) {
            $prioA = $prioridades[$a['statusPedido']] ?? 999;
            $prioB = $prioridades[$b['statusPedido']] ?? 999;
            return $prioA - $prioB;
        });
    
        return $pedidos;
    }
}  