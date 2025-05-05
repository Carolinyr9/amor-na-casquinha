<?php
namespace app\controller2;

use app\model2\Endereco;
use app\repository\EnderecoRepository;
use app\utils\Logger;
use Exception;

class EnderecoController {

    private $repositorio;

    public function __construct() {
        $this->repositorio = new EnderecoRepository();
    }

    public function criarEndereco($dados){
        try {
            $idEndereco = $this->repositorio->criarEndereco($dados['rua'], $dados['numero'], $dados['cep'], $dados['bairro'], $dados['cidade'], $dados['estado'], $dados['complemento']);

            if ($idEndereco) {
                new Endereco(
                    $idEndereco, 
                    $dados['rua'], 
                    $dados['numero'], 
                    $dados['cep'], 
                    $dados['bairro'], 
                    $dados['cidade'], 
                    $dados['estado'], 
                    $dados['complemento']
                );

                return $idEndereco;
            } else {
                Logger::logError("Erro ao criar endereço");
                return false;
            }

        } catch (Exception $e) {
            Logger::logError("Erro ao criar endereço: " . $e->getMessage());
        }
    }

    public function listarEnderecoPorId($idEndereco) {
        try {
            if(!isset($idEndereco) || empty($idEndereco)){
                Logger::logError("ID do endereço não fornecido!");
            }
            $dados = $this->repositorio->listarEnderecoPorId($idEndereco);

            if ($dados) {
                return new Endereco(
                    $idEndereco, 
                    $dados['rua'], 
                    $dados['numero'], 
                    $dados['cep'], 
                    $dados['bairro'], 
                    $dados['cidade'], 
                    $dados['estado'], 
                    $dados['complemento']
                );
            }
        } catch (Exception $e) {
            Logger::logError("Erro ao listar endereço ID: " . $e->getMessage());
        }
    }    

    function editarEndereco($dados) {
        try {
            if (!is_numeric($dados['cep'])) {
                Logger::logError("CEP inválido! Insira um CEP válido e sem formatação.");
            }
            if (!is_numeric($dados['numero'])) {
                Logger::logError("Número inválido! Insira um número válido.");
            }
    
            $endereco = new Endereco(
                0,
                $dados['rua'],
                $dados['numero'],
                $dados['cep'],
                $dados['bairro'],
                $dados['cidade'],
                $dados['estado'],
                $dados['complemento']
            );
    
            $answer = $this->repositorio->editarEndereco($endereco);
    
            return $answer ? ["success" => "Endereço editado com sucesso!"] : Logger::logError("Erro ao editar endereço!");
        } catch (Exception $e) {
            Logger::logError("Erro ao editar endereço: " . $e->getMessage());
        }
    }

}
