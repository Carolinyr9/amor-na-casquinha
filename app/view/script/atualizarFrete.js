$(document).ready(function(){
    $('#ckbIsDelivery').change(function(){
        if($(this).is(':checked')) {
            $('#freteDiv').fadeIn();
        } else {
            $('#freteDiv').fadeOut(); 
        }
    });
});

document.addEventListener("DOMContentLoaded", function () {
    const checkbox = document.getElementById("ckbIsDelivery");
    const form = document.getElementById("pedidoForm");
    const isDeliveryInput = document.getElementById("isDelivery");

    checkbox.addEventListener("change", function () {
        // Atualiza o valor do campo oculto
        isDeliveryInput.value = checkbox.checked ? "1" : "0";

        // Atualiza apenas o frete via AJAX sem recarregar a página
        fetch(window.location.href, {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: new URLSearchParams(new FormData(form)),
        })
        .then(response => response.text())
        .then(html => {
            // Atualiza os elementos necessários no DOM
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, "text/html");

            // Atualizar as seções de frete e total
            document.querySelector(".frete-div").innerHTML = doc.querySelector(".frete-div").innerHTML;
            document.querySelector(".total-com-frete").innerHTML = doc.querySelector(".total-com-frete").innerHTML;
        });
    });
});
