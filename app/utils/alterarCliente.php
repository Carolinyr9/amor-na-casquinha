<?php
use app\controller\ClienteController;
use app\controller\EnderecoController;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["btnAlterarCliente"])) {
        $clienteController = new ClienteController();
        $enderecoController = new EnderecoController();
        $dadosCliente = [
            'nome' => $_POST["nome"],
            'telefone' => $_POST["telefone"],
            'email' => $_POST["email"],
            'emailAntigo' => $_SESSION["userEmail"]
        ];

        $clienteController->editarCliente($dadosCliente);

        $dadosEndereco = [
            'rua' => $_POST["rua"],
            'numero' => $_POST["numero"],
            'complemento' => $_POST["complemento"],
            'cep' => $_POST["cep"],
            'bairro' => $_POST["bairro"],
            'estado' => $_POST["estado"],
            'cidade' => $_POST["cidade"],
            'idEndereco' => $_POST["idEndereco"]
        ];

        $enderecoController->editarEndereco($dadosEndereco);

        unset($_POST);
        header("Location: perfil.php");
        exit();
    }
} 
            
            
            
            
            
            
            
            
            
            
        