<?php
namespace app\controller2;

use app\repository\ItemPedidoRepository;
use app\model2\ItemPedido;
use app\utils\Logger;
use Exception;

class ItemPedidoController {
    private ItemPedidoRepository $repository;

    public function __construct(ItemPedidoRepository $repository = null) {
        $this->repository = $repository ?? new ItemPedidoRepository();
    }
}