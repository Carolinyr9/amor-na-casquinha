<?php
namespace app\controller2;

use app\repository\EstoqueRepository;
use app\model2\Estoque;
use Exception;

class EstoqueController {
    private $repository;

    public function __construct(EstoqueRepository $repository) {
        $this->repository = $repository;
    }

    public function criarProdutoEstoque($idProduto, $idVariacao, $lote, $dataEntrada, $dataFabricacao, $dataVencimento, $quantidadeMinima, $quantidade, $precoCompra) {
        try {
            $resultado = $this->repository->criarProdutoEstoque($idProduto, $idVariacao, $lote, $dataEntrada, $dataFabricacao, $dataVencimento, $quantidadeMinima, $quantidade, $precoCompra);
            
            if ($resultado) {
                return new Estoque($idProduto, $idVariacao, $lote, $dataEntrada, $quantidade, $dataFabricacao, $dataVencimento, $precoCompra, $quantidadeMinima, 0, 0, "", 0);
            } else {
                throw new Exception("Erro ao criar produto no estoque.");
            }
        } catch (Exception $e) {
            throw new Exception("Erro ao criar produto: " . $e->getMessage());
        }
    }

    public function listarEstoque() {
        try {
            $dados = $this->repository->listarEstoque();
            $estoque = [];
    
            foreach ($dados as $produto) {
                if (!$produto instanceof Estoque) {
                    $produto = new Estoque(
                        $produto['idEstoque'],
                        $produto['idProduto'],
                        $produto['idVariacao'],
                        $produto['dtEntrada'],
                        $produto['quantidade'],
                        $produto['dtFabricacao'],
                        $produto['dtVencimento'],
                        $produto['lote'],
                        $produto['precoCompra'],
                        $produto['qtdVendida'],
                        $produto['qtdOcorrencia'],
                        $produto['ocorrencia'],
                        $produto['desativado']
                    );
                }
                $estoque[] = $produto;
            }
    
            return $estoque;
        } catch (Exception $e) {
            throw new Exception("Erro ao listar estoque: " . $e->getMessage());
        }
    }
    
    public function selecionarProdutoEstoquePorID(int $idEstoque) {
        try {
            return $this->repository->selecionarProdutoEstoquePorID($idEstoque);
        } catch (Exception $e) {
            throw new Exception("Erro ao buscar produto por ID: " . $e->getMessage());
        }
    }

    public function editarProdutoEstoque(Estoque $produto) {
        try {
            $estoqueProduto = $this->repository->selecionarProdutoEstoquePorID($produto->idEstoque);

            if ($estoqueProduto) {
                $estoqueProduto->editarProdutoEstoque(
                    $estoqueProduto->getIdEstoque(),
                    $estoqueProduto->getDtEntrada(),
                    $estoqueProduto->getQuantidade(),
                    $estoqueProduto->getDtFabricacao(),
                    $estoqueProduto->getDtVencimento(),
                    $estoqueProduto->getPrecoCompra(),
                    $estoqueProduto->getQtdMinima(),
                    $estoqueProduto->getQtdOcorrencia(),
                    $estoqueProduto->getOcorrencia()
                );

                $resultado = $this->repository->editarProdutoEstoque($produto);

                if ($resultado) {
                    return true;
                } else {
                    throw new Exception("Erro ao editar produto.");
                }
            } else {
                throw new Exception("Produto não encontrado.");
            }
        } catch (Exception $e) {
            throw new Exception("Erro ao editar produto: " . $e->getMessage());
        }
    }

    public function desativarProdutoEstoque(int $idEstoque) {
        try {
            $produto = $this->repository->selecionarProdutoEstoquePorID($idEstoque);

            if ($produto) {
                $produto->setDesativado(1);

                $resultado = $this->repository->desativarProdutoEstoque($idEstoque);

                if ($resultado) {
                    return true;
                } else {
                    throw new Exception("Erro ao desativar produto.");
                }
            } else {
                throw new Exception("Produto não encontrado.");
            }
        } catch (Exception $e) {
            throw new Exception("Erro ao remover produto: " . $e->getMessage());
        }
    }

    public function verificarQuantidadeMinima(){
        try {
            return $this->repository->verificarQuantidadeMinima();
        } catch (Exception $e) {
            throw new Exception("Erro ao verificar quantidade mínima: " . $e->getMessage());
        }
    }
}
?>
