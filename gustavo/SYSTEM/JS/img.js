document.addEventListener('DOMContentLoaded', (e) => {
    const inputFoto = document.getElementById('foto_formulario');
    const imagenPrevia = document.getElementById('imagen_previa');
    if (inputFoto && imagenPrevia) {
        inputFoto.addEventListener('change', (event) => {
            const archivo = event.target.files[0];
            if (archivo) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    imagenPrevia.src = e.target.result;};
                reader.readAsDataURL(archivo);}});}});