<?php
$_img_b_r = 'img/svg/';
$_c_cinco = 'CSS/';
$_tit_b_e = 'Registrar';
$_img_b = 'habilidad_negro.svg';
$_tit_b = 'Técnica';
$_c_uno = 'Listado de';
$_c_dos = 'Técnicas';
$_c_tres = 'Listados';
$_c_cuatro = 'list_t.css';
$_c_seis = 'Buscar por Nombre, ...';
$mensajes_s['1'] = 'Técnica registrada exitosamente.';
$mensajes_e['3'] = 'La Técnica ya está registrada en el sistema.';
$mensajes_e['7'] = 'El campo Nombre debe contener de tres (03) a treinta y dos (32) digitos.';
$orden = 't.id_tecnica';
$tabla = 'tecnica';
$columna_id = 'id_tecnica';
$status = 'status_tecnica';
$url = 'list_t.php';
$por_pagina = 4;
$where = " WHERE t.{$status} = 1 ";
$sql = "SELECT t.*, u.nombres_usuario, u.img_usuario FROM {$tabla} as t INNER JOIN usuario as u ON t.usuario_ci = u.ci_usuario {$where}";
$sql_conteo = "SELECT COUNT(*) FROM {$tabla} as t {$where}";
$columnas = [
    't.nombre_tecnica' => 's'
];
$titulo = 'Registro de Técnica';
$form_rut_e = 'includes/forms/form_t.php';
?>