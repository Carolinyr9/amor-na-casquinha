$(() => {
    const btnGoToLogin = $('#btnGoToLogin');
    const ckbIsDelivery = $('#ckbIsDelivery');
    const labelForCkbIsDelivery = $('#labelForCkbIsDelivery');
    const addressDiv = $('#addressDiv');

    if (addressDiv.length) {
        const addressInputs = addressDiv.html();

        ckbIsDelivery.change(() => {
            if (ckbIsDelivery.is(':checked')) {
                labelForCkbIsDelivery.html("O pedido será entregue no seu endereço!");
                addressDiv.html(addressInputs);
            } else {
                labelForCkbIsDelivery.html("Você escolheu buscar o pedido na sorveteria!");
                addressDiv.html("");
            }
        });
    }

    if (btnGoToLogin.length) {
        btnGoToLogin.click((event) => {
            event.preventDefault();
            window.location.replace('login.php');
        });
    }
});
