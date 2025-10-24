<?php
$_img_b_r = 'img/svg/';
$_c_cinco = 'CSS/';
$_tit_b_e = 'Registrar';
$ruta = 'uploads/art/';
$_img_b = 'registrar_ventanita_negro.svg';
$_tit_b = 'Obra';
$_c_uno = 'Listado de';
$_c_dos = 'Obras de Arte';
$_c_tres = 'Listados';
$_c_cuatro = 'list_o.css';
$_c_seis = 'Buscar por Codigo, Título, Categoría, ...';
$mensajes_s['1'] = 'Obra registrada exitosamente.';
$mensajes_e['3'] = 'La Obra ya está registrada en el sistema.';
$mensajes_e['7'] = 'El Título debe contener de cuatro (04) a veinticinco (65) digitos.';
$mensajes_e['12'] = 'El campo Estado de Conservación debe contener de cuatro (04) a doce (12) digitos.';
$mensajes_e['13'] = 'El campo Número de Registro Civil debe contener once (11) digitos máximo.';
$mensajes_e['14'] = 'El campo Procedencia debe contener de ocho (08) a trece (13) digitos.';
$mensajes_e['15'] = 'Los campos Valor Estimado, Ancho, Alto y Profundidad deben contener solo valores númericos.';
$mensajes_e['16'] = 'Está intentando asociar registros inexistentes en la Base de Datos. Consulte con el Técnico.';
$mensajes_e['17'] = 'Ha ocurrido un error en el registro de autor(es). Por Favor, contacte con el técnico.';
$orden = 'o.cod_obra';
$tabla = 'obra';
$columna_id = 'cod_obra';
$status = 'status_obra';
$url = 'list_o.php';
$where = " WHERE o.{$status} = 1 ";
$por_pagina = 4;
$sql =
    "SELECT 
        o.cod_obra, o.titulo_obra, o.estado_obra, GROUP_CONCAT(a.nombres_autor SEPARATOR ', ') AS nombres_autores
    FROM 
        {$tabla} AS o
    LEFT JOIN autor_obra AS ao ON o.cod_obra = ao.obra_cod
    LEFT JOIN autor AS a ON ao.autor_ci = a.ci_autor
    {$where} 
    GROUP BY o.cod_obra, o.titulo_obra, o.estado_obra";
$sql_conteo = "SELECT COUNT(DISTINCT o.cod_obra) FROM {$tabla} AS o {$where}";
$columnas = [
    "o.{$columna_id}" => 's',
    'o.titulo_obra ' => 's',
    'ao.f_elaboracion_obra' => 's',
    'c.nombre_categoria' => 's',
    't.nombre_tecnica' => 's',
    'm.nombre_material' => 's',
    'nombres_autores' => 's'
];
$titulo = 'Registro de Obra de Arte';
$form_rut_e = 'includes/forms/form_o.php';
$reports_rut_e = 'reports/reports_o.php'
?>