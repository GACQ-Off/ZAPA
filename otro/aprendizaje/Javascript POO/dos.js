
class ControladorModal {
    constructor(modalElement, claseCSS) {
        this.modalElement = modalElement;
        this.claseCSS = claseCSS;
    }

    abrir() {
        this.modalElement.classList.add(this.claseCSS);
    }

    cerrar() {
        this.modalElement.classList.remove(this.claseCSS);
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const abrirPrimerModalBtn = document.querySelector(".acciones_boton");
    const primerModal = document.querySelector("#primer_modal");
    const cerrarPrimerModalBtn = document.querySelector("#boton_cerrar_modal");

    const miModal = new ControladorModal(primerModal, 'modal--visible');

    if (abrirPrimerModalBtn) {
        abrirPrimerModalBtn.addEventListener('click', (e) => {
            e.preventDefault();
            miModal.abrir();
        });
    }

    if (cerrarPrimerModalBtn) {
        cerrarPrimerModalBtn.addEventListener('click', () => {
            miModal.cerrar();
        });
    }
});