let idsArray = [];

$('input[name="produtoCheck"]').on('change', function () {
    if ($(this).is(':checked')) {
        console.log("Checkbox ID: " + $(this).attr('id') + " está selecionado.");
        idsArray.push($(this).attr('id'));
    } else {
        console.log("Checkbox ID: " + $(this).attr('id') + " foi desmarcado.");
    }
});