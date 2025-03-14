<?php
namespace app\controller;

use app\model\Entregador;

class EntregadorController {
    private $entregadorModel;

    public function __construct() {
        $this->entregadorModel = new Entregador();
    }

    public function getEntregadores() {
        return $this->entregadorModel->listarEntregadores();
    }

    public function getEntregadorPorId($idEntregador) {
        return $this->entregadorModel->listarEntregadorPorId($idEntregador);
    }
}
?>
