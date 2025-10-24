<?php

require_once "../controladores/configuracion.controlador.php";
require_once "../modelos/configuracion.modelo.php";

if (isset($_POST["estadoEvento"])) {

    $nuevoEstado = intval($_POST["estadoEvento"]) === 1;

    $respuesta = ControladorConfiguracion::ctrCambiarEstadoLog($nuevoEstado);

    echo json_encode(["respuesta" => $respuesta]);
}
