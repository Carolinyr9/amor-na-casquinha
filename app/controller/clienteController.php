<?php
require_once '../model/Cliente.php';

class ClienteController {
    private $cliente;

    public function __construct() {
        $this->cliente = new Cliente();
    }

    public function getClienteData($email) {
        return $this->cliente->getCliente($email);
    }

    public function editarCliente($email, $idEndereco, $nome, $telefone, $rua, $cep, $numero, $bairro, $cidade, $estado, $complemento) {
        $this->cliente->editarCliente($email, $idEndereco, $nome, $telefone, $rua, $cep, $numero, $bairro, $cidade, $estado, $complemento);
    }
}
?>
