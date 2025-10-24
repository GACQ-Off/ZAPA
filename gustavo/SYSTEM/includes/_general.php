<?php $mensajes_e = [
    '1' => 'Los campos obligatorios (*) son necesarios.',
    '2' => 'Ha ocurrido un error con la Base de Datos. Por favor, contacte con el técnico.',
    '4' => 'La cédula debe contener solo números. Nueve (09) digitos máximo.',
    '5' => 'El Correo Electrónico ingresado no tiene un formato válido.',
    '8' => 'El campo Apellidos debe contener de tres (03) a treinta y dos (32) digitos.',
    '9' => 'El Teléfono debe contener de siete (07) a trece (13) digitos númericos.',
    '10' => 'El Código debe contener veinticinco (25) digitos cómo máximo.',
    '11' => 'La Descripción debe contener de veinte (20) a doscientos veinticinco (255) digitos.'
];
$mensajes_s = [
    '4' => 'Registro eliminado exitosamente',
    '5' => 'Registro modificado de forma exitosa',
    '6' => 'Base de Datos exportada exitosamente',
    '7' => 'Base de Datos importada exitosamente'
];
$ci_usuario_sesion = $_SESSION['ci_usuario']; 
$status_comun = 1; 
if (($_tabs_atr >= 0 && $_tabs_atr <= 8) || $_tabs_atr == 11) {
    $datos = $bd->obtenerRegistrosPorPagina($sql, $sql_conteo, $por_pagina, $columnas, $orden);
    $nro_registros = $datos['nro_registros'];
    $total_paginas = $datos['total_paginas'];
    $paginaActual = $datos['paginaActual'];
}
$ci_usuario_sesion = $_SESSION['ci_usuario'] ?? null; ?>