<?php
namespace app\controller2;

use app\repository\CategoriaProdutoRepository;
use app\model2\CategoriaProduto;
use app\utils\Logger;
use Exception;

class CategoriaProdutoController {
    private CategoriaProdutoRepository $repository;

    public function __construct(CategoriaProdutoRepository $repository = null) {
        $this->repository = $repository ?? new CategoriaProdutoRepository();
    }

    public function listarCategorias() {
        try {
            $dados = $this->repository->buscarCategoriasAtivas();
            $categorias = [];

            foreach ($dados as $categoria) {
                if (!$categoria instanceof CategoriaProduto) {
                    $categoria = new CategoriaProduto(
                        $categoria['id'],
                        $categoria['fornecedor'],
                        $categoria['nome'],
                        $categoria['marca'],
                        $categoria['descricao'],
                        0,
                        $categoria['foto']
                    );
                }
                $categorias[] = $categoria;
            }

            return $categorias;
        } catch (Exception $e) {
            Logger::logError("Erro ao listar categorias: " . $e->getMessage());
            return false;
        }
    }

    public function buscarCategoriaPorID($id) {
        try {
            return $this->repository->buscarCategoriaPorID($id);
        } catch (Exception $e) {
            Logger::logError("Erro ao buscar categoria por ID: " . $e->getMessage());
            return false;
        }
    }

    public function criarCategoria($dados) {
        try {
            $idCategoria = $this->repository->criarCategoria(
                $dados['nome'],
                $dados['marca'],
                $dados['descricao'],
                $dados['fornecedor'],
                $dados['foto']
            );

            if ($idCategoria) {
                new CategoriaProduto(
                    $idCategoria,
                    $dados['fornecedor'],
                    $dados['nome'],
                    $dados['marca'],
                    $dados['descricao'],
                    0,
                    $dados['foto'],
                    null,
                    []
                );
                return true;
            } else {
                Logger::logError("Erro ao criar categoria");
                return false;
            }
        } catch (Exception $e) {
            Logger::logError("Erro ao criar categoria: " . $e->getMessage());
            return false;
        }
    }

    public function editarCategoria($dados) {
        try {
            $categoria = $this->repository->buscarCategoriaPorID($dados['idCategoria']);

            if ($categoria) {
                $categoria->editarCategoria(
                    $dados['nome'],
                    $dados['marca'],
                    $dados['descricao'],
                    $dados['foto']
                );

                $resultado = $this->repository->editarCategoria(
                    $dados['idCategoria'],
                    $dados['nome'],
                    $dados['marca'],
                    $dados['descricao'],
                    $dados['foto']
                );

                if ($resultado) {
                    return true;
                } else {
                    Logger::logError("Erro ao editar categoria");
                    return false;
                }
            } else {
                Logger::logError("Categoria não encontrada");
                return false;
            }
        } catch (Exception $e) {
            Logger::logError("Erro ao editar categoria: " . $e->getMessage());
            return false;
        }
    }

    public function removerCategoria($idCategoria) {
        try {
            $categoria = $this->repository->buscarCategoriaPorID($idCategoria);

            if ($categoria) {
                $categoria->setDesativado(1);
                $resultado = $this->repository->desativarCategoria($idCategoria);

                if ($resultado) {
                    return true;
                } else {
                    Logger::logError("Erro ao desativar categoria");
                    return false;
                }
            } else {
                Logger::logError("Categoria não encontrada");
                return false;
            }
        } catch (Exception $e) {
            Logger::logError("Erro ao remover categoria: " . $e->getMessage());
            return false;
        }
    }
}
