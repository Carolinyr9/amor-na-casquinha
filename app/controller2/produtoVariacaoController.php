<?php
namespace app\controller2;

use app\repository\ProdutoVariacaoRepository;
use app\model2\ProdutoVariacao;
use app\utils\Logger;
use Exception;

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
                        $variacao['idVariacao'],
                        $variacao['desativado'],
                        $variacao['nomeVariacao'],
                        $variacao['precoVariacao'],
                        $variacao['fotoVariacao'],
                        $variacao['idProduto']
                    );
                }
                $variacoesModel[] = $variacao;
            }

            return $variacoesModel;
        } catch (Exception $e) {
            Logger::logError("Erro ao listar variações: " . $e->getMessage());
            return false;
        }
    }

    public function criarVariacao($dados) {
        try {
            $idVariacao = $this->repository->criarVariacao(
                $dados['idProduto'],
                $dados['nomeVariacao'],
                $dados['precoVariacao'],
                $dados['fotoVariacao']
            );

            if ($idVariacao) {
                new ProdutoVariacao(
                    $idVariacao,
                    $dados['desativado'],
                    $dados['nomeVariacao'],
                    $dados['precoVariacao'],
                    $dados['fotoVariacao'],
                    $dados['idProduto']
                );
                return true;
            } else {
                Logger::logError("Erro ao criar variação");
                return false;
            }
        } catch (Exception $e) {
            Logger::logError("Erro ao criar variação: " . $e->getMessage());
            return false;
        }
    }

    public function selecionarVariacaoPorID($id) {
        try {
            return $this->repository->selecionarVariacaoPorID($id);
        } catch (Exception $e) {
            Logger::logError("Erro ao selecionar variação por ID: " . $e->getMessage());
            return false;
        }
    }

    public function editarVariacao($dados) {
        try {
            $variacao = $this->repository->selecionarVariacaoPorID($dados['idVariacao']);

            if ($variacao) {
                $variacao->editarVariacao(
                    $dados['idProduto'],
                    $dados['nomeVariacao'],
                    $dados['precoVariacao'],
                    $dados['fotoVariacao']
                );

                $resultado = $this->repository->editarVariacao(
                    $dados['idVariacao'],
                    $dados['idProduto'],
                    $dados['nomeVariacao'],
                    $dados['precoVariacao'],
                    $dados['fotoVariacao']
                );

                if ($resultado) {
                    return true;
                } else {
                    Logger::logError("Erro ao editar variação");
                    return false;
                }
            } else {
                Logger::logError("Variação de produto não encontrada");
                return false;
            }
        } catch (Exception $e) {
            Logger::logError("Erro ao editar a variação: " . $e->getMessage());
            return false;
        }
    }

    public function desativarVariacao($idVariacao) {
        try {
            $variacao = $this->repository->selecionarVariacaoPorID($idVariacao);

            if ($variacao) {
                $variacao->setDesativado(1);
                $resultado = $this->repository->desativarVariacao($idVariacao);

                if ($resultado) {
                    return true;
                } else {
                    Logger::logError("Erro ao desativar variação");
                    return false;
                }
            } else {
                Logger::logError("Variação de produto não encontrada");
                return false;
            }
        } catch (Exception $e) {
            Logger::logError("Erro ao remover a variação: " . $e->getMessage());
            return false;
        }
    }
}
