<?php
use app\controller2\EntregadorController;

if(isset($_GET['exclEntr'])) {
    $entregadorController = new EntregadorController();
    $entregadorController->desativarEntregador($_GET['exclEntr']);
    header("Location: gerenciarEntregadores.php");
    exit;
}