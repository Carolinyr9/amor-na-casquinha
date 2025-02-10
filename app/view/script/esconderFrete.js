$(document).ready(function() {
    var freteValue = $('#frete').val();
    var isDeliveryCheckbox = $('#ckbIsDelivery');
    var freteDiv = $('#freteDiv');
    
    if (isNaN(freteValue) || freteValue === 'null') {
        isDeliveryCheckbox.prop('checked', false);
        isDeliveryCheckbox.prop('disabled', true);
        freteDiv.hide();
        $('#isDelivery').val('0');
    } else {
        if (parseFloat(freteValue) > 0) {
            freteDiv.show();
            $('#isDelivery').val('1');
            isDeliveryCheckbox.prop('checked', true);
        }
    }

    isDeliveryCheckbox.change(function() {
        if (this.checked) {
            $('#isDelivery').val('1');
            freteDiv.show();
        } else {
            $('#isDelivery').val('0');
            freteDiv.hide();
        }
    });
});
