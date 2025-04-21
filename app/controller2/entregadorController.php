<?php
namespace app\controller;

use app\model\Entregador;
use app\repository\EntregadorRepository;

class EntregadorController {
    private $repositorio;

    public function __construct() {
        $this->repositorio = new EntregadorRepository();
    }

    public function listarEntregadores() {
        $dados = $this->repositorio->listarEntregadores();

        if($dados) {
            $entregadores = [];

            foreach ($dados as $entregador) {
                $entregadores[] = new Entregador(
                    $entregador['id'],
                    $entregador['desativado'],
                    $entregador['perfil'],
                    $entregador['nome'],
                    $entregador['email'],
                    $entregador['telefone'],
                    $entregador['senha'],
                    $entregador['cnh']
                );
            }

            return $entregadores;
        } else {
            return Logger::logError("Erro ao listar entregadores: Nenhum entregador encontrado.");
        }
    }

    public function listarEntregadorPorId($idEntregador) {
        if (!is_numeric($idEntregador)) {
            return Logger::logError("Erro ao listar entregador: ID inválido.");
        }

        $dados = $this->repositorio->listarEntregadorPor($idEntregador);
        if($dados) {
            $entregador = new Entregador(
                $dados['id'],
                $dados['desativado'],
                $dados['perfil'],
                $dados['nome'],
                $dados['email'],
                $dados['telefone'],
                $dados['senha'],
                $dados['cnh']
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

        $dados = $this->repositorio->listarEntregadorPorEmail($email);
        if($dados) {
            $entregador = new Entregador(
                $dados['id'],
                $dados['desativado'],
                $dados['perfil'],
                $dados['nome'],
                $dados['email'],
                $dados['telefone'],
                $dados['senha'],
                $dados['cnh']
            );

            return $entregador;
        } else {
            return Logger::logError("Erro ao listar entregador: Entregador não encontrado.");
        }
    }
}
?>
