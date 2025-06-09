<?php
use app\controller\ClienteController;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["btnAlterarSenha"])) {
        $clienteController = new ClienteController();
        $dadosCliente = [
            'senhaAtual' => $_POST["senhaAtual"],
            'senhaNova' => $_POST["senhaNova"],
            'idCliente' => $_POST["idCliente"]
        ];

        $result = $clienteController->alterarSenha($dadosCliente);

        unset($_POST);
        if($result){
            echo "<script>
                    alert('Senha alterada com sucesso!');
                    window.location.href = 'perfil.php';
                </script>";
            exit();
        } else {
            echo "<script>
                    alert('Senha atual incorreta!');
                    window.location.href = 'perfil.php';
                </script>";
            exit();
        }
        
    }
}      