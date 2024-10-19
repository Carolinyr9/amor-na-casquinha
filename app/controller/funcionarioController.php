<?php 
    require '../model/funcionarios.php';

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

    }
?>