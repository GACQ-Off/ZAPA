<?php
if (!empty($_POST) && (!empty($_POST['accion']) && $_POST['accion'] == '_modificar')) {
    $id_tecnica_a_modificar = trim($_POST['id_registro_a_modificar_m'] ?? ''); 
    $nom_tecnica = trim(ucwords(filter_var($_POST['nom_formulario_m'] ?? '', FILTER_SANITIZE_STRING)));
    if (empty($nom_tecnica)) {
        header("Location: {$url}?e=1");
        exit();}
    if (strlen($nom_tecnica) < 3 || strlen($nom_tecnica) > 32) {
        header("Location: {$url}?e=7");
        exit();}
    $sql = "UPDATE {$tabla} SET nombre_tecnica = ? WHERE {$columna_id} = ?";
    $tipos = 'si';
    $parametros = [
        $nom_tecnica, 
        (int)$id_tecnica_a_modificar 
    ];
    if ($bd->accionRegistro($sql, $tipos, $parametros)) {
        header("Location: {$url}?s=5"); 
        exit();} 
    else {
        header("Location: {$url}?e=2"); 
        exit();}}?>