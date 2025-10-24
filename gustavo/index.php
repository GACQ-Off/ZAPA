<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();}
$_tabs_atr = NULL;
require_once '_con.php';
require_once 'SYSTEM/includes/logic/logic_g.php';
$mensajes_s = [
    '1' => 'Se ha cerrado la sesión de forma exitosa'
];
$mensajes_e = [
    '1'  => 'Usuario o contraseña incorrectos.',
    '2' => 'Por favor, llena todos los campos.',
    '3' => 'Cierre de sesión inesperado.',
];
if (!empty($_POST)) {
    if (empty($_POST['nickname_us']) || empty($_POST['contra_us'])) {
        header('Location: index.php?e=2');
        exit();}
    $nick_us_i = trim($_POST['nickname_us']);
    $pass_us_i = trim($_POST['contra_us']);
    $usuario = $bd->obtenerUsuario($nick_us_i, $pass_us_i);
    if ($usuario) {
        $_SESSION['active'] = true;
        foreach ($usuario as $indice => $valor) {
            $_SESSION[$indice] = $valor;}
        header('Location: ./SYSTEM/index.php?s=1');
        exit();
    } else {
        header('Location: index.php?e=1');
        exit();}} ?>
<! DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión | Asociación Civil Museo Trapiche de los Clavo</title>
    <link rel="shortcut icon" href="SYSTEM/img/logo-glass.png" type="image/png">
    <script type="module" src="SYSTEM/JS/ind_l.js" defer></script>
    <script type="module" src="SYSTEM/JS/_messages.js" defer></script>
    <link rel="stylesheet" href="SYSTEM/CSS/messages.css">
    <link rel="stylesheet" href="SYSTEM/CSS/ind_l.css">
    <link rel="stylesheet" href="SYSTEM/CSS/buttons.css">
</head>

<body>

    <main>
        <form method="post">
            <div id="primer_contenedor">
                <img alt="Logo" src="SYSTEM/img/logo-glass.png">
                <div id="titulo">
                    <h3>Aplicación Web para el Control de los Procesos de Mantenimiento de las Obras de Arte</h3>
                </div>
                <div id="subtitulo">
                    <h2>Bienvenido</h2>
                </div>
                <div id="texto_breve">
                    <p>Ingrese sus datos en los siguientes campos y presione <em>Entrar</em> para Iniciar Sesión.</p>
                </div>
                <div id="formulario">
                    <table>
                        <tr>
                            <th colspan="2">
                                <label for="nickname_us"><img src="SYSTEM/img/svg/usuario_negro.svg" alt="icono de usuario" class="icono">Usuario:</label>
                                <input type="text" id="nickname_us" name="nickname_us" placeholder="Nombre de Usuario" maxlength="32" autocomplete="off">
                            </th>
                        </tr>
                        <tr>
                            <th colspan="2">
                                <label for="contra_us"><img src="SYSTEM/img/svg/contrasena_negro.svg" alt="icono de contraseña" class="icono">Contraseña:</label>
                                <input type="password" id="contra_us" name="contra_us" placeholder="Contraseña" maxlength="32" autocomplete="off">
                            </th>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <?php include 'SYSTEM/includes/forms/b_form.php'; ?>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <div id="segundo_contenedor">
                <img alt="Logo" src="SYSTEM/img/logotrapiche.png">
                <p id="soporte" class="acciones_boton">Soporte Técnico</p>
            </div>
        </form>
    </main>

    <aside>
        <img src="" alt="visor de fotos" id="fotos">
    </aside>

    <section id="primer_modal">
        <div id="modal__contenedor">
            <h2>Contacto para Soporte Técnico</h2>
            <p><strong>Nombre: </strong> Gustavo Celada.</p>
            <p><strong>Teléfono de Contacto:</strong> +58-414-7401948.</p>
            <p><strong>Correo Electrónico:</strong></p>
            <ul>
                <li>gacqoficial@gmail.com</li>
                <li>gustav0ficial@outlook.es</li>
            </ul>
        </div>
    </section>
    <?php include 'SYSTEM/includes/_messages.php' ?>
</body>
</html>