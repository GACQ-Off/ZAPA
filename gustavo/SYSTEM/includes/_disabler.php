<?php 
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === '_deshabilitar') {
    $id_registro = $_POST['id_registro'] ?? null;
    $entidad_post = $_POST['entidad'] ?? null; 
    if (!$id_registro || empty($tabla)) { 
        header("Location: {$url}?e=2"); 
        exit();}
    $sql_deshabilitar = "UPDATE {$tabla} SET {$status} = 0 WHERE {$columna_id} = ?";
    $tipos = ($columna_id == 'id_mantenimiento' || $columna_id == 'id_categoria' || $columna_id == 'id_tecnica' || $columna_id == 'id_material' || $columna_id == 'id_area') ? 'i' : 's';
    $parametros = [$id_registro];
    if ($bd->accionRegistro($sql_deshabilitar, $tipos, $parametros)) {
        header("Location: {$url}?s=4");
        exit();
    } else {
        header("Location: {$url}?e=2");
        exit();}}?>
