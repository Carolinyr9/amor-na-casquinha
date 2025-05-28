<?php
namespace app\controller;

use app\model\Entregador;
use app\repository\EntregadorRepository;
use app\utils\helpers\Logger;
use Exception;

class EntregadorController {
    private $repository;

    public function __construct(EntregadorRepository $repository = null) {
        $this->repository = $repository ?? new EntregadorRepository();
    }

    public function criarEntregador($dados){
        try{
            if (empty($dados['nome']) || empty($dados['email']) || 
                !filter_var($dados['email'], FILTER_VALIDATE_EMAIL) ||
                empty($dados['telefone']) || empty($dados['cnh']) ||
                empty($dados['senha'])) {
                Logger::logError("Dados inválidos para criação do entregador.");
                return false;
            }

            $idEntregador = $this->repository->criarEntregador($dados['nome'], $dados['email'], $dados['telefone'], $dados['cnh'], $dados['senha']);

            if($idEntregador){
                $desativado = 0;
                $entregador = new Entregador(
                    $idEntregador,
                    $desativado,
                    $dados['nome'], 
                    $dados['email'], 
                    $dados['telefone'],
                    $dados['senha'],
                    $dados['cnh']
                );

                return $entregador;
                
            } else {
                Logger::logError("Erro ao criar entregador");
            }
        } catch (Exception $e) {
            Logger::logError("Erro ao criar entregador: " . $e->getMessage());
        }
    }

    public function listarEntregadores() {
        $dados = $this->repository->listarEntregadores();

        if($dados) {
            $entregadores = [];

            foreach ($dados as $entregador) {
                $entregadores[] = new Entregador(
                    $entregador['idEntregador'],
                    $entregador['desativado'],
                    $entregador['nome'],
                    $entregador['email'],
                    $entregador['telefone'],
                    $entregador['senha'],
                    $entregador['cnh'],
                    $entregador['perfil']
                );
            }

            return $entregadores;
        } else {
            return Logger::logError("Erro ao listar entregadores: Nenhum entregador encontrado.");
        }
    }

    public function listarEntregadorPorId($idEntregador) {
        if (!is_numeric($idEntregador) || !isset($idEntregador) || empty($idEntregador)) {
            return Logger::logError("Erro ao listar entregador: ID inválido.");
        }

        $dados = $this->repository->listarEntregadorPorId($idEntregador);
        if($dados) {
            $entregador = new Entregador(
                $dados['idEntregador'],
                $dados['desativado'],
                $dados['nome'],
                $dados['email'],
                $dados['telefone'],
                $dados['senha'],
                $dados['cnh'],
                $dados['perfil']
            );

            return $entregador;
        } else {
            return Logger::logError("Erro ao listar entregador: Entregador não encontrado.");
        }
    }

    public function listarEntregadorPorEmail($email) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return Logger::logError("Erro ao listar entregador: Email inválido.");
        }

        $dados = $this->repository->listarEntregadorPorEmail($email);
        if($dados) {
            $entregador = new Entregador(
                $dados['idEntregador'],
                $dados['desativado'],
                $dados['nome'],
                $dados['email'],
                $dados['telefone'],
                $dados['senha'],
                $dados['cnh'],
                $dados['perfil']
            );

            return $entregador;
        } else {
            return Logger::logError("Erro ao listar entregador: Entregador não encontrado.");
        }
    }

    public function editarEntregador($dados) {
        try {
            if (empty($dados['nome']) || empty($dados['email']) || 
                !filter_var($dados['email'], FILTER_VALIDATE_EMAIL) ||
                empty($dados['telefone'])) {
                Logger::logError("Dados inválidos para edição do entregador.");
                return false;
            }

            $entregador = $this->listarEntregadorPorEmail($dados['emailAntigo']);
    
            if (!$entregador) {
                Logger::logError("Entregador não encontrado para edição.");
                return false;
            }
    
            $entregador->editarEntregador(
                $dados['nome'],
                $dados['email'],
                $dados['telefone']
            );
    
            $resultado = $this->repository->editarEntregador(
                $dados['emailAntigo'],
                $dados['nome'],
                $dados['email'],
                $dados['telefone']
            );
    
            if (!$resultado) {
                Logger::logError("Erro ao editar entregador no repositório.");
                return false;
            }
    
            return true;
    
        } catch (Exception $e) {
            Logger::logError("Erro ao editar entregador: " . $e->getMessage());
            return false;
        }
    }    

    public function desativarEntregador($email) {
        try {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return Logger::logError("Erro ao desativar entregador: E-mail inválido.");
            }

            $entregador = $this->listarEntregadorPorEmail($email);
    
            if (!$entregador) {
                Logger::logError("Entregador não encontrado para desativação.");
                return false;
            }
    
            $entregador->setDesativado(1);
    
            $resultado = $this->repository->desativarEntregador($email);
    
            if (!$resultado) {
                Logger::logError("Erro ao desativar entregador no repositório.");
                return false;
            }
    
            return true;
    
        } catch (Exception $e) {
            Logger::logError("Erro ao desativar entregador: " . $e->getMessage());
            return false;
        }
    }    

}
?>
