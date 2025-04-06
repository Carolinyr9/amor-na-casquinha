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
            return ['erro' => 'Nenhum entregador encontrado.'];
        }
    }

    public function listarEntregadorPorId($idEntregador) {
        if (!is_numeric($idEntregador)) {
            return ['erro' => 'ID inválido.'];
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
            return ['erro' => 'Entregador não encontrado.'];
        }
    }
}
?>
