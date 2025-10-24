<?php

// ESTA DEBE SER LA RUTA EN tipos.controlador.php
require_once __DIR__ . "/../modelos/tipos.modelo.php";
require_once __DIR__ . "/../modelos/eventolog.modelo.php"; // ¡Importante para el log!

class ControladorTipos{

	/*=============================================
	CREAR Tipos
	=============================================*/

	static public function ctrCrearTipo(){

		if(isset($_POST["nuevaTipo"])){

			if(preg_match('/^[a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["nuevaTipo"])){

				$tabla = "tipos";

				$datos = $_POST["nuevaTipo"];

				$respuesta = ModeloTipos::mdlIngresarTipo($tabla, $datos);

				if($respuesta == "ok"){
                    // =============================================
                    // REGISTRAR CREACIÓN DE TIPO EN EL LOG
                    // =============================================
                    $empleadoCedula = (isset($_SESSION["cedula"])) ? $_SESSION["cedula"] : "Sistema/Desconocido";
                    $logData = array(
                        "event_type" => "Tipo Creado",
                        "description" => "Nuevo tipo '{$datos}' registrado.",
                        "employee_cedula" => $empleadoCedula,
                        "affected_table" => $tabla,
                        "affected_row_id" => $datos // Usamos el nombre como ID lógico si no hay un ID numérico autoincremental devuelto
                    );
                    ModeloEventoLog::mdlGuardarEvento($logData);

					echo'<script>

					swal({
						type: "success",
						title: "El tipo ha sido guardado correctamente",
						showConfirmButton: true,
						confirmButtonText: "Cerrar"
						}).then(function(result){
									if (result.value) {

									window.location = "tipos";

									}
								})

					</script>';

				}else if($respuesta == "repetido"){ // Corregí el operador de asignación '=' a comparación '=='
                    // =============================================
                    // REGISTRAR INTENTO DE CREACIÓN DE TIPO REPETIDO
                    // =============================================
                    $empleadoCedula = (isset($_SESSION["cedula"])) ? $_SESSION["cedula"] : "Sistema/Desconocido";
                    $logData = array(
                        "event_type" => "Creación Tipo Fallida",
                        "description" => "Intento de crear tipo con nombre '{$datos}' fallido por ser repetido.",
                        "employee_cedula" => $empleadoCedula,
                        "affected_table" => $tabla,
                        "affected_row_id" => $datos
                    );
                    ModeloEventoLog::mdlGuardarEvento($logData);

					echo'<script>

					swal({
						type: "error",
						title: "El tipo ya existe",
						showConfirmButton: true,
						confirmButtonText: "Cerrar"
						}).then(function(result) {
									if (result.value) {

									window.location = "tipos";

									}
								})

					</script>';
				} else {
                    // =============================================
                    // REGISTRAR FALLO DESCONOCIDO EN LA CREACIÓN DE TIPO
                    // =============================================
                    $empleadoCedula = (isset($_SESSION["cedula"])) ? $_SESSION["cedula"] : "Sistema/Desconocido";
                    $logData = array(
                        "event_type" => "Creación Tipo Fallida",
                        "description" => "Error desconocido al intentar crear el tipo '{$datos}'.",
                        "employee_cedula" => $empleadoCedula,
                        "affected_table" => $tabla,
                        "affected_row_id" => $datos
                    );
                    ModeloEventoLog::mdlGuardarEvento($logData);
                }


			}else{
                // =============================================
                // REGISTRAR INTENTO DE CREACIÓN DE TIPO CON DATOS INVÁLIDOS
                // =============================================
                $empleadoCedula = (isset($_SESSION["cedula"])) ? $_SESSION["cedula"] : "Sistema/Desconocido";
                $logData = array(
                    "event_type" => "Creación Tipo Fallida",
                    "description" => "Intento de crear tipo con datos inválidos: " . (isset($_POST["nuevaTipo"]) ? $_POST["nuevaTipo"] : 'N/A') . ".",
                    "employee_cedula" => $empleadoCedula,
                    "affected_table" => "tipos",
                    "affected_row_id" => (isset($_POST["nuevaTipo"])) ? $_POST["nuevaTipo"] : 'N/A'
                );
                ModeloEventoLog::mdlGuardarEvento($logData);

				echo'<script>

					swal({
						type: "error",
						title: "¡El tipo no puede ir vacío, llevar caracteres especiales o números!",
						showConfirmButton: true,
						confirmButtonText: "Cerrar"
						}).then(function(result){
							if (result.value) {

							window.location = "tipos";

							}
						})

			 	</script>';

			}

		}

	}

	/*=============================================
	MOSTRAR Tipos
	=============================================*/
    // Este método es solo para mostrar, no necesita logeo.
	static public function ctrMostrarTipos($item, $valor){

		$tabla = "tipos";

		$respuesta = ModeloTipos::mdlMostrarTipos($tabla, $item, $valor);

		return $respuesta;
	
	}

	/*=============================================
	EDITAR Tipo
	=============================================*/

	static public function ctrEditarTipo(){

		if(isset($_POST["editarTipo"])){

			if(preg_match('/^[a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["editarTipo"])){

				$tabla = "tipos";

				$datos = array(
                    "tipo" => $_POST["editarTipo"],
				    "id"   => $_POST["idTipo"]
                );

                // Obtener el nombre anterior del tipo para el log
                // Asegúrate de que ModeloTipos::mdlMostrarTipos puede buscar por ID y devuelve el array completo
                $tipoAnterior = ModeloTipos::mdlMostrarTipos($tabla, "id", $datos["id"]);
                $nombreTipoAnterior = (isset($tipoAnterior['tipo'])) ? $tipoAnterior['tipo'] : 'N/A';


				$respuesta = ModeloTipos::mdlEditarTipo($tabla, $datos);

				if($respuesta == "ok"){
                    // =============================================
                    // REGISTRAR EDICIÓN DE TIPO EN EL LOG
                    // =============================================
                    $empleadoCedula = (isset($_SESSION["cedula"])) ? $_SESSION["cedula"] : "Sistema/Desconocido";
                    $logData = array(
                        "event_type" => "Tipo Editado",
                        "description" => "Tipo '{$nombreTipoAnterior}' (ID: {$datos["id"]}) modificado a '{$datos["tipo"]}'.",
                        "employee_cedula" => $empleadoCedula,
                        "affected_table" => $tabla,
                        "affected_row_id" => $datos["id"]
                    );
                    ModeloEventoLog::mdlGuardarEvento($logData);

					echo'<script>

					swal({
						type: "success",
						title: "El tipo ha sido cambiado correctamente",
						showConfirmButton: true,
						confirmButtonText: "Cerrar"
						}).then(function(result){
									if (result.value) {

									window.location = "tipos";

									}
								})

					</script>';

				} else {
                    // =============================================
                    // REGISTRAR FALLO EN EDICIÓN DE TIPO
                    // =============================================
                    $empleadoCedula = (isset($_SESSION["cedula"])) ? $_SESSION["cedula"] : "Sistema/Desconocido";
                    $logData = array(
                        "event_type" => "Edición Tipo Fallida",
                        "description" => "Error al intentar modificar el tipo '{$nombreTipoAnterior}' (ID: {$datos["id"]}) a '{$datos["tipo"]}'.",
                        "employee_cedula" => $empleadoCedula,
                        "affected_table" => $tabla,
                        "affected_row_id" => $datos["id"]
                    );
                    ModeloEventoLog::mdlGuardarEvento($logData);
                }


			}else{
                // =============================================
                // REGISTRAR INTENTO DE EDICIÓN DE TIPO CON DATOS INVÁLIDOS
                // =============================================
                $empleadoCedula = (isset($_SESSION["cedula"])) ? $_SESSION["cedula"] : "Sistema/Desconocido";
                $logData = array(
                    "event_type" => "Edición Tipo Fallida",
                    "description" => "Intento de editar tipo con datos inválidos (ID: " . (isset($_POST["idTipo"]) ? $_POST["idTipo"] : 'N/A') . ", nuevo nombre: " . (isset($_POST["editarTipo"]) ? $_POST["editarTipo"] : 'N/A') . ").",
                    "employee_cedula" => $empleadoCedula,
                    "affected_table" => "tipos",
                    "affected_row_id" => (isset($_POST["idTipo"])) ? $_POST["idTipo"] : 'N/A'
                );
                ModeloEventoLog::mdlGuardarEvento($logData);

				echo'<script>

					swal({
						type: "error",
						title: "¡El tipo no puede ir vacío, llevar caracteres especiales o números!",
						showConfirmButton: true,
						confirmButtonText: "Cerrar"
						}).then(function(result){
							if (result.value) {

							window.location = "tipos";

							}
						})

			 	</script>';

			}

		}

	}

	/*=============================================
	BORRAR Tipo
	=============================================*/

	static public function ctrBorrarTipo(){

		if(isset($_GET["idTipo"])){

			$tabla ="tipos";
			$datos = $_GET["idTipo"];

            // Obtener el nombre del tipo antes de eliminar para el log
            // Asegúrate de que ModeloTipos::mdlMostrarTipos puede buscar por ID y devuelve el array completo
            $tipoAEliminar = ModeloTipos::mdlMostrarTipos($tabla, "id", $datos);
            $nombreTipoBorrado = (isset($tipoAEliminar['tipo'])) ? $tipoAEliminar['tipo'] : 'N/A';

			$respuesta = ModeloTipos::mdlBorrarTipo($tabla, $datos);

			if($respuesta == "ok"){
                // =============================================
                // REGISTRAR ELIMINACIÓN DE TIPO EN EL LOG
                // =============================================
                $empleadoCedula = (isset($_SESSION["cedula"])) ? $_SESSION["cedula"] : "Sistema/Desconocido";
                $logData = array(
                    "event_type" => "Tipo Eliminado",
                    "description" => "Tipo '{$nombreTipoBorrado}' (ID: {$datos}) eliminado.",
                    "employee_cedula" => $empleadoCedula,
                    "affected_table" => $tabla,
                    "affected_row_id" => $datos
                );
                ModeloEventoLog::mdlGuardarEvento($logData);

				echo'<script>

					swal({
						type: "success",
						title: "El Tipo ha sido borrada correctamente",
						showConfirmButton: true,
						confirmButtonText: "Cerrar"
						}).then(function(result){
									if (result.value) {

									window.location = "tipos";

									}
								})

					</script>';
			} else {
                // =============================================
                // REGISTRAR FALLO EN ELIMINACIÓN DE TIPO
                // =============================================
                $empleadoCedula = (isset($_SESSION["cedula"])) ? $_SESSION["cedula"] : "Sistema/Desconocido";
                $logData = array(
                    "event_type" => "Eliminación Tipo Fallida",
                    "description" => "Error al intentar eliminar tipo (ID: {$datos}).",
                    "employee_cedula" => $empleadoCedula,
                    "affected_table" => $tabla,
                    "affected_row_id" => $datos
                );
                ModeloEventoLog::mdlGuardarEvento($logData);
            }
		}
		
	}
}