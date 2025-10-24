<?php
$_c_uno = 'Configuración de';
$_c_dos = 'Datos de Trapiche';
$_c_tres = 'Configuración';
$_c_cuatro = 'sett_e.css';
$_c_seis = '';
require_once 'includes/logic/logic_e.php'; 
$mensajes_s['1'] = 'Datos del trapiche registrados exitosamente.';
$mensajes_s['1'] = 'Modificación en los datos del trapiche registrada exitosamente.';
$mensajes_e['6'] = 'El RIF debe contener ocho (08) caracteres, como minimo.';
$orden = 't.nombre_trapiche';
$tabla = 'trapiche';
$columna_id = 'rif_trapiche';
$status = 'status_trapiche';
$url = 'sett_t.php';
$ruta = 'uploads/trapiche/';
if (!empty($_POST) && !empty($_POST['accion'])) { 
    $accion = trim($_POST['accion']);
    $original_rif = trim($_POST['original_rif'] ?? ''); 
    $rif_tr_post = trim(filter_var($_POST['ci_formulario'] ?? '', FILTER_SANITIZE_STRING)); 
    $nom_tr_post = trim(filter_var($_POST['nombre_trapiche_formulario'] ?? '', FILTER_SANITIZE_STRING)); 
    $ci_dir_tr_post = trim(filter_var($_POST['ci_formulario_dir'] ?? '', FILTER_SANITIZE_STRING));
    $nom_dir_tr_post = trim(ucwords(filter_var($_POST['nom_formulario'] ?? '', FILTER_SANITIZE_STRING)));
    $ape_dir_tr_post = trim(ucwords(filter_var($_POST['ape_formulario'] ?? '', FILTER_SANITIZE_STRING)));
    $ubi_tr_post = trim(filter_var($_POST['dom_formulario'] ?? '', FILTER_SANITIZE_STRING));
    $tel_tr_post = trim(filter_var($_POST['tel_formulario'] ?? '', FILTER_SANITIZE_STRING));
    $mail_tr_post = trim(filter_var($_POST['mail_formulario'] ?? '', FILTER_SANITIZE_EMAIL));
    $f_fu_tr_post = $_POST['f_nac_formulario'] ?? ''; 
    
    $logo_a_guardar = !empty($logo_tr) ? $logo_tr : 'img/default.png';

    if (empty($rif_tr_post) || empty($nom_tr_post) || empty($ci_dir_tr_post) || empty($nom_dir_tr_post) || empty($ape_dir_tr_post) || empty($ubi_tr_post) || empty($tel_tr_post) || empty($mail_tr_post) || empty($f_fu_tr_post)) {
        $_SESSION['error_code'] = 1;
        header("Location: {$url}");
        exit();
    }
    
    if (!empty($tel_tr_post) && (!ctype_digit($tel_tr_post) || strlen($tel_tr_post) < 7 || strlen($tel_tr_post) > 13)) {
        $_SESSION['error_code'] = 9;
        header("Location: {$url}");
        exit();
    }
    
    if (!filter_var($mail_tr_post, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error_code'] = 5;
        header("Location: {$url}");
        exit();
    }
    
    if ($accion === '_registrar') {
        $sql_check = "SELECT rif_trapiche FROM trapiche WHERE rif_trapiche = ?";
        if (!empty($bd->obtenerRegistro($sql_check, 's', [$rif_tr_post]))) {
            $_SESSION['error_code'] = 15;
            header("Location: {$url}");
            exit();
        }
    } elseif ($accion === '_modificar' && $rif_tr_post !== $original_rif) {
        $sql_check = "SELECT rif_trapiche FROM trapiche WHERE rif_trapiche = ? AND rif_trapiche <> ?";
        if (!empty($bd->obtenerRegistro($sql_check, 'ss', [$rif_tr_post, $original_rif]))) {
             $_SESSION['error_code'] = 15;
            header("Location: {$url}");
            exit();
        }
    }

    if (isset($_FILES['foto_formulario']) && $_FILES['foto_formulario']['error'] !== UPLOAD_ERR_NO_FILE) {
        
        if ($_FILES['foto_formulario']['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['error_code'] = 99; 
            header("Location: {$url}");
            exit();
        }

        try {
            $manejador = new logica_files($ruta); 
            $nuevo_nombre_archivo = $manejador->procesarYGuardarImagen($_FILES['foto_formulario']);
            if (!empty($nuevo_nombre_archivo)) {
                $logo_a_guardar = $nuevo_nombre_archivo;
            } else {
                $_SESSION['error_code'] = 2; 
                header("Location: {$url}");
                exit();
            }
        } catch (Exception $e) {
            if ($e->getCode() === 13) {
                $_SESSION['error_code'] = 99; 
                header("Location: {$url}");
                exit(); 
            }
            $_SESSION['error_code'] = 2; 
            header("Location: {$url}");
            exit();
        }
    }
    
    if ($accion === '_modificar') {
        $sql = "UPDATE trapiche SET 
            rif_trapiche = ?, 
            nombre_trapiche = ?, 
            ci_director_a_trapiche = ?, 
            nombres_director_a_trapiche = ?, 
            apellidos_director_a_trapiche = ?, 
            ubicacion_trapiche = ?, 
            telefono_trapiche = ?, 
            mail_trapiche = ?, 
            f_fundacion_trapiche = ?, 
            logo_trapiche = ?, 
            usuario_ci = ? 
        WHERE rif_trapiche = ?";
        $tipos = 'ssssssssssss'; 
        $parametros = [
            $rif_tr_post,
            $nom_tr_post,
            $ci_dir_tr_post,
            $nom_dir_tr_post,
            $ape_dir_tr_post, 
            $ubi_tr_post, 
            $tel_tr_post, 
            $mail_tr_post, 
            $f_fu_tr_post, 
            $logo_a_guardar, 
            $ci_usuario_sesion, 
            $original_rif 
        ];
        $mensaje_exito = 5; 
    } else { 
        $sql = "INSERT INTO trapiche (rif_trapiche, nombre_trapiche, ci_director_a_trapiche, nombres_director_a_trapiche, apellidos_director_a_trapiche, ubicacion_trapiche, telefono_trapiche, mail_trapiche, f_fundacion_trapiche, logo_trapiche, usuario_ci) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $tipos = 'sssssssssss'; 
        $parametros = [
            $rif_tr_post,
            $nom_tr_post, 
            $ci_dir_tr_post,
            $nom_dir_tr_post,
            $ape_dir_tr_post, 
            $ubi_tr_post, 
            $tel_tr_post, 
            $mail_tr_post, 
            $f_fu_tr_post, 
            $logo_a_guardar, 
            $ci_usuario_sesion 
        ];
        $mensaje_exito = 1;
    }   
    
    if ($bd->accionRegistro($sql, $tipos, $parametros)['success']) {
        $_SESSION['success_code'] = $mensaje_exito;
        header("Location: {$url}"); 
        exit();
    } else {
        $_SESSION['error_code'] = 2; 
        header("Location: {$url}");
        exit();
    }
}
?>
<script type="module" src="JS/img.js"></script>