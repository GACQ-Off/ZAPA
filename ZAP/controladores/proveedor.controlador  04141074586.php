<?php
ini_set('display_errors',1);
error_reporting(E_ALL);

// ESTA DEBE SER LA RUTA DEL MODELO DE PROVEEDOR
require_once __DIR__ . "/../modelos/proveedor.modelo.php"; 
// ¡Importante para el log! Asegúrate de que esta ruta es correcta para tu modelo de log.
require_once __DIR__ . "/../modelos/eventolog.modelo.php"; 

class ControladorProveedor
{

    /*=============================================
    CREAR PROVEEDOR
    =============================================*/

    static public function ctrCrearProveedor()
    {

        if (isset($_POST["nuevoRif"])) {

            if (
                preg_match('/^[gjvpcGJVPC]-[0-9]{9}$/', $_POST["nuevoRif"]) &&
                preg_match('/^[a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["nuevoNombre"]) &&
                preg_match('/^[a-zA-ZñÑáéíóúÁÉÍÓÚ 0-9-]+$/', $_POST["nuevaEmpresa"]) &&
                preg_match('/^[a-zA-ZñÑáéíóúÁÉÍÓÚ0-9 º#-]+$/', $_POST["nuevaDireccion"]) &&
                preg_match('/^[a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["nuevoApellido"])
            ) {

                $tabla = "proveedores";

                $datos = array(
                    "rif" => $_POST["nuevoRif"],
                    "nombre_representante" => $_POST["nuevoNombre"],
                    "nombre" => $_POST["nuevaEmpresa"], // Asumo que "nombre" en DB es "nuevaEmpresa" del POST
                    "cedula_representante" => $_POST["nuevaCedula"],
                    "apellido_representante" => $_POST["nuevoApellido"],
                    "telefono" => $_POST["nuevoTelefono"],
                    "direccion" => $_POST["nuevaDireccion"],
                    "email" => $_POST["nuevoEmail"]
                );

                $respuesta = ModeloProveedor::mdlIngresarProveedor($tabla, $datos);

                if ($respuesta == "ok") {
                    // =============================================
                    // REGISTRAR CREACIÓN DE PROVEEDOR EN EL LOG
                    // =============================================
                    $empleadoCedula = (isset($_SESSION["cedula"])) ? $_SESSION["cedula"] : "Sistema/Desconocido";
                    $logData = array(
                        "event_type" => "Proveedor Creado",
                        "description" => "Nuevo proveedor '{$datos['nombre']}' (RIF: {$datos['rif']}) registrado. Representante: {$datos['nombre_representante']} {$datos['apellido_representante']}.",
                        "employee_cedula" => $empleadoCedula,
                        "affected_table" => $tabla,
                        "affected_row_id" => $datos['rif']
                    );
                    ModeloEventoLog::mdlGuardarEvento($logData);

                    echo '<script>

                    swal({
                        type: "success",
                        title: "El proveedor ha sido guardado correctamente",
                        showConfirmButton: true,
                        confirmButtonText: "Cerrar"
                        }).then(function(result){
                                    if (result.value) {

                                    window.location = "proveedor";

                                    }
                                })

                    </script>';
                } else if ($respuesta == "repetido") {
                    // =============================================
                    // REGISTRAR INTENTO DE CREACIÓN DE PROVEEDOR REPETIDO
                    // =============================================
                    $empleadoCedula = (isset($_SESSION["cedula"])) ? $_SESSION["cedula"] : "Sistema/Desconocido";
                    $logData = array(
                        "event_type" => "Creación Proveedor Fallida",
                        "description" => "Intento de crear proveedor con RIF '{$datos['rif']}' fallido por ser repetido. Empresa: '{$datos['nombre']}'.",
                        "employee_cedula" => $empleadoCedula,
                        "affected_table" => $tabla,
                        "affected_row_id" => $datos['rif']
                    );
                    ModeloEventoLog::mdlGuardarEvento($logData);

                    echo '<script>

                    swal({
                        type: "error",
                        title: "El RIF del proveedor ya existe",
                        showConfirmButton: true,
                        confirmButtonText: "Cerrar"
                        }).then(function(result) {
                                    if (result.value) {

                                    window.location = "proveedor";

                                    }
                                })

                    </script>';
                } else {
                    // =============================================
                    // REGISTRAR FALLO DESCONOCIDO EN LA CREACIÓN DE PROVEEDOR
                    // =============================================
                    $empleadoCedula = (isset($_SESSION["cedula"])) ? $_SESSION["cedula"] : "Sistema/Desconocido";
                    $logData = array(
                        "event_type" => "Creación Proveedor Fallida",
                        "description" => "Error desconocido al intentar crear el proveedor con RIF '{$datos['rif']}'. Empresa: '{$datos['nombre']}'.",
                        "employee_cedula" => $empleadoCedula,
                        "affected_table" => $tabla,
                        "affected_row_id" => $datos['rif']
                    );
                    ModeloEventoLog::mdlGuardarEvento($logData);
                }


            } else {
                // =============================================
                // REGISTRAR INTENTO DE CREACIÓN DE PROVEEDOR CON DATOS INVÁLIDOS
                // =============================================
                $empleadoCedula = (isset($_SESSION["cedula"])) ? $_SESSION["cedula"] : "Sistema/Desconocido";
                $logData = array(
                    "event_type" => "Creación Proveedor Fallida",
                    "description" => "Intento de crear proveedor con datos inválidos. RIF: " . (isset($_POST["nuevoRif"]) ? $_POST["nuevoRif"] : 'N/A') . ". Nombre Empresa: " . (isset($_POST["nuevaEmpresa"]) ? $_POST["nuevaEmpresa"] : 'N/A') . ".",
                    "employee_cedula" => $empleadoCedula,
                    "affected_table" => "proveedores",
                    "affected_row_id" => (isset($_POST["nuevoRif"])) ? $_POST["nuevoRif"] : 'N/A'
                );
                ModeloEventoLog::mdlGuardarEvento($logData);

                echo '<script>

                    swal({
                        type: "error",
                        title: "¡El Proveedor no puede ir vacío o llevar caracteres especiales!",
                        showConfirmButton: true,
                        confirmButtonText: "Cerrar"
                        }).then(function(result){
                            if (result.value) {

                            window.location = "proveedor";

                            }
                        })

                </script>';
            }
        }
    }

    /*=============================================
    MOSTRAR PROVEEDOR
    =============================================*/
    // Este método es solo para mostrar, no necesita logeo de eventos.
    static public function ctrMostrarProveedor($item, $valor)
    {

        $tabla = "proveedores";

        $respuesta = ModeloProveedor::mdlMostrarProveedor($tabla, $item, $valor);

        return $respuesta;
    }

    /*=============================================
    EDITAR PROVEEDOR
    =============================================*/

    static public function ctrEditarProveedor()
    {
        if (isset($_POST["editarRif"])) {

            if (
                preg_match('/^[a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["editarNombre"]) &&
                preg_match('/^[a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["editarApellido"]) &&
                preg_match('/^[a-zA-ZñÑáéíóúÁÉÍÓÚ 0-9-]+$/', $_POST["editarEmpresa"]) &&
                preg_match('/^[0-9-]{12}$/', $_POST["editarTelefono"]) &&
                preg_match('/^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/', $_POST["editarEmail"]) &&
                preg_match('/^[a-zA-ZñÑáéíóúÁÉÍÓÚ0-9 º#-]+$/', $_POST["editarDireccion"])
            ) {

                $tabla = "proveedores";

                // Obtener datos actuales del proveedor antes de la edición para el log
                // Se asume que el RIF es el identificador único para buscar en la BD
                $proveedorAnterior = ModeloProveedor::mdlMostrarProveedor($tabla, "rif", $_POST["editarRif"]);
                $nombreEmpresaAnterior = (isset($proveedorAnterior['nombre'])) ? $proveedorAnterior['nombre'] : 'N/A';
                $nombreRepAnterior = (isset($proveedorAnterior['nombre_representante'])) ? $proveedorAnterior['nombre_representante'] : 'N/A';
                $apellidoRepAnterior = (isset($proveedorAnterior['apellido_representante'])) ? $proveedorAnterior['apellido_representante'] : 'N/A';
                $telefonoAnterior = (isset($proveedorAnterior['telefono'])) ? $proveedorAnterior['telefono'] : 'N/A';

                $datos = array(
                    "rif" => $_POST["editarRif"],
                    "empresa" => $_POST["editarEmpresa"], // Asumo que "empresa" en DB es "editarEmpresa" del POST
                    "nombre" => $_POST["editarNombre"], // nombre del representante
                    "apellido" => $_POST["editarApellido"], // apellido del representante
                    "cedula" => $_POST["editarCedula"],
                    "email" => $_POST["editarEmail"],
                    "telefono" => $_POST["editarTelefono"],
                    "direccion" => $_POST["editarDireccion"]
                );

                $respuesta = ModeloProveedor::mdlEditarProveedor($tabla, $datos);

                if ($respuesta == "ok") {
                    // =============================================
                    // REGISTRAR EDICIÓN DE PROVEEDOR EN EL LOG
                    // =============================================
                    $empleadoCedula = (isset($_SESSION["cedula"])) ? $_SESSION["cedula"] : "Sistema/Desconocido";
                    $description = "Proveedor (RIF: {$datos['rif']}) editado. ";
                    $description .= "Empresa: '{$nombreEmpresaAnterior}' a '{$datos['empresa']}'. ";
                    $description .= "Representante: '{$nombreRepAnterior} {$apellidoRepAnterior}' a '{$datos['nombre']} {$datos['apellido']}'. ";
                    $description .= "Teléfono: '{$telefonoAnterior}' a '{$datos['telefono']}'.";

                    $logData = array(
                        "event_type" => "Proveedor Editado",
                        "description" => $description,
                        "employee_cedula" => $empleadoCedula,
                        "affected_table" => $tabla,
                        "affected_row_id" => $datos["rif"]
                    );
                    ModeloEventoLog::mdlGuardarEvento($logData);

                    echo '<script>

                    swal({
                        type: "success",
                        title: "El proveedor ha sido cambiado correctamente",
                        showConfirmButton: true,
                        confirmButtonText: "Cerrar"
                        }).then(function(result){
                                    if (result.value) {

                                    window.location = "proveedor";

                                    }
                                })

                    </script>';


                } else {
                    // =============================================
                    // REGISTRAR FALLO EN EDICIÓN DE PROVEEDOR
                    // =============================================
                    $empleadoCedula = (isset($_SESSION["cedula"])) ? $_SESSION["cedula"] : "Sistema/Desconocido";
                    $logData = array(
                        "event_type" => "Edición Proveedor Fallida",
                        "description" => "Error al intentar modificar el proveedor con RIF '{$datos['rif']}'. Empresa: '{$datos['empresa']}'.",
                        "employee_cedula" => $empleadoCedula,
                        "affected_table" => $tabla,
                        "affected_row_id" => $datos["rif"]
                    );
                    ModeloEventoLog::mdlGuardarEvento($logData);
                }


            } else {
                // =============================================
                // REGISTRAR INTENTO DE EDICIÓN DE PROVEEDOR CON DATOS INVÁLIDOS
                // =============================================
                $empleadoCedula = (isset($_SESSION["cedula"])) ? $_SESSION["cedula"] : "Sistema/Desconocido";
                $logData = array(
                    "event_type" => "Edición Proveedor Fallida",
                    "description" => "Intento de editar proveedor con datos inválidos. RIF: " . (isset($_POST["editarRif"]) ? $_POST["editarRif"] : 'N/A') . ". Nombre Empresa: " . (isset($_POST["editarEmpresa"]) ? $_POST["editarEmpresa"] : 'N/A') . ".",
                    "employee_cedula" => $empleadoCedula,
                    "affected_table" => "proveedores",
                    "affected_row_id" => (isset($_POST["editarRif"])) ? $_POST["editarRif"] : 'N/A'
                );
                ModeloEventoLog::mdlGuardarEvento($logData);

                echo '<script>

                    swal({
                            type: "error",
                            title: "¡El Representante no puede ir vacío , llevar caracteres especiales o números!",
                            showConfirmButton: true,
                            confirmButtonText: "Cerrar"
                            }).then(function(result){
                                if (result.value) {

                                window.location = "proveedor";

                                }
                            })

                </script>';


            }
        }
    }

    /*=============================================
    ELIMINAR PROVEEDOR
    =============================================*/

    static public function ctrEliminarProveedor()
    {

        if (isset($_GET["idCliente"])) { // Asumo que idCliente aquí es el RIF del proveedor a eliminar

            $tabla = "proveedores";
            $datos = $_GET["idCliente"]; // Este $datos es el RIF

            // Obtener los datos del proveedor antes de eliminar para el log
            $proveedorAEliminar = ModeloProveedor::mdlMostrarProveedor($tabla, "rif", $datos);
            $nombreEmpresaBorrada = (isset($proveedorAEliminar['nombre'])) ? $proveedorAEliminar['nombre'] : 'N/A';


            $respuesta = ModeloProveedor::mdlEliminarProveedor($tabla, $datos);

            if ($respuesta == "ok") {
                // =============================================
                // REGISTRAR ELIMINACIÓN DE PROVEEDOR EN EL LOG
                // =============================================
                $empleadoCedula = (isset($_SESSION["cedula"])) ? $_SESSION["cedula"] : "Sistema/Desconocido";
                $logData = array(
                    "event_type" => "Proveedor Eliminado",
                    "description" => "Proveedor '{$nombreEmpresaBorrada}' (RIF: {$datos}) eliminado.",
                    "employee_cedula" => $empleadoCedula,
                    "affected_table" => $tabla,
                    "affected_row_id" => $datos
                );
                ModeloEventoLog::mdlGuardarEvento($logData);

                echo '<script>

                swal({
                    type: "success",
                    title: "El proveedor ha sido borrado correctamente",
                    showConfirmButton: true,
                    confirmButtonText: "Cerrar",
                    closeOnConfirm: false
                    }).then(function(result){
                                if (result.value) {

                                window.location = "proveedor";

                                }
                            })

                </script>';
            } else {
                // =============================================
                // REGISTRAR FALLO EN ELIMINACIÓN DE PROVEEDOR
                // =============================================
                $empleadoCedula = (isset($_SESSION["cedula"])) ? $_SESSION["cedula"] : "Sistema/Desconocido";
                $logData = array(
                    "event_type" => "Eliminación Proveedor Fallida",
                    "description" => "Error al intentar eliminar proveedor '{$nombreEmpresaBorrada}' (RIF: {$datos}).",
                    "employee_cedula" => $empleadoCedula,
                    "affected_table" => $tabla,
                    "affected_row_id" => $datos
                );
                ModeloEventoLog::mdlGuardarEvento($logData);
            }
        }
    }
}