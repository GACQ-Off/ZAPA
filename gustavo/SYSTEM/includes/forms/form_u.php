<?php
$sql_roles = "SELECT id_rol, descripcion_rol FROM rol";
$roles = $bd->obtenerRegistro($sql_roles, '', []);
if (!empty($_POST) && ((!empty($_POST['accion']) && $_POST['accion'] == '_registrar'))) {
    if (empty($_POST['ci_formulario']) || empty($_POST['nom_formulario']) || empty($_POST['ape_formulario']) || empty($_POST['nick_formulario']) || empty($_POST['pass_formulario']) || empty($_POST['rol_formulario'])) {
        header("Location: {$url}?e=1");
        exit();} 
    else {
        $ci_us = trim($_POST['ci_formulario']);
        $nom_us = trim(ucwords(filter_var($_POST['nom_formulario'], FILTER_SANITIZE_STRING)));
        $ape_us = trim(ucwords(filter_var($_POST['ape_formulario'], FILTER_SANITIZE_STRING)));
        $f_n_us = !empty($_POST['f_nac_formulario']) ? $_POST['f_nac_formulario'] : NULL;
        $tel_us = !empty($_POST['tel_formulario']) ? trim($_POST['tel_formulario']) : NULL;
        $mail_us = !empty($_POST['mail_formulario']) ? filter_var($_POST['mail_formulario'], FILTER_SANITIZE_EMAIL) : NULL;
        $dom_us = !empty($_POST['dom_formulario']) ? filter_var($_POST['dom_formulario'], FILTER_SANITIZE_STRING) : NULL;
        $nick_us = filter_var($_POST['nick_formulario'], FILTER_SANITIZE_STRING);
        $pass_us = $_POST['pass_formulario'];
        $rol_Id = intval($_POST['rol_formulario']);
        $nombre_archivo_db = $_POST['foto_formulario'] ?? null;
        try {
            require_once 'includes/logic/logic_e.php';
            if (!empty($_FILES['foto_formulario']) && $_FILES['foto_formulario']['error'] !== UPLOAD_ERR_NO_FILE) {
                $manejador = new logica_files($ruta);
                $nuevo_nombre_archivo = $manejador->procesarYGuardarImagen($_FILES['foto_formulario']);
                if (!empty($nuevo_nombre_archivo)) {
                    $nombre_archivo_db = $nuevo_nombre_archivo;
                    $foto_anterior = $nombre_archivo_db ?? null;
                    if (!empty($foto_anterior) && $foto_anterior !== 'img/default.jpg') {
                        $ruta_anterior = $ruta . $foto_anterior;}}}} 
        catch (Exception $e) {
            if ($e->getCode() === 13) {
                header("Location: {$url}?e=13");} 
            elseif ($e->getCode() === 14) {
                header("Location: {$url}?e=14");} 
            else {
                header("Location: {$url}?e=99");}
            exit();}
        if (!preg_match('/^[0-9]+$/', $ci_us)) {
            header("Location: {$url}?e=4");
            exit();} 
        elseif (!empty($mail_us) && !filter_var($mail_us, FILTER_VALIDATE_EMAIL)) {
            header("Location: {$url}?e=5");
            exit();} 
        elseif (strlen($pass_us) < 8) {
            header("Location: {$url}?e=6");
            exit();} 
        elseif ($bd->existeEnTabla('usuario', 'ci_usuario', $ci_us) || $bd->existeEnTabla('usuario', 'nombre_usuario', $nick_us)) {
            header("Location: {$url}?e=3");
            exit();} 
        else {
            $sql = "INSERT INTO {$tabla} (ci_usuario, nombres_usuario, apellidos_usuario, f_nacimiento_usuario, telefono_usuario, mail_usuario, domicilio_usuario, img_usuario, nombre_usuario, pass_usuario, status_usuario, rol_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $tipos = 'isssssssssii';
            $parametros = [$ci_us, $nom_us, $ape_us, $f_n_us, $tel_us, $mail_us, $dom_us, $nombre_archivo_db, $nick_us, $pass_us, $status_comun, $rol_Id];
            if ($bd->accionRegistro($sql, $tipos, $parametros)) {
                header("Location: {$url}?s=1");
                exit();} 
            else {
                header("Location: {$url}?e=2");
                exit();}}}}?>
    <section id="cuerpo_formulario">
        <div class="contenedor_mitad">
            <p class="label_artificial">
                Foto de Perfil
            </p>
            <div class="imagen_contenedor_uno">
                <img id="imagen_previa" src="img/default_usuario_negro.png" alt="Previsualización de la imagen">
            </div>
            <div id="foto_input_contenedor">
                <label for="foto_formulario" class="label_de_imagen_o_archivo">
                    <img src="img/svg/foto_blanco.svg" class="icono">
                    Seleccionar Foto
                </label>
                <input type="file" name="foto_formulario" id="foto_formulario" accept="image/*"
                    class="label_de_imagen_o_archivo">
            </div>
        </div>

        <div id="segundoparte">
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
                                        Cédula de Identidad (*):
                                    </label>
                                    <input type="text" id="ci_formulario" name="ci_formulario" placeholder="C.I"
                                        maxlength="9" autocomplete="off">
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <div>
                                    <label for="nom_formulario" class="labels">
                                        Nombres (*):
                                    </label>
                                    <input type="text" name="nom_formulario" id="nom_formulario"
                                        placeholder="Primer y Segundo Nombre" minlength="3" maxlength="32"
                                        autocomplete="off">
                                </div>
                            </td>
                            <td>
                                <div>
                                    <label for="ape_formulario" class="labels">
                                        Apellidos (*):
                                    </label>
                                    <input type="text" name="ape_formulario" id="ape_formulario"
                                        placeholder="Primer y Segundo Apellido" minlength="3" maxlength="32"
                                        autocomplete="off">
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td  colspan="2">
                                <div>
                                    <label for="f_nac_formulario" class="labels">
                                        Fecha de Nacimiento:
                                    </label>
                                    <input type="date" name="f_nac_formulario" id="f_nac_formulario">
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <div>
                                    <label for="mail_formulario" class="labels">
                                        Correo Electrónico:
                                    </label>
                                    <input type="email" name="mail_formulario" id="mail_formulario"
                                        placeholder="Correo Electrónico" minlength="10" maxlength="65"
                                        autocomplete="off">
                                </div>
                            </td>
                            <td>
                                <div>
                                    <label for="tel_formulario" class="labels">
                                        Teléfono de Contacto:
                                    </label>
                                    <input type="text" name="tel_formulario" id="tel_formulario"
                                        placeholder="Número Teléfonico" minlength="8" maxlength="13" autocomplete="off">
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
                                        minlength="6" maxlength="65" autocomplete="off"></textarea>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </fieldset>

            <fieldset id="informacion_usuario">
                <legend class="label_artificial">
                    Información de Sistema
                </legend>
                <table class="formulario_tabla">
                    <tbody>
                        <tr>
                            <td>
                                <div>
                                    <label for="nick_formulario">
                                        Nombre de Usuario (*):
                                    </label>
                                    <br>
                                    <input type="text" id="nick_formulario" name="nick_formulario"
                                        placeholder="Nombre de Usuario" minlength="4" maxlength="32" autocomplete="off">
                                </div>
                            </td>
                            <td>
                                <div>
                                    <label for="pass_formulario_visible">
                                        Contraseña (*):
                                    </label>
                                    <br>
                                    <input type="password" name="pass_formulario" id="pass_formulario_visible"
                                        placeholder="Contraseña" minlength="8" maxlength="32" autocomplete="off">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div>
                                    <label for="rol_input_visible">
                                        Rol (*):
                                    </label>
                                    <input type="text" id="rol_input_visible" list="roles_datalist"
                                        placeholder='Indique Rol del Usuario' autocomplete="off">
                                    <input type="hidden" name="rol_formulario" id="rol_input_oculto">
                                    <datalist id="roles_datalist">
                                        <?php
                                        if (!empty($roles)) {
                                            foreach ($roles as $rol) {
                                                echo '<option data-id="' . htmlspecialchars($rol["id_rol"]) . '" value="' . htmlspecialchars($rol["descripcion_rol"]) . '"></option>';}}?>
                                    </datalist>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </fieldset>
        </div>
    </section>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const rolInputVisible = document.getElementById('rol_input_visible');
        const rolInputOculto = document.getElementById('rol_input_oculto');
        const rolDatalist = document.getElementById('roles_datalist');
        const rolMap = {};
        rolDatalist.querySelectorAll('option').forEach(option => {
            rolMap[option.value] = option.getAttribute('data-id');});
        rolInputVisible.addEventListener('input', function () {
            const descripcionSeleccionada = this.value;
            const rolId = rolMap[descripcionSeleccionada];
            if (rolId) {
                rolInputOculto.value = rolId;} 
            else {
                rolInputOculto.value = '';}});});
</script>