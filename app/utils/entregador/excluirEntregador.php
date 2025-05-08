<?php
use app\controller\EntregadorController;

if(isset($_GET['exclEntr'])) {
    $entregadorController = new EntregadorController();
    $entregadorController->desativarEntregador($_GET['exclEntr']);
    header("Location: gerenciarEntregadores.php");
    exit;
}