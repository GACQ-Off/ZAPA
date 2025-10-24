document.addEventListener('DOMContentLoaded', () => {
    const modal_e = document.querySelector('#modal_cuatro');
    const titulo_e = document.querySelector('#titulo_modal_eliminar');
    const display_e = document.querySelector('#display_eliminar');
    const id_e = document.querySelector('#id_registro_eliminar');
    const entidad_e = document.querySelector('#entidad_eliminar');
    const cerrar_e = document.querySelector('#cerrar');
    const cancelar_e = document.querySelector('.cancelar_eliminar');
    const formularioEliminar = document.getElementById('eliminar_form');
    const botonConfirmar = document.getElementById('btn_confirmar_eliminar');
    const tabla = document.querySelector('#contenedor_de_tabla');
    function abrir_e(id, tipo) {
        titulo_e.textContent = `¿Deshabilitar ${tipo}?`; 
        id_e.value = id; 
        entidad_e.value = tipo.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, ""); 
        modal_e.classList.add('mostrar');}
    if (tabla) {
        tabla.addEventListener('click', (e) => {
            const btnEliminar = e.target.closest('.btn_eliminar');
            if (btnEliminar) {
                e.preventDefault();
                const id = btnEliminar.dataset.id;
                const tipo = btnEliminar.dataset.entidad;
                const nombre = btnEliminar.dataset.nombre;
                abrir_e(id, tipo); 
                display_e.innerHTML = `<strong>${nombre || id}</strong>`;}});}
    function cerrar_() {
        if (modal_e) modal_e.classList.remove('mostrar');}
        if (modal_e) {
            modal_e.addEventListener('click', (e) => {
            if (e.target === modal_e) {
                cerrar_();}});}
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && modal_e.classList.contains('mostrar')) {
            cerrar_();}});
    if (cerrar_e) (cerrar_e.addEventListener('click', cerrar_));
    if (cancelar_e) (cancelar_e.addEventListener('click', cerrar_));
    if (botonConfirmar && formularioEliminar) {
    botonConfirmar.addEventListener('click', function(e) {
        formularioEliminar.submit(); });}});