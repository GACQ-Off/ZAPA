function confirmarSalida() {
    if (confirm("¿Está seguro que desea salir del sistema?")) {
        // Redirige a la página de salida (reemplaza "salir.html" con la URL correcta)
        window.location.href = "salir.php";
    }
}