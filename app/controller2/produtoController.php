<?php
namespace app\controller2;

use app\repository\ProdutoRepository;
use app\model2\Produto;
use Exception;

class ProdutoController {
    private $repository;

    public function __construct(ProdutoRepository $repository) {
        $this->repository = $repository;
    }

    public function listarProdutos() {
        try {
            $dados = $this->repository->buscarProdutosAtivos();
            $produtos = [];
    
            foreach ($dados as $produto) {
                if (!$produto instanceof Produto) {
                    
                    $produto = new Produto(
                        $produto['id'],
                        $produto['fornecedor'],
                        $produto['nome'],
                        $produto['marca'],
                        $produto['descricao'],
                        $produto['desativado'],
                        $produto['foto'],
                        $produto['produtosVariacao'] ?? []
                    );
                }
                $produtos[] = $produto;
            }
    
            return $produtos;
        } catch (Exception $e) {
            throw new Exception("Erro ao listar produtos: " . $e->getMessage());
        }
    }
    

    public function buscarProdutoPorID($id) {
        try {
            return $this->repository->buscarProdutoPorID($id);
        } catch (Exception $e) {
            throw new Exception("Erro ao buscar produto por ID: " . $e->getMessage());
        }
    }

    public function criarProduto($fornecedor, $nome, $marca, $descricao, $foto, $preco, $produtosVariacao){
        $idProduto = $this->repository->criarProduto($nome, $marca, $descricao, $fornecedor, $foto);
    
        if ($idProduto) {
            $produto = new Produto($idProduto, $fornecedor, $nome, $marca, $descricao, 0, $foto, $preco, $produtosVariacao);
            return $produto;
        } else {
            throw new Exception("Erro ao criar produto");
        }
    }
    
    public function editarProduto($idProduto, $nome, $marca, $descricao, $foto) {
        try {
            $produto = $this->repository->buscarProdutoPorID($idProduto);
            
            if ($produto) {
                $produto->editarProduto($nome, $marca, $descricao, $foto);
                
                $resultado = $this->repository->editarProduto($idProduto, $nome, $marca, $descricao, $foto);
                
                if ($resultado) {
                    return true;
                } else {
                    throw new Exception("Erro ao editar produto");
                }
            } else {
                throw new Exception("Produto não encontrado");
            }
        } catch (Exception $e) {
            return "Erro ao editar produto: " . $e->getMessage();
        }
    }

    public function removerProduto($idProduto) {
        try {
            $produto = $this->repository->buscarProdutoPorID($idProduto);
            
            if ($produto) {
                $produto->setDesativado(0);
                
                $resultado = $this->repository->desativarProduto($idProduto);
                
                if ($resultado) {
                    return true;
                } else {
                    throw new Exception("Erro ao desativar produto");
                }
            } else {
                throw new Exception("Produto não encontrado");
            }
        } catch (Exception $e) {
            echo "Erro ao remover produto: " . $e->getMessage();
        }
    }
    
}
