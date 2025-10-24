<?php 
    if (!empty($_POST) && ((!empty($_POST['accion']) && $_POST['accion'] == '_registrar'))) {
        if (empty($_POST['nom_formulario'])) {
            header('Location: list_ar.php?e=1');
            exit();} 
        else {
            $nom_are = trim(ucwords(filter_var($_POST['nom_formulario'], FILTER_SANITIZE_STRING)));
            $nombre_archivo_db = NULL; 
            try {
                require_once 'includes/logic/logic_e.php';
                if (!empty($_FILES['foto_formulario']) && $_FILES['foto_formulario']['error'] !== UPLOAD_ERR_NO_FILE) {
                    $manejador = new logica_files($ruta);
                    $nuevo_nombre_archivo = $manejador->procesarYGuardarImagen($_FILES['foto_formulario']);
                    if (!empty($nuevo_nombre_archivo)) {
                        $nombre_archivo_db = $nuevo_nombre_archivo;}}
                if (strlen($nom_are) > 32 || strlen($nom_are) < 4) {
                    if (!empty($nombre_archivo_db) && file_exists($ruta . $nombre_archivo_db)) {
                        unlink($ruta . $nombre_archivo_db);}
                    header('Location: list_ar.php?e=6'); 
                    exit();} 
                $sql = 'INSERT INTO area (nombre_area, img_area, status_area, usuario_ci) VALUES (?, ?, ?, ?)';
                $tipos = 'sssi'; 
                $parametros = [$nom_are, $nombre_archivo_db, $status_comun, $ci_usuario_sesion];
                if ($bd->accionRegistro($sql, $tipos, $parametros)) {
                    header('Location: list_ar.php?s=1');
                    exit();} 
                else {
                    if (!empty($nombre_archivo_db) && file_exists($ruta . $nombre_archivo_db)) {
                        unlink($ruta . $nombre_archivo_db);}
                    header('Location: list_ar.php?e=2');
                    exit();}} 
            catch (Exception $e) {
                    header("Location: {$url}?e=99");}
                exit();}}?>
<section id="cuerpo_formulario">
    <div>
        <label for="nom_formulario" class="label_artificial">
            Nombre (*):
        </label>
        <input type="text" name="nom_formulario" id="nom_formulario" placeholder="Nombre del Area" maxlength="32" autocomplete="off" minlength="4">
    </div>
    <div>
        <label for="" class="label_artificial">
            Material Fotográfico de Referencia:
        </label>
        <div class="imagen_contenedor">
            <img id="imagen_previa" src="" alt="Previsualización de la imagen">
        </div>
        <div>
            <label for="foto_formulario" class="label_de_imagen_o_archivo">
                <img src="img/svg/foto_blanco.svg" alt="icono de cerrar" class="icono">
                Seleccionar Foto
            </label>
            <input type="file" name="foto_formulario" id="foto_formulario" accept="image/*" class="label_de_imagen_o_archivo">
        </div>
    </div>
</section>