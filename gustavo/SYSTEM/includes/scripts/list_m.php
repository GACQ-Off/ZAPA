<?php
$_img_b_r = 'img/svg/';
$_c_cinco = 'CSS/';
$_tit_b_e = 'Registrar';
$_img_b = 'cuadrado_negro.svg';
$_tit_b = 'Material';
$_c_uno = 'Listado de';
$_c_dos = 'Materiales';
$_c_tres = 'Listados';
$_c_cuatro = 'list_m.css';
$_c_seis = 'Buscar por Nombre, ...';
$mensajes_s['1'] = 'Material registrado exitosamente.';
$mensajes_e['3'] = 'El Material ya está registrada en el sistema.';
$mensajes_e['7'] = 'El campo Nombre debe contener de tres (03) a treinta y dos (32) digitos.';
$orden = 'm.id_material';
$tabla = 'material';
$columna_id = 'id_material';
$status = 'status_material';
$url = 'list_m.php';
$por_pagina = 4;
$where = " WHERE m.{$status} = 1 ";
$sql = "SELECT m.*, u.nombres_usuario, u.img_usuario FROM {$tabla} as m INNER JOIN usuario as u ON m.usuario_ci = u.ci_usuario {$where}";
$sql_conteo = "SELECT COUNT(*) FROM {$tabla} AS m {$where}";
$columnas = [
    'm.nombre_material' => 's'
];
$titulo = 'Registro de Material';
$form_rut_e = 'includes/forms/form_m.php';
?>