<?php
namespace app\controller2;

use app\repository\ProdutoRepository;
use app\model2\Produto;
use app\utils\Logger;
use Exception;

class ProdutoController {
    private ProdutoRepository $repository;

    public function __construct(ProdutoRepository $repository = null) {
        $this->repository = $repository ?? new ProdutoRepository();
    }

    public function selecionarProdutosAtivos($idCategoria) {
        try {
            $produtosBanco = $this->repository->selecionarProdutosAtivosPorCategoria($idCategoria);
            $produtosModel = [];

            foreach ($produtosBanco as $produto) {
                if (!$produto instanceof Produto) {
                    $produto = new Produto(
                        $produto['idProduto'],
                        $produto['desativado'],
                        $produto['nome'],
                        $produto['preco'],
                        $produto['foto'],
                        $produto['categoria']
                    );
                }
                $produtosModel[] = $produto;
            }

            return $produtosModel;
        } catch (Exception $e) {
            Logger::logError("Erro ao listar produtos: " . $e->getMessage());
            return false;
        }
    }

    public function criarProduto($dados) {
        try {
            $idProduto = $this->repository->criarProduto(
                $dados['categoria'],
                $dados['nome'],
                $dados['preco'],
                $dados['foto']
            );

            if ($idProduto) {
                new Produto(
                    $idProduto,
                    0,
                    $dados['nome'],
                    $dados['preco'],
                    $dados['foto'],
                    $dados['categoria']
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

    public function selecionarProdutoPorID($id) {
        try {
            return $this->repository->selecionarProdutoPorID($id);
        } catch (Exception $e) {
            Logger::logError("Erro ao selecionar produto por ID: " . $e->getMessage());
            return false;
        }
    }

    public function editarProduto($dados) {
        try {
            $produto = $this->repository->selecionarProdutoPorID($dados['idProduto']);

            if ($produto) {
                $produto->editar(
                    $dados['categoria'],
                    $dados['nome'],
                    $dados['preco'],
                    $dados['foto']
                );

                $resultado = $this->repository->editarProduto(
                    $dados['idProduto'],
                    $dados['categoria'],
                    $dados['nome'],
                    $dados['preco'],
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

    public function desativarProduto($idProduto) {
        try {
            $produto = $this->repository->selecionarProdutoPorID($idProduto);

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
