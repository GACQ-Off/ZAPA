class formulario {
    constructor(formularioElement, faseSelector, botonSiguiente) {
        this.formularioElement = document.querySelector(formularioElement);
        this.fases = document.querySelectorAll(faseSelector);
        this.botonSiguiente = document.querySelector(botonSiguiente);
        this.faseActual = 0;
    }
    init() {
        this.bindEvents();
    }
    validarFaseActual() {
        const inputs = this.fases[this.faseActual].querySelectorAll("input");
        for (let i = 0; i < inputs.length; i++) {
            if (inputs[i].value.trim() === "") {
                return false;
            }
        }
        return true;
    }
    mostrarSiguienteFase(event) {
        event.preventDefault();
        const val = this.validarFaseActual();
        if (val == false) {
            console.log("Error en la validación");
            return false;
        }
        this.fases[this.faseActual].classList.remove("mostrar");
        this.faseActual++;
        this.fases[this.faseActual].classList.add("mostrar");
    }
    bindEvents() {
        this.botonSiguiente.addEventListener( "click", this.mostrarSiguienteFase.bind(this)
        );
    }
}
