<?php

require_once "conexion.php"; // Asegúrate de que la ruta sea correcta desde este archivo

class ModeloEventoLog {

    /*=============================================
    REGISTRAR EVENTO EN EL LOG
    =============================================*/
    static public function mdlGuardarEvento($datos) {

        $tabla = "event_log"; // El nombre de la tabla que crearemos

        $stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(event_type, description, employee_cedula, affected_table, affected_row_id) VALUES (:event_type, :description, :employee_cedula, :affected_table, :affected_row_id)");

        $stmt->bindParam(":event_type", $datos["event_type"], PDO::PARAM_STR);
        $stmt->bindParam(":description", $datos["description"], PDO::PARAM_STR);
        $stmt->bindParam(":employee_cedula", $datos["employee_cedula"], PDO::PARAM_STR);
        $stmt->bindParam(":affected_table", $datos["affected_table"], PDO::PARAM_STR);
        $stmt->bindParam(":affected_row_id", $datos["affected_row_id"], PDO::PARAM_STR);

        if ($stmt->execute()) {
            return "ok";
        } else {
            error_log("Error al insertar en event_log: " . print_r($stmt->errorInfo(), true));
            return "error";
        }

        $stmt->closeCursor();
        $stmt = null;
    }

    /*=============================================
    MOSTRAR TODOS LOS REGISTROS DEL LOG DE EVENTOS (CON NOMBRE Y APELLIDO DEL EMPLEADO)
    =============================================*/
    static public function mdlMostrarEventosLog($tabla, $item, $valor) {

        if ($item != null) {
            $stmt = Conexion::conectar()->prepare("SELECT el.*, e.nombre, e.apellido 
                                                    FROM $tabla AS el 
                                                    LEFT JOIN empleados AS e ON el.employee_cedula = e.cedula -- ¡Corregido aquí!
                                                    WHERE el.$item = :$item 
                                                    ORDER BY el.id DESC");
            $stmt -> bindParam(":" . $item, $valor, PDO::PARAM_STR);
            $stmt -> execute();
            return $stmt -> fetch();

        } else {
            $stmt = Conexion::conectar()->prepare("SELECT el.*, e.nombre, e.apellido 
                                                    FROM $tabla AS el 
                                                    LEFT JOIN empleados AS e ON el.employee_cedula = e.cedula -- ¡Corregido aquí!
                                                    ORDER BY el.id DESC"); 
            $stmt -> execute();
            return $stmt -> fetchAll();
        }

        $stmt->closeCursor();
        $stmt = null;
    }

    /*=============================================
    BORRAR EVENTO (Opcional, si quieres implementar una función de limpieza)
    =============================================*/
    static public function mdlBorrarEventoLog($tabla, $idEvento) {
        $stmt = Conexion::conectar()->prepare("DELETE FROM $tabla WHERE id = :id");
        $stmt->bindParam(":id", $idEvento, PDO::PARAM_INT);
        if ($stmt->execute()) {
            return "ok";
        } else {
            return "error";
        }
        $stmt->closeCursor();
        $stmt = null;
    }
}