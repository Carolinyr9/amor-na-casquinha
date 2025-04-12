<?php
namespace app\controller2;

use app\repository\FuncionarioRepository;
use app\model2\Funcionario;
use app\utils\Logger;
use Exception;

class FuncionarioController {
    private $repository;

    public function __construct(FuncionarioRepository $repository) {
        $this->repository = $repository;
    }

    public function buscarFuncionarios() {
        try {
            $dados = $this->repository->buscarFuncionarios();
            $funcionarios = [];

            foreach ($dados as $dado) {
                $dado['senha'] = null; // não retornar a senha
                $funcionarios[] = new Funcionario(...array_values($dado));
            }

            return $funcionarios;
        } catch (Exception $e) {
            Logger::logError("Erro ao listar funcionários: " . $e->getMessage());
        }
    }

    public function buscarFuncionarioPorEmail($email) {
        try {
            $dado = $this->repository->buscarFuncionarioPorEmail($email);

            if ($dado) {
                $dado['senha'] = null;
                return new Funcionario(...array_values($dado));
            }

            return null;
        } catch (Exception $e) {
            Logger::logError("Erro ao buscar funcionário por email: " . $e->getMessage());
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
                $dados['idFuncionario'] = $idFuncionario;
                $dados['desativado'] = $dados['desativado'] ?? 0;
                $dados['idEndereco'] = $dados['idEndereco'] ?? null;

                new Funcionario(...array_values($dados));
                return true;
            } else {
                Logger::logError("Erro ao criar funcionário");
            }
        } catch (Exception $e) {
            Logger::logError("Erro ao criar funcionário: " . $e->getMessage());
        }
    }

    public function editarFuncionario($dados) {
        try {
            $emailAntigo = $dados['emailAntigo'];
            $emailNovo = $dados['emailNovo'];
            $nome = $dados['nome'];
            $telefone = $dados['telefone'];

            $funcionarioExistente = $this->repository->buscarFuncionarioPorEmail($emailAntigo);

            if ($funcionarioExistente) {
                $resultado = $this->repository->editarFuncionario($emailAntigo, $nome, $emailNovo, $telefone);

                if ($resultado) {
                    return true;
                } else {
                    Logger::logError("Erro ao editar funcionário");
                }
            } else {
                Logger::logError("Funcionário não encontrado");
            }
        } catch (Exception $e) {
            Logger::logError("Erro ao editar funcionário: " . $e->getMessage());
        }
    }

    public function desativarFuncionario($email) {
        try {
            $funcionario = $this->repository->buscarFuncionarioPorEmail($email);

            if ($funcionario) {
                $resultado = $this->repository->desativarFuncionario($email);

                if ($resultado) {
                    return true;
                } else {
                    Logger::logError("Erro ao desativar funcionário");
                }
            } else {
                Logger::logError("Funcionário não encontrado");
            }
        } catch (Exception $e) {
            Logger::logError("Erro ao desativar funcionário: " . $e->getMessage());
        }
    }
}
