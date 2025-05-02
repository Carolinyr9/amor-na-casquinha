<?php
namespace app\controller2;

use app\model2\Cliente;
use app\repository\ClienteRepository;
use app\utils\Logger;
use Exception;

class ClienteController {
    private $repository;

    public function __contruct(ClienteRepository $repository = null) {
        $this->repository = $repository ?? new ClienteRepository();
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
                empty($dados['telefone']) || !is_numeric($dados['telefone']) ||
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
