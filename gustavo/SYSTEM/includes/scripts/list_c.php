<?php
$_img_b_r = 'img/svg/';
$_c_cinco = 'CSS/';
$_tit_b_e = 'Registrar';
$_img_b = 'categoria_negro.svg';
$_tit_b = 'Categoría';
$_c_uno = 'Listado de';
$_c_dos = 'Categorías';
$_c_tres = 'Listados';
$_c_cuatro = 'list_c.css';
$_c_seis = 'Buscar por Nombre, Descripción, ...';
$mensajes_s['1'] = 'Categoría registrada exitosamente.';
$mensajes_e['3'] = 'La Categoría ya está registrada en el sistema.';
$mensajes_e['7'] = 'El campo Nombre debe contener de tres (03) a treinta y dos (32) digitos.';
$orden = 'c.id_categoria';
$tabla = 'categoria';
$columna_id = 'id_categoria';
$status = 'status_categoria';
$url = 'list_c.php';
$por_pagina = 4;
$where = " WHERE c.{$status} = 1 ";
$sql = "SELECT c.id_categoria, c.nombre_categoria FROM {$tabla} as c {$where}";
$sql_conteo = "SELECT COUNT(*) FROM {$tabla} AS c {$where}";
$columnas = [
    'c.nombre_categoria' => 's',
    'c.descripcion_categoria' => 's'
];
$titulo = 'Registro de Categoría';
$form_rut_e = 'includes/forms/form_c.php';
?>