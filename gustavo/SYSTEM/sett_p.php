<?php
session_start();
$_tabs_atr = 9;
require_once 'includes/_wrong.php';
require_once '../_con.php';
require_once 'includes/logic/logic_g.php';
require_once 'includes/scripts/sett_p.php';
require_once 'includes/_general.php';
require_once 'includes/logic/logic_e.php';
require_once 'reports/reports_.php';
require_once 'includes/_messages.php';
$datos_usuario = null;
if ($ci_usuario_sesion) {
    $sql = "SELECT * FROM {$tabla} WHERE {$columna_id} = ?";
    $resultado = $bd->obtenerRegistro($sql, 's', [$ci_usuario_sesion]);
    if (!empty($resultado)) {
        $datos_usuario = $resultado[0];
        $fotoPerfil = !empty($datos_usuario['img_usuario']) ? $ruta . $datos_usuario['img_usuario'] : 'img/default_profile.jpg';
        $_SESSION['nombre_usuario'] = $datos_usuario['nombre_usuario'];
        $_SESSION['nombres_usuario'] = $datos_usuario['nombres_usuario'];
        $_SESSION['apellidos_usuario'] = $datos_usuario['apellidos_usuario'];
        $_SESSION['f_nacimiento_usuario'] = $datos_usuario['f_nacimiento_usuario'];
        $_SESSION['telefono_usuario'] = $datos_usuario['telefono_usuario'];
        $_SESSION['mail_usuario'] = $datos_usuario['mail_usuario'];
        $_SESSION['pass_usuario'] = $datos_usuario['pass_usuario']; 
        $_SESSION['domicilio_usuario'] = $datos_usuario['domicilio_usuario'];} 
    else {
        header("Location: ../../index.php?e=4"); 
        exit();}} 
else {
    header("Location: ../../index.php");
    exit();}
