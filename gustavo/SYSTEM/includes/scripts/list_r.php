<?php
$_img_b_r = 'img/svg/';
$_c_cinco = 'CSS/';
$_tit_b_e = 'Registrar';
$_img_b = 'persona_otro_negro.svg';
$_tit_b = 'Restaurador';
$_c_uno = 'Listado de';
$_c_dos = 'Restauradores';
$_c_tres = 'Listados';
$_c_cuatro = 'list_r.css';
$_c_seis = 'Buscar por Cédula, Nombres, Apellidos, Correo, Teléfono...';
$mensajes_s['1'] = 'Restaurador registrado exitosamente.';
$mensajes_e['3'] = 'El Restaurador ya está registrado en el sistema.';
$mensajes_e['7'] = 'El campo Nombres debe contener de tres (03) a treinta y dos (32) digitos.';
$orden = 'r.nombres_restaurador';
$tabla = 'restaurador';
$columna_id = 'ci_restaurador';
$status = 'status_restaurador';
$url = 'list_r.php';
$por_pagina = 4;
$where = " WHERE r.{$status} = 1 ";
$sql = "SELECT r.*, u.nombres_usuario, u.img_usuario FROM {$tabla} as r INNER JOIN usuario as u ON r.usuario_ci = u.ci_usuario {$where}";
$sql_conteo = "SELECT COUNT(*) FROM {$tabla} as r {$where}";
$columnas = [
    "r.{$columna_id}" => 's',
    'r.nombres_restaurador' => 's',
    'r.apellidos_restaurador' => 's',
    'r.telefono_restaurador' => 's',
    'r.mail_restaurador' => 's',
    'r.domicilio_restaurador' => 's'
];
$titulo = 'Registro de Restaurador';
$form_rut_e = 'includes/forms/form_r.php';
$reports_rut_e = 'reports/reports_r.php'
?>