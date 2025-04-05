<?php
namespace app\controller2;

use app\repository\FuncionarioRepository;
use app\model2\Funcionario;
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
                $funcionario = new Funcionario(
                    $dado['idFuncionario'] ?? null,
                    $dado['desativado'] ?? 0,
                    $dado['adm'] ?? 0,
                    $dado['nome'] ?? '',
                    $dado['telefone'] ?? '',
                    $dado['email'] ?? '',
                    null, // não retornar a senha
                    $dado['idEndereco'] ?? null
                );
                $funcionarios[] = $funcionario;
            }

            return $funcionarios;
        } catch (Exception $e) {
            throw new Exception("Erro ao listar funcionários: " . $e->getMessage());
        }
    }

    public function buscarFuncionarioPorEmail($email) {
        try {
            $dado = $this->repository->buscarFuncionarioPorEmail($email);

            if ($dado) {
                return new Funcionario(
                    $dado['idFuncionario'] ?? null,
                    $dado['desativado'] ?? 0,
                    $dado['adm'] ?? 0,
                    $dado['nome'] ?? '',
                    $dado['telefone'] ?? '',
                    $dado['email'] ?? '',
                    null,
                    $dado['idEndereco'] ?? null
                );
            }

            return null;
        } catch (Exception $e) {
            throw new Exception("Erro ao buscar funcionário por email: " . $e->getMessage());
        }
    }

    public function criarFuncionario($nome, $email, $telefone, $senha, $adm) {
        try {
            $idFuncionario = $this->repository->criarFuncionario($nome, $email, $telefone, $senha, $adm);

            if ($idFuncionario) {
                return new Funcionario($idFuncionario, 0, $adm, $nome, $telefone, $email, null, null);
            } else {
                throw new Exception("Erro ao criar funcionário");
            }
        } catch (Exception $e) {
            throw new Exception("Erro ao criar funcionário: " . $e->getMessage());
        }
    }

    public function editarFuncionario($emailAntigo, $nome, $emailNovo, $telefone) {
        try {
            $funcionarioExistente = $this->repository->buscarFuncionarioPorEmail($emailAntigo);

            if ($funcionarioExistente) {
                $funcionario->editarFuncionario($nome, $emailNovo, $telefone);

                $resultado = $this->repository->editarFuncionario($emailAntigo, $nome, $emailNovo, $telefone);

                if ($resultado) {
                    return true;
                } else {
                    throw new Exception("Erro ao editar funcionário");
                }
            } else {
                throw new Exception("Funcionário não encontrado");
            }
        } catch (Exception $e) {
            return "Erro ao editar funcionário: " . $e->getMessage();
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
                    throw new Exception("Erro ao desativar funcionário");
                }
            } else {
                throw new Exception("Funcionário não encontrado");
            }
        } catch (Exception $e) {
            echo "Erro ao desativar funcionário: " . $e->getMessage();
        }
    }
}
