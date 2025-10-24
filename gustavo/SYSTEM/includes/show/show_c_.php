<?php 
if (!empty($_POST) && (!empty($_POST['accion']) && $_POST['accion'] == '_modificar')) {
    $id_cat = trim($_POST['id_registro_a_modificar_m'] ?? '');
    $nom_cat = trim(ucwords(filter_var($_POST['nom_formulario_m'] ?? '', FILTER_SANITIZE_STRING)));
    $desc_cat = trim(filter_var($_POST['resu_formulario_m'] ?? '', FILTER_SANITIZE_STRING));
    if (empty($id_cat) || empty($nom_cat)) {
        header("Location: {$url}?e=1");
        exit();} 
    if (strlen($nom_cat) > 32 || strlen($nom_cat) < 4) {
        header("Location: {$url}?e=5"); 
        exit();} 
    $sql = "UPDATE {$tabla} SET 
                nombre_categoria = ?, 
                descripcion_categoria = ?, 
                usuario_ci = ?
            WHERE {$columna_id} = ?";
    $tipos = 'ssii'; 
    $parametros = [
        $nom_cat, 
        $desc_cat, 
        (int)$ci_usuario_sesion,
        (int)$id_cat 
    ];
    if ($bd->accionRegistro($sql, $tipos, $parametros)) {
        header("Location: {$url}?s=5");
        exit();} 
    else {
        header("Location: {$url}?e=2"); 
        exit();}}
?>