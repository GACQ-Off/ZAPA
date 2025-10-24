<?php


// Rutas corregidas usando __DIR__ para mayor robustez
require_once __DIR__ . "/../modelos/empleados.modelo.php";
require_once __DIR__ . "/../modelos/eventolog.modelo.php";


class ControladorEmpleados{

	/*=============================================
	REGISTRO DE EMPLEADO
	=============================================*/
	static public function ctrCrearEmpleado(){

		if(isset($_POST["nuevaCedula"])){

			// LÍNEAS CORREGIDAS PARA ELIMINAR CARACTERES INVISIBLES
			if(preg_match('/^[a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["nuevoNombre"]) &&
			   preg_match('/^[a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["nuevoApellido"]) &&	
			   preg_match('/^[a-zA-ZñÑáéíóúÁÉÍÓÚ0-9 º#-]+$/', $_POST["nuevaLocalizacion"]) &&
			   preg_match('/^[VEve]{1}-[0-9]{7,8}$/', $_POST["nuevaCedula"]) &&
			   preg_match('/^[0-9+]+$/', $_POST["prefijo_telefono"]) &&
			   preg_match('/^[0-9]+$/', $_POST["numero_telefono"])){

				/*=============================================
				VALIDAR IMAGEN
				=============================================*/
				$ruta = "";

				if(isset($_FILES["nuevaFoto"]["tmp_name"]) && !empty($_FILES["nuevaFoto"]["tmp_name"])){

					list($ancho, $alto) = getimagesize($_FILES["nuevaFoto"]["tmp_name"]);

					$nuevoAncho = 500;
					$nuevoAlto = 500;

					$directorio = "vistas/img/personal/".$_POST["nuevaCedula"];

					if (!is_dir($directorio)) {
						if (!mkdir($directorio, 0755, true)) {
							error_log("Error al crear el directorio: " . $directorio);
						}
					}
					
					if($_FILES["nuevaFoto"]["type"] == "image/jpeg"){
						$aleatorio = mt_rand(100,999);
						$ruta = "vistas/img/personal/".$_POST["nuevaCedula"]."/".$aleatorio.".jpg";
						$origen = imagecreatefromjpeg($_FILES["nuevaFoto"]["tmp_name"]);						
						$destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);
						imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);
						imagejpeg($destino, $ruta);
					}

					if($_FILES["nuevaFoto"]["type"] == "image/png"){
						$aleatorio = mt_rand(100,999);
						$ruta = "vistas/img/personal/".$_POST["nuevaCedula"]."/".$aleatorio.".png";
						$origen = imagecreatefrompng($_FILES["nuevaFoto"]["tmp_name"]);						
						$destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);
						imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);
						imagepng($destino, $ruta);
					}
				}

				$tabla = "empleado";
				
				$datos = array("cedula" => $_POST["nuevaCedula"],
					"nombre" => $_POST["nuevoNombre"],
					"apellido" => $_POST["nuevoApellido"],
					"prefijo_telefono" => $_POST["prefijo_telefono"],
					"numero_telefono" => $_POST["numero_telefono"],
					"direccion" => $_POST["nuevaLocalizacion"],
					"foto"=>$ruta);


				$respuesta = ModeloEmpleados::mdlIngresarEmpleado($tabla, $datos);
			
				if($respuesta == "ok"){
					// REGISTRAR CREACIÓN DE EMPLEADO EN EL LOG
					$empleadoCedulaLog = (isset($_SESSION["cedula"])) ? $_SESSION["cedula"] : "Sistema/Desconocido";
					$logData = array(
						"event_type" => "Empleado Creado",
						"description" => "Nuevo empleado '{$datos["nombre"]} {$datos["apellido"]}' (Cédula: {$datos["cedula"]}) registrado con éxito.",
						"employee_cedula" => $empleadoCedulaLog,
						"affected_table" => "empleado",
						"affected_row_id" => $datos["cedula"]
					);
					ModeloEventoLog::mdlGuardarEvento($logData);

					echo '<script>
					swal({
						type: "success",
						title: "¡El empleado ha sido guardado correctamente!",
						showConfirmButton: true,
						confirmButtonText: "Cerrar"
					}).then(function(result){
						if(result.value){
							window.location = "personal";
						}
					});
					</script>';

				} else if($respuesta == "repetido"){
					// REGISTRAR INTENTO DE CREACIÓN DE EMPLEADO REPETIDO
					$empleadoCedulaLog = (isset($_SESSION["cedula"])) ? $_SESSION["cedula"] : "Sistema/Desconocido";
					$logData = array(
						"event_type" => "Creación Empleado Fallida",
						"description" => "Intento de crear empleado con cédula '{$datos["cedula"]}' fallido: Cédula ya existe.",
						"employee_cedula" => $empleadoCedulaLog,
						"affected_table" => "empleado",
						"affected_row_id" => $datos["cedula"]
					);
					ModeloEventoLog::mdlGuardarEvento($logData);

					echo'<script>
					swal({
						type: "error",
						title: "La cédula del empleado ya existe",
						showConfirmButton: true,
						confirmButtonText: "Cerrar"
					}).then(function(result) {
						if (result.value) {
							window.location = "personal";
						}
					})
					</script>';
				} else {
					// REGISTRAR FALLO GENÉRICO EN CREACIÓN DE EMPLEADO
					$empleadoCedulaLog = (isset($_SESSION["cedula"])) ? $_SESSION["cedula"] : "Sistema/Desconocido";
					$logData = array(
						"event_type" => "Creación Empleado Fallida",
						"description" => "Error desconocido al intentar crear el empleado (Cédula: " . (isset($datos["cedula"]) ? $datos["cedula"] : 'N/A') . ").",
						"employee_cedula" => $empleadoCedulaLog,
						"affected_table" => "empleado",
						"affected_row_id" => (isset($datos["cedula"])) ? $datos["cedula"] : 'N/A'
					);
					ModeloEventoLog::mdlGuardarEvento($logData);
				}


			}else{
				// REGISTRAR INTENTO DE CREACIÓN DE EMPLEADO CON DATOS INVÁLIDOS
				$empleadoCedulaLog = (isset($_SESSION["cedula"])) ? $_SESSION["cedula"] : "Sistema/Desconocido";
				$logData = array(
					"event_type" => "Creación Empleado Fallida",
					"description" => "Intento de crear empleado con datos inválidos. Validación de campos fallida.",
					"employee_cedula" => $empleadoCedulaLog,
					"affected_table" => "empleado",
					"affected_row_id" => (isset($_POST["nuevaCedula"])) ? $_POST["nuevaCedula"] : 'N/A'
				);
				ModeloEventoLog::mdlGuardarEvento($logData);

				echo '<script>
					swal({
						type: "error",
						title: "¡El Empleado no puede ir vacío, llevar caracteres especiales o números! O formatos de cédula/teléfono incorrectos.",
						showConfirmButton: true,
						confirmButtonText: "Cerrar"
					}).then(function(result){
						if(result.value){
							window.location = "personal";
						}
					});
				</script>';

			}
		}
	}

	/*=============================================
	MOSTRAR EMPLEADO
	=============================================*/
	static public function ctrMostrarEmpleados($item, $valor){
		$tabla = "empleado";
		$respuesta = ModeloEmpleados::MdlMostrarEmpleados($tabla, $item, $valor);
		return $respuesta;
	}

	/*=============================================
	EDITAR EMPLEADO
	=============================================*/
	
	static public function ctrEditarEmpleado(){
		if(isset($_POST["editarCedula"])){

			// LÍNEAS CORREGIDAS PARA ELIMINAR CARACTERES INVISIBLES
			if(preg_match('/^[a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["editarNombre"])&&	
			   preg_match('/^[a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["editarApellido"]) &&	
			   preg_match('/^[VEve]{1}-[0-9]{7,8}$/', $_POST["editarCedula"]) &&
			   preg_match('/^[0-9+]+$/', $_POST["clieditarPrefijoTelefono"]) &&
			   preg_match('/^[0-9]+$/', $_POST["clieditarRestoTelefono"])){
		
				/*=============================================
				VALIDAR IMAGEN
				=============================================*/
				$empleadoActual = ControladorEmpleados::ctrMostrarEmpleados("cedula", $_POST["editarCedula"]);
				$ruta = $empleadoActual["foto"];

				if(isset($_FILES["editarFoto"]["tmp_name"]) && !empty($_FILES["editarFoto"]["tmp_name"])){

					list($ancho, $alto) = getimagesize($_FILES["editarFoto"]["tmp_name"]);

					$nuevoAncho = 500;
					$nuevoAlto = 500;

					$directorio = "vistas/img/personal/".$_POST["editarCedula"];

					if(!empty($_POST["fotoActual"]) && file_exists($_POST["fotoActual"]) && $_POST["fotoActual"] != "vistas/img/default/default.png"){
						unlink($_POST["fotoActual"]);
					}
					
					if (!is_dir($directorio)) {
						if (!mkdir($directorio, 0755, true)) {
							error_log("Error al crear el directorio: " . $directorio);
						}
					}	

					if($_FILES["editarFoto"]["type"] == "image/jpeg"){
						$aleatorio = mt_rand(100,999);
						$ruta = "vistas/img/personal/".$_POST["editarCedula"]."/".$aleatorio.".jpg";
						$origen = imagecreatefromjpeg($_FILES["editarFoto"]["tmp_name"]);						
						$destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);
						imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);
						imagejpeg($destino, $ruta);
					}

					if($_FILES["editarFoto"]["type"] == "image/png"){
						$aleatorio = mt_rand(100,999);
						$ruta = "vistas/img/personal/".$_POST["editarCedula"]."/".$aleatorio.".png";
						$origen = imagecreatefrompng($_FILES["editarFoto"]["tmp_name"]);						
						$destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);
						imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);
						imagepng($destino, $ruta);
					}
				}

				$tabla = "empleado";
				
				$datos = array("nombre" => $_POST["editarNombre"],
							   "apellido" => $_POST["editarApellido"],
							   "cedula" => $_POST["editarCedula"],
							   "prefijo_telefono" => $_POST["clieditarPrefijoTelefono"],
							   "numero_telefono" => $_POST["clieditarRestoTelefono"],
							   "direccion" => $_POST["editarLocalizacion"],
							   "foto" => $ruta);

				$respuesta = ModeloEmpleados::mdlEdiarEmpleado($tabla, $datos);

				if($respuesta == "ok"){
					// REGISTRAR EDICIÓN DE EMPLEADO EN EL LOG
					$empleadoCedulaLog = (isset($_SESSION["cedula"])) ? $_SESSION["cedula"] : "Sistema/Desconocido";
					$logData = array(
						"event_type" => "Empleado Editado",
						"description" => "Datos del empleado '{$datos["nombre"]} {$datos["apellido"]}' (Cédula: {$datos["cedula"]}) modificados con éxito.",
						"employee_cedula" => $empleadoCedulaLog,
						"affected_table" => "empleado",
						"affected_row_id" => $datos["cedula"]
					);
					ModeloEventoLog::mdlGuardarEvento($logData);

					echo'<script>
					swal({
						type: "success",
						title: "El empleado ha sido editado correctamente",
						showConfirmButton: true,
						confirmButtonText: "Cerrar"
					}).then(function(result) {
						if (result.value) {
							window.location = "personal";
						}
					})
					</script>';

				} else {
					// REGISTRAR FALLO EN EDICIÓN DE EMPLEADO
					$empleadoCedulaLog = (isset($_SESSION["cedula"])) ? $_SESSION["cedula"] : "Sistema/Desconocido";
					$logData = array(
						"event_type" => "Edición Empleado Fallida",
						"description" => "Error al intentar modificar datos del empleado.",
						"employee_cedula" => $empleadoCedulaLog,
						"affected_table" => "empleado",
						"affected_row_id" => (isset($datos["cedula"])) ? $datos["cedula"] : 'N/A'
					);
					ModeloEventoLog::mdlGuardarEvento($logData);

					echo'<script>
					swal({
						type: "error",
						title: "Ha ocurrido un error al editar el empleado o la cédula ya existe.",
						showConfirmButton: true,
						confirmButtonText: "Cerrar"
					}).then(function(result) {
						if (result.value) {
							window.location = "personal";
						}
					})
					</script>';
				}


			}else{
				// REGISTRAR INTENTO DE EDICIÓN DE EMPLEADO CON DATOS INVÁLIDOS
				$empleadoCedulaLog = (isset($_SESSION["cedula"])) ? $_SESSION["cedula"] : "Sistema/Desconocido";
				$logData = array(
					"event_type" => "Edición Empleado Fallida",
					"description" => "Intento de editar empleado con datos inválidos.",
					"employee_cedula" => $empleadoCedulaLog,
					"affected_table" => "empleado",
					"affected_row_id" => (isset($_POST["editarCedula"])) ? $_POST["editarCedula"] : 'N/A'
				);
				ModeloEventoLog::mdlGuardarEvento($logData);

				echo'<script>
					swal({
						type: "error",
						title: "¡Los campos de edición no pueden ir vacíos, llevar caracteres especiales o números! O formatos de cédula/teléfono incorrectos.",
						showConfirmButton: true,
						confirmButtonText: "Cerrar"
					}).then(function(result) {
						if (result.value) {
							window.location = "personal";
						}
					})
			 	</script>';
			}
		}
	}

	/*=============================================
	BORRAR EMPLEADO
	=============================================*/
	static public function ctrBorrarUsuario(){
		if(isset($_GET["idUsuario"])){

			$tabla ="empleado";
			$datos = $_GET["idUsuario"];

			$empleadoAEliminar = ControladorEmpleados::ctrMostrarEmpleados("cedula", $datos);
			$nombreEmpleadoBorrado = (isset($empleadoAEliminar['nombre'])) ? $empleadoAEliminar['nombre'] : 'N/A';
			$apellidoEmpleadoBorrado = (isset($empleadoAEliminar['apellido'])) ? $empleadoAEliminar['apellido'] : 'N/A';
			$cedulaEmpleadoBorrado = (isset($empleadoAEliminar['cedula'])) ? $empleadoAEliminar['cedula'] : 'N/A';

			// Borrado de archivos y directorios
			if (!empty($empleadoAEliminar["foto"]) && file_exists($empleadoAEliminar["foto"]) && $empleadoAEliminar["foto"] != "vistas/img/default/default.png") {
				unlink($empleadoAEliminar["foto"]);
			}
			$directorioEmpleado = "vistas/img/personal/".$cedulaEmpleadoBorrado;
			if (is_dir($directorioEmpleado) && count(scandir($directorioEmpleado)) == 2) {
				rmdir($directorioEmpleado);
			}


			$respuesta = ModeloEmpleados::mdlBorrarEmpleado($tabla, $datos);

			if($respuesta == "ok"){
				// LOG DE ÉXITO
				$empleadoCedulaLog = (isset($_SESSION["cedula"])) ? $_SESSION["cedula"] : "Sistema/Desconocido";
				$logData = array(
					"event_type" => "Empleado Eliminado",
					"description" => "Empleado '{$nombreEmpleadoBorrado} {$apellidoEmpleadoBorrado}' (Cédula: {$cedulaEmpleadoBorrado}) eliminado con éxito.",
					"employee_cedula" => $empleadoCedulaLog,
					"affected_table" => "empleado",
					"affected_row_id" => $cedulaEmpleadoBorrado
				);
				ModeloEventoLog::mdlGuardarEvento($logData);

				echo'<script>
				swal({
					type: "success",
					title: "El empleado ha sido borrado correctamente",
					showConfirmButton: true,
					confirmButtonText: "Cerrar",
					closeOnConfirm: false
				}).then(function(result) {
					if (result.value) {
						window.location = "personal";
					}
				})
				</script>';

			} else if ($respuesta == "fk_fail") { // DETECCIÓN DE FALLO FK

				// LOG DE FALLO POR CLAVE FORÁNEA
				$empleadoCedulaLog = (isset($_SESSION["cedula"])) ? $_SESSION["cedula"] : "Sistema/Desconocido";
				$logData = array(
					"event_type" => "Eliminación Empleado Fallida",
					"description" => "Fallo de clave foránea al intentar eliminar empleado (Cédula: {$cedulaEmpleadoBorrado}).",
					"employee_cedula" => $empleadoCedulaLog,
					"affected_table" => "empleado",
					"affected_row_id" => $cedulaEmpleadoBorrado
				);
				ModeloEventoLog::mdlGuardarEvento($logData);

				// ALERTA ESPECÍFICA DE NO SE PUEDE BORRAR
				echo'<script>
				swal({
					type: "error",
					title: "¡No se puede borrar el Empleado! ",
					text: "El empleado tiene registros de actividad asociados (ej. en la tabla `event_log`). Debe eliminar esos registros primero.",
					showConfirmButton: true,
					confirmButtonText: "Cerrar"
				}).then(function(result) {
					if (result.value) {
						window.location = "personal";
					}
				})
				</script>';

			} else {
				// LOG DE FALLO GENÉRICO
				$empleadoCedulaLog = (isset($_SESSION["cedula"])) ? $_SESSION["cedula"] : "Sistema/Desconocido";
				$logData = array(
					"event_type" => "Eliminación Empleado Fallida",
					"description" => "Error desconocido al intentar eliminar empleado (Cédula: {$cedulaEmpleadoBorrado}).",
					"employee_cedula" => $empleadoCedulaLog,
					"affected_table" => "empleado",
					"affected_row_id" => $cedulaEmpleadoBorrado
				);
				ModeloEventoLog::mdlGuardarEvento($logData);

				echo'<script>
				swal({
					type: "error",
					title: "Ocurrió un error al intentar borrar el empleado.",
					showConfirmButton: true,
					confirmButtonText: "Cerrar"
				}).then(function(result) {
					if (result.value) {
						window.location = "personal";
					}
				})
				</script>';
			}
		}
	}
}