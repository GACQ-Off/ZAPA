<?php
$alert = '';
include ('editar/Encriptar.php');
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
if (!empty($_POST)) {
    if (empty($_POST['usuario']) || empty($_POST['Clave'])) {
        $alert = 'Ingrese su usuario y su clave';
    } else {

        require_once "conexion/conexion.php";

        $usuario = $_POST['usuario'];
        $pass = $_POST['Clave'];
           $clave_encriptada=encriptar($pass, $usuario);

        $query = mysqli_query($conn, "SELECT * FROM usuario WHERE clave = '$clave_encriptada' AND estado_usuario != 2 AND nombre_usuario= '$usuario'");
        $result = mysqli_num_rows($query);
        if ($result > 0) {
            $data = mysqli_fetch_array($query);
            session_start();
            $_SESSION['active'] = true;
            $_SESSION['id_usuario'] = $data['id_usuario'];
            $_SESSION['clave'] = $data['clave'];
            $_SESSION['nombre_usuario'] = $data['nombre_usuario'];
            $_SESSION['id_tipo_usuario'] = $data['id_tipo_usuario'];

            if ($_SESSION['id_tipo_usuario'] == 1) {
                header('location: menu.php');
            } elseif ($_SESSION['id_tipo_usuario'] == 2) {
                header('location: ventas/cajero.php?action=nueva_venta');
            } else {
                echo "<script>alert('Usuario no encontrado');</script>";
            }

        } else {
            $query_estado = mysqli_query($conn, "SELECT estado_usuario FROM usuario WHERE nombre_usuario= '$usuario'");
            if (mysqli_num_rows($query_estado) > 0) {
                $data_estado = mysqli_fetch_array($query_estado);
                if ($data_estado['estado_usuario'] == 2) {
                    echo "<script>alert('Este usuario no tiene permiso para iniciar sesión se encuentra en estado inactivo.');</script>";
                } else {
                    echo "<script>alert('Usuario o clave incorrectos.');</script>";
                }
            } else {
                echo "<script>alert('Usuario o clave incorrectos.');</script>";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="icon" href="assets/images/logo_icon.png">
</head>
<body>
    <div class="login-container">
        <div class="logo-section">
            <img src="assets/images/vertex.png" alt="Logo de la Empresa">
            <h2>Alcanza la cima de tus finanzas</h2>
        </div>

        <div class="form-section">
            <h2>Iniciar Sesión</h2>
            <form action="" method="post" autocomplete="off">
                <div class="form-group">
                    <label for="username">Usuario:</label>
                    <input type="text" id="username" name="usuario" placeholder="Tu nombre de usuario" required="">
                </div>
                <div class="form-group">
                    <label for="password">Contraseña:</label>
                    <input type="password" id="Clave" name="Clave" placeholder="Tu contraseña" required="">
                </div>
                <button type="submit">Ingresar</button>
                <div class="extra-links">
                </div>
            </form>
        </div>
    </div>

</body>
</html>