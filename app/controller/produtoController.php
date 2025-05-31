<?php
namespace app\controller;

use app\repository\ProdutoRepository;
use app\model\Produto;
use app\utils\helpers\Logger;
use Exception;

class ProdutoController {
    private ProdutoRepository $repository;

    public function __construct(ProdutoRepository $repository = null) {
        $this->repository = $repository ?? new ProdutoRepository();
    }

    public function selecionarProdutosAtivos($idCategoria) {
        try {
            if (!isset($idCategoria) || empty($idCategoria)) {
                Logger::logError("Erro ao buscar produto por ID categoria: ID não fornecido!");
                return false;
            }

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

    public function selecionarProdutos() {
        try {

            $produtosBanco = $this->repository->selecionarProdutos();
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
            if (empty($dados['nome']) || empty($dados['categoria']) ||
                empty($dados['preco']) || empty($dados['foto'])) {
                Logger::logError("Dados inválidos para criação do produto.");
                return false;
            }

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
                return $idProduto;
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
            if (!isset($id) || empty($id)) {
                Logger::logError("Erro ao buscar produto por ID: ID não fornecido!");
                return false;
            }

            return $this->repository->selecionarProdutoPorID($id);
        } catch (Exception $e) {
            Logger::logError("Erro ao selecionar produto por ID: " . $e->getMessage());
            return false;
        }
    }

    public function editarProduto($dados) {
        try {
            if (empty($dados['nome']) || empty($dados['preco']) || empty($dados['foto'])) {
                Logger::logError("Dados inválidos para edição do produto.");
                return false;
            }

            $produto = $this->repository->selecionarProdutoPorID($dados['id']);

            if ($produto) {
                $produto->editar(
                    $dados['nome'],
                    $dados['preco'],
                    $dados['foto']
                );

                $resultado = $this->repository->editarProduto(
                    $dados['id'],
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

    public function desativarProduto($id) {
        try {
            if (!isset($id) || empty($id)) {
                Logger::logError("Erro ao buscar desativar produto por ID: ID não fornecido!");
                return false;
            }

            $produto = $this->repository->selecionarProdutoPorID($id);

            if ($produto) {
                $produto->setDesativado(1);
                $resultado = $this->repository->desativarProduto($id);

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
