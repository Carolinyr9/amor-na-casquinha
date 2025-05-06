<?php
use app\controller2\ClienteController;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["btnAlterarCliente"])) {
        $clienteController->editarCliente(
            $_SESSION["userEmail"],
            $_POST["idEndereco"],
            $_POST["nome"],
            $_POST["telefone"],
            $_POST["rua"],
            $_POST["cep"],
            $_POST["numero"],
            $_POST["bairro"],
            $_POST["cidade"],
            $_POST["estado"],
            $_POST["complemento"]
        );
        unset($_POST);
        header("Location: perfil.php");
        exit();
    }
}