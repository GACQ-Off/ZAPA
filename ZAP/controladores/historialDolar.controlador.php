<?php

// Incluimos únicamente el modelo que interactúa con la tabla historial_dolar
require_once __DIR__ . "/../modelos/historialDolar.modelo.php";

class ControladorHistorialDolar {

    /*=============================================
    INSERTAR CAMBIO DE DOLAR EN HISTORIAL (MODIFICADO - Equivalencia eliminada)
    =============================================*/
    static public function ctrIngresarCambioDolar($nuevoPrecioDolar) {

        $tabla = "historial_dolar";
        $fechaActual = date("Y-m-d H:i:s");

        $precioAnterior = null;
        $estadoCambio = "Inicial";

        // Obtener el último precio registrado en el historial para calcular el anterior y el estado
        $ultimoRegistro = ModeloHistorialDolar::mdlMostrarHistorialDolar($tabla, null, null);

        if (!empty($ultimoRegistro) && isset($ultimoRegistro[0]['precio_dolar'])) {
            $precioAnterior = floatval($ultimoRegistro[0]['precio_dolar']);
            $nuevoPrecioDolarFloat = floatval($nuevoPrecioDolar);

            if (abs($precioAnterior - $nuevoPrecioDolarFloat) > 0.00001) {
                if ($nuevoPrecioDolarFloat > $precioAnterior) {
                    $estadoCambio = "Subió";
                } else {
                    $estadoCambio = "Bajó";
                }
            } else {
                $estadoCambio = "Sin Cambios";
            }
        }

        $datos = array(
            "precio_dolar"          => $nuevoPrecioDolar,
            "precio_anterior"       => $precioAnterior,
            // Se eliminó "equivalencia_bolivares"
            "estado_cambio"         => $estadoCambio,
            "fecha_cambio"          => $fechaActual
        );

        $respuesta = ModeloHistorialDolar::mdlIngresarCambioDolar($tabla, $datos);

        return $respuesta;
    }

    /*=============================================
    MOSTRAR HISTORIAL DE DOLAR COMPLETO para la DataTable
    =============================================*/
    static public function ctrMostrarHistorialDolarCompleto() {
        $tabla = "historial_dolar";
        $respuesta = ModeloHistorialDolar::mdlMostrarHistorialDolar($tabla, null, null);
        return $respuesta;
    }

    /*=============================================
    MOSTRAR ULTIMO PRECIO DE DOLAR DEL HISTORIAL
    =============================================*/
    static public function ctrMostrarUltimoPrecioDolar() {
        $tabla = "historial_dolar";
        $respuesta = ModeloHistorialDolar::mdlMostrarHistorialDolar($tabla, null, null);
        
        if (!empty($respuesta)) {
            return $respuesta[0]['precio_dolar'];
        }
        return null;
    }
}