<?php
if (!empty($_POST) && (!empty($_POST['accion']) && $_POST['accion'] == '_modificar')) {
    $ci_anterior = trim($_POST['id_registro_a_modificar_m'] ?? ''); 
    $foto_anterior_db = trim($_POST['foto_anterior_m'] ?? ''); 
    $nom_m = trim(ucwords(filter_var($_POST['nom_formulario_m'] ?? '', FILTER_SANITIZE_STRING)));
    $ape_m = trim(ucwords(filter_var($_POST['ape_formulario_m'] ?? '', FILTER_SANITIZE_STRING)));
    $f_nac_m = $_POST['f_nac_formulario_m'] ?? NULL;
    $tel_m = trim(filter_var($_POST['tel_formulario_m'] ?? '', FILTER_SANITIZE_STRING));
    $mail_m = trim(filter_var($_POST['mail_formulario_m'] ?? '', FILTER_SANITIZE_EMAIL));
    $dom_m = trim(filter_var($_POST['dom_formulario_m'] ?? '', FILTER_SANITIZE_STRING));
    $nick_m = trim(filter_var($_POST['nick_formulario_m'] ?? '', FILTER_SANITIZE_STRING));
    $pass_m = trim($_POST['pass_formulario_m'] ?? ''); 
    $rol_m = trim(filter_var($_POST['rol_formulario_m'] ?? '', FILTER_SANITIZE_STRING)); 
    if (empty($ci_anterior) || empty($nom_m) || empty($ape_m) || empty($nick_m) || empty($pass_m) || empty($rol_m)) {
        header("Location: {$url}?e=1"); 
        exit();}
    elseif (strlen($nom_m) < 3 || strlen($nom_m) > 32) {
        header("Location: {$url}?e=7"); 
        exit();} 
    elseif (strlen($ape_m) < 3 || strlen($ape_m) > 32) {
        header("Location: {$url}?e=8"); 
        exit();} 
    elseif (strlen($nick_m) < 4 || strlen($nick_m) > 32) {
        header("Location: {$url}?e=20"); 
        exit();}
    elseif (strlen($pass_m) < 8 || strlen($pass_m) > 32) {
        header("Location: {$url}?e=6"); 
        exit();}
    elseif (!empty($tel_m) && (strlen($tel_m) < 8 || strlen($tel_m) > 13)) {
        header("Location: {$url}?e=9"); 
        exit();}
    elseif (!empty($mail_m) && !filter_var($mail_m, FILTER_VALIDATE_EMAIL)) {
        header("Location: {$url}?e=5"); 
        exit();}
    $nombre_archivo_db = $_POST['foto_formulario'] ?? null;
    try {
        if (!empty($_FILES['foto_formulario']) && $_FILES['foto_formulario']['error'] !== UPLOAD_ERR_NO_FILE) {
            
            $manejador = new logica_files($ruta);
            $nuevo_nombre_archivo = $manejador->procesarYGuardarImagen($_FILES['foto_anterior']);
            if (!empty($nuevo_nombre_archivo)) {
                $foto_anterior = $datos['img_usuario'] ?? null;
                if (!empty($foto_anterior) && $foto_anterior !== 'default_usuario_negro.png') { 
                    $ruta_anterior = $ruta . $foto_anterior;
                    if (file_exists($ruta_anterior)) {
                        unlink($ruta_anterior);
                    }
                }
                $nombre_archivo_db = $nuevo_nombre_archivo;
            }
        }
        
    } catch (Exception $e) {
        if ($e->getCode() === 13) {
            header("Location: {$url}?e=13"); 
        } elseif ($e->getCode() === 14) {
            header("Location: {$url}?e=14"); 
        } else {
            header("Location: {$url}?e=99"); 
        }
        exit();
    }
    $sql = "UPDATE {$tabla} SET 
                nombre_usuario = ?, 
                pass_usuario = ?, 
                nombres_usuario = ?, 
                apellidos_usuario = ?, 
                f_nacimiento_usuario = ?, 
                telefono_usuario = ?, 
                mail_usuario = ?, 
                domicilio_usuario = ?, 
                img_usuario = ?, 
                rol_id = ?
            WHERE {$columna_id} = ?";
    $tipos = 'sssssssssis';
    $id_rol_param = (int)$rol_m; 
    $parametros = [
        $nick_m, 
        $pass_m, 
        $nom_m, 
        $ape_m, 
        $f_nac_m, 
        $tel_m, 
        $mail_m, 
        $dom_m, 
        $nombre_archivo_db, 
        $id_rol_param,
        $ci_anterior 
    ];
    if ($bd->accionRegistro($sql, $tipos, $parametros)) {
        header("Location: {$url}?s=5"); 
        exit();
    } else {
        header("Location: {$url}?e=2"); 
        exit();}}
?>