<?php
$_img_b_r = 'img/svg/';
$_c_cinco = 'CSS/';
$_tit_b_e = 'Registrar';
$_img_b = 'coleccion_negro.svg';
$_tit_b = 'Colección';
$_c_uno = 'Listado de';
$_c_dos = 'Colecciones';
$_c_tres = 'Listados';
$_c_cuatro = 'list_co.css';
$_c_seis = 'Buscar por Codigo, Título, Categoría, ...';
$mensajes_s['1'] = 'Colección registrada exitosamente.';
$mensajes_e['3'] = 'La Colección ya está registrada en el sistema.';
$mensajes_e['7'] = 'El Título debe contener de cuatro (04) a sesenta y cinco (65) digitos.';
$orden = 'c.cod_coleccion';
$tabla = 'coleccion';
$columna_id = 'cod_coleccion';
$status = 'status_coleccion';
$url = 'list_co.php';
$por_pagina = 4;
$where = " WHERE c.{$status} = 1 ";
$sql = "SELECT c.*, u.nombres_usuario, u.img_usuario FROM {$tabla} as c INNER JOIN usuario as u ON c.usuario_ci = u.ci_usuario {$where}";
$sql_conteo = "SELECT COUNT(*) FROM {$tabla} as c {$where}";
$columnas = [
    "c.{$columna_id}" => 's',
    'c.titulo_coleccion' => 's',
    'c.f_creacion_coleccion' => 's',
    'c.naturaleza_coleccion' => 's',
    'c.estado_coleccion' => 's'
];
$titulo = 'Registro de Colección';
$form_rut_e = 'includes/forms/form_co.php';
$reports_rut_e = 'reports/reports_co.php'
?>