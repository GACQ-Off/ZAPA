function activarPreviewFotoRegistro() {
    const inputFoto = document.getElementById('foto_formulario');
    const imagenPrevia = document.getElementById('imagen_previa');
    if (inputFoto && imagenPrevia && !inputFoto.dataset.listenerActivado) {
        inputFoto.addEventListener('change', (event) => {
            const archivo = event.target.files[0];
            if (archivo) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    imagenPrevia.src = e.target.result;};
                reader.readAsDataURL(archivo);}});
        inputFoto.dataset.listenerActivado = 'true';}}
class LogicaDetallesModal {
    constructor() {
        this.modalSiete = document.getElementById('modal_siete');
        this.modalAreaDinamica = document.getElementById('modal_area_dinamica');
        this.guardarModal_el = document.getElementById('modal_once');
        this.guardarConfirmar_el = document.getElementById('confirmar_guardar_'); 
        this.guardarCancelar_el = document.getElementById('cancelar_guardar_'); 
        this.editarModal_el = document.getElementById('modal_doce');
        this.editarConfirmar_el = document.getElementById('confirmar_editar_');
        this.editarCancelar_el = document.getElementById('cancelar_editar_');
        this.registroActual = {}; 
        this.bindGlobalEvents();}
    abrir() {
        this.modalSiete.classList.add('mostrar');}
    cerrar() {
        this.modalSiete.classList.remove('mostrar');
        this.modalAreaDinamica.innerHTML = '';
        this.cerrarGuardar();
        this.cerrarEditar();}
    abrirGuardar(event) {
        event.preventDefault();
        if (this.guardarModal_el) {
            this.guardarModal_el.classList.add('mostrar');}}
    cerrarGuardar() {
        if (this.guardarModal_el) {
            this.guardarModal_el.classList.remove('mostrar');}}
    abrirEditar(event) {
        event.preventDefault(); 
        if (this.editarModal_el) {
            this.editarModal_el.classList.add('mostrar');}}
    cerrarEditar() {
        if (this.editarModal_el) {
            this.editarModal_el.classList.remove('mostrar');}}
            _activarPreviewFotoModal() {    
        const inputFoto_m = document.getElementById('foto_formulario_m');
        const imagenPrevia_m = document.getElementById('imagen_previa_m'); 
        if (inputFoto_m && imagenPrevia_m && !inputFoto_m.dataset.listenerActivado) {
            inputFoto_m.addEventListener('change', (event) => {
                const archivo_m = event.target.files[0];
                if (archivo_m) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        imagenPrevia_m.src = e.target.result;};
                    reader.readAsDataURL(archivo_m);}});
            inputFoto_m.dataset.listenerActivado = 'true';}}
    cargar(id, tipo, modo, nombre) {
        this.registroActual = { id, tipo, modo, nombre };
        this.abrir();
        fetch('includes/logic/logic_s.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `id_registro=${id}&tipo_registro=${tipo}&modo=${modo}&nombre_registro=${nombre}`})
        .then(response => response.text())
        .then(htmlContent => {
            this.modalAreaDinamica.innerHTML = htmlContent;
            this.bindDynamicEvents();})
        .catch(error => {
            this.modalAreaDinamica.innerHTML = `<section id="cabezal_modal_m"><h1 id="h_modificacion">Error de Carga</h1></section><div id="modal_area_m"><p class="error-msg">Error: ${error.message}</p></div>`;
            console.error('Error en la carga AJAX:', error);});}
            bindDynamicEvents() {
        const { id, tipo, nombre } = this.registroActual;
        const btnEditar = document.getElementById('btn_cambiar_a_edicion');
        if (btnEditar) {
            btnEditar.addEventListener('click', (event) => {
                this.abrirEditar(event); });}
                if (this.editarConfirmar_el) {
                    this.editarConfirmar_el.addEventListener('click', () => {
                this.cerrarEditar();
                const { id, tipo, nombre } = this.registroActual; 
                this.cargar(id, tipo, 'editar', nombre); });}
        const btnCancelarEdicion = document.getElementById('btn_cambiar_a_visualizacion');
        if (btnCancelarEdicion) {
            btnCancelarEdicion.addEventListener('click', () => {
                this.cargar(id, tipo, 'ver', nombre); });}
        const btnGuardar = document.getElementById('boton_guardar_m');
        if (btnGuardar) {
            btnGuardar.addEventListener('click', this.abrirGuardar.bind(this));}
        if (this.guardarConfirmar_el) {
            this.guardarConfirmar_el.onclick = () => { 
                this.cerrarGuardar();
                const formulario = document.getElementById('_modificar');
                if (formulario) {
                    formulario.submit();}};}
                    this._activarPreviewFotoModal(); }
    bindGlobalEvents() {
        this.modalSiete.addEventListener('click', (event) => {
            if (event.target.closest('#boton_cerrar_modal_mo')) {
                this.cerrar();
                return; }
                if (event.target === this.modalSiete) {
                this.cerrar();}});
        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && this.modalSiete.classList.contains('mostrar')) {
                this.cerrar();}});
        if (this.guardarCancelar_el) {
            this.guardarCancelar_el.addEventListener('click', this.cerrarGuardar.bind(this));}
        if (this.editarCancelar_el) {
            this.editarCancelar_el.addEventListener('click', this.cerrarEditar.bind(this));}
        document.querySelectorAll('.btn_detalles').forEach(button => {
            button.addEventListener('click', () => {
                const id = button.getAttribute('data-id');
                const tipo = button.getAttribute('data-entidad');
                const nombre = button.getAttribute('data-nombre');
                this.cargar(id, tipo, 'ver', nombre); });});}}
        document.addEventListener("DOMContentLoaded", () => {
    activarPreviewFotoRegistro();
    window.detallesModal = new LogicaDetallesModal();});