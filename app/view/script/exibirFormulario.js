//gerenciarCategorias.php
$("#addCategoria").click(function() {
    $("#formulario").toggle("slow", function(){});
});

//gerenciarProdutos.php

$("#addProduto").click(function() {
    $("#formulario").toggle("slow", function(){});
});

$(document).ready(function() {
    
    //pedido.php
    $("#addPedido").click(function() {
        $("#formulario").toggle("slow");
    });

    //gerenciarFuncionarios.php
    $('#addFuncionario').click(function() {
        $('#formulario').toggle("slow");
    });

    //gerenciarFornecedores.php
    $('#addFornecedor').click(function() {
        $('#formulario').toggle("slow");
    });

    //gerenciarEntregador.php
    $('#addEntregador').click(function() {
        $('#formulario').toggle("slow");
    });

    //perfil.php
    $('#editPerfil').click(function() {
        $('#formularioPerfil').toggle("slow");
        $('#dadosPerfil').toggle("slow");
        $('.fechar').toggle("slow");
    });
    $('.fechar').click(function() {
        $('#formularioPerfil').hide("slow");
        $('#dadosPerfil').show("slow");
        $('.fechar').hide("slow");
    });
    
});
    