if (!empty($_POST)) {
    $ci_au = $ci_usuario_sesion; 
    $nick_au = trim(filter_var($_POST['nick_formulario'] ?? '', FILTER_SANITIZE_STRING));
    $pass_au = trim($_POST['pass_formulario'] ?? '');
    $nom_au = trim(ucwords(filter_var($_POST['nom_formulario'] ?? '', FILTER_SANITIZE_STRING)));
    $ape_au = trim(ucwords(filter_var($_POST['ape_formulario'] ?? '', FILTER_SANITIZE_STRING)));
    $f_nac_au = $_POST['f_nac_formulario'] ?? NULL;
    $tel_au = trim(filter_var($_POST['tel_formulario'] ?? '', FILTER_SANITIZE_STRING));
    $mail_au = trim(filter_var($_POST['mail_formulario'] ?? '', FILTER_SANITIZE_EMAIL));
    $dom_au = trim(filter_var($_POST['dom_formulario'] ?? '', FILTER_SANITIZE_STRING));
    if (empty($nick_au) || empty($nom_au) || empty($ape_au) || empty($nick_au) || empty($pass_au)) {
        header("Location: {$url}?e=1"); 
        exit();}
    if (empty($pass_au)) {
        header("Location: {$url}?e=1"); 
        exit();}
    if (strlen($nick_au) < 4 || strlen($nick_au) > 32) {
        header("Location: {$url}?e=20"); 
        exit();}
    if (!empty($tel_au) && (strlen($tel_au) < 8 || strlen($tel_au) > 13)) {
        header("Location: {$url}?e=7"); 
        exit();}
    if (!empty($mail_au) && !filter_var($mail_au, FILTER_VALIDATE_EMAIL)) {
        header("Location: {$url}?e=55"); 
        exit();};
    if (strlen($pass_au) < 8 || strlen($pass_au) > 32) {
        header("Location: {$url}?e=6"); 
        exit();}
    $nombre_archivo_db = $datos_usuario['img_usuario'] ?? null;
    try {
        if (!empty($_FILES['foto_formulario']) && $_FILES['foto_formulario']['error'] !== UPLOAD_ERR_NO_FILE) {
            $manejador = new logica_files($ruta);
            $nuevo_nombre_archivo = $manejador->procesarYGuardarImagen($_FILES['foto_formulario']);
            if (!empty($nuevo_nombre_archivo)) {
                $nombre_archivo_db = $nuevo_nombre_archivo;
                $foto_anterior = $datos_usuario['img_usuario'] ?? null;
                if (!empty($foto_anterior) && $foto_anterior !== 'img/default.jpg') { 
                    $ruta_anterior = $ruta . $foto_anterior;}}}} 
    catch (Exception $e) {
        if ($e->getCode() === 13) {
            header("Location: {$url}?e=13");} 
        elseif ($e->getCode() === 14) {
            header("Location: {$url}?e=14");} 
        else {
            header("Location: {$url}?e=99"); }
            exit();}
    $sql_update = "UPDATE {$tabla} SET nombre_usuario = ?, pass_usuario = ?, nombres_usuario = ?, apellidos_usuario = ?, f_nacimiento_usuario = ?, telefono_usuario = ?, mail_usuario = ?, domicilio_usuario = ?, img_usuario = ? WHERE {$columna_id} = ?";
    $tipos = 'ssssssssss';
    $parametros = [
        $nick_au, 
        $pass_au,
        $nom_au, 
        $ape_au, 
        $f_nac_au, 
        $tel_au, 
        $mail_au, 
        $dom_au, 
        $nombre_archivo_db, 
        $ci_au 
    ];
    if ($bd->accionRegistro($sql_update, $tipos, $parametros)) {
        $_SESSION['nombre_usuario'] = $nick_au;
        $_SESSION['nombres_usuario'] = $nom_au;
        $_SESSION['apellidos_usuario'] = $ape_au;
        $_SESSION['pass_usuario'] = $pass_au;
        $_SESSION['img_usuario'] = $nombre_archivo_db;
        header("Location: {$url}?s=1");
        exit();
    } else {
        header("Location: {$url}?e=2");
        exit();}}?>
