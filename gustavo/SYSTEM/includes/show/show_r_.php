<?php
if (!empty($_POST) && (!empty($_POST['accion']) && $_POST['accion'] == '_modificar')) {
    $ci_anterior = trim($_POST['id_registro_a_modificar_m']);
    $nom_m = trim(ucwords(filter_var($_POST['nom_formulario_m'], FILTER_SANITIZE_STRING)));
    $ape_m = trim(ucwords(filter_var($_POST['ape_formulario_m'], FILTER_SANITIZE_STRING)));
    $tel_m = trim(filter_var($_POST['tel_formulario_m'] ?? NULL, FILTER_SANITIZE_STRING));
    $mail_m = trim(filter_var($_POST['mail_formulario_m'] ?? NULL, FILTER_SANITIZE_EMAIL));
    $dom_m = trim(filter_var($_POST['dom_formulario_m'] ?? NULL, FILTER_SANITIZE_STRING));
    if (empty($ci_anterior) || empty($nom_m) || empty($ape_m)) {
        header("Location: {$url}?e=1");
        exit();}
    elseif (strlen($nom_m) < 3 || strlen($nom_m) > 32) {
        header("Location: {$url}?e=7"); 
        exit();} 
    elseif (strlen($ape_m) < 3 || strlen($ape_m) > 32) {
        header("Location: {$url}?e=8"); 
        exit();} 
    elseif (!empty($tel_m) && (!is_numeric($tel_m) || strlen($tel_m) < 8 || strlen($tel_m) > 13)) {
        header("Location: {$url}?e=9");
        exit();}
    elseif (!empty($mail_m) && !filter_var($mail_m, FILTER_VALIDATE_EMAIL)) {
        header("Location: {$url}?e=5");
        exit();}
    $sql_update = "UPDATE {$tabla} SET 
                    nombres_restaurador = ?, 
                    apellidos_restaurador = ?, 
                    telefono_restaurador = ?, 
                    mail_restaurador = ?, 
                    domicilio_restaurador = ? 
                    WHERE {$columna_id} = ?";
    $tipos = 'ssssss';     
    $parametros = [
        $nom_m,
        $ape_m,
        $tel_m,
        $mail_m,
        $dom_m,
        $ci_anterior
    ];
    if ($bd->accionRegistro($sql_update, $tipos, $parametros)) {
        header("Location: {$url}?s=1");
        exit();} 
    else {
        header("Location: {$url}?e=2");
        exit();}}?>