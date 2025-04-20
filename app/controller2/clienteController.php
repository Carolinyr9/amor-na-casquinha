<?php
namespace app\controller2;

use app\model2\Cliente;
use app\repository\ClienteRepository;

class ClienteController {
    private $repositorio;

    public function __contruct() {
        $this->repositorio = new ClienteRepository();
    }

    public function listarClientePorEmail($email) {
        if(!isset($email) || empty($email)) {
            return ["error" => "Email não fornecido!"];
        }

        $dados = $this->repositorio->listarClientePorEmail($email);
        
        return $dados ?: ["error" => "Cliente não encontrado!"];
    }

    public function editarCliente($dados) {
        if(!filter_var($dados['email'], FILTER_VALIDATE_EMAIL)) {
            $erro = ["error" => "Email inválido! Insira um email válido."];
        }
        if(!is_numeric($dados['telefone'])) {
            $erro = ["error" => "Telefone inválido! Insira um telefone válido e sem formatação."];
        }

        if(isset($erro)) {
            return $erro;
        } else{
            $cliente = new Cliente(0, $dados['nome'], $dados['email'], $dados['telefone'], $dados['senha'], $dados['idEndereco']);

            $answer = $this->repositorio->editarCliente($cliente);
            
            return $answer ?: ["error" => "Erro ao editar cliente!"];
        }

    }
}
?>
