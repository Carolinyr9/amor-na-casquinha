<?php
namespace app\controller;

use app\model\Endereco;
use app\repository\EnderecoRepository;

class EnderecoController {

    private $repositorio;

    public function __construct() {
        $this->repositorio = new EnderecoRepository();
    }

    public function listarEnderecoPorId($idEndereco) {
        if(!isset($idEndereco) || empty($idEndereco)) {
            return ["error" => "ID do endereço não fornecido!"];
        }

        $dados = $this->repositorio->listarEnderecoPorId($idEndereco);
        
        if($dados) {
            $endereco = new Endereco($dados['idEndereco'], $dados['rua'], $dados['numero'], $dados['cep'], $dados['bairro'], $dados['cidade'], $dados['estado'], $dados['complemento']);
            return $endereco;
        } else {
            return ["error" => "Endereço não encontrado!"];
        }
    }

    function editarEndereco($dados) {
        if(!is_numeric($dados['cep'])) {
            $erro = ["error" => "CEP inválido! Insira um CEP válido e sem formatação."];
        }
        if(!is_numeric($dados['numero'])) {
            $erro = ["error" => "Número inválido! Insira um número válido."];
        }

        if(isset($erro)) {
            return $erro;
        } else{
            $endereco = new Endereco(0, $dados['rua'], $dados['numero'], $dados['cep'], $dados['bairro'], $dados['cidade'], $dados['estado'], $dados['complemento']);
            $answer = $this->repositorio->editarEndereco($endereco);

            if($answer) {
                return ["success" => "Endereço editado com sucesso!"];
            } else {
                return ["error" => "Erro ao editar o endereço!"];
            }

        }
    }
}
