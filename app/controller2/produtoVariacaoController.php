<?php
namespace app\controller2;

use app\repository\ProdutoVariacaoRepository;
use app\model2\ProdutoVariacao;
use Exception;
use PDOException;

class ProdutoVariacaoController {
    private ProdutoVariacaoRepository $repository;

    public function __construct(ProdutoVariacaoRepository $repository) {
        $this->repository = $repository;
    }

    public function selecionarVariacaoAtiva($idProduto) {
        try {
            $variacoesBanco = $this->repository->selecionarVariacaoAtiva($idProduto);
            $variacoesModel = [];
    
            foreach ($variacoesBanco as $variacao) {
                if (!$variacao instanceof ProdutoVariacao) {
                    
                    $variacao = new ProdutoVariacao(
                        $dados['idVariacao'],
                        $dados['desativado'],
                        $dados['nomeVariacao'],
                        $dados['precoVariacao'],
                        $dados['fotoVariacao'],
                        $dados['idProduto']
                    );
                }
                $variacoesModel[] = $variacao;
            }
    
            return $variacoesModel;
        } catch (Exception $e) {
            throw new Exception("Erro ao listar variações: " . $e->getMessage());
        }
    }
    

    public function criarVariacao($idProduto, $nomeProduto, $preco, $imagem) {
        $idVariacao = $this->repository->criarVariacao($idProduto, $nomeProduto, $preco, $imagem);
    
        if ($idVariacao) {
            return new ProdutoVariacao($idVariacao, $idProduto, $nomeProduto, $preco, $imagem);
        } else {
            throw new Exception("Erro ao criar variação");
        }
    }

    public function selecionarVariacaoPorID($id) {
        return $this->repository->selecionarVariacaoPorID($id);
    }

    public function editarVariacao($idVariacao, $idProduto, $nomeProduto, $preco, $imagemProduto) {
        try {
            $variacao = $this->repository->selecionarVariacaoPorID($idVariacao);
            
            if ($variacao) {
                $variacao->editarVariacao($idProduto, $nomeProduto, $preco, $imagemProduto);
                $resultado = $this->repository->editarVariacao($idVariacao, $idProduto, $nomeProduto, $preco, $imagemProduto);
                
                if ($resultado) {
                    return true;
                } else {
                    throw new Exception("Erro ao editar variação");
                }
            } else {
                throw new Exception("Variação de produto não encontrada");
            }
        } catch (Exception $e) {
            return "Erro ao editar a variação: " . $e->getMessage();
        }
    }

    public function desativarVariacao($idVariacao) {
        try {
            $variacao = $this->repository->selecionarVariacaoPorID($idVariacao);
            
            if ($variacao) {
                $variacao->setDesativado(0);
                $resultado = $this->repository->desativarVariacao($idVariacao);
                
                if ($resultado) {
                    return true;
                } else {
                    throw new Exception("Erro ao desativar variação");
                }
            } else {
                throw new Exception("Variação de produto não encontrada");
            }
        } catch (Exception $e) {
            echo "Erro ao remover a variação: " . $e->getMessage();
        }
    }
}
