function modal_() {
    const modal = document.querySelector("#primer_modal");
    const gatillo = document.querySelector("#soporte");    
    gatillo.addEventListener("click", () => {
        modal.classList.add("mostrar");});
    modal.addEventListener("click", (e) => {
        if (e.target.id === "primer_modal") {
            modal.classList.remove("mostrar");}});}
function carrusel_() {
    const visor = document.querySelector("#fotos");
    const imagenes = [
        "SYSTEM/img/trapiche/foto1.jpg", "SYSTEM/img/trapiche/foto2.jpg", "SYSTEM/img/trapiche/foto3.jpg",
        "SYSTEM/img/trapiche/foto4.jpg", "SYSTEM/img/trapiche/foto5.jpg", "SYSTEM/img/trapiche/foto6.jpg",
        "SYSTEM/img/trapiche/foto7.jpg", "SYSTEM/img/trapiche/foto8.jpg", "SYSTEM/img/trapiche/foto9.jpg",
        "SYSTEM/img/trapiche/foto10.png"];
    const intervalo = 8000;
    let indice = 0;
    function nextImage() {
        indice = (indice + 1) % imagenes.length;
        visor.classList.remove('mostrar'); 
        setTimeout(() => {
            visor.src = imagenes[indice];
            visor.classList.add('mostrar'); 
        }, 400);}
    visor.src = imagenes[indice];
    visor.classList.add('mostrar');
    setInterval(nextImage, intervalo);}
document.addEventListener("DOMContentLoaded", () => {
    modal_();
    carrusel_();});