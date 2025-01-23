let idsArray = [];

$('#editarProdutoEstoque').hide();
$('#excluirProdutoEstoque').hide();

$('input[name="produtoCheck"]').on('change', function () {
    const checkboxId = $(this).attr('id');

    if ($(this).is(':checked')) {
        idsArray.push(checkboxId);
    } else {
        idsArray = idsArray.filter(id => id !== checkboxId);
    }

    if (idsArray.length > 0) {
        $('#editarProdutoEstoque').show();
        $('#excluirProdutoEstoque').show();
    } else {
        $('#editarProdutoEstoque').hide();
        $('#excluirProdutoEstoque').hide();
    }

    $('#editarProdutoEstoque').attr('href', 'editarEstoque.php?idsArray=' + idsArray.join(','));
    $('#excluirProdutoEstoque').attr('href', 'excluirEstoque.php?idsArray=' + idsArray.join(','));
});
