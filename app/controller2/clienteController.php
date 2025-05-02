<?php
namespace app\controller2;

use app\model2\Cliente;
use app\repository\ClienteRepository;
use app\config\Logger;

class ClienteController {
    private $repositorio;

    public function __contruct() {
        $this->repositorio = new ClienteRepository();
    }

    public function listarClientePorEmail($email) {
        if(!isset($email) || empty($email)) {
            Logger::logError("Erro ao listar cliente: E-mail não fornecido!");
            return false;
        }

        $dados = $this->repositorio->listarClientePorEmail($email);
        
        if($dados) {
            return $dados;
        } else {
            Logger::logError("Erro ao listar cliente: Cliente não encotrado!");
            return false;
        }
    }

    public function editarCliente($dados) {
        if(!filter_var($dados['email'], FILTER_VALIDATE_EMAIL)) {
            Logger::logError("Erro ao editar cliente: E-mail inválido!");
            return false;
        }
        if(!is_numeric($dados['telefone'])) {
            Logger::logError("Erro ao editar cliente: Telefone inválido!");
            return false;
        }

        $cliente = new Cliente(0, $dados['nome'], $dados['email'], $dados['telefone'], $dados['senha'], $dados['idEndereco']);

        $answer = $this->repositorio->editarCliente($cliente);
        
        if($answer) {
            Logger::logInfo("Cliente editado com sucesso!");
            return true;
        } else {
            Logger::logError("Erro ao editar cliente: Erro ao editar cliente!");
            return false;
        }

    }
}
?>
