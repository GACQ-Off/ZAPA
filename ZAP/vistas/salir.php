<?php




// 1. Inicia la sesión si aún no está iniciada.
// Es crucial para poder manipular la sesión (destruirla, acceder a variables, etc.).
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 2. Destruye todas las variables de la sesión.
// Esto elimina los datos del usuario logeado del array $_SESSION.
$_SESSION = array();

// 3. Elimina la cookie de sesión del navegador.
// Esto es vital para asegurar que la cookie de sesión sea eliminada del lado del cliente,
// impidiendo que el navegador intente enviar una cookie de sesión inválida en futuras peticiones.
// session_name() obtiene el nombre de la cookie de sesión (usualmente "PHPSESSID").
// time() - 42000 establece una fecha en el pasado para que la cookie expire inmediatamente.
// session_get_cookie_params() obtiene los parámetros actuales de la cookie (path, domain, secure, httponly)
// para asegurarse de que la cookie se elimine correctamente, respetando cómo fue creada.
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"], // true si usas HTTPS, false si usas HTTP (considera siempre HTTPS en producción)
        $params["httponly"] // true para proteger contra XSS
    );
}

session_destroy();

echo '<script>

	window.location = "ingreso";

</script>';