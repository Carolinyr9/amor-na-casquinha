<?php
namespace app\controller;

use app\repository\EstoqueRepository;
use app\model\Estoque;
use app\utils\helpers\Logger;
use Exception;

class EstoqueController {
    private $repository;

    public function __construct(EstoqueRepository $repository = null) {
        $this->repository = $repository ?? new EstoqueRepository();
    }

    public function criarProdutoEstoque($dados) {
        try {
            if (empty($dados['idCategoria']) || empty($dados['idProduto']) || 
                empty($dados['lote']) || empty($dados['dtEntrada']) ||
                empty($dados['dtFabricacao']) || empty($dados['dtVencimento']) ||
                empty($dados['qtdMinima']) || empty($dados['quantidade']) ||
                empty($dados['precoCompra'])) {
                Logger::logError("Dados inválidos para criação do produto no estoque.");
                return false;
            }

            $idEstoque = $this->repository->criarProdutoEstoque(
                $dados['idCategoria'] ?? null,
                $dados['idProduto'] ?? null,
                $dados['lote'] ?? '',
                $dados['dtEntrada'] ?? '',
                $dados['dtFabricacao'] ?? '',
                $dados['dtVencimento'] ?? '',
                $dados['qtdMinima'] ?? 0,
                $dados['quantidade'] ?? 0,
                $dados['precoCompra'] ?? 0.0
            );

            if ($idEstoque) {
                new Estoque(
                    $idEstoque, 
                    $dados['idCategoria'] ?? null,
                    $dados['idProduto'] ?? null,
                    $dados['dtEntrada'] ?? '',
                    $dados['quantidade'] ?? 0,
                    $dados['dtFabricacao'] ?? '',
                    $dados['dtVencimento'] ?? '',
                    $dados['lote'] ?? '',
                    $dados['precoCompra'] ?? 0.0,
                    $dados['qtdMinima'] ?? 0,
                    null,
                    null,
                    null,
                    0
                );
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
                if ($produto->getDtVencimento() < date('Y-m-d')) {
                    Logger::logError("Produto com data de vencimento ultrapassada: " . $produto->getIdEstoque());
                    $this->repository->decrementarProduto(0, $produto->getIdEstoque());
                    $this->repository->desativarProduto($produto->getIdEstoque());
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
            if (!is_numeric($idEstoque) || !isset($idEstoque) || empty($idEstoque)) {
                return Logger::logError("Erro ao selecionar produto do estoque: ID inválido.");
            }
            return $this->repository->selecionarProdutoEstoquePorID($idEstoque);
        } catch (Exception $e) {
            Logger::logError("Erro ao buscar produto por ID: " . $e->getMessage());
        }
    }

    public function editarProdutoEstoque($dados) {
        try {
            if (empty($dados['ocorrencia']) || empty($dados['qtdOcorrencia']) || 
                empty($dados['lote']) || empty($dados['dtEntrada']) ||
                empty($dados['dtFabricacao']) || empty($dados['dtVencimento']) ||
                empty($dados['qtdMinima']) || empty($dados['quantidade']) ||
                empty($dados['precoCompra']) || empty($dados['idEstoque'])) {
                Logger::logError("Dados inválidos para edição do produto no estoque.");
                return false;
            }
            $estoqueProduto = $this->repository->selecionarProdutoEstoquePorID($dados['idEstoque']);

            if ($estoqueProduto) {
                $estoqueProduto->editarProdutoEstoque(
                    $dados['lote'],
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
                    $dados['lote'],
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
                Logger::logError("Produto não encontrado no estoque.");
            }
        } catch (Exception $e) {
            Logger::logError("Erro ao editar produto: " . $e->getMessage());
        }
    }

    public function desativarProdutoEstoque($idEstoque) {
        try {
            if (!is_numeric($idEstoque) || !isset($idEstoque) || empty($idEstoque)) {
                return Logger::logError("Erro ao desativar produto do estoque: ID inválido.");
            }

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

    public function decrementarQuantidade($dados) {
        try {
            if (empty($dados['idProduto']) || empty($dados['quantidade'])) {
                Logger::logError("Dados inválidos para decrementação do produto no estoque.");
                return false;
            }

            $produto = $this->repository->selecionarProdutoEstoquePorID($dados['idProduto']);

            if ($produto) {

                if($produto->getDtVencimento() < date('Y-m-d')) {
                    Logger::logError("Produto com data de vencimento ultrapassada: " . $produto->getIdEstoque());
                    $this->repository->decrementarProduto(0, $dados['idProduto']);
                    return false;
                }

                $novaQuantidade = $this->calcularNovaQuantidade($produto->getQuantidade(), (int)$dados['quantidade']);

                if ($novaQuantidade < 0) {
                    Logger::logError("Quantidade insuficiente em estoque do produto: " . $dados['idProduto']);
                    $this->repository->desativarProduto($dados['idProduto']);
                    return false;
                }

                $resultado = $this->repository->decrementarProduto($novaQuantidade, $dados['idProduto']);

                if ($resultado) {
                    return true;
                } else {
                    Logger::logError("Erro ao decrementar produto.");
                }
            } else {
                Logger::logError("Produto não encontrado.");
            }
        } catch (Exception $e) {
            Logger::logError("Erro ao decrementar produto: " . $e->getMessage());
        }

        return false;
    }


    public function verificarQuantidadeMinima(){
        try {
            return $this->repository->verificarQuantidadeMinima();
        } catch (Exception $e) {
            Logger::logError("Erro ao verificar quantidade mínima: " . $e->getMessage());
        }
    }

    public function desativarProduto($idProduto){
        try {
            if (!is_numeric($idProduto) || !isset($idProduto) || empty($idProduto)) {
                return Logger::logError("Erro ao desativar produto: ID inválido.");
            }

            $resultado = $this->repository->desativarProduto($idProduto);

            if ($resultado) {
                return true;
            } else {
                Logger::logError("Erro ao desativar produto.");
            }
        } catch (Exception $e) {
            Logger::logError("Erro ao remover produto: " . $e->getMessage());
        }
    }

    private function calcularNovaQuantidade($quantidadeAntiga, $quantidadeDecrementada){
        return $quantidadeAntiga - $quantidadeDecrementada;
    }
}
?>
