<?php
use app\controller2\UsuarioController;
use app\controller2\ClienteController;
use app\controller2\EnderecoController;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['registrar'])) {
    $usuarioController = new UsuarioController();
    if($usuarioController->validarDados($_POST['email'], $_POST['senha'], $_POST['celular'], $_POST['cep'])){
        $clienteController = new ClienteController();
        $enderecoController = new EnderecoController();

        $dadosEndereco = [
            'rua' => $_POST['rua'] ?? '',
            'numero' => $_POST['numero'] ?? '',
            'cep' =>$_POST['cep'] ?? '',
            'bairro' => $_POST['bairro'] ?? '',
            'cidade' => $_POST['cidade'] ?? '',
            'estado' => $_POST['estado'] ?? '',
            'complemento' => $_POST['complemento'] ?? ''
        ];

        $idEndereco = $enderecoController->criarEndereco($dadosEndereco);
        $dadosCliente = [
            'idEndereco' => $idEndereco,
            'nome' => $_POST['nome'] ?? '',
            'email' => $_POST['email'] ?? '',
            'telefone' => $_POST['celular'] ?? '',
            'senha' => password_hash($_POST['senha'], PASSWORD_DEFAULT)
        ];

       $idCliente = $clienteController->criarCliente($dadosCliente);

       if($idCliente){
        $usuarioController->registrarCliente($_POST['email'], $_POST['nome'], $_POST['celular']);
       }

    } else {
        echo 'Erro ao registrar usuario';
    }
    
}