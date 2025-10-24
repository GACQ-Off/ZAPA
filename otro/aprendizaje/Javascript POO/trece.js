class modal {
    constructor(modalSelector, cerrarModalSelector) {
        this.modal = document.querySelector(modalSelector);
        this.cerrarModal = document.querySelector(cerrarModalSelector);
    }
    abrir() {
            this.modal.classList.add('modal_mostrar');
    }
    cerrar(){
            this.modal.classList.remove('modal_mostrar');
    }
    bindEvents() {
        this.cerrarModal.addEventListener('click', this.cerrar.bind(this))
    }
}
class datosMostrador {
    constructor(abrirModalSelector, contenedorSelector) {
        this.abrirModal = document.querySelector(abrirModalSelector);
        this.contenedor = document.querySelector(contenedorSelector);
    } 
    mostrarDatos(datos) {
        let html = '';
        datos.forEach(item => {
            html += `<li>ID: ${item.id} - Nombre: ${item.nombre}</li>`;
        });
        this.contenedor.innerHTML = html;
}
    bindEvents(modalInstancia) {
        this.abrirModal.addEventListener('click', modalInstancia.abrir.bind(modalInstancia))
    }
}
class Dashboard {
    constructor() {
        this.modalInstancia = new modal('#primerModal', '#cerrarModalBtn');
        this.datosMostrador = new datosMostrador('#abrirModalBtn', '#dataList');

        const datos = [
            { id: 1, nombre: "Item A" },
            { id: 2, nombre: "Item B" },
            { id: 3, nombre: "Item C" },
        ];

        this.modalInstancia.bindEvents();
        this.datosMostrador.bindEvents(this.modalInstancia);

        this.datosMostrador.mostrarDatos(datos);
    }
}

document.addEventListener('DOMContentLoaded', () => {
    new Dashboard();
});