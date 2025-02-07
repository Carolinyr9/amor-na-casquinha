$(document).ready(function() {
    $('input[name="meioDePagamento"]').change(function() {
        if ($('#meioDePagamentoDinheiro').is(':checked')) {
            $('#trocoDiv').show();
        } else {
            $('#trocoDiv').hide();
        }
    });
});