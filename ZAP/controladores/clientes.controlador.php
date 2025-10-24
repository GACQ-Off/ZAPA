<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// ESTA DEBE SER LA RUTA EN clientes.controlador.php
require_once __DIR__ . "/../modelos/clientes.modelo.php";
require_once __DIR__ . "/../modelos/eventolog.modelo.php";

class ControladorClientes{

    /*=============================================
    CREAR CLIENTE
    =============================================*/
     /*=============================================
    CREAR CLIENTE
    =============================================*/
    static public function ctrCrearCliente(){

    if(isset($_POST["nuevoCliente"])){

        if(preg_match('/^[a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["nuevoCliente"]) &&
           preg_match('/^[a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["nuevoApellido"]) &&
           preg_match('/^[VvEe]$/', $_POST["tipoCedula"]) &&
           preg_match('/^[0-9]{7,8}$/', $_POST["numeroCedula"]) &&
           preg_match('/^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/', $_POST["nuevoEmail"]) &&
           preg_match('/^[#\.\-a-zA-Z0-9 ]+$/', $_POST["nuevaDireccion"]) &&
           preg_match('/^[0-9+]+$/', $_POST["prefijo_telefono"]) &&
           preg_match('/^[0-9]+$/', $_POST["numero_telefono"])){

            $tabla = "clientes";

            // ✅ CÉDULA DIVIDIDA: Guardamos tipo y número por separado
            $datos = array(
                "tipo_ced" => $_POST["tipoCedula"],
                "num_ced" => $_POST["numeroCedula"],
                "nombre" => $_POST["nuevoCliente"],
                "apellido" => $_POST["nuevoApellido"],
                "direccion" => $_POST["nuevaDireccion"],
                "email" => $_POST["nuevoEmail"],
                "prefijo_telefono" => $_POST["prefijo_telefono"],
                "numero_telefono" => $_POST["numero_telefono"]
            );

            $respuesta = ModeloClientes::mdlIngresarCliente($tabla, $datos);

            if($respuesta == "ok"){
                $empleadoCedula = (isset($_SESSION["cedula"])) ? $_SESSION["cedula"] : "Sistema/Desconocido";
                $logData = array(
                    "event_type" => "Cliente Creado",
                    "description" => "Nuevo cliente '{$datos["nombre"]} {$datos["apellido"]}' (Cédula: {$datos["tipo_ced"]}-{$datos["num_ced"]}) registrado.",
                    "employee_cedula" => $empleadoCedula,
                    "affected_table" => "clientes",
                    "affected_row_id" => $datos["tipo_ced"] . '-' . $datos["num_ced"]
                );
                ModeloEventoLog::mdlGuardarEvento($logData);

                echo'<script>
                swal({
                        type: "success",
                        title: "El cliente ha sido guardado correctamente",
                        showConfirmButton: true,
                        confirmButtonText: "Cerrar"
                        }).then(function(result){
                                if (result.value) {
                                window.location = "crear-venta";
                                }
                            })
                </script>';

            } else if ($respuesta == "repetido") {
                $empleadoCedula = (isset($_SESSION["cedula"])) ? $_SESSION["cedula"] : "Sistema/Desconocido";
                $logData = array(
                    "event_type" => "Creación Cliente Fallida",
                    "description" => "Intento de crear cliente con cédula '{$datos["tipo_ced"]}-{$datos["num_ced"]}' fallido por ser repetida.",
                    "employee_cedula" => $empleadoCedula,
                    "affected_table" => "clientes",
                    "affected_row_id" => $datos["tipo_ced"] . '-' . $datos["num_ced"]
                );
                ModeloEventoLog::mdlGuardarEvento($logData);

                echo'<script>
                swal({
                        type: "error",
                        title: "La cédula del cliente ya existe",
                        showConfirmButton: true,
                        confirmButtonText: "Cerrar"
                        }).then(function(result) {
                                if (result.value) {
                                window.location = "clientes";
                                }
                            })
                </script>';
            }

        } else {
            $empleadoCedula = (isset($_SESSION["cedula"])) ? $_SESSION["cedula"] : "Sistema/Desconocido";
            $cedulaTexto = (isset($_POST["tipoCedula"]) && isset($_POST["numeroCedula"])) ? $_POST["tipoCedula"] . '-' . $_POST["numeroCedula"] : 'N/A';
            $logData = array(
                "event_type" => "Creación Cliente Fallida",
                "description" => "Intento de crear cliente con datos inválidos (nombre: " . (isset($_POST["nuevoCliente"]) ? $_POST["nuevoCliente"] : 'N/A') . ", cédula: " . $cedulaTexto . ").",
                "employee_cedula" => $empleadoCedula,
                "affected_table" => "clientes",
                "affected_row_id" => $cedulaTexto
            );
            ModeloEventoLog::mdlGuardarEvento($logData);

            echo'<script>
                swal({
                        type: "error",
                        title: "¡El cliente no puede ir vacío o llevar caracteres especiales!",
                        showConfirmButton: true,
                        confirmButtonText: "Cerrar"
                        }).then(function(result){
                            if (result.value) {
                            window.location = "clientes";
                            }
                        })
            </script>';
        }
    }
}

    /*=============================================
    MOSTRAR CLIENTES
    =============================================*/
    public static function ctrMostrarClientes($item, $valor) {
    $tabla = "clientes";
    
    if ($item != null && $valor != null) {
        // Si hay un filtro, se realiza la consulta con filtro y ordenación
        $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item ORDER BY tipo_ced ASC, num_ced ASC");
        $stmt->bindParam(":$item", $valor, PDO::PARAM_STR);
    } else {
        // Si no hay filtro, se traen todos los clientes, ordenados por cédula (tipo_ced y num_ced)
        $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla ORDER BY tipo_ced ASC, num_ced ASC");
    }

    $stmt->execute();
    return $stmt->fetchAll();
}


    static public function ctrMostrarClientesDosClaves($item1, $item2, $valor1, $valor2) {
    $tabla = "clientes";
    return ModeloClientes::mdlMostrarClienteDosClaves($tabla, $item1, $item2, $valor1, $valor2);
    }


   /*=============================================
EDITAR CLIENTE
=============================================*/
/*=============================================
EDITAR CLIENTE
=============================================*/
static public function ctrEditarCliente(){

    if (isset($_POST["editarCedula"])) {

        // VALIDACIÓN: Validamos nombre, apellido, email, dirección y teléfono
        if (
            preg_match('/^[a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["editarCliente"]) ) {
            $tabla = "clientes";

            // ✅ Clave primaria ORIGINAL de la cédula (no editable)
            $tipo_ced_original = $_POST["original_tipo_ced"];
            $num_ced_original = $_POST["original_num_ced"];

            // ✅ Teléfono y cédula (separados)
            $prefijoTelefono = $_POST["editarPrefijoTelefono"];
            $numeroTelefono = $_POST["editarNumeroTelefono"];

            // ✅ Cédula nueva (si se permite cambiar)
            $tipoCedulaNuevo = isset($_POST["tipoCedula"]) ? $_POST["tipoCedula"] : $tipo_ced_original;
            $numeroCedulaNuevo = isset($_POST["numeroCedula"]) ? $_POST["numeroCedula"] : $num_ced_original;

            // ✅ Datos a actualizar
            $datos = array(
                "tipo_ced_original" => $tipo_ced_original,
                "num_ced_original" => $num_ced_original,
                "tipo_ced" => $tipoCedulaNuevo,
                "num_ced" => $numeroCedulaNuevo,
                "nombre" => $_POST["editarCliente"],
                "apellido" => $_POST["editarApellido"],
                "email" => $_POST["editarEmail"],
                "direccion" => $_POST["editarDireccion"],
                "prefijo_telefono" => $prefijoTelefono,
                "numero_telefono" => $numeroTelefono
            );

            $respuesta = ModeloClientes::mdlEditarCliente($tabla, $datos);

            if ($respuesta == "ok") {
                $empleadoCedula = (isset($_SESSION["cedula"])) ? $_SESSION["cedula"] : "Sistema/Desconocido";
                $logData = array(
                    "event_type" => "Cliente Editado",
                    "description" => "Datos del cliente '{$datos["nombre"]} {$datos["apellido"]}' (Cédula: {$datos["tipo_ced"]}-{$datos["num_ced"]}) modificados.",
                    "employee_cedula" => $empleadoCedula,
                    "affected_table" => "clientes",
                    "affected_row_id" => $datos["tipo_ced"] . "-" . $datos["num_ced"]
                );
                ModeloEventoLog::mdlGuardarEvento($logData);

                echo '<script>
                swal({
                        type: "success",
                        title: "El cliente ha sido cambiado correctamente",
                        showConfirmButton: true,
                        confirmButtonText: "Cerrar"
                        }).then(function(result){
                                if (result.value) {
                                window.location = "clientes";
                                }
                            })
                </script>';
            } else {
                // Registro de error en la edición
                $empleadoCedula = (isset($_SESSION["cedula"])) ? $_SESSION["cedula"] : "Sistema/Desconocido";
                $logData = array(
                    "event_type" => "Edición Cliente Fallida",
                    "description" => "Error al intentar modificar datos del cliente '{$datos["nombre"]} {$datos["apellido"]}' (Cédula: {$datos["tipo_ced"]}-{$datos["num_ced"]}).",
                    "employee_cedula" => $empleadoCedula,
                    "affected_table" => "clientes",
                    "affected_row_id" => $datos["tipo_ced"] . "-" . $datos["num_ced"]
                );
                ModeloEventoLog::mdlGuardarEvento($logData);
            }

        } else {
            // Validación de datos inválidos
            $empleadoCedula = (isset($_SESSION["cedula"])) ? $_SESSION["cedula"] : "Sistema/Desconocido";
            $logData = array(
                "event_type" => "Edición Cliente Fallida",
                "description" => "Intento de editar cliente con datos inválidos (nombre: " . (isset($_POST["editarCliente"]) ? $_POST["editarCliente"] : 'N/A') . ", cédula: " . (isset($_POST["editarCedula"]) ? $_POST["editarCedula"] : 'N/A') . ").",
                "employee_cedula" => $empleadoCedula,
                "affected_table" => "clientes",
                "affected_row_id" => (isset($_POST["editarCedula"])) ? $_POST["editarCedula"] : 'N/A'
            );
            ModeloEventoLog::mdlGuardarEvento($logData);

            echo '<script>
                swal({
                        type: "error",
                        title: "¡El cliente no puede ir vacío o llevar caracteres especiales!",
                        showConfirmButton: true,
                        confirmButtonText: "Cerrar"
                        }).then(function(result){
                            if (result.value) {
                            window.location = "clientes";
                            }
                        })
            </script>';
        }
    }
}



   /*=============================================
ELIMINAR CLIENTE (clave compuesta)
=============================================*/

static public function ctrEliminarCliente(){

    if(isset($_GET["tipoCed"]) && isset($_GET["numCed"])){

        $tabla = "clientes";

        $tipo_ced = $_GET["tipoCed"];
        $num_ced = $_GET["numCed"];

       

        $nombreClienteBorrado = $clienteAEliminar["nombre"] ?? "N/A";
        $apellidoClienteBorrado = $clienteAEliminar["apellido"] ?? "N/A";

        $datos = array(
            "tipo_ced" => $tipo_ced,
            "num_ced" => $num_ced
        );

        $respuesta = ModeloClientes::mdlEliminarCliente($tabla, $datos);

        if($respuesta == "ok"){
            echo '<script>
                swal({
                    type: "success",
                    title: "El cliente ha sido borrado correctamente",
                    showConfirmButton: true,
                    confirmButtonText: "Cerrar"
                }).then(function(result){
                    if (result.value) {
                        window.location = "clientes";
                    }
                });
            </script>';

        } else if ($respuesta == "restrict") {
            echo '<script>
                swal({
                    type: "error",
                    title: "No se puede eliminar el cliente",
                    text: "Este cliente tiene registros asociados (ventas u otros) y no puede ser eliminado.",
                    showConfirmButton: true,
                    confirmButtonText: "Cerrar"
                }).then(function(result){
                    if (result.value) {
                        window.location = "clientes";
                    }
                });
            </script>';
        } else {
            echo '<script>
                swal({
                    type: "error",
                    title: "Error al eliminar",
                    text: "Ocurrió un error inesperado al intentar eliminar el cliente.",
                    showConfirmButton: true,
                    confirmButtonText: "Cerrar"
                }).then(function(result){
                    if (result.value) {
                        window.location = "clientes";
                    }
                });
            </script>';
        }
    }
}



}