<?php 
    require_once '../model/estoque.php';

    class EstoqueController{
        private $estoque;

        function __construct(){
            $this->estoque = new Estoque();
        }

        function listarEstoque(){
            return $this->estoque->listarEstoque();
        }
        
    } 

?>