<?php
namespace app\controller2;

use app\repository\EstoqueRepository;
use app\model2\Estoque;
use app\utils\Logger;
use Exception;

class EstoqueController {
    private $repository;

    public function __construct(EstoqueRepository $repository) {
        $this->repository = $repository;
    }

    public function criarProdutoEstoque($dados) {
        try {
            $resultado = $this->repository->criarProdutoEstoque(
                $dados['idProduto'] ?? null,
                $dados['idVariacao'] ?? null,
                $dados['lote'] ?? '',
                $dados['dtEntrada'] ?? '',
                $dados['dtFabricacao'] ?? '',
                $dados['dtVencimento'] ?? '',
                $dados['qtdMinima'] ?? 0,
                $dados['quantidade'] ?? 0,
                $dados['precoCompra'] ?? 0.0
            );

            if ($resultado) {
                return true;
            } else {
                Logger::logError("Erro ao criar produto no estoque.");
            }
        } catch (Exception $e) {
            Logger::logError("Erro ao criar produto: " . $e->getMessage());
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
            Logger::logError("Erro ao listar estoque: " . $e->getMessage());
        }
    }

    public function selecionarProdutoEstoquePorID($idEstoque) {
        try {
            return $this->repository->selecionarProdutoEstoquePorID($idEstoque);
        } catch (Exception $e) {
            Logger::logError("Erro ao buscar produto por ID: " . $e->getMessage());
        }
    }

    public function editarProdutoEstoque($dados) {
        try {
            $estoqueProduto = $this->repository->selecionarProdutoEstoquePorID($dados['idEstoque']);

            if ($estoqueProduto) {
                $estoqueProduto->editarProdutoEstoque(
                    $dados['idEstoque'],
                    $dados['dtEntrada'],
                    $dados['quantidade'],
                    $dados['dtFabricacao'],
                    $dados['dtVencimento'],
                    $dados['precoCompra'],
                    $dados['qtdMinima'],
                    $dados['qtdOcorrencia'],
                    $dados['ocorrencia']
                );

                $resultado = $this->repository->editarProdutoEstoque(
                    $dados['idEstoque'],
                    $dados['dtEntrada'],
                    $dados['quantidade'],
                    $dados['dtFabricacao'],
                    $dados['dtVencimento'],
                    $dados['precoCompra'],
                    $dados['qtdMinima'],
                    $dados['qtdOcorrencia'],
                    $dados['ocorrencia']
                );

                if ($resultado) {
                    return true;
                } else {
                    Logger::logError("Erro ao editar produto.");
                }
            } else {
                Logger::logError("Produto não encontrado.");
            }
        } catch (Exception $e) {
            Logger::logError("Erro ao editar produto: " . $e->getMessage());
        }
    }

    public function desativarProdutoEstoque($idEstoque) {
        try {
            $produto = $this->repository->selecionarProdutoEstoquePorID($idEstoque);

            if ($produto) {
                $produto->setDesativado(1);

                $resultado = $this->repository->desativarProdutoEstoque($idEstoque);

                if ($resultado) {
                    return true;
                } else {
                    Logger::logError("Erro ao desativar produto.");
                }
            } else {
                Logger::logError("Produto não encontrado.");
            }
        } catch (Exception $e) {
            Logger::logError("Erro ao remover produto: " . $e->getMessage());
        }
    }

    public function verificarQuantidadeMinima(){
        try {
            return $this->repository->verificarQuantidadeMinima();
        } catch (Exception $e) {
            Logger::logError("Erro ao verificar quantidade mínima: " . $e->getMessage());
        }
    }
}
?>
