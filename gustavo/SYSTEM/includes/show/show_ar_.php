<?php 
if (!empty($_POST) && (!empty($_POST['accion']) && $_POST['accion'] == '_modificar')) {
    $id = trim($_POST['id_registro_a_modificar_m'] ?? '');
    $nom_area = trim(ucwords(filter_var($_POST['nom_formulario_m'] ?? '', FILTER_SANITIZE_STRING)));
    $fot_area = $_POST['foto_existente_m'] ?? NULL; 
    $nuevo_archivo_db = NULL; 
    if (empty($id) || empty($nom_area)) {
        header("Location: {$url}?e=1"); 
        exit();} 
    if (strlen($nom_area) > 32 || strlen($nom_area) < 4) {
        header("Location: {$url}?e=7"); 
        exit();} 
        try {
            require_once 'includes/logic/logic_e.php'; 
            if (!empty($_FILES['foto_formulario_m']) && $_FILES['foto_formulario_m']['error'] !== UPLOAD_ERR_NO_FILE) {
                
                $manejador = new logica_files($ruta);
                $nuevo_archivo_db = $manejador->procesarYGuardarImagen($_FILES['foto_formulario_m']);
                if (!empty($nuevo_archivo_db)) {
                $fot_area = $nuevo_archivo_db;}}} 
    catch (Exception $e) {
        if ($e->getCode() === 13) {
            header("Location: {$url}?e=13");} 
        elseif ($e->getCode() === 14) {
            header("Location: {$url}?e=14");} 
        else {
            header("Location: {$url}?e=99"); }
        exit();}
    $sql = "UPDATE {$tabla} SET 
        nombre_area = ?, 
        img_area = ?, 
        usuario_ci = ?
    WHERE {$columna_id} = ?";
    $tipos = 'ssii';
    $parametros = [
        $nom_area, 
        $fot_area, 
        (int)$ci_usuario_sesion,
        (int)$id        
    ];
    if ($bd->accionRegistro($sql, $tipos, $parametros)) {
        header("Location: {$url}?s=5");
        exit();} 
    else {
        if (!empty($nuevo_archivo_db) && file_exists($ruta . $nuevo_archivo_db)) {
            unlink($ruta . $nuevo_archivo_db); }
        header("Location: {$url}?e=2"); 
        exit();}}?>