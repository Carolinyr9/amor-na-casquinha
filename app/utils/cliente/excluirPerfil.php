<?php

use app\controller\ClienteController;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["btnExcluirPerfil"])) {

        foreach ($_POST["statusPedidos"] as $status) {
            if ($status != 'Concluído' && $status != 'Cancelado') {
                echo "<script>
                        alert('Não é possível excluir o perfil com pedidos pendentes!');
                        window.location.href = 'perfil.php';
                      </script>";
                exit();
            }
        }

        $clienteController = new ClienteController();
        $result = $clienteController->excluirPerfil($_POST["emailCliente"]);

        if ($result) {
            echo "<script>
                    alert('Perfil excluído com sucesso!');
                  </script>";
            require_once(__DIR__ . '/../autenticacao/logout.php');
            exit();
        } else {
            echo "<script>
                    alert('Problema ao excluir o perfil!');
                    window.location.href = 'perfil.php';
                  </script>";
            exit();
        }
    }
}
