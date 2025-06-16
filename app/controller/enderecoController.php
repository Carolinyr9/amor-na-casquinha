<?php
namespace app\controller;

use app\model\Endereco;
use app\repository\EnderecoRepository;
use app\utils\helpers\Logger;
use Exception;

class EnderecoController {

    private $repository;

    public function __construct() {
        $this->repository = new EnderecoRepository();
    }

    public function criarEndereco($dados){
        try {
            if (!is_numeric($dados['numero'])) {
                Logger::logError("Número inválido! Insira um número válido.");
                return;
            }

            if (!preg_match("/^\d{5}-?\d{3}$/", $dados['cep'])) {
                Logger::logError("CEP inválido. Use o formato 99999-999");
                return;
            }
            
            $idEndereco = $this->repository->criarEndereco($dados['rua'], $dados['numero'], $dados['cep'], $dados['bairro'], $dados['cidade'], $dados['estado'], $dados['complemento']);

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
            return false;
        }
    }

    public function listarEnderecoPorId($idEndereco) {
        try {
            if(!isset($idEndereco) || empty($idEndereco)){
                Logger::logError("ID do endereço não fornecido!");
                return;
            }
            $dados = $this->repository->listarEnderecoPorId($idEndereco);

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
            return false;
        }
    }    

    function editarEndereco($dados) {
        try {
            if (!is_numeric($dados['numero'])) {
                Logger::logError("Número inválido! Insira um número válido.");
                return false;
            }

            if (!preg_match("/^\d{5}-?\d{3}$/", $dados['cep'])) {
                Logger::logError("CEP inválido. Use o formato 99999-999");
                return false;
            }

            $endereco = $this->listarEnderecoPorId($dados['idEndereco']);

            if($endereco){
                $endereco->editarEndereco($dados['rua'], $dados['numero'], $dados['complemento'], $dados['cep'], $dados['bairro'], $dados['estado'], $dados['cidade']);

                $resultado = $this->repository->editarEndereco($dados['rua'], $dados['numero'], $dados['complemento'], $dados['cep'], $dados['bairro'], $dados['estado'], $dados['cidade'], $dados['idEndereco']);

                return $resultado ? Logger::logInfo("Endereço editado com sucesso!") : Logger::logError("Erro ao editar endereço!");
            } else {
                Logger::logError("Endereço não encontrado para edição");
                return false;
            }
        } catch (Exception $e) {
            Logger::logError("Erro ao editar endereço: " . $e->getMessage());
            return false;
        }
    }

    public function listarEnderecos() {
        try {
    
            $dados = $this->repository->listarEnderecos();
    
            if($dados) {
                $enderecos = [];

                foreach ($dados as $endereco) {
                    $enderecos[] = new Endereco(
                        $endereco['idEndereco'], 
                        $endereco['rua'], 
                        $endereco['numero'], 
                        $endereco['cep'], 
                        $endereco['bairro'], 
                        $endereco['cidade'], 
                        $endereco['estado'], 
                        $endereco['complemento']
                    );
                }

                return $enderecos;

            } else {
                Logger::logError("Erro ao listar endereços: Nenhum endereço encontrado.");
                return false;
            }
        } catch (Exception $e) {
            Logger::logError("Erro ao listar endereços: " . $e->getMessage());
            return false;
        }
    }    

}
