<?php
// salir.php

// Iniciar la sesión si no está ya iniciada. Esto es crucial para acceder a $_SESSION.
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Asegúrate de incluir el modelo de EventoLog
require_once __DIR__ . "../../../modelos/eventolog.modelo.php";

// 1. Capturar los datos del usuario antes de destruir la sesión
$usuario_logueado = isset($_SESSION["usuario"]) ? $_SESSION["usuario"] : "Desconocido";
$cedula_logueado = isset($_SESSION["cedula"]) ? $_SESSION["cedula"] : "N/A";
$perfil_logueado = isset($_SESSION["perfil"]) ? $_SESSION["perfil"] : "N/A";

// 2. Destruir todas las variables de sesión
$_SESSION = array();

// 3. Si se utiliza una cookie de sesión, eliminarla también.
// Nota: esto destruirá la sesión, ¡y no solo los datos de sesión!
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 4. Destruir la sesión.
session_destroy();

// 5. Registrar el evento de cierre de sesión después de capturar los datos y antes de redirigir
// Es importante que la inclusión de ModeloEventoLog::mdlGuardarEvento esté ANTES de la redirección,
// de lo contrario, el script podría terminar antes de registrar el evento.

$logData = array(
    "event_type" => "Cierre de Sesión",
    "description" => "El usuario '{$usuario_logueado}' (Cédula: {$cedula_logueado}, Perfil: {$perfil_logueado}) ha cerrado sesión.",
    "employee_cedula" => $cedula_logueado, // La cédula del usuario que cerró sesión
    "affected_table" => "usuarios",
    "affected_row_id" => $usuario_logueado // El nombre de usuario como ID afectado
);
ModeloEventoLog::mdlGuardarEvento($logData);

// 6. Redirigir al usuario a la página de inicio o login
echo '<script>
    window.location = "ingreso"; // O la página de login que uses
</script>';
exit(); // Es buena práctica usar exit() después de una redirección para asegurar que no se ejecute más código
?>