<!DOCTYPE html>
    <html lang="es">

    <head>
        <?php include "includes/_head.php"; ?>
    </head>

    <body>
        <div id="fondo"></div>
        <main>
            <?php include "includes/_header.php";
            include "includes/sett_tabs.php"; ?>
            <section id="listados_">
                <form method="post" action="" id="contenedor_de_botones" enctype="multipart/form-data">
                    <div class="contenedor_mitad">
                        <p class="label_artificial">
                            Foto de Perfil
                        </p>
                        <div class="imagen_contenedor_uno">
                            <img id="imagen_previa"src="<?php echo htmlspecialchars($fotoPerfil); ?>" alt="Previsualización de la imagen">
                        </div>
                        <div id="foto_input_contenedor">
                            <label for="foto_formulario" class="label_de_imagen_o_archivo">
                                <img src="img/svg/foto_blanco.svg" class="icono">
                                Cambiar Foto
                            </label>
                            <input type="file" name="foto_formulario" id="foto_formulario" accept="image/*"
                                class="label_de_imagen_o_archivo">
                        </div>
                    </div>

                    <div id="segundoparte">
                        <fieldset id="informacion_usuario">
                            <legend class="label_artificial">
                                Información de Sistema
                            </legend>
                            <table class="formulario_tabla">
                                <tr>
                                    <td>
                                        <div>
                                            <label for="nick_formulario">
                                                Nombre de Usuario:
                                            </label>
                                            <br>
                                            <input type="text" id="nick_formulario" name="nick_formulario"
                                                placeholder="Nombre de Usuario" minlength="4" maxlength="32" autocomplete="off"
                                                value="<?php echo htmlspecialchars($_SESSION['nombre_usuario']); ?>">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                    <div>
                                        <label for="pass_formulario">
                                            Contraseña:
                                        </label>
                                        <br>
                                        <input type="text" name="pass_formulario" id="pass_formulario"
                                            placeholder="Contraseña" minlength="8" maxlength="32" autocomplete="off"
                                            value="<?php echo htmlspecialchars($_SESSION['pass_usuario'] ?? ''); ?>">
                                    </div>
                                    </td>
                                </tr>
                            </table>
                        </fieldset>
                        <fieldset id="informacion_general">
                            <legend class="label_artificial">
                                Información General
                            </legend>
                            <table class="formulario_tabla">
                                <tbody>
                                <tr>
                                    <td colspan="2">
                                        <div>
                                            <label for="ci_formulario" class="labels">
                                                Cédula de Identidad (No Modificable):
                                            </label>
                                            <input type="text" id="ci_formulario" name="ci_formulario" readonly autocomplete="off"
                                                value="<?php echo htmlspecialchars($_SESSION['ci_usuario']); ?>">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div>
                                            <label for="nom_formulario" class="labels">
                                                Nombres:
                                            </label>
                                            <input type="text" name="nom_formulario" id="nom_formulario"
                                                placeholder="Primer y Segundo Nombre" minlength="3" maxlength="32" autocomplete="off"
                                                value="<?php echo htmlspecialchars($_SESSION['nombres_usuario']); ?>">
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <label for="ape_formulario" class="labels">
                                                Apellidos:
                                            </label>
                                            <input type="text" name="ape_formulario" id="ape_formulario"
                                                placeholder="Primer y Segundo Apellido" minlength="3" maxlength="32" autocomplete="off"
                                                value="<?php echo htmlspecialchars($_SESSION['apellidos_usuario']); ?>">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <div>
                                            <label for="f_nac_formulario" class="labels">
                                                Fecha de Nacimiento:
                                            </label><input type="date" name="f_nac_formulario" 
                                                id="f_nac_formulario" value="<?php echo htmlspecialchars(date('Y-m-d', strtotime($_SESSION['f_nacimiento_usuario']))); ?>">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div>
                                            <label for="tel_formulario" class="labels">
                                                Teléfono(s) de Contacto:
                                            </label>
                                            <input type="text" name="tel_formulario" id="tel_formulario"
                                                placeholder="Número Teléfonico" minlength="8" maxlength="13" autocomplete="off"
                                                value="<?php echo htmlspecialchars($_SESSION['telefono_usuario']); ?>">
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <label for="mail_formulario" class="labels">
                                                Correo Electrónico:
                                            </label>
                                            <input type="email" name="mail_formulario" id="mail_formulario"
                                                placeholder="Correo Electrónico" minlength="10" maxlength="65" autocomplete="off"
                                                value="<?php echo htmlspecialchars($_SESSION['mail_usuario']); ?>">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <div>
                                            <label for="dom_formulario" class="labels">
                                                Domicilio:
                                            </label>
                                            <textarea name="dom_formulario" id="dom_formulario" placeholder="Dirección"
                                                minlength="6" autocomplete="off"
                                                maxlength="65"><?php echo htmlspecialchars($_SESSION['domicilio_usuario']); ?></textarea>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                            </table>
                        </fieldset>
                        <button type="submit" id="boton_guardar">
                            <img src="img/svg/disket_blanco.svg" alt="Icono de Guardar" class="icono">
                            <p>Guardar</p>
                        </button>
                        <div id="modal_tercero" class="modal_confirmacion">
                            <div class="modal_contenido">
                                <p>¿Está seguro de que los datos ingresados son los correctos?</p>
                                <div class="modal_botones">
                                    <button id="confirmar_guardar">Sí, Guardar</button>
                                    <button id="cancelar_guardar">Cancelar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </section>
        </main>
        <?php include "includes/_bar.php";
        include 'includes/_messages.php'; ?>
    </body>

    </html>





