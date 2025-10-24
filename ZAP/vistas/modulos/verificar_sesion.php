<?php
// Asegúrate de que la sesión se inicie antes de usarla
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verifica si la sesión de inicio de sesión no está establecida o no es 'ok'
// O si alguna de las variables cruciales de sesión no existe
if (!isset($_SESSION["iniciarSesion"]) || $_SESSION["iniciarSesion"] != "ok" || !isset($_SESSION["usuario"])) {
    // Si no está logueado, redirige a la página de ingreso
    echo '<script>
        window.location = "ingreso"; // O la ruta URL base de tu página de login, ej. "/prueba/ventaslog/ingreso"
    </script>';
    exit(); // Importante para detener la ejecución del script
}

// Si la sesión es válida, el script continúa ejecutándose
?>