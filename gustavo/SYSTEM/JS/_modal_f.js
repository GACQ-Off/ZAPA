class Logica_de_modal {
    constructor(
        modal_contenedor,
        boton_abrir,
        boton_cerrar,
        modal_formulario,
        modal_previa,
        modal_input,
        modal_fases,
        boton_siguiente,
        boton_atras,
        modal_footer,
        boton_limpiar,
        limpiar_modal,
        limpiar_confirmar,
        limpiar_cancelar,
        guardar_modal,
        guardar_confirmar,
        guardar_cancelar,
        boton_guardar
    ) {
        this.modal_co = document.querySelector(modal_contenedor);
        this.boton_a = document.querySelector(boton_abrir);
        this.boton_ce = document.querySelector(boton_cerrar);
        this.modal_f = document.querySelector(modal_formulario);
        this.modal_p = document.querySelector(modal_previa);
        this.modal_i = document.querySelector(modal_input);
        this.modal_fa = document.querySelectorAll(modal_fases);
        this.boton_si = document.querySelector(boton_siguiente);
        this.boton_at = document.querySelector(boton_atras);
        this.faseActual = 0;
        this.modal_fo = document.querySelector(modal_footer);
        this.modal_l = document.querySelector(boton_limpiar);
        this.boton_guardar = document.querySelector(boton_guardar);
        this.limpiarModal_el = document.querySelector(limpiar_modal);
        this.limpiarConfirmar_el = document.querySelector(limpiar_confirmar);
        this.limpiarCancelar_el = document.querySelector(limpiar_cancelar);
        this.guardarModal_el = document.querySelector(guardar_modal);
        this.guardarConfirmar_el = document.querySelector(guardar_confirmar);
        this.guardarCancelar_el = document.querySelector(guardar_cancelar);
        this.bindEvents();}
    abrir() {
        this.modal_co.classList.add("mostrar");
        if (this.modal_fa.length > 0) {
            this.modal_fa.forEach((fase, index) => {
                if (index === 0) {fase.classList.add("mostrar");
                } else {fase.classList.remove("mostrar");}});
        this.faseActual = 0;}
        this.actualizarUI();}
    cerrar() {
        this.modal_co.classList.remove("mostrar");
        this.reset_();
        this.cerrar_r();
        this.cerrar_g();}
    siguiente(e) {
        e.preventDefault();
        if (this.faseActual < this.modal_fa.length - 1) {
            this.modal_fa[this.faseActual].classList.remove("mostrar");
            this.faseActual++;
            this.modal_fa[this.faseActual].classList.add("mostrar");}
        this.actualizarUI();}
    atras(e) {
        e.preventDefault();
        if (this.faseActual > 0) {
            this.modal_fa[this.faseActual].classList.remove("mostrar");
            this.faseActual--;
            this.modal_fa[this.faseActual].classList.add("mostrar");}
        this.actualizarUI();}
    abrir_r(e) {
        e.preventDefault();
        if (this.limpiarModal_el) {this.limpiarModal_el.classList.add('mostrar');}}
    cerrar_r() {
        if (this.limpiarModal_el) {this.limpiarModal_el.classList.remove('mostrar');}}
    abrir_g(e) {
        e.preventDefault(); 
        if (this.guardarModal_el) {this.guardarModal_el.classList.add('mostrar');}}
    cerrar_g() {
        if (this.guardarModal_el) {this.guardarModal_el.classList.remove('mostrar');}}
    reset_() {
        if (this.modal_f) {this.modal_f.reset()};
        if (this.modal_p) {this.modal_p.src = ""};
        if (this.modal_i) {this.modal_i.value = ""};
        if (this.modal_fa.length > 0) {
            this.modal_fa.forEach(fase => fase.classList.remove("mostrar"));
            this.faseActual = 0;
            this.modal_fa[this.faseActual].classList.add("mostrar");}
        this.actualizarUI();}
    actualizarUI() {
        if (this.boton_at) {
            if (this.faseActual === 0) {this.boton_at.classList.add('ocultar');} 
            else {this.boton_at.classList.remove('ocultar');}}
        if (this.boton_si) {
            if (this.faseActual === this.modal_fa.length - 1) {this.boton_si.classList.add('ocultar');} 
            else {this.boton_si.classList.remove('ocultar');}}
        if (this.modal_fo) {
            if (this.faseActual === this.modal_fa.length - 1) {this.modal_fo.classList.add('mostrar');} 
            else {this.modal_fo.classList.remove('mostrar');}}}
    auxiliar(e) {
        if (e.target === this.modal_co) {this.cerrar();}}
    tecla(e) {
        if (e.key === "Escape") {this.cerrar();}}
    bindEvents() {
        if (this.boton_a) {this.boton_a.addEventListener("click", this.abrir.bind(this));}
        if (this.boton_ce) {this.boton_ce.addEventListener("click", this.cerrar.bind(this));}
        if (this.boton_at) {this.boton_at.addEventListener("click", this.atras.bind(this));}
        if (this.boton_si) {this.boton_si.addEventListener("click", this.siguiente.bind(this));}
        if (this.modal_l) {this.modal_l.addEventListener("click", this.abrir_r.bind(this));}
        if (this.limpiarConfirmar_el) {this.limpiarConfirmar_el.addEventListener("click", () => {
            this.reset_();
            this.cerrar_r();});}
        if (this.limpiarCancelar_el) {this.limpiarCancelar_el.addEventListener("click", this.cerrar_r.bind(this));}
        if (this.boton_guardar) {this.boton_guardar.addEventListener("click", this.abrir_g.bind(this));}
        if (this.guardarConfirmar_el) {this.guardarConfirmar_el.addEventListener("click", () => {
            this.cerrar_g();
            this.modal_f.submit();});}
        if (this.guardarCancelar_el) {this.guardarCancelar_el.addEventListener("click", this.cerrar_g.bind(this))};
        document.addEventListener("click", this.auxiliar.bind(this));
        document.addEventListener("keydown", this.tecla.bind(this));}
}
document.addEventListener("DOMContentLoaded", () => {
    window.mainModal = new Logica_de_modal(
        "#primer_modal",
        ".acciones_boton",
        "#boton_cerrar_modal_r",
        "#_registrar",
        "#imagen_previa",
        "#foto_formulario",
        ".formulario_parte",
        "#boton_siguiente",
        "#boton_atras",
        "#bottom_modal",
        "#boton_limpiar",
        "#modal_segundo",
        "#confirmar_limpiar",
        "#cancelar_limpiar",
        "#modal_tercero",
        "#confirmar_guardar",
        "#cancelar_guardar",
        "#boton_guardar"
    );
});