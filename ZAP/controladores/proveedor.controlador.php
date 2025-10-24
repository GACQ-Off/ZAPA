<?php
ini_set('display_errors',1);
error_reporting(E_ALL);

// Modelo del proveedor
require_once __DIR__ . "/../modelos/proveedor.modelo.php";
// Modelo para el registro de eventos (log)
require_once __DIR__ . "/../modelos/eventolog.modelo.php";

class ControladorProveedor
{

    /*=============================================
    CREAR PROVEEDOR
    =============================================*/
    static public function ctrCrearProveedor()
    {
        // Se verifica si se enviaron los campos de tipo_rif y num_rif
        if (isset($_POST["tipo_rif"]) && isset($_POST["num_rif"])) {

            // Recupera los campos de teléfono del formulario
            $prefijoTelefono = $_POST["prefijo_telefono"] ?? '';
            $numeroTelefono = $_POST["numero_telefono"] ?? '';

            // Obtiene los valores de tipo_rif y num_rif directamente del POST
            $tipoRif = $_POST["tipo_rif"];
            $numRif = $_POST["num_rif"];
            
            // Valida los datos del formulario usando expresiones regulares
            if (
                // Validación de RIF: una letra seguida de 9 dígitos numéricos
                preg_match('/^[GJVPCAg]{1}$/', $tipoRif) && 
                preg_match('/^[0-9]{9}$/', $numRif) &&      

                // Validaciones para otros campos
                preg_match('/^[a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["nuevoNombre"]) &&
                preg_match('/^[a-zA-ZñÑáéíóúÁÉÍÓÚ 0-9-]+$/', $_POST["nuevaEmpresa"]) &&
                preg_match('/^[a-zA-ZñÑáéíóúÁÉÍÓÚ0-9 º#-]+$/', $_POST["nuevaDireccion"]) &&
                preg_match('/^[a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["nuevoApellido"]) &&
                
                // Validaciones para el teléfono separado
                preg_match('/^[0-9]+$/', $prefijoTelefono) && 
                preg_match('/^[0-9]{7}$/', $numeroTelefono)    
            ) {

                $tabla = "proveedores";

                // Prepara el array de datos para el modelo
                $datos = array(
                    "tipo_rif" => $tipoRif,
                    "num_rif" => $numRif,
                    "nombre_representante" => $_POST["nuevoNombre"],
                    "nombre" => $_POST["nuevaEmpresa"], // Asumo que "nombre" en DB es "nuevaEmpresa" del POST
                    "tipo_ced" => $_POST["tipo_ced"],
                    "num_ced" => $_POST["num_ced"],
                    "apellido_representante" => $_POST["nuevoApellido"],
                    "prefijo_telefono" => $_POST["prefijo_telefono"],
                    "numero_telefono" => $_POST["numero_telefono"],
                    "direccion" => $_POST["nuevaDireccion"],
                    "correo" => $_POST["nuevoEmail"] // Asumo que el campo 'email' en la DB es 'correo'
                );

                // Llama al modelo para insertar el proveedor
                $respuesta = ModeloProveedor::mdlIngresarProveedor($tabla, $datos);

                if ($respuesta == "ok") {
                    // =============================================
                    // REGISTRAR CREACIÓN DE PROVEEDOR EN EL LOG
                    // =============================================
                    $empleadoCedula = (isset($_SESSION["cedula"])) ? $_SESSION["cedula"] : "Sistema/Desconocido";
                    $logData = array(
                        "event_type" => "Proveedor Creado",
                        "description" => "Nuevo proveedor '{$datos['nombre']}' (RIF: {$datos['tipo_rif']}-{$datos['num_rif']}) registrado. Representante: {$datos['nombre_representante']} {$datos['apellido_representante']}. Teléfono: {$datos['prefijo_telefono']}-{$datos['numero_telefono']}.",
                        "employee_cedula" => $empleadoCedula,
                        "affected_table" => $tabla,
                        "affected_row_id" => $datos['tipo_rif'] . '-' . $datos['num_rif'] // RIF combinado para el ID afectado en el log
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
                        "description" => "Intento de crear proveedor con RIF '{$datos['tipo_rif']}-{$datos['num_rif']}' fallido por ser repetido. Empresa: '{$datos['nombre']}'.",
                        "employee_cedula" => $empleadoCedula,
                        "affected_table" => $tabla,
                        "affected_row_id" => $datos['tipo_rif'] . '-' . $datos['num_rif'] // RIF combinado para el ID afectado
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
                        "description" => "Error desconocido al intentar crear el proveedor con RIF '" . (isset($tipoRif) ? $tipoRif : 'N/A') . "-" . (isset($numRif) ? $numRif : 'N/A') . "'. Empresa: '" . (isset($_POST["nuevaEmpresa"]) ? $_POST["nuevaEmpresa"] : 'N/A') . "'.",
                        "employee_cedula" => $empleadoCedula,
                        "affected_table" => $tabla,
                        "affected_row_id" => (isset($tipoRif) && isset($numRif)) ? $tipoRif . '-' . $numRif : 'N/A'
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
                    "description" => "Intento de crear proveedor con datos inválidos. RIF: " . (isset($tipoRif) ? $tipoRif : 'N/A') . "-" . (isset($numRif) ? $numRif : 'N/A') . ". Nombre Empresa: " . (isset($_POST["nuevaEmpresa"]) ? $_POST["nuevaEmpresa"] : 'N/A') . ". Prefijo Teléfono: " . (isset($prefijoTelefono) ? $prefijoTelefono : 'N/A') . ". Número Teléfono: " . (isset($numeroTelefono) ? $numeroTelefono : 'N/A') . ".",
                    "employee_cedula" => $empleadoCedula,
                    "affected_table" => "proveedores",
                    "affected_row_id" => (isset($tipoRif) && isset($numRif)) ? $tipoRif . '-' . $numRif : 'N/A'
                );
                ModeloEventoLog::mdlGuardarEvento($logData);

                echo '<script>
                    swal({
                        type: "error",
                        title: "¡El Proveedor no puede ir vacío o llevar caracteres especiales o los campos de RIF/teléfono no son válidos!",
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
        // Se verifica si se enviaron los campos de RIF ORIGINAL para la edición
        // Estos vienen de los campos ocultos en el formulario HTML (original_tipo_rif, original_num_rif)
        if (isset($_POST["original_tipo_rif"]) && isset($_POST["original_num_rif"])) {

            // Obtiene los valores del RIF ORIGINAL para identificar el registro a editar
            $tipoRifOriginal = $_POST["original_tipo_rif"];
            $numRifOriginal = $_POST["original_num_rif"];

            // Si el RIF no es editable en el formulario, los valores nuevos son los mismos que los originales.
            // Si el RIF fuera editable (lo cual no recomendamos para claves primarias sin una lógica compleja),
            // necesitarías leer los nuevos valores aquí.
            
            // Recupera los campos de teléfono para edición
            $editarPrefijoTelefono = $_POST["eprefijo_telefono"] ?? '';
            $editarNumeroTelefono = $_POST["enumero_telefono"] ?? '';

            
            // Valida los datos del formulario de edición
            if (
                // Validación de RIF: una letra seguida de 9 dígitos numéricos
                preg_match('/^[GJVPCAg]{1}$/', $tipoRifOriginal) && 
                preg_match('/^[0-9]{9}$/', $numRifOriginal) &&      

                // Validaciones para otros campos
                preg_match('/^[a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["editarNombre"]) &&
                preg_match('/^[a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["editarApellido"])&&
                preg_match('/^[a-zA-ZñÑáéíóúÁÉÍÓÚ 0-9-]+$/', $_POST["editarEmpresa"])&&
                preg_match('/^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/', $_POST["editarEmail"])  &&
                preg_match('/^[a-zA-ZñÑáéíóúÁÉÍÓÚ0-9 º#-]+$/', $_POST["editarDireccion"]) &&
                
                // Validaciones para el teléfono separado en edición
                preg_match('/^[0-9]+$/', $editarPrefijoTelefono) &&
                preg_match('/^[0-9]{7}$/', $editarNumeroTelefono)
            ) {

                $tabla = "proveedores";

                // Importante: Para obtener los datos anteriores del proveedor para el log,
                // se necesita un identificador único del proveedor ANTES de aplicar los cambios.
                // Si el RIF (tipo_rif y num_rif) es el identificador único y PUEDE cambiar,
                // necesitarás un campo oculto en el formulario HTML que guarde el RIF original.
                // Asumo que tienes un campo oculto en el formulario de edición llamado 'rifOriginalParaEdicion'
                // que contiene el RIF combinado (ej. "J-123456789") del proveedor que se está editando.
                $rifOriginalCompleto = $_POST["rifOriginalParaEdicion"] ?? '';

                $proveedorAnterior = null;
                if (!empty($rifOriginalCompleto)) {
                    // Se asume que mdlMostrarProveedor puede buscar por un campo 'rif' combinado.
                    // Si tu BD ahora tiene 'tipo_rif' y 'num_rif' separados, este método en el Modelo
                    // debe ser adaptado para buscar por ambos, o por un ID_Proveedor.
                    $proveedorAnterior = ModeloProveedor::mdlMostrarProveedor($tabla, "rif", $rifOriginalCompleto);
                }
                
                // Prepara las variables para el log, usando 'N/A' si no se encontraron datos anteriores
                $nombreEmpresaAnterior = (isset($proveedorAnterior['nombre'])) ? $proveedorAnterior['nombre'] : 'N/A';
                $nombreRepAnterior = (isset($proveedorAnterior['nombre_representante'])) ? $proveedorAnterior['nombre_representante'] : 'N/A';
                $apellidoRepAnterior = (isset($proveedorAnterior['apellido_representante'])) ? $proveedorAnterior['apellido_representante'] : 'N/A';
                $prefijoTelefonoAnterior = (isset($proveedorAnterior['prefijo_telefono'])) ? $proveedorAnterior['prefijo_telefono'] : 'N/A';
                $numeroTelefonoAnterior = (isset($proveedorAnterior['numero_telefono'])) ? $proveedorAnterior['numero_telefono'] : 'N/A';
                $tipoRifAnterior = (isset($proveedorAnterior['tipo_rif'])) ? $proveedorAnterior['tipo_rif'] : 'N/A';
                $numRifAnterior = (isset($proveedorAnterior['num_rif'])) ? $proveedorAnterior['num_rif'] : 'N/A';


                // Prepara el array de datos para la actualización en el modelo
               $datos = array(
                    "tipo_rif" => $tipoRifOriginal,
                    "num_rif" => $numRifOriginal,
                    "nombre" => $_POST["editarEmpresa"], // nombre de la empresa
                    "nombre_representante" => $_POST["editarNombre"],
                    "apellido_representante" => $_POST["editarApellido"],
                    "tipo_ced" => $_POST["tipo_ced"],
                    "num_ced" => $_POST["num_ced"],
                    "correo" => $_POST["editarEmail"],
                    "prefijo_telefono" => $editarPrefijoTelefono,
                    "numero_telefono" => $editarNumeroTelefono,
                    "direccion" => $_POST["editarDireccion"]
                );


                // Si tu modelo necesita el RIF anterior para el WHERE, debes incluirlo en $datos
                // Ejemplo: "rif_anterior_tipo" => $tipoRifAnterior, "rif_anterior_numero" => $numRifAnterior,
                // O si tienes un ID_Proveedor oculto en el formulario de edición:
                // "id_proveedor" => $_POST["idProveedorEdicion"], // ¡Muy recomendado usar un ID interno!

                $respuesta = ModeloProveedor::mdlEditarProveedor($tabla, $datos);

                if ($respuesta == "ok") {
                    // =============================================
                    // REGISTRAR EDICIÓN DE PROVEEDOR EN EL LOG
                    // =============================================
                    $empleadoCedula = (isset($_SESSION["cedula"])) ? $_SESSION["cedula"] : "Sistema/Desconocido";
                    $description = "Proveedor (RIF: {$tipoRifAnterior}-{$numRifAnterior}) editado a (RIF: {$datos['tipo_rif']}-{$datos['num_rif']}). ";
                    $description .= "Empresa: '{$nombreEmpresaAnterior}' a '{$datos['empresa']}'. ";
                    $description .= "Representante: '{$nombreRepAnterior} {$apellidoRepAnterior}' a '{$datos['nombre']} {$datos['apellido']}'. ";
                    $description .= "Teléfono: '{$prefijoTelefonoAnterior}-{$numeroTelefonoAnterior}' a '{$datos['prefijo_telefono']}-{$datos['numero_telefono']}'.";

                    $logData = array(
                        "event_type" => "Proveedor Editado",
                        "description" => $description,
                        "employee_cedula" => $empleadoCedula,
                        "affected_table" => $tabla,
                        "affected_row_id" => $datos["tipo_rif"] . '-' . $datos["num_rif"] // El nuevo RIF combinado para el log
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
                        "description" => "Error al intentar modificar el proveedor con RIF '" . (isset($tipoRifEditar) ? $tipoRifEditar : 'N/A') . "-" . (isset($numRifEditar) ? $numRifEditar : 'N/A') . "'. Empresa: '" . (isset($_POST["editarEmpresa"]) ? $_POST["editarEmpresa"] : 'N/A') . "'.",
                        "employee_cedula" => $empleadoCedula,
                        "affected_table" => $tabla,
                        "affected_row_id" => (isset($tipoRifEditar) && isset($numRifEditar)) ? $tipoRifEditar . '-' . $numRifEditar : 'N/A'
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
                    "description" => "Intento de editar proveedor con datos inválidos. RIF: " . (isset($tipoRifEditar) ? $tipoRifEditar : 'N/A') . "-" . (isset($numRifEditar) ? $numRifEditar : 'N/A') . ". Nombre Empresa: " . (isset($_POST["editarEmpresa"]) ? $_POST["editarEmpresa"] : 'N/A') . ". Prefijo: " . (isset($editarPrefijoTelefono) ? $editarPrefijoTelefono : 'N/A') . ". Número: " . (isset($editarNumeroTelefono) ? $editarNumeroTelefono : 'N/A') . ".",
                    "employee_cedula" => $empleadoCedula,
                    "affected_table" => "proveedores",
                    "affected_row_id" => (isset($tipoRifEditar) && isset($numRifEditar)) ? $tipoRifEditar . '-' . $numRifEditar : 'N/A'
                );
                ModeloEventoLog::mdlGuardarEvento($logData);

                echo '<script>
                    swal({
                        type: "error",
                        title: "¡Los campos de RIF/Representante/Empresa/Dirección/Email o Teléfono no pueden ir vacíos , llevar caracteres especiales o no son válidos!",
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

    static public function ctrEliminarProveedor()
{
    if (isset($_GET["idCliente"])) { 

        $tabla = "proveedores";
        $rifCombinadoAEliminar = $_GET["idCliente"]; 
        $proveedorAEliminar = ModeloProveedor::mdlMostrarProveedor($tabla, "rif", $rifCombinadoAEliminar);
        
        $nombreEmpresaBorrada = $proveedorAEliminar['nombre'] ?? 'N/A';
        $prefijoTelefonoBorrado = $proveedorAEliminar['prefijo_telefono'] ?? 'N/A';
        $numeroTelefonoBorrado = $proveedorAEliminar['numero_telefono'] ?? 'N/A';
        $tipoRifBorrado = $proveedorAEliminar['tipo_rif'] ?? 'N/A';
        $numRifBorrado = $proveedorAEliminar['num_rif'] ?? 'N/A';

        $respuesta = ModeloProveedor::mdlEliminarProveedor($tabla, $rifCombinadoAEliminar);

        if ($respuesta == "ok") {

            $empleadoCedula = $_SESSION["cedula"] ?? "Sistema/Desconocido";
            $logData = array(
                "event_type" => "Proveedor Eliminado",
                "description" => "Proveedor '{$nombreEmpresaBorrada}' (RIF: {$tipoRifBorrado}-{$numRifBorrado}) eliminado. Teléfono: {$prefijoTelefonoBorrado}-{$numeroTelefonoBorrado}.",
                "employee_cedula" => $empleadoCedula,
                "affected_table" => $tabla,
                "affected_row_id" => $rifCombinadoAEliminar
            );
            ModeloEventoLog::mdlGuardarEvento($logData);

            echo '<script>
                swal({
                    type: "success",
                    title: "El proveedor ha sido borrado correctamente",
                    showConfirmButton: true,
                    confirmButtonText: "Cerrar"
                }).then(function(result){
                    if (result.value) {
                        window.location = "proveedor";
                    }
                });
            </script>';

        } else if ($respuesta == "restrict") {

            echo '<script>
                swal({
                    type: "error",
                    title: "No se puede eliminar el proveedor",
                    text: "Este proveedor tiene registros asociados (como compras) y no puede ser eliminado.",
                    showConfirmButton: true,
                    confirmButtonText: "Cerrar"
                }).then(function(result){
                    if (result.value) {
                        window.location = "proveedor";
                    }
                });
            </script>';

        } else {

            echo '<script>
                swal({
                    type: "error",
                    title: "Error al eliminar",
                    text: "Ocurrió un error inesperado al intentar eliminar el proveedor.",
                    showConfirmButton: true,
                    confirmButtonText: "Cerrar"
                }).then(function(result){
                    if (result.value) {
                        window.location = "proveedor";
                    }
                });
            </script>';

        }
    }
}

}