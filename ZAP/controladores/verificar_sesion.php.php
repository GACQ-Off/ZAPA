<?php
// vistas/modulos/verificar_sesion.php

// Iniciar la sesión si no está ya iniciada. Esto es crucial.
// Asegúrate de que no haya NADA (ni siquiera un espacio o línea en blanco) antes de <?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// ==============================================================
// Lógica para verificar si el usuario está logueado
// ==============================================================
if (!isset($_SESSION["iniciarSesion"]) || $_SESSION["iniciarSesion"] != "ok" || !isset($_SESSION["usuario"])) {
    // Si NO está logueado, redirige a la página de ingreso
    echo '<script>
        // Asegúrate de que esta ruta sea la correcta para tu página de login
        // Basado en tus URLs, "ingreso" debería funcionar.
        // Si no, prueba con la ruta absoluta: window.location = "http://localhost/prueba/ventaslog/ingreso";
        window.location = "ingreso"; 
    </script>';
    exit(); // Importante para detener la ejecución del script
}

// Si la sesión es válida (el usuario está logueado),
// el script continuará ejecutándose y la página protegida se cargará normalmente.
?>