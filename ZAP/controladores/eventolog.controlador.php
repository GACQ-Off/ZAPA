<?php

require_once __DIR__ . "/../modelos/eventolog.modelo.php"; // Asegúrate de que esta ruta sea correcta

class ControladorEventoLog {

    /*=============================================
    MÉTODO PARA REGISTRAR UN NUEVO EVENTO
    =============================================*/
    static public function ctrGuardarEventoLog($event_type, $description, $employee_cedula = null, $affected_table = null, $affected_row_id = null) {

        $datos = array(
            "event_type" => $event_type,
            "description" => $description,
            "employee_cedula" => $employee_cedula,
            "affected_table" => $affected_table,   
            "affected_row_id" => $affected_row_id  
        );

        $respuesta = ModeloEventoLog::mdlGuardarEvento($datos);

        return $respuesta; 
    }

    /*=============================================
    MÉTODO PARA MOSTRAR TODOS LOS REGISTROS DEL LOG
    =============================================*/
    static public function ctrMostrarEventosLog($item, $valor) {

        $tabla = "event_log";

        $respuesta = ModeloEventoLog::mdlMostrarEventosLog($tabla, $item, $valor);

        return $respuesta;
    }

    /*=============================================
    MÉTODO PARA BORRAR EVENTOS (Opcional)
    =============================================*/
    static public function ctrBorrarEventoLog() {
        // Lógica para borrar, si es necesaria
    }
}