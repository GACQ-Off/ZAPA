<?php
$_img_b_r = 'img/svg/';
$_c_cinco = 'CSS/';
$_tit_b_e = 'Registrar';
$_img_b = 'anadir_persona_negro.svg';
$_tit_b = 'Autor';
$_c_uno = 'Listado de';
$_c_dos = 'Autores';
$_c_tres = 'Listados';
$_c_cuatro = 'list_a.css';
$_c_seis = 'Buscar por Cédula, Nombres, Apellidos, Correo, Teléfono...';
$mensajes_s['1'] = 'Autor registrado exitosamente.';
$mensajes_e['3'] = 'El Autor ya está registrado en el sistema.';
$mensajes_e['7'] = 'El campo Nombre debe contener de tres (03) a treinta y dos (32) digitos.';
$orden = 'a.ci_autor';
$tabla = 'autor';
$columna_id = 'ci_autor';
$status = 'status_autor';
$url = 'list_a.php';
$where = " WHERE a.{$status} = 1 ";
$por_pagina = 4;
$sql = "SELECT a.ci_autor, a.nombres_autor, a.apellidos_autor, a.telefono_autor, a.mail_autor, a.f_nacimiento_autor, a.f_fallecimiento_autor FROM {$tabla} as a {$where}";
$sql_conteo = "SELECT COUNT(*) FROM {$tabla} AS a {$where}";
$columnas = [
    "a.{$columna_id}" => 's',
    'a.nombres_autor' => 's',
    'a.apellidos_autor' => 's',
    'a.mail_autor' => 's',
    'a.telefono_autor' => 's',
    'a.domicilio_autor' => 's'
];
$titulo = 'Registro de Autor';
$form_rut_e = 'includes/forms/form_a.php';
$reports_rut_e = 'reports/reports_a.php'
?>