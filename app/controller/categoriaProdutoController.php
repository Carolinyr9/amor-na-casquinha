<?php
namespace app\controller;

use app\repository\CategoriaProdutoRepository;
use app\model\CategoriaProduto;
use app\utils\helpers\Logger;
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

    public function buscarCategorias() {
        try {
            $dados = $this->repository->buscarCategorias();
            $categorias = [];

            foreach ($dados as $categoria) {
                if (!$categoria instanceof CategoriaProduto) {
                    $categoria = new CategoriaProduto(
                        $categoria['id'],
                        $categoria['fornecedor'],
                        $categoria['nome'],
                        $categoria['marca'],
                        $categoria['descricao'],
                        $categoria['desativado'],
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
            if (!isset($id) || empty($id)) {
                Logger::logError("Erro ao buscar categoria por ID: ID não fornecido!");
                return false;
            }
            return $this->repository->buscarCategoriaPorID($id);
        } catch (Exception $e) {
            Logger::logError("Erro ao buscar categoria por ID: " . $e->getMessage());
            return false;
        }
    }

    public function criarCategoria($dados) {
        try {
            if (empty($dados['nome']) || empty($dados['fornecedor']) ||
                empty($dados['marca']) || empty($dados['foto']) ||
                empty($dados['descricao'])) {
                Logger::logError("Dados inválidos para criação da categoria.");
                return false;
            }

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
            if (empty($dados['nome']) || 
                empty($dados['marca']) || 
                empty($dados['foto']) ||
                empty($dados['descricao'])) {
                Logger::logError("Dados inválidos para edição da categoria.");
                return false;
            }

            $categoria = $this->repository->buscarCategoriaPorID($dados['id']);

            if ($categoria) {
                $categoria->editarCategoria(
                    $dados['nome'],
                    $dados['marca'],
                    $dados['descricao'],
                    $dados['foto']
                );

                $resultado = $this->repository->editarCategoria(
                    $dados['id'],
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
            if (!isset($idCategoria) || empty($idCategoria)) {
                Logger::logError("Erro ao buscar remover categoria: ID não fornecido!");
                return false;
            }
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

    public function ativarCategoria($idCategoria) {
        try {
            if (!isset($idCategoria) || empty($idCategoria)) {
                Logger::logError("Erro ao buscar ativar categoria: ID não fornecido!");
                return false;
            }
            
            $resultado = $this->repository->ativarCategoria($idCategoria);

            $categoria = $this->repository->buscarCategoriaPorID($idCategoria);
            $categoria->setDesativado(0);

            if ($resultado) {
                return true;
            } else {
                Logger::logError("Erro ao ativar categoria");
                return false;
            }
            
        } catch (Exception $e) {
            Logger::logError("Erro ao ativar categoria: " . $e->getMessage());
            return false;
        }
    }
}
