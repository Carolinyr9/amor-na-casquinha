$(() => {
    $("#edit").click(function() {
        $("#formulario").addClass("exibir");
        $("#edit").css({display:"none"});
        $("#dados").css({display:"none"});
    });
});