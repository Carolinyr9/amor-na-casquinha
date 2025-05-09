<?php
namespace app\controller;

use app\model\Cliente;
use app\repository\ClienteRepository;
use app\utils\helpers\Logger;
use Exception;

class ClienteController {
    private $repository;

    public function __construct(ClienteRepository $repository = null){
        $this->repository = $repository ?? new ClienteRepository();
    }

    public function criarCliente($dados) {
        try {
            if (empty($dados['email']) || !filter_var($dados['email'], FILTER_VALIDATE_EMAIL) ||
                empty($dados['telefone']) ||
                empty($dados['nome'])) {
                Logger::logError("Dados inválidos para criação do cliente.");
                return false;
            }

            $idCliente = $this->repository->criarCliente(
                $dados['nome'],
                $dados['email'],
                $dados['senha'],
                $dados['telefone'],
                $dados['idEndereco']
            );
    
            if ($idCliente) {
                $cliente = new Cliente(
                    $idCliente,
                    $dados['nome'],
                    $dados['email'],
                    $dados['telefone'],
                    $dados['senha'],
                    $dados['idEndereco']
                );
    
                return $idCliente;
            } else {
                Logger::logError("Erro ao criar cliente: Não foi possível inserir no banco.");
                return false;
            }
        } catch (Exception $e) {
            Logger::logError("Erro ao criar cliente: " . $e->getMessage());
            return false;
        }
    }
    

    public function listarClientePorEmail($email) {
        try {
            if (!isset($email) || empty($email)) {
                Logger::logError("Erro ao listar clientes: Email não fornecido!");
                return false;
            }
    
            $cliente = $this->repository->listarClientePorEmail($email);
    
            if (!$cliente instanceof Cliente) {
                if (is_array($cliente)) {
                    return new Cliente(
                        $cliente['idCliente'],
                        $cliente['nome'],
                        $cliente['email'], 
                        $cliente['telefone'], 
                        $cliente['senha'], 
                        $cliente['idEndereco']
                    );
                } else {
                    Logger::logError("Erro ao listar clientes: Cliente não encontrado ou formato inválido.");
                    return false;
                }
            }
    
            return $cliente;
    
        } catch (Exception $e) {
            Logger::logError("Erro ao listar clientes: " . $e->getMessage());
            return false;
        }
    }    

    public function editarCliente($dados) {
        try {
            if (empty($dados['email']) || !filter_var($dados['email'], FILTER_VALIDATE_EMAIL) ||
                empty($dados['telefone']) ||
                empty($dados['nome']) || empty($dados['emailAntigo'])) {
                Logger::logError("Dados inválidos para edição do cliente.");
                return false;
            }
    
            $cliente = $this->listarClientePorEmail($dados['emailAntigo']);
    
            if (!$cliente instanceof Cliente) {
                Logger::logError("Cliente não encontrado para edição.");
                return false;
            }
    
            $cliente->editar($dados['nome'], $dados['telefone'], $dados['email']);
    
            $resultado = $this->repository->editarCliente(
                $dados['nome'],
                $dados['telefone'],
                $dados['email'],
                $dados['emailAntigo']
            );
    
            return $resultado ?: Logger::logError("Erro ao editar cliente no repositório.");
            
        } catch (Exception $e) {
            Logger::logError("Erro ao editar cliente: " . $e->getMessage());
            return false;
        }
    }
    
}
?>
