<?php
$_img_b_r = 'img/svg/';
$_c_cinco = 'CSS/';
$_tit_b_e = 'Registrar';
$_img_b = 'circulo_registrar_negro.svg';
$_tit_b = 'Usuario';
$_c_uno = 'Configuración de';
$_c_dos = 'Usuarios';
$_c_tres = 'Configuración';
$_c_cuatro = 'sett_u.css';
$_c_seis = 'Buscar por Cédula, Nombres, Apellidos, Correo, Teléfono...';
$mensajes_s['1'] = 'Usuario registrado exitosamente.';
$mensajes_e['3'] = 'El Usuario ya está registrado en el sistema.';
$mensajes_e['7'] = 'El campo Nombres debe contener de tres (03) a treinta y dos (32) digitos.';
$mensajes_e['6'] = 'La Contraseña debe contener de ocho (08) a treinta y dos (32) digitos.';
$mensajes_e['20'] = 'El Nombre de usuario debe contener de cuatro (04) a treinta y dos (32) digitos.';
$orden = 'u.nombres_usuario';
$tabla = 'usuario';
$columna_id = 'ci_usuario';
$status = 'status_usuario';
$url = 'sett_u.php';
$por_pagina = 4;
$where = " WHERE u.{$status} = 1 ";
$sql = "SELECT u.*, r.descripcion_rol FROM {$tabla} as u INNER JOIN rol as r ON u.rol_id = r.id_rol {$where}";
$sql_conteo = "SELECT COUNT(*) FROM {$tabla} AS u {$where}";
$columnas = [
    "u.{$columna_id}" => 's',
    'u.nombres_usuario' => 's',
    'u.apellidos_usuario' => 's',
    'u.mail_usuario' => 's',
    'u.telefono_usuario' => 's',
    'u.domicilio_usuario' => 's'
];
$titulo = 'Registro de Usuario';
$form_rut_e = 'includes/forms/form_u.php';
$ruta = 'uploads/profiles/';
?>