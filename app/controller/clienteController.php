<?php
namespace app\controller;

use app\model\Cliente;
use app\repository\ClienteRepository;
use app\utils\helpers\Logger;
use Exception;

class ClienteController {
    private $repository;

    public function __construct(?ClienteRepository $repository = null){
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
    
            if($resultado) {
                return true;
            } else {
                Logger::logError("Erro ao editar cliente no repositório.");
                return false;
            }
            
        } catch (Exception $e) {
            Logger::logError("Erro ao editar cliente: " . $e->getMessage());
            return false;
        }
    }

    public function alterarSenha($dados) {
        try {
            if (
                empty($dados['senhaNova']) || 
                empty($dados['senhaAtual']) || 
                empty($dados['idCliente'])
            ) {
                Logger::logError("Dados inválidos para edição da senha.");
                return false;
            }

            $resultado = $this->repository->editarSenha(
                $dados['senhaNova'],
                $dados['senhaAtual'], 
                $dados['idCliente']
            );

            if ($resultado) {
                return true;
            } else {
                Logger::logError("Erro ao editar senha: Senha atual incorreta ou falha na atualização.");
                return false;
            }

        } catch (Exception $e) {
            Logger::logError("Erro ao editar senha: " . $e->getMessage());
            return false;
        }
    }

    public function desativarPerfil($email) {
        try {
            if (!isset($email) || empty($email)) {
                Logger::logError("Erro ao buscar desativar cliente: e-mail não fornecido!");
                return false;
            }

            $pedido = $this->listarClientePorEmail($email);

            if ($pedido) {
                $resultado = $this->repository->desativarPerfil($email);

                if ($resultado) {
                    return true;
                } else {
                    Logger::logError("Erro ao desativar cliente");
                    return false;
                }
            } else {
                Logger::logError("Cliente não encontrado");
                return false;
            }
        } catch (Exception $e) {
            Logger::logError("Erro ao desativar cliente: " . $e->getMessage());
            return false;
        }
    }

    public function listarClientes() {
        try {
    
            $dados = $this->repository->listarClientes();
    
            if($dados) {
                $clientes = [];

                foreach ($dados as $cliente) {
                    $clientes[] = new Cliente(
                        $cliente['idCliente'],
                        $cliente['nome'],
                        $cliente['email'], 
                        $cliente['telefone'], 
                        $cliente['senha'], 
                        $cliente['idEndereco']
                    );
                }

                return $clientes;

            } else {
                Logger::logError("Erro ao listar clientes: Nenhum cliente encontrado.");
                return false;
            }
        } catch (Exception $e) {
            Logger::logError("Erro ao listar clientes: " . $e->getMessage());
            return false;
        }
    }    
    
}
?>
