document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.autor_selector');
    const input_oculto = document.getElementById('aut_formulario');
    const formulario = document.querySelector('form');
    function input_actualizable() {
        let seleccion = [];
        checkboxes.forEach(checkbox => {
            if (checkbox.checked) {
                seleccion.push(checkbox.value);}});
        input_oculto.value = seleccion.join(','); }
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', input_actualizable);});
    if (formulario) {
        formulario.addEventListener('submit', input_actualizable);}
    input_actualizable();});