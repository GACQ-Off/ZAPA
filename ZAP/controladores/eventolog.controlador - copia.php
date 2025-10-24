<?php

// Asegúrate de que esta ruta sea correcta relativa a la ubicación de tu controlador.
// Por ejemplo, si 'controladores' y 'modelos' están al mismo nivel, esta ruta es correcta.
require_once "modelos/eventolog.modelo.php"; 

class ControladorEventoLog {

    /*=============================================
    MÉTODO PARA REGISTRAR UN NUEVO EVENTO
    Este método es el que llamarías cada vez que una acción significativa ocurra
    =============================================*/
    static public function ctrGuardarEventoLog($event_type, $description, $employee_cedula = null, $affected_table = null, $affected_row_id = null) {

        $datos = array(
            "event_type" => $event_type,
            "description" => $description,
            "employee_cedula" => $employee_cedula,
            "affected_table" => $affected_table,   
            "affected_row_id" => $affected_row_id  
        );

        // ¡DESCOMENTAR Y LLAMAR AL MÉTODO DEL MODELO PARA GUARDAR!
        $respuesta = ModeloEventoLog::mdlGuardarEvento($datos);

        return $respuesta; 
    }

    /*=============================================
    MÉTODO PARA MOSTRAR TODOS LOS REGISTROS DEL LOG
    Este es el método que usarás en tu archivo .php de la tabla
    =============================================*/
    static public function ctrMostrarEventosLog($item, $valor) {

        $tabla = "event_log"; // El nombre de tu tabla de eventos

        // ¡DESCOMENTAR Y LLAMAR AL MÉTODO DEL MODELO PARA MOSTRAR!
        $respuesta = ModeloEventoLog::mdlMostrarEventosLog($tabla, $item, $valor);

        return $respuesta;
    }

    /*=============================================
    MÉTODO PARA BORRAR EVENTOS (Opcional)
    =============================================*/
    static public function ctrBorrarEventoLog() {
        // Si tienes lógica para borrar, iría aquí, llamando a un método del modelo
    }
}