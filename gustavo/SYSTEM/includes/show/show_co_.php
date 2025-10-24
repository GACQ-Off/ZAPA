<?php 
if (!empty($_POST) && (!empty($_POST['accion']) && $_POST['accion'] == '_modificar')) {
    $cod_col = trim($_POST['id_registro_a_modificar_m'] ?? ''); 
    $ti_col = trim(filter_var($_POST['ti_formulario_m'] ?? '', FILTER_SANITIZE_STRING));
    $f_cr_col = $_POST['f_cr_formulario_m'] ?? NULL;
    $tip_col = trim(filter_var($_POST['tip_formulario_m'] ?? '', FILTER_SANITIZE_STRING));
    $es_col = trim(filter_var($_POST['es_formulario_m'] ?? '', FILTER_SANITIZE_STRING));
    $desc_col = trim(filter_var($_POST['resu_formulario_m'] ?? '', FILTER_SANITIZE_STRING));
    if (empty($cod_col) || empty($ti_col) || empty($tip_col) || empty($es_col)) {
        header("Location: {$url}?e=1"); 
        exit();} 
    if (strlen($ti_col) > 65 || strlen($ti_col) < 4) {
        header("Location: {$url}?e=7"); 
        exit();} 
    $sql = "UPDATE {$tabla} SET 
                titulo_coleccion = ?, 
                f_creacion_coleccion = ?, 
                naturaleza_coleccion = ?, 
                estado_coleccion = ?, 
                descripcion_coleccion = ?, 
                usuario_ci = ?
            WHERE {$columna_id} = ?";
    $tipos = 'ssssssi'; 
    $parametros = [
        $ti_col, 
        $f_cr_col, 
        $tip_col, 
        $es_col, 
        $desc_col, 
        (int)$ci_usuario_sesion, 
        $cod_col 
    ];
    if ($bd->accionRegistro($sql, $tipos, $parametros)) {
        header("Location: {$url}?s=5");
        exit();} 
    else {
        header("Location: {$url}?e=2");
        exit();}}
?>