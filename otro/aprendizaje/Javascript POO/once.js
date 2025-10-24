class modal_uno {
    constructor( modal_, modal_abrir, modal_cerrar) {
        this.modalSelector = document.querySelector(modal_);
        this.modalSelectorAbrir = document.querySelector(modal_abrir);
        this.modalSelectorCerrar = document.querySelector(modal_cerrar);
    }
    abrir() {
        this.modalSelector.classList.add('modal_mostrar');
    }
    cerrar() {
        this.modalSelector.classList.remove('modal_mostrar')
        this.limpiarFormulario();
    }
    limpiarFormulario() {
        this.modalSelector.querySelector('form')?.reset();
    }
    bindEvents(){
        this.modalSelectorAbrir.addEventListener('click', this.abrir.bind(this));
        this.modalSelectorCerrar.addEventListener('click', this.cerrar.bind(this));
    }
}

document.addEventListener('DOMContentLoaded', ()  => {
    const mimodal = new modal_uno('#primerModal', '#abrirModalBtn', '#cerrarModalBtn')
})