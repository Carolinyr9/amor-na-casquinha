<?php 
namespace app\controller;

use app\model\Funcionario;

class FuncionarioController{
    private $funcionario;

    public function __construct(){
        $this->funcionario = new Funcionarios();
    }

    public function listarFunc(){
        $listaFuncionarios = $this->funcionario->listarFunc();

        if($listaFuncionarios == null || count($listaFuncionarios) == 0){
            return "Nenhum funcionário encontrado.";
        }else{ 
            return $listaFuncionarios;
        }
    }

    public function listarFuncionarioEmail($email){
        $funDados = $this->funcionario->listarFuncionarioEmail($email);
        return $funDados;
    }

    public function inserirFunc($nome, $email, $telefone, $senha, $adm){
        $this->funcionario->inserirFunc($nome, $email, $telefone, $senha, $adm);
    }

    public function atualizarFunc($emailAntigo, $nome, $email, $telefone){
        $this->funcionario->atualizarFunc($emailAntigo, $nome, $email, $telefone);
    }

    public function deletarFunc($email){
        $this->funcionario->deletarFunc($email);
    }
}
?>