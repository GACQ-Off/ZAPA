function mensaje_() {
    const mensaje_s = document.querySelector("#mensaje_exito");
    const mensaje_e = document.querySelector("#mensaje_error");
    const intervalo = 5000;
    if (mensaje_s) {
        mensaje_s.classList.add('visible');
        setTimeout(() => {
            mensaje_s.classList.remove('visible');
        }, intervalo);} 
    else if (mensaje_e) {
        mensaje_e.classList.add('visible');
        setTimeout(() => {
            mensaje_e.classList.remove('visible');
        }, intervalo);}}
document.addEventListener('DOMContentLoaded', () => {
    mensaje_();})