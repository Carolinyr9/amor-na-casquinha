<?php
namespace app\controller2;

use app\repository\FuncionarioRepository;
use app\model2\Funcionario;
use app\utils\Logger;
use Exception;

class FuncionarioController {
    private $repository;

    public function __construct(FuncionarioRepository $repository = null) {
        $this->repository = $repository ?? new FuncionarioRepository();
    }

    public function listarFuncionario() {
        try {
            $dados = $this->repository->buscarFuncionarios();
            $funcionarios = [];

            foreach ($dados as $func) {
                if (!$func instanceof Funcionario) {
                    
                    $funcionario = new Funcionario(
                        $func['idFuncionario'],
                        $func['nome'],
                        $func['telefone'],
                        $func['email'],
                        null,
                        $func['adm'],
                        $func['desativado'] == 0,
                        $func['idEndereco']
                    );
                } else {
                    $funcionario = $func;
                }
                $funcionarios[] = $funcionario;
            }

            return $funcionarios;
        } catch (Exception $e) {
            Logger::logError("Erro ao listar funcionarios: " . $e->getMessage());
        }
    }

    public function buscarFuncionarioPorEmail($email) {
        try {
            return $this->repository->buscarFuncionarioPorEmail($email);
            
        } catch (Exception $e) {
            Logger::logError("Erro ao buscar funcionario por email: " . $e->getMessage());
        }
    }

    public function criarFuncionario($dados) {
        try {
            $idFuncionario = $this->repository->criarFuncionario(
                $dados['nome'],
                $dados['email'],
                $dados['telefone'],
                $dados['senha'],
                $dados['adm']
            );

            if ($idFuncionario) {
                $funcionario = new Funcionario(
                    $idFuncionario,
                    $dados['nome'],
                    $dados['telefone'],
                    $dados['email'],
                    $dados['senha'],
                    $dados['adm'],
                    0,
                    $dados['idEndereco'] ?? null
                );
                return $funcionario;
            } 
        } catch (Exception $e) {
            Logger::logError("Erro ao criar funcionario: " . $e->getMessage());
        }
    }

    public function editarFuncionario($dados) {
        try {
            $funcionario = $this->repository->buscarFuncionarioPorEmail($dados['emailAntigo']);

            if ($funcionario) {
                $funcionario->editarFuncionario($dados['nome'], $dados['email'], $dados['telefone']);

                $resultado = $this->repository->editarFuncionario(
                    $dados['emailAntigo'],
                    $dados['nome'],
                    $dados['email'],
                    $dados['telefone']
                );

                if ($resultado) {
                    return true;
                } else {
                    throw new Exception("Erro ao editar funcionario");
                }
            } else {
                Logger::logError("Funcionario nao encontrado para edicao");
            }
        } catch (Exception $e) {
            Logger::logError("Erro ao editar funcionario: " . $e->getMessage());
        }
    }

    public function desativarFuncionario($email) {
        try {
            $funcionario = $this->repository->buscarFuncionarioPorEmail($email);

            if ($funcionario) {
                $funcionario->setDesativado(1);
                $resultado = $this->repository->desativarFuncionario($email);

                if ($resultado) {
                    return true;
                } else {
                    Logger::logError("Erro ao desativar funcionario");
                }
            } else {
                Logger::logError("Funcionario nao encontrado");
            }
        } catch (Exception $e) {
            Logger::logError("Erro ao desativar funcionario: " . $e->getMessage());
        }
    }
}
?>
