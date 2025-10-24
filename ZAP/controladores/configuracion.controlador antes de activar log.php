<?php

// INICIO: CONFIGURACIÓN DE DEPURACIÓN (DESACTIVAR EN PRODUCCIÓN)
ini_set("display_errors", 1);
error_reporting(E_ALL);
// FIN: CONFIGURACIÓN DE DEPURACIÓN

// Estas rutas deben ser correctas para tu estructura de carpetas
require_once __DIR__ . "/../modelos/categorias.modelo.php"; // Si las categorías se manejan aquí
require_once __DIR__ . "/../modelos/configuracion.modelo.php"; // Asegúrate de que este modelo existe
require_once __DIR__ . "/../modelos/eventolog.modelo.php"; // ¡Importante para el log!
// AÑADIDO: Incluir el nuevo controlador del historial del dólar
require_once __DIR__ . "/historialDolar.controlador.php"; // <--- ESTA ES LA ÚNICA INCLUSIÓN NUEVA

class ControladorConfiguracion{

    /*=============================================
    CREAR CATEGORIAS
    =============================================*/
    static public function ctrCrearCategoria(){
        if(isset($_POST["nuevaCategoria"])){
            if(preg_match('/^[a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["nuevaCategoria"])){
                $tabla = "categorias"; // O "empresa" si este método crea la configuración inicial
                $datos = $_POST["nuevaCategoria"]; // O los datos de la empresa
                $respuesta = ModeloCategorias::mdlIngresarCategoria($tabla, $datos); // O ModeloConfiguracion::mdlIngresarConfiguracion
                if($respuesta == "ok"){
                    $empleadoCedula = (isset($_SESSION["cedula"])) ? $_SESSION["cedula"] : "Sistema/Desconocido";
                    $logData = array(
                        "event_type" => "Categoría Creada", // O "Configuración Inicial Creada"
                        "description" => "Nueva categoría '{$datos}' registrada.", // O "Configuración inicial de la empresa establecida."
                        "employee_cedula" => $empleadoCedula,
                        "affected_table" => $tabla,
                        "affected_row_id" => $datos // Podría ser el ID insertado si es autoincremental
                    );
                    ModeloEventoLog::mdlGuardarEvento($logData);
                    echo'<script>
                    swal({
                        type: "success",
                        title: "La categoría ha sido guardada correctamente",
                        showConfirmButton: true,
                        confirmButtonText: "Cerrar"
                        }).then(function(result){
                                        if (result.value) {
                                        window.location = "categorias"; // O "configuracion" si es el caso
                                        }
                                })
                    </script>';
                } else {
                    $empleadoCedula = (isset($_SESSION["cedula"])) ? $_SESSION["cedula"] : "Sistema/Desconocido";
                    $logData = array(
                        "event_type" => "Creación Categoría Fallida", // O "Creación Configuración Fallida"
                        "description" => "Error al intentar crear la categoría '{$datos}'.", // O "Error al intentar establecer la configuración inicial de la empresa."
                        "employee_cedula" => $empleadoCedula,
                        "affected_table" => $tabla,
                        "affected_row_id" => $datos
                    );
                    ModeloEventoLog::mdlGuardarEvento($logData);
                }
            }else{
                $empleadoCedula = (isset($_SESSION["cedula"])) ? $_SESSION["cedula"] : "Sistema/Desconocido";
                $logData = array(
                    "event_type" => "Creación Categoría Fallida",
                    "description" => "Intento de crear categoría con datos inválidos: " . (isset($_POST["nuevaCategoria"]) ? $_POST["nuevaCategoria"] : 'N/A') . ".",
                    "employee_cedula" => $empleadoCedula,
                    "affected_table" => "categorias",
                    "affected_row_id" => (isset($_POST["nuevaCategoria"])) ? $_POST["nuevaCategoria"] : 'N/A'
                );
                ModeloEventoLog::mdlGuardarEvento($logData);
                echo'<script>
                    swal({
                        type: "error",
                        title: "¡La categoría no puede ir vacía o llevar caracteres especiales!",
                        showConfirmButton: true,
                        confirmButtonText: "Cerrar"
                        }).then(function(result){
                            if (result.value) {
                            window.location = "categorias";
                            }
                        })
                </script>';
            }
        }
    }

    /*=============================================
    MOSTRAR CONFIGURACION
    =============================================*/
    static public function ctrMostrarConfigracion($item, $valor){
        $tabla = "empresa";
        $respuesta = ModeloConfiguracion::mdlMostrarConfiguracion($tabla, $item, $valor);
        return $respuesta;
    }

    /*=============================================
    EDITAR CONFIGURACION
    =============================================*/
    static public function ctrEditarConfiguracion(){
        if(isset($_POST["rif"])){
            if (
                preg_match('/^[egjvpcEGJVPC]-[0-9]{9}$/', $_POST["rif"])&&
                preg_match('/^[a-zA-ZñÑáéíóúÁÉÍÓÚ0-9 ]+$/', $_POST["nombre"])
            ) {
                $tabla = "empresa";

                // 1. Obtener la configuración actual ANTES de la actualización
                $configuracionActual = ModeloConfiguracion::mdlMostrarConfiguracion($tabla, "id", 1);
                $precioDolarAnterior = (isset($configuracionActual["precio_dolar"])) ? floatval($configuracionActual["precio_dolar"]) : null;
                $impuestoAnterior = (isset($configuracionActual["impuesto"])) ? floatval($configuracionActual["impuesto"]) : null;

                $nuevoPrecioDolar = floatval($_POST["precio_dolar"]);
                $nuevoImpuesto = floatval($_POST["impuesto"]);

                $datos = array(
                    "rif"           => $_POST["rif"],
                    "telefono"      => $_POST["telefono"],
                    "precio_dolar"  => $nuevoPrecioDolar,
                    "impuesto"      => $nuevoImpuesto,
                    "direccion"     => $_POST["direccion"],
                    "key"           => $_POST["key"],
                    "nombre"        => $_POST["nombre"]
                );

                $respuesta = ModeloConfiguracion::mdlIngresarConfiguracion($tabla, $datos);

                if($respuesta == "ok"){
                    // =============================================
                    // AÑADIDO: REGISTRAR CAMBIO EN TABLA HISTORIAL_DOLAR
                    // =============================================
                    // Solo registrar en historial si el precio del dólar realmente cambió
                    if ($precioDolarAnterior === null || abs($precioDolarAnterior - $nuevoPrecioDolar) > 0.00001) {
                         ControladorHistorialDolar::ctrIngresarCambioDolar($nuevoPrecioDolar); // <--- ESTA ES LA LÍNEA CLAVE
                    }
                    // =============================================
                    // FIN DEL BLOQUE A AÑADIR
                    // =============================================


                    // =============================================
                    // REGISTRAR EDICIÓN DE CONFIGURACIÓN EN EL LOG GENERAL
                    // =============================================
                    $empleadoCedula = (isset($_SESSION["cedula"])) ? $_SESSION["cedula"] : "Sistema/Desconocido";

                    $logDescription = "Configuración de la empresa '{$datos["nombre"]}' (RIF: {$datos["rif"]}) modificada.";
                    $cambiosDetectados = [];

                    if ($precioDolarAnterior !== null && abs($precioDolarAnterior - $nuevoPrecioDolar) > 0.00001) {
                        $cambiosDetectados[] = "Precio Dólar: Anterior " . number_format($precioDolarAnterior, 2, ',', '.') . " , Nuevo " . number_format($nuevoPrecioDolar, 2, ',', '.') . ' ';
                    } elseif ($precioDolarAnterior === null) {
                         $cambiosDetectados[] = "Precio Dólar inicial establecido en " . number_format($nuevoPrecioDolar, 2, ',', '.') . ' ';
                    }

                    if ($impuestoAnterior !== null && abs($impuestoAnterior - $nuevoImpuesto) > 0.001) {
                        $cambiosDetectados[] = "Impuesto: Anterior " . number_format($impuestoAnterior, 2, ',', '.') . '% ' . ", Nuevo " . number_format($nuevoImpuesto, 2, ',', '.') . '%';
                    } elseif ($impuestoAnterior === null) {
                         $cambiosDetectados[] = "Impuesto inicial establecido en " . number_format($nuevoImpuesto, 2, ',', '.') . '%';
                    }

                    if (!empty($cambiosDetectados)) {
                        $logDescription .= " Detalles: " . implode("; ", $cambiosDetectados) . ".";
                    } else {
                        $logDescription .= " Otros datos de configuración modificados.";
                    }

                    $logData = array(
                        "event_type" => "Configuración Editada",
                        "description" => $logDescription,
                        "employee_cedula" => $empleadoCedula,
                        "affected_table" => "empresa",
                        "affected_row_id" => $datos["rif"]
                    );
                    ModeloEventoLog::mdlGuardarEvento($logData);

                    echo'<script>
                    swal({
                        type: "success",
                        title: "Los datos de la empresa han sido cambiados correctamente",
                        showConfirmButton: true,
                        confirmButtonText: "Cerrar"
                        }).then(function(result){
                                        if (result.value) {
                                        window.location = "configuracion";
                                        }
                                })
                    </script>';

                } else {
                    $empleadoCedula = (isset($_SESSION["cedula"])) ? $_SESSION["cedula"] : "Sistema/Desconocido";
                    $logData = array(
                        "event_type" => "Edición Configuración Fallida",
                        "description" => "Error al intentar modificar la configuración de la empresa '" . (isset($datos["nombre"]) ? $datos["nombre"] : 'N/A') . " (RIF: " . (isset($datos["rif"]) ? $datos["rif"] : 'N/A') . ").",
                        "employee_cedula" => $empleadoCedula,
                        "affected_table" => "empresa",
                        "affected_row_id" => (isset($datos["rif"])) ? $datos["rif"] : 'N/A'
                    );
                    ModeloEventoLog::mdlGuardarEvento($logData);

                    echo'<script>
                        swal({
                            type: "error",
                            title: "¡Los datos de la empresa no han podido ser cambiados!",
                            showConfirmButton: true,
                            confirmButtonText: "Cerrar"
                            }).then(function(result){
                                if (result.value) {
                                window.location = "configuracion";
                                }
                            })
                    </script>';
                }


            }else{
                $empleadoCedula = (isset($_SESSION["cedula"])) ? $_SESSION["cedula"] : "Sistema/Desconocido";
                $logData = array(
                    "event_type" => "Edición Configuración Fallida",
                    "description" => "Intento de editar configuración con datos inválidos (RIF: " . (isset($_POST["rif"]) ? $_POST["rif"] : 'N/A') . ", nombre: " . (isset($_POST["nombre"]) ? $_POST["nombre"] : 'N/A') . ").",
                    "employee_cedula" => $empleadoCedula,
                    "affected_table" => "empresa",
                    "affected_row_id" => (isset($_POST["rif"])) ? $_POST["rif"] : 'N/A'
                );
                ModeloEventoLog::mdlGuardarEvento($logData);

                echo'<script>
                    swal({
                        type: "error",
                        title: "¡Los datos de la empresa no pueden ir vacíos o llevar caracteres especiales!",
                        showConfirmButton: true,
                        confirmButtonText: "Cerrar"
                        }).then(function(result){
                            if (result.value) {
                            window.location = "configuracion";
                            }
                        })
                </script>';
            }
        }
    }

    /*=============================================
    BORRAR CATEGORIA
    =============================================*/
    static public function ctrBorrarCategoria(){
        if(isset($_GET["idCategoria"])){
            $tabla ="Categorias";
            $datos = $_GET["idCategoria"];

            $categoriaAEliminar = ModeloCategorias::mdlMostrarCategorias($tabla, "id", $datos);
            $nombreCategoriaBorrada = (isset($categoriaAEliminar['nombre'])) ? $categoriaAEliminar['nombre'] : 'N/A';

            $respuesta = ModeloCategorias::mdlBorrarCategoria($tabla, $datos);

            if($respuesta == "ok"){
                $empleadoCedula = (isset($_SESSION["cedula"])) ? $_SESSION["cedula"] : "Sistema/Desconocido";
                $logData = array(
                    "event_type" => "Categoría Eliminada",
                    "description" => "Categoría '{$nombreCategoriaBorrada}' (ID: {$datos}) eliminada.",
                    "employee_cedula" => $empleadoCedula,
                    "affected_table" => $tabla,
                    "affected_row_id" => $datos
                );
                ModeloEventoLog::mdlGuardarEvento($logData);

                echo'<script>
                    swal({
                        type: "success",
                        title: "La categoría ha sido borrada correctamente",
                        showConfirmButton: true,
                        confirmButtonText: "Cerrar"
                        }).then(function(result){
                                        if (result.value) {
                                        window.location = "categorias";
                                        }
                                })
                    </script>';
            } else {
                $empleadoCedula = (isset($_SESSION["cedula"])) ? $_SESSION["cedula"] : "Sistema/Desconocido";
                $logData = array(
                    "event_type" => "Eliminación Categoría Fallida",
                    "description" => "Error al intentar eliminar categoría (ID: {$datos}).",
                    "employee_cedula" => $empleadoCedula,
                    "affected_table" => $tabla,
                    "affected_row_id" => $datos
                );
                ModeloEventoLog::mdlGuardarEvento($logData);
            }
        }
    }
}