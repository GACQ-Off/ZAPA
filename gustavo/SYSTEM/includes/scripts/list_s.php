<?php
$_img_b_r = 'img/svg/';
$_c_cinco = 'CSS/';
$_tit_b_e = 'Registrar';
$_img_b = 'anadir_escrito_negro.svg';
$_tit_b = 'Solicitar Mantenimiento';
$_c_uno = 'Solicitudes de';
$_c_dos = 'Mantenimiento';
$_c_tres = 'Dashboard';
$_c_cuatro = 'ind_s.css';
$_c_seis = 'Buscar por Título, Restaurador, ...';
$mensajes_s['1'] = 'Inicio de sesión exitoso.';
$mensajes_s['2'] = 'Solicitud enviada exitosamente.';
$mensajes_s['3'] = 'Solicitud aprobada.';
$mensajes_e['3'] = 'La Solicitud ya existe en el sistema.';
$mensajes_e['7'] = 'El Título debe contener de tres (03) a treinta y dos (32) digitos.';
$orden = 's.id_mantenimiento';
$tabla = 'mantenimiento';
$columna_id = 'id_mantenimiento';
$status = 'status_mantenimiento';
$url = 'index.php';
$por_pagina = 4;
$where = " WHERE s.{$status} = 1 ";
$sql =
    "SELECT 
            s.id_mantenimiento,
            s.resumen_mantenimiento, 
            s.f_escritura_mantenimiento, 
            s.f_actualizacion_mantenimiento,
            s.estado_mantenimiento, 
            u.nombres_usuario
        FROM 
            {$tabla} AS s
        INNER JOIN 
            usuario AS u ON s.usuario_ci = u.ci_usuario
        {$where}";
$sql_conteo = "SELECT COUNT(*) FROM {$tabla} AS s {$where}";
$columnas = [
    's.resumen_mantenimiento' => 's',
    's.estado_mantenimiento' => 's',
    's.f_escritura_mantenimiento' => 's',
    's.f_actualizacion_mantenimiento' => 's'
];
$ruta = 'uploads/requests/';
$titulo = 'Solicitud de Mantenimiento';
$form_rut_e = 'includes/forms/form_s.php';
?>