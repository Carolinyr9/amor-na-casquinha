$(document).ready(function() {
    $('#imagem').on('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#preview').attr('src', e.target.result); 
                $('#preview').show(); 
            };
            reader.readAsDataURL(file);
        }
    });
});