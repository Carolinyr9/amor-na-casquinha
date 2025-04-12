<?php
namespace app\controller2;

use app\repository\ProdutoRepository;
use app\model2\Produto;
use app\utils\Logger;
use Exception;

class ProdutoController {
    private ProdutoRepository $repository;

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
            Logger::logError("Erro ao listar produtos: " . $e->getMessage());
            return false;
        }
    }

    public function buscarProdutoPorID($id) {
        try {
            return $this->repository->buscarProdutoPorID($id);
        } catch (Exception $e) {
            Logger::logError("Erro ao buscar produto por ID: " . $e->getMessage());
            return false;
        }
    }

    public function criarProduto($dados) {
        try {
            $idProduto = $this->repository->criarProduto(
                $dados['nome'],
                $dados['marca'],
                $dados['descricao'],
                $dados['fornecedor'],
                $dados['foto']
            );

            if ($idProduto) {
                new Produto(
                    $idProduto,
                    $dados['fornecedor'],
                    $dados['nome'],
                    $dados['marca'],
                    $dados['descricao'],
                    0,
                    $dados['foto'],
                    null
                );
                return true;
            } else {
                Logger::logError("Erro ao criar produto");
                return false;
            }
        } catch (Exception $e) {
            Logger::logError("Erro ao criar produto: " . $e->getMessage());
            return false;
        }
    }

    public function editarProduto($dados) {
        try {
            $produto = $this->repository->buscarProdutoPorID($dados['idProduto']);

            if ($produto) {
                $produto->editarProduto(
                    $dados['nome'],
                    $dados['marca'],
                    $dados['descricao'],
                    $dados['foto']
                );

                $resultado = $this->repository->editarProduto(
                    $dados['idProduto'],
                    $dados['nome'],
                    $dados['marca'],
                    $dados['descricao'],
                    $dados['foto']
                );

                if ($resultado) {
                    return true;
                } else {
                    Logger::logError("Erro ao editar produto");
                    return false;
                }
            } else {
                Logger::logError("Produto não encontrado");
                return false;
            }
        } catch (Exception $e) {
            Logger::logError("Erro ao editar produto: " . $e->getMessage());
            return false;
        }
    }

    public function removerProduto($idProduto) {
        try {
            $produto = $this->repository->buscarProdutoPorID($idProduto);

            if ($produto) {
                $produto->setDesativado(1);

                $resultado = $this->repository->desativarProduto($idProduto);

                if ($resultado) {
                    return true;
                } else {
                    Logger::logError("Erro ao desativar produto");
                    return false;
                }
            } else {
                Logger::logError("Produto não encontrado");
                return false;
            }
        } catch (Exception $e) {
            Logger::logError("Erro ao remover produto: " . $e->getMessage());
            return false;
        }
    }
}
