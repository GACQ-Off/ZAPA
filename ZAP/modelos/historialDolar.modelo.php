<?php

require_once "conexion.php"; // Asume que tienes un archivo de conexión a la base de datos

class ModeloHistorialDolar {

    /*=============================================
    REGISTRAR CAMBIO DE DOLAR EN HISTORIAL (MODIFICADO - Equivalencia eliminada)
    =============================================*/
    static public function mdlIngresarCambioDolar($tabla, $datos) {

        $pdo = Conexion::conectar();

        try {
            // Se eliminó 'equivalencia_bolivares' de la lista de columnas y de los VALUES
            $stmt = $pdo->prepare("INSERT INTO $tabla(precio_dolar, precio_anterior, estado_cambio, fecha_cambio) VALUES (:precio_dolar, :precio_anterior, :estado_cambio, :fecha_cambio)");

            $stmt->bindParam(":precio_dolar", $datos["precio_dolar"], PDO::PARAM_STR);
            $stmt->bindParam(":precio_anterior", $datos["precio_anterior"], PDO::PARAM_STR);
            // Se eliminó bindParam para equivalencia_bolivares
            $stmt->bindParam(":estado_cambio", $datos["estado_cambio"], PDO::PARAM_STR);
            $stmt->bindParam(":fecha_cambio", $datos["fecha_cambio"], PDO::PARAM_STR);

            if ($stmt->execute()) {
                return "ok";
            } else {
                return "error";
            }

        } catch (PDOException $e) {
            // error_log("Excepción PDO en mdlIngresarCambioDolar: " . $e->getMessage());
            return "error";
        } finally {
            $stmt->closeCursor();
            $stmt = null;
        }
    }

    /*=============================================
    MOSTRAR HISTORIAL DE DOLAR
    Este método es genérico para buscar por item/valor o traer todo.
    =============================================*/
    static public function mdlMostrarHistorialDolar($tabla, $item, $valor) {

        $pdo = Conexion::conectar();

        if ($item != null) {
            // SELECT * sigue siendo válido, pero si prefieres, puedes listar las columnas explícitamente
            $stmt = $pdo->prepare("SELECT id, precio_dolar, precio_anterior, estado_cambio, fecha_cambio FROM $tabla WHERE $item = :$item ORDER BY fecha_cambio DESC");
            $stmt->bindParam(":" . $item, $valor, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch();
        } else {
            // SELECT * sigue siendo válido, pero si prefieres, puedes listar las columnas explícitamente
            $stmt = $pdo->prepare("SELECT id, precio_dolar, precio_anterior, estado_cambio, fecha_cambio FROM $tabla ORDER BY fecha_cambio DESC");
            $stmt->execute();
            return $stmt->fetchAll();
        }

        $stmt->closeCursor();
        $stmt = null;
    }

}