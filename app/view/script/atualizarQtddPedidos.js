function toggleQuantidade(id) {
    const checkbox = document.getElementById('produto' + id);
    const wrapper = document.getElementById('quantidade-wrapper-' + id);

    if (checkbox.checked) {
        wrapper.style.display = 'inline-block';
    } else {
        wrapper.style.display = 'none';
        document.getElementById('quantidade-' + id).value = 1; // resetar para 1
    }
}

function alterarQuantidade(id, delta) {
    const input = document.getElementById('quantidade-' + id);
    let valor = parseInt(input.value) + delta;
    if (valor < 1) valor = 1;
    input.value = valor;
}
