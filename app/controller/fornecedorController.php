<?php
namespace app\controller;

use app\repository\FornecedorRepository;
use app\model\Fornecedor;
use app\utils\helpers\Logger;
use Exception;

class FornecedorController {
    private $repository;

    public function __construct(?FornecedorRepository $repository = null) {
        $this->repository = $repository ?? new FornecedorRepository();
    }

    public function listarFornecedor(){
        try {
            $dados = $this->repository->listarFornecedor();
            $fornecedores = [];
    
            foreach ($dados as $fornecedor) {
                if (!$fornecedor instanceof Fornecedor) {
                    
                    $fornecedor = new Fornecedor(
                        $fornecedor['idFornecedor'],
                        $fornecedor['nome'],  
                        $fornecedor['telefone'],  
                        $fornecedor['email'],
                        $fornecedor['cnpj'],  
                        $fornecedor['desativado'] == 0, 
                        $fornecedor['idEndereco']
                    );
                }
                $fornecedores[] = $fornecedor;
            }
    
            return $fornecedores;
        } catch (Exception $e) {
            Logger::logError("Erro ao listar fornecedores: " . $e->getMessage());
        }
    }

    public function buscarFornecedorPorEmail($email){
        try {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return Logger::logError("Erro ao listar fornecedor: Email inválido.");
            }

            return $this->repository->buscarFornecedorPorEmail($email);
        } catch (Exception $e) {
            Logger::logError("Erro ao buscar fornecedor por ID: " . $e->getMessage());
        }
    }

    public function criarFornecedor($dados){
        try{
            if (empty($dados['nome']) || empty($dados['email']) || 
                !filter_var($dados['email'], FILTER_VALIDATE_EMAIL) ||
                empty($dados['telefone']) || empty($dados['cnpj']) ||
                empty($dados['idEndereco'])) {
                Logger::logError("Dados inválidos para criação do fornecedor.");
                return false;
            }

            $idFornecedor = $this->repository->criarFornecedor($dados['nome'], $dados['email'], $dados['telefone'], $dados['cnpj'], $dados['idEndereco']);
        
            if ($idFornecedor) {
                $desativado = 0;
                $fornecedor = new Fornecedor($idFornecedor, $dados['nome'], $dados['telefone'], $dados['email'], $dados['cnpj'], $desativado, $dados['idEndereco']);
                return $fornecedor;
            } else {
                Logger::logError("Erro ao criar fornecedor");
            }
        } catch (Exception $e) {
            Logger::logError("Erro ao criar fornecedor: " . $e->getMessage());
        }
    }

    public function editarFornecedor($dados){
        try {
            if (empty($dados['nome']) || empty($dados['email']) || 
                !filter_var($dados['email'], FILTER_VALIDATE_EMAIL) ||
                empty($dados['telefone'])) {
                Logger::logError("Dados inválidos para edição do fornecedor.");
                return false;
            }
            
            $fornecedor = $this->repository->buscarFornecedorPorEmail($dados['emailAntigo']);
            
            if ($fornecedor) {
                $fornecedor->editarFornecedor($dados['nome'], $dados['email'], $dados['telefone'], $dados['cnpj']);
                
                $resultado = $this->repository->editarFornecedor($dados['emailAntigo'], $dados['nome'], $dados['email'], $dados['telefone'], $dados['cnpj']);
                
                if ($resultado) {
                    return true;
                } else {
                    throw new Exception("Erro ao editar fornecedor");
                }
            } else {
                Logger::logError("Fornecedor não encontrado");
            }
        } catch (Exception $e) {
            Logger::logError("Fornecedor não encontrado: " . $e->getMessage());
        }
    }

    public function desativarFornecedor($email){
        try {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return Logger::logError("Erro ao desativar entregador: Email inválido.");
            }

            $fornecedor = $this->repository->buscarFornecedorPorEmail($email);
    
            if ($fornecedor) {
                $fornecedor->setDesativado(1);
                $resultado = $this->repository->desativarFornecedor($email);
    
                if ($resultado) {
                    return true;
                } else {
                    Logger::logError("Erro ao desativar fornecedor");
                }
            } else {
                Logger::logError("Fornecedor não encontrado");
            }
        } catch (Exception $e) {
            Logger::logError("Erro ao desativar fornecedor: " . $e->getMessage());
        }
    }
}
?>
