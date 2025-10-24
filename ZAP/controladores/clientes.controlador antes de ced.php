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
    static public function ctrCrearCliente(){

        if(isset($_POST["nuevoCliente"])){


            // VALIDACIÓN CORREGIDA: Incluye todos los campos

            if(preg_match('/^[a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["nuevoCliente"]) &&
               preg_match('/^[a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["nuevoApellido"]) &&
               //preg_match('/^[0-9]+$/', $_POST["nuevaCedula"]) && flata la correcion dos camos
               preg_match('/^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-0_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/', $_POST["nuevoEmail"]) &&
               preg_match('/^[#\.\-a-zA-Z0-9 ]+$/', $_POST["nuevaDireccion"]) &&
               preg_match('/^[0-9+]+$/', $_POST["prefijo_telefono"]) &&
               preg_match('/^[0-9]+$/', $_POST["numero_telefono"])){

                $tabla = "clientes";

                // MODIFICACIÓN: Capturamos prefijo_telefono y numero_telefono
                $datos = array(
                    "nombre" => $_POST["nuevoCliente"],
                    "cedula" => $_POST["nuevaCedula"],
                    "apellido" => $_POST["nuevoApellido"],
                    "direccion" => $_POST["nuevaDireccion"],
                    "email" => $_POST["nuevoEmail"],
                    "prefijo_telefono" => $_POST["prefijo_telefono"],
                    "numero_telefono" => $_POST["numero_telefono"]
                );

                $respuesta = ModeloClientes::mdlIngresarCliente($tabla, $datos);

                if($respuesta == "ok"){
                    // =============================================
                    // REGISTRAR CREACIÓN DE CLIENTE EN EL LOG
                    // =============================================
                    $empleadoCedula = (isset($_SESSION["cedula"])) ? $_SESSION["cedula"] : "Sistema/Desconocido";
                    $logData = array(
                        "event_type" => "Cliente Creado",
                        "description" => "Nuevo cliente '{$datos["nombre"]} {$datos["apellido"]}' (Cédula: {$datos["cedula"]}) registrado.",
                        "employee_cedula" => $empleadoCedula,
                        "affected_table" => "clientes",
                        "affected_row_id" => $datos["cedula"]
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

                }else if($respuesta == "repetido"){
                    // =============================================
                    // REGISTRAR INTENTO DE CREACIÓN DE CLIENTE REPETIDO
                    // =============================================
                    $empleadoCedula = (isset($_SESSION["cedula"])) ? $_SESSION["cedula"] : "Sistema/Desconocido";
                    $logData = array(
                        "event_type" => "Creación Cliente Fallida",
                        "description" => "Intento de crear cliente con cédula '{$datos["cedula"]}' fallido por ser repetida.",
                        "employee_cedula" => $empleadoCedula,
                        "affected_table" => "clientes",
                        "affected_row_id" => $datos["cedula"]
                    );
                    ModeloEventoLog::mdlGuardarEvento($logData);

                    echo'<script>
                    swal({
                            type: "error",
                            title: "La cedula del cliente ya existe",
                            showConfirmButton: true,
                            confirmButtonText: "Cerrar"
                            }).then(function(result) {
                                    if (result.value) {
                                    window.location = "clientes";
                                    }
                                })
                    </script>';
                }

            }else{
                // =============================================
                // REGISTRAR INTENTO DE CREACIÓN DE CLIENTE CON DATOS INVÁLIDOS
                // =============================================
                $empleadoCedula = (isset($_SESSION["cedula"])) ? $_SESSION["cedula"] : "Sistema/Desconocido";
                $logData = array(
                    "event_type" => "Creación Cliente Fallida",
                    "description" => "Intento de crear cliente con datos inválidos (nombre: " . (isset($_POST["nuevoCliente"]) ? $_POST["nuevoCliente"] : 'N/A') . ", cédula: " . (isset($_POST["nuevaCedula"]) ? $_POST["nuevaCedula"] : 'N/A') . ").",
                    "employee_cedula" => $empleadoCedula,
                    "affected_table" => "clientes",
                    "affected_row_id" => (isset($_POST["nuevaCedula"])) ? $_POST["nuevaCedula"] : 'N/A'
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
    static public function ctrMostrarClientes($item, $valor){

        $tabla = "clientes";
        $respuesta = ModeloClientes::mdlMostrarClientes($tabla, $item, $valor);
        return $respuesta;
    }

    static public function ctrMostrarClientesDosClaves($item1, $item2, $valor1, $valor2) {
    $tabla = "clientes";
    return ModeloClientes::mdlMostrarClienteDosClaves($tabla, $item1, $item2, $valor1, $valor2);
    }


    /*=============================================
    EDITAR CLIENTE
    =============================================*/
    static public function ctrEditarCliente(){

        if(isset($_POST["editarCedula"])){

            // VALIDACIÓN CORREGIDA: Se verifica el patrón de prefijo y número
            if(preg_match('/^[a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["editarCliente"]) &&
               preg_match('/^[a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["editarApellido"]) &&
               preg_match('/^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/', $_POST["editarEmail"]) &&
               preg_match('/^[#\.\-a-zA-Z0-9 ]+$/', $_POST["editarDireccion"]) &&
               preg_match('/^[0-9+]+$/', $_POST["editarPrefijoTelefono"]) &&
               preg_match('/^[0-9]+$/', $_POST["editarRestoTelefono"])){
                
                $tabla = "clientes";

                // MODIFICACIÓN: Capturamos prefijo_telefono y numero_telefono en el array de datos
                $datos = array(
                    "cedula" => $_POST["editarCedula"],
                    "nombre" => $_POST["editarCliente"],
                    "apellido" => $_POST["editarApellido"],
                    "email" => $_POST["editarEmail"],
                    "direccion" => $_POST["editarDireccion"],
                    "prefijo_telefono" => $_POST["editarPrefijoTelefono"],
                    "numero_telefono" => $_POST["editarRestoTelefono"]
                );

                $respuesta = ModeloClientes::mdlEditarCliente($tabla, $datos);

                if($respuesta == "ok"){
                    // =============================================
                    // REGISTRAR EDICIÓN DE CLIENTE EN EL LOG
                    // =============================================
                    $empleadoCedula = (isset($_SESSION["cedula"])) ? $_SESSION["cedula"] : "Sistema/Desconocido";
                    $logData = array(
                        "event_type" => "Cliente Editado",
                        "description" => "Datos del cliente '{$datos["nombre"]} {$datos["apellido"]}' (Cédula: {$datos["cedula"]}) modificados.",
                        "employee_cedula" => $empleadoCedula,
                        "affected_table" => "clientes",
                        "affected_row_id" => $datos["cedula"]
                    );
                    ModeloEventoLog::mdlGuardarEvento($logData);

                    echo'<script>
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
                    // =============================================
                    // REGISTRAR FALLO EN EDICIÓN DE CLIENTE
                    // =============================================
                    $empleadoCedula = (isset($_SESSION["cedula"])) ? $_SESSION["cedula"] : "Sistema/Desconocido";
                    $logData = array(
                        "event_type" => "Edición Cliente Fallida",
                        "description" => "Error al intentar modificar datos del cliente '" . (isset($datos["nombre"]) ? $datos["nombre"] : 'N/A') . " " . (isset($datos["apellido"]) ? $datos["apellido"] : 'N/A') . "' (Cédula: " . (isset($datos["cedula"]) ? $datos["cedula"] : 'N/A') . ").",
                        "employee_cedula" => $empleadoCedula,
                        "affected_table" => "clientes",
                        "affected_row_id" => (isset($datos["cedula"])) ? $datos["cedula"] : 'N/A'
                    );
                    ModeloEventoLog::mdlGuardarEvento($logData);
                }

            }else{
                // =============================================
                // REGISTRAR INTENTO DE EDICIÓN DE CLIENTE CON DATOS INVÁLIDOS
                // =============================================
                $empleadoCedula = (isset($_SESSION["cedula"])) ? $_SESSION["cedula"] : "Sistema/Desconocido";
                $logData = array(
                    "event_type" => "Edición Cliente Fallida",
                    "description" => "Intento de editar cliente con datos inválidos (nombre: " . (isset($_POST["editarCliente"]) ? $_POST["editarCliente"] : 'N/A') . ", cédula: " . (isset($_POST["editarCedula"]) ? $_POST["editarCedula"] : 'N/A') . ").",
                    "employee_cedula" => $empleadoCedula,
                    "affected_table" => "clientes",
                    "affected_row_id" => (isset($_POST["editarCedula"])) ? $_POST["editarCedula"] : 'N/A'
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
    ELIMINAR CLIENTE
    =============================================*/
    static public function ctrEliminarCliente(){

        if(isset($_GET["idCliente"])){ // Aquí idCliente es la 'cedula' del cliente a borrar

            $tabla ="clientes";
            $datos = $_GET["idCliente"];

            // Obtener datos del cliente antes de eliminar para el log
            $clienteAEliminar = ModeloClientes::mdlMostrarClientes($tabla, "cedula", $datos);
            $nombreClienteBorrado = (isset($clienteAEliminar['nombre'])) ? $clienteAEliminar['nombre'] : 'N/A';
            $apellidoClienteBorrado = (isset($clienteAEliminar['apellido'])) ? $clienteAEliminar['apellido'] : 'N/A';
            $cedulaClienteBorrado = (isset($clienteAEliminar['cedula'])) ? $clienteAEliminar['cedula'] : 'N/A';


            $respuesta = ModeloClientes::mdlEliminarCliente($tabla, $datos);

            if($respuesta == "ok"){
                // =============================================
                // REGISTRAR ELIMINACIÓN DE CLIENTE EN EL LOG
                // =============================================
                $empleadoCedula = (isset($_SESSION["cedula"])) ? $_SESSION["cedula"] : "Sistema/Desconocido";
                $logData = array(
                    "event_type" => "Cliente Eliminado",
                    "description" => "Cliente '{$nombreClienteBorrado} {$apellidoClienteBorrado}' (Cédula: {$cedulaClienteBorrado}) eliminado.",
                    "employee_cedula" => $empleadoCedula,
                    "affected_table" => "clientes",
                    "affected_row_id" => $cedulaClienteBorrado
                );
                ModeloEventoLog::mdlGuardarEvento($logData);

                echo'<script>
                swal({
                        type: "success",
                        title: "El cliente ha sido borrado correctamente",
                        showConfirmButton: true,
                        confirmButtonText: "Cerrar",
                        closeOnConfirm: false
                        }).then(function(result){
                                if (result.value) {
                                window.location = "clientes";
                                }
                            })
                </script>';
            } else {
                // =============================================
                // REGISTRAR FALLO EN ELIMINACIÓN DE CLIENTE
                // =============================================
                $empleadoCedula = (isset($_SESSION["cedula"])) ? $_SESSION["cedula"] : "Sistema/Desconocido";
                $logData = array(
                    "event_type" => "Eliminación Cliente Fallida",
                    "description" => "Error al intentar eliminar cliente (Cédula: {$datos}).",
                    "employee_cedula" => $empleadoCedula,
                    "affected_table" => "clientes",
                    "affected_row_id" => $datos
                );
                ModeloEventoLog::mdlGuardarEvento($logData);
            }
        }
    }
}