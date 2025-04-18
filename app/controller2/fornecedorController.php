<?php
namespace app\controller2;

use app\repository\FornecedorRepository;
use app\model2\Fornecedor;
use app\utils\Logger;
use Exception;

class FornecedorController {
    private $repository;

    public function __construct(FornecedorRepository $repository) {
        $this->repository = $repository;
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
                        $fornecedor['idEndereco'],
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
            return $this->repository->buscarFornecedorPorEmail($email);
        } catch (Exception $e) {
            Logger::logError("Erro ao buscar fornecedor por ID: " . $e->getMessage());
        }
    }

    // RELACAO ENTRE FORNECEDOR E ENDERECO, PERGUNTAR P JESSICA TB
    public function criarFornecedor($dados){
        $idFornecedor = $this->repository->criarFornecedor($dados['nome'], $dados['email'], $dados['telefone'], $dados['cnpj']);
    
        if ($idFornecedor) {
            $desativado = 0;
            $endereco = null; // verificar depois relação entre fornecedor e endereco
            $fornecedor = new Fornecedor($idFornecedor, $dados['nome'], $dados['telefone'], $dados['email'], $dados['cnpj'], $desativado, $endereco);
            return $fornecedor;
        } else {
            Logger::logError("Erro ao criar fornecedor: " . $e->getMessage());
        }
    }

    public function editarFornecedor($dados){
        try {
            $fornecedor = $this->repository->buscarFornecedorPorEmail($dados['emailAntigo']);
            
            if ($fornecedor) {
                $fornecedor->editarFornecedor($dados['nome'], $dados['email'], $dados['telefone']);
                
                $resultado = $this->repository->editarFornecedor($dados['emailAntigo'], $dados['nome'], $dados['email'], $dados['telefone']);
                
                if ($resultado) {
                    return true;
                } else {
                    throw new Exception("Erro ao editar fornecedor");
                }
            } else {
                Logger::logError("Fornecedor não encontrado: " . $e->getMessage());
            }
        } catch (Exception $e) {
            Logger::logError("Fornecedor não encontrado: " . $e->getMessage());
        }
    }

    public function desativarFornecedor($email){
        try {
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
                Logger::logError("Fornecedor não encontrado: " . $e->getMessage());
            }
        } catch (Exception $e) {
            Logger::logError("Erro ao desativar fornecedor: " . $e->getMessage());
        }
    }
}
?>
