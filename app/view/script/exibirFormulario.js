document.getElementById("toggleFormButton").addEventListener("click", function() {
    const form = document.getElementById("addPedidoForm");
    form.style.display = form.style.display === "none" || form.style.display === "" ? "block" : "none";
});
