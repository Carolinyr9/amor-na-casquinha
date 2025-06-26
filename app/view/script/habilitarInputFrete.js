$(document).ready(function() {
    $('#ckbIsDelivery').change(function() {
        if ($(this).is(':checked')) {
            $('#valorFrete').prop( "disabled", false );
        } else {
            $('#valorFrete').prop( "disabled", true );
            $('#valorFrete').val('');
        }
    });
});