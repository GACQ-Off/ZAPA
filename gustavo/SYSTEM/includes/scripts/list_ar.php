<?php
$_img_b_r = 'img/svg/';
$_c_cinco = 'CSS/';
$_tit_b_e = 'Registrar';
$_img_b = 'puerta_negro.svg';
$_tit_b = 'Área';
$_c_uno = 'Listado de';
$_c_dos = 'Áreas';
$_c_tres = 'Listados';
$_c_cuatro = 'list_ar.css';
$_c_seis = 'Buscar por Nombre, ...';
$mensajes_s['1'] = 'Área registrada exitosamente.';
$mensajes_e['3'] = 'El Área ya está registrada en el sistema.';
$mensajes_e['7'] = 'El campo Nombre debe contener de cuatro (04) a treinta y dos (32) digitos.';
$orden = 'a.id_area';
$tabla = 'area';
$columna_id = 'id_area';
$status = 'status_area';
$url = 'list_ar.php';
$por_pagina = 4;
$where = " WHERE a.{$status} = 1 ";
$sql = "SELECT a.*, u.nombres_usuario, u.img_usuario FROM {$tabla} as a INNER JOIN usuario as u ON a.usuario_ci = u.ci_usuario {$where}";
$sql_conteo = "SELECT COUNT(*) FROM {$tabla} as a {$where}";
$columnas = [
    'a.nombre_area' => 's'
];
$ruta = 'uploads/zones/';
$titulo = 'Registro de Área';
$form_rut_e = 'includes/forms/form_ar.php';
?>
<script type="module" src="JS/img.js"></script>