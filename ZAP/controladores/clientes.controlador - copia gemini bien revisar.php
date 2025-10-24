<?php

require_once "modelos/clientes.modelo.php";
require_once "modelos/eventolog.modelo.php"; // ¡Añadir esta línea!

class ControladorClientes{

	/*=============================================
	CREAR CLIENTES
	=============================================*/
	static public function ctrCrearCliente(){

		if(isset($_POST["nuevoCliente"])){

			if(preg_match('/^[a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["nuevoCliente"]) &&
			   preg_match('/^[a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["nuevoApellido"]) &&
			   preg_match('/^[0-9]+$/', $_POST["nuevaCedula"]) && // Agregada validación para la cédula
               preg_match('/^[()\-0-9 ]+$/', $_POST["nuevoTelefono"]) && // Agregada validación para teléfono
               preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ.,\- ]+$/', $_POST["nuevaDireccion"]) && // Agregada validación para dirección
               preg_match('/^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/', $_POST["nuevoEmail"]) // Agregada validación para email
            )
			{
				$tabla = "clientes";

			   	$datos = array(
                    "nombre"        => $_POST["nuevoCliente"],
                    "cedula"        => $_POST["nuevaCedula"],
                    "apellido"      => $_POST["nuevoApellido"],
                    "telefono"      => $_POST["nuevoTelefono"],
                    "direccion"     => $_POST["nuevaDireccion"],
                    "email"         => $_POST["nuevoEmail"]
                );

			   	$respuesta = ModeloClientes::mdlIngresarCliente($tabla, $datos);

			   	if($respuesta == "ok"){
                    // =============================================
                    // REGISTRAR CREACIÓN DE CLIENTE EN EL LOG
                    // =============================================
                    $empleadoCedula = (isset($_SESSION["cedula"])) ? $_SESSION["cedula"] : "Sistema/Desconocido"; // Asegúrate de tener la cédula del empleado logueado en la sesión
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

				}else if($respuesta == "repetido"){ // Corregí el operador de asignación '=' a comparación '=='
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
						  title: "¡El cliente no puede ir vacío, llevar caracteres especiales o números!",
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

	/*=============================================
	EDITAR CLIENTE
	=============================================*/
	static public function ctrEditarCliente(){

		if(isset($_POST["editarCedula"])){

			if(preg_match('/^[a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["editarCliente"]) &&
			   preg_match('/^[a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["editarApellido"]) &&
			   preg_match('/^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/', $_POST["editarEmail"]) &&
			   preg_match('/^[0-9]{4}-[0-9]{7}$/', $_POST["editarTelefono"]) &&
			   preg_match('/^[#\.\-a-zA-Z0-9 ]+$/', $_POST["editarDireccion"])){

			   	$tabla = "clientes";

			   	$datos = array(
                    "cedula"    => $_POST["editarCedula"],
                    "nombre"    => $_POST["editarCliente"],
                    "apellido"  => $_POST["editarApellido"],
                    "email"     => $_POST["editarEmail"],
                    "telefono"  => $_POST["editarTelefono"],
                    "direccion" => $_POST["editarDireccion"]
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
						  title: "¡El cliente no puede ir vacío, llevar caracteres especiales o números!",
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

    /*=============================================
    ACTUALIZAR CLIENTE (Este es un método del modelo, no del controlador)
    =============================================*/
    // Se mantiene como comentario porque parece ser un error de copiado del usuario
    // Si necesitas un método de controlador para actualizar un cliente, debe estar definido dentro de la clase ControladorClientes
    /*
    static public function mdlActualizarCliente($tabla, $item1, $valor1, $valor){
        // Este método está en el modelo, no en el controlador.
        // Si hay una lógica de controlador que invoca esto para, por ejemplo, actualizar solo un estado,
        // entonces el logeo debería ir en ese controlador.
        // En este ejemplo, el método mdlActualizarCliente parece ser invocado internamente por el modelo,
        // o por otro controlador que no hemos visto. Si es llamado por un controlador, el log iría allí.
        // Por ahora, no lo modifico aquí ya que no es un controlador.
    }
    */
}