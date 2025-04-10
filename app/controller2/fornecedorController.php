<?php
namespace app\controller2;

use app\repository\FornecedorRepository;
use app\model2\Fornecedor;
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
            throw new Exception("Erro ao listar fornecedores: " . $e->getMessage());
        }
    }

    public function buscarFornecedorPorEmail($email){
        try {
            return $this->repository->buscarFornecedorPorEmail($email);
        } catch (Exception $e) {
            throw new Exception("Erro ao buscar fornecedor por ID: " . $e->getMessage());
        }
    }

    // RELACAO ENTRE FORNECEDOR E ENDERECO, PERGUNTAR P JESSICA TB
    public function criarFornecedor($nome, $email, $telefone, $cnpj){
        $idFornecedor = $this->repository->criarFornecedor($nome, $email, $telefone, $cnpj);
    
        if ($idFornecedor) {
            $desativado = 0;
            $endereco = null; // verificar depois relação entre fornecedor e endereco
            $fornecedor = new Fornecedor($idFornecedor, $nome, $telefone, $email, $cnpj, $desativado, $endereco);
            return $fornecedor;
        } else {
            throw new Exception("Erro ao criar fornecedor");
        }
    }

    public function editarFornecedor($emailAntigo, $nome, $email, $telefone){
        try {
            $fornecedor = $this->repository->buscarFornecedorPorEmail($emailAntigo);
            
            if ($fornecedor) {
                $fornecedor->editarFornecedor($nome, $email, $telefone);
                
                $resultado = $this->repository->editarFornecedor($emailAntigo, $nome, $email, $telefone);
                
                if ($resultado) {
                    return true;
                } else {
                    throw new Exception("Erro ao editar fornecedor");
                }
            } else {
                throw new Exception("Fornecedor não encontrado");
            }
        } catch (Exception $e) {
            return "Erro ao editar fornecedor: " . $e->getMessage();
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
                    throw new Exception("Erro ao desativar fornecedor");
                }
            } else {
                throw new Exception("Fornecedor não encontrado");
            }
        } catch (Exception $e) {
            throw new Exception("Erro ao desativar fornecedor: " . $e->getMessage());
        }
    }
}
?>
