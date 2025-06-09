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
        $result = $clienteController->desativarPerfil($_POST["emailCliente"]);

        if ($result) {
            echo "<script>
                    alert('Perfil excluído com sucesso!');
                    window.location.href = '/amor-na-casquinha/app/utils/autenticacao/logout.php';
                </script>";
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
