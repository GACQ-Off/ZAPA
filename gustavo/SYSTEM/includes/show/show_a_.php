<?php 
if (!empty($_POST) && (!empty($_POST['accion']) && $_POST['accion'] == '_modificar')) {
    if (empty($_POST['id_registro_a_modificar']) || empty($_POST['ci_formulario']) || empty($_POST['nom_formulario']) || empty($_POST['ape_formulario'])) {
        header("Location: {$url}?e=1"); 
        exit();
    } else {
        $id_original = trim($_POST['id_registro_a_modificar']);
        $ci_au = trim($_POST['ci_formulario']);
        $nom_au = trim(ucwords(filter_var($_POST['nom_formulario'], FILTER_SANITIZE_STRING)));
        $ape_au = trim(ucwords(filter_var($_POST['ape_formulario'], FILTER_SANITIZE_STRING)));
        $pseu_au = !empty($_POST['pseu_formulario']) ? ucwords(filter_var($_POST['pseu_formulario'], FILTER_SANITIZE_STRING)) : NULL;
        $f_nac_au = !empty($_POST['f_nac_formulario']) ? $_POST['f_nac_formulario'] : NULL;
        $f_fall_au = !empty($_POST['f_fall_formulario']) ? $_POST['f_fall_formulario'] : NULL;
        $tel_au = !empty($_POST['tel_formulario']) ? $_POST['tel_formulario'] : NULL;
        $mail_au = !empty($_POST['mail_formulario']) ? filter_var($_POST['mail_formulario'], FILTER_SANITIZE_EMAIL) : NULL;
        $dom_au = !empty($_POST['dom_formulario']) ? filter_var($_POST['dom_formulario'], FILTER_SANITIZE_STRING) : NULL;
        if (strlen($nom_au) < 3 || strlen($nom_au) > 32) {
            header("Location: {$url}?e=7"); 
            exit();} 
        elseif (strlen($ape_au) < 3 || strlen($ape_au) > 32) {
            header("Location: {$url}?e=8"); 
            exit();} 
        elseif (!empty($pseu_au) && (strlen($pseu_au) < 3 || strlen($pseu_au) > 65)) {
            header("Location: {$url}?e=9");
            exit();} 
        elseif (!empty($tel_au) && (strlen($tel_au) < 7 || strlen($tel_au) > 13)) {
            header("Location: {$url}?e=9");
            exit();} 
        elseif (!empty($mail_au) && !filter_var($mail_au, FILTER_VALIDATE_EMAIL)) {
            header("Location: {$url}?e=5");
            exit();} 
        else {
            $sql = "UPDATE {$tabla} SET 
                ci_autor = ?, 
                nombres_autor = ?, 
                apellidos_autor = ?, 
                seudonimos_autor = ?, 
                f_nacimiento_autor = ?, 
                f_fallecimiento_autor = ?, 
                telefono_autor = ?, 
                mail_autor = ?, 
                domicilio_autor = ?, 
                usuario_ci = ?
            WHERE {$columna_id} = ?";
    $tipos = 'ssssssssiss'; 
    $parametros = [
        $ci_au, $nom_au, $ape_au, $pseu_au, $f_nac_au, $f_fall_au, $tel_au, $mail_au, $dom_au, 
        $ci_usuario_sesion, 
        $id_original        
    ];
    if ($bd->accionRegistro($sql, $tipos, $parametros)) {
        header("Location: {$url}?s=5"); 
        exit();} 
    else {
        header("Location: {$url}?e=2");
        exit();}}}}
?>