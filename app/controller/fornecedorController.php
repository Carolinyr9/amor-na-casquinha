<?php 
    require '../model/fornecedores.php';

    class FornecedorController{
        private $fornecedor;

        public function __construct(){
            $this->fornecedor = new Fornecedores();
        }

        public function listarForn(){
            $listaFornecedores = $this->fornecedor->listarForn();

            if($listaFornecedores == null || count($listaFornecedores) == 0){
                return "Nenhum funcionário encontrado.";
            }else{ 
                return $listaFornecedores;
            }
        }

        public function listarFornecedorEmail($email){
            $furnDados = $this->fornecedor->listarFornecedorEmail($email);
            return $furnDados;
        }

        public function inserirForn($nome, $email, $telefone, $cnpj, $rua, $numero, $bairro, $complemento, $cep, $cidade, $estado){
            $this->fornecedor->inserirForn($nome, $email, $telefone, $cnpj, $rua, $numero, $bairro, $complemento, $cep, $cidade, $estado);
        }

        public function atualizarForn($emailAntigo, $nome, $email, $telefone){
            $this->fornecedor->atualizarForn($emailAntigo, $nome, $email, $telefone);
        }

        public function deletarForn($email){
            $this->fornecedor->deletarForn($email);
        }
    }
?>