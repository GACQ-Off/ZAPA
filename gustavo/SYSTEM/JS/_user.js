function modal_() {
    const modal = document.querySelector("#modal_seis");
    const gatillo = document.querySelector("#salir_sesion");    
    gatillo.addEventListener("click", (e) => {
        e.preventDefault();
        modal.classList.add("mostrar");});
    modal.addEventListener("click", (e) => {
        if (e.target.id === "modal_seis") {
            modal.classList.remove("mostrar");}});}