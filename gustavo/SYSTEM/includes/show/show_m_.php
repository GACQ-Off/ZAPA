<?php 
if (!empty($_POST) && (!empty($_POST['accion']) && $_POST['accion'] == '_modificar')) {
    $id_mat = trim($_POST['id_registro_a_modificar_m'] ?? '');
    $nom_mat = trim(ucwords(filter_var($_POST['nom_formulario_m'] ?? '', FILTER_SANITIZE_STRING)));
    if (empty($id_mat) || empty($nom_mat)) {
        header("Location: {$url}?e=1"); 
        exit();} 
    if (strlen($nom_mat) > 32 || strlen($nom_mat) < 3) {
        header("Location: {$url}?e=7");
        exit();} 
    $sql = "UPDATE {$tabla} SET 
                nombre_material = ?, 
                usuario_ci = ?
            WHERE {$columna_id} = ?";
    $tipos = 'sii'; 
    $parametros = [
        $nom_mat, 
        (int)$ci_usuario_sesion, 
        (int)$id_mat 
    ];
    if ($bd->accionRegistro($sql, $tipos, $parametros)) {
        header("Location: {$url}?s=5");
        exit();} 
    else {
        header("Location: {$url}?e=2");
        exit();}}?>