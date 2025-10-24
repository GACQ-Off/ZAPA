<?php

// ESTA DEBE SER LA RUTA EN marcas.controlador.php
require_once __DIR__ . "/../modelos/marcas.modelo.php";
require_once __DIR__ . "/../modelos/eventolog.modelo.php"; // ¡Importante para el log!

class ControladorMarcas{

	/*=============================================
	CREAR MARCAS
	=============================================*/
	static public function ctrCrearMarca(){

		if(isset($_POST["nuevaMarca"])){

			if(preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["nuevaMarca"])){

				$tabla = "marcas";

				$datos = $_POST["nuevaMarca"];

				$respuesta = ModeloMarcas::mdlIngresarMarca($tabla, $datos);

				if($respuesta == "ok"){
                    // =============================================
                    // REGISTRAR CREACIÓN DE MARCA EN EL LOG
                    // =============================================
                    $empleadoCedula = (isset($_SESSION["cedula"])) ? $_SESSION["cedula"] : "Sistema/Desconocido";
                    $logData = array(
                        "event_type" => "Marca Creada",
                        "description" => "Nueva marca '{$datos}' registrada.",
                        "employee_cedula" => $empleadoCedula,
                        "affected_table" => $tabla,
                        "affected_row_id" => $datos // Usamos el nombre como ID lógico si no hay un ID numérico autoincremental
                    );
                    ModeloEventoLog::mdlGuardarEvento($logData);

					echo'<script>

					swal({
						type: "success",
						title: "La marca ha sido guardada correctamente",
						showConfirmButton: true,
						confirmButtonText: "Cerrar"
						}).then(function(result){
									if (result.value) {

									window.location = "marcas";

									}
								})

					</script>';

				}else if($respuesta == "repetido"){ // Corregí el operador de asignación '=' a comparación '=='
                    // =============================================
                    // REGISTRAR INTENTO DE CREACIÓN DE MARCA REPETIDA
                    // =============================================
                    $empleadoCedula = (isset($_SESSION["cedula"])) ? $_SESSION["cedula"] : "Sistema/Desconocido";
                    $logData = array(
                        "event_type" => "Creación Marca Fallida",
                        "description" => "Intento de crear marca con nombre '{$datos}' fallido por ser repetida.",
                        "employee_cedula" => $empleadoCedula,
                        "affected_table" => $tabla,
                        "affected_row_id" => $datos
                    );
                    ModeloEventoLog::mdlGuardarEvento($logData);

					echo'<script>

					swal({
						type: "error",
						title: "La marca ya existe",
						showConfirmButton: true,
						confirmButtonText: "Cerrar"
						}).then(function(result) {
									if (result.value) {

									window.location = "marcas";

									}
								})

					</script>';
				} else {
                    // =============================================
                    // REGISTRAR FALLO DESCONOCIDO EN LA CREACIÓN DE MARCA
                    // =============================================
                    $empleadoCedula = (isset($_SESSION["cedula"])) ? $_SESSION["cedula"] : "Sistema/Desconocido";
                    $logData = array(
                        "event_type" => "Creación Marca Fallida",
                        "description" => "Error desconocido al intentar crear la marca '{$datos}'.",
                        "employee_cedula" => $empleadoCedula,
                        "affected_table" => $tabla,
                        "affected_row_id" => $datos
                    );
                    ModeloEventoLog::mdlGuardarEvento($logData);
                }


			}else{
                // =============================================
                // REGISTRAR INTENTO DE CREACIÓN DE MARCA CON DATOS INVÁLIDOS
                // =============================================
                $empleadoCedula = (isset($_SESSION["cedula"])) ? $_SESSION["cedula"] : "Sistema/Desconocido";
                $logData = array(
                    "event_type" => "Creación Marca Fallida",
                    "description" => "Intento de crear marca con datos inválidos: " . (isset($_POST["nuevaMarca"]) ? $_POST["nuevaMarca"] : 'N/A') . ".",
                    "employee_cedula" => $empleadoCedula,
                    "affected_table" => "marcas",
                    "affected_row_id" => (isset($_POST["nuevaMarca"])) ? $_POST["nuevaMarca"] : 'N/A'
                );
                ModeloEventoLog::mdlGuardarEvento($logData);

				echo'<script>

					swal({
						type: "error",
						title: "¡La marca no puede ir vacía o llevar caracteres especiales!",
						showConfirmButton: true,
						confirmButtonText: "Cerrar"
						}).then(function(result){
							if (result.value) {

							window.location = "marcas";

							}
						})

			 	</script>';

			}

		}

	}

	/*=============================================
	MOSTRAR MARCAS
	=============================================*/
    // Este método es solo para mostrar, no necesita logeo.
	static public function ctrMostrarMarcas($item, $valor){

		$tabla = "marcas";

		$respuesta = ModeloMarcas::mdlMostrarMarcas($tabla, $item, $valor);

		return $respuesta;
	
	}

	/*=============================================
	EDITAR MARCA
	=============================================*/

	static public function ctrEditarMarca(){

		if(isset($_POST["editarMarca"])){

			if(preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["editarMarca"])){

				$tabla = "marcas";

				$datos = array(
                    "marca" => $_POST["editarMarca"],
				    "id"    => $_POST["idMarca"]
                );

                // Obtener el nombre anterior de la marca para el log
                // Asegúrate de que ModeloMarcas::mdlMostrarMarcas puede buscar por ID y devuelve el array completo
                $marcaAnterior = ModeloMarcas::mdlMostrarMarcas($tabla, "id", $datos["id"]);
                $nombreMarcaAnterior = (isset($marcaAnterior['marca'])) ? $marcaAnterior['marca'] : 'N/A';


				$respuesta = ModeloMarcas::mdlEditarMarca($tabla, $datos);

				if($respuesta == "ok"){
                    // =============================================
                    // REGISTRAR EDICIÓN DE MARCA EN EL LOG
                    // =============================================
                    $empleadoCedula = (isset($_SESSION["cedula"])) ? $_SESSION["cedula"] : "Sistema/Desconocido";
                    $logData = array(
                        "event_type" => "Marca Editada",
                        "description" => "Marca '{$nombreMarcaAnterior}' (ID: {$datos["id"]}) modificada a '{$datos["marca"]}'.",
                        "employee_cedula" => $empleadoCedula,
                        "affected_table" => $tabla,
                        "affected_row_id" => $datos["id"]
                    );
                    ModeloEventoLog::mdlGuardarEvento($logData);

					echo'<script>

					swal({
						type: "success",
						title: "La Marca ha sido cambiada correctamente",
						showConfirmButton: true,
						confirmButtonText: "Cerrar"
						}).then(function(result){
									if (result.value) {

									window.location = "marcas";

									}
								})

					</script>';

				} else {
                    // =============================================
                    // REGISTRAR FALLO EN EDICIÓN DE MARCA
                    // =============================================
                    $empleadoCedula = (isset($_SESSION["cedula"])) ? $_SESSION["cedula"] : "Sistema/Desconocido";
                    $logData = array(
                        "event_type" => "Edición Marca Fallida",
                        "description" => "Error al intentar modificar la marca '{$nombreMarcaAnterior}' (ID: {$datos["id"]}) a '{$datos["marca"]}'.",
                        "employee_cedula" => $empleadoCedula,
                        "affected_table" => $tabla,
                        "affected_row_id" => $datos["id"]
                    );
                    ModeloEventoLog::mdlGuardarEvento($logData);
                }


			}else{
                // =============================================
                // REGISTRAR INTENTO DE EDICIÓN DE MARCA CON DATOS INVÁLIDOS
                // =============================================
                $empleadoCedula = (isset($_SESSION["cedula"])) ? $_SESSION["cedula"] : "Sistema/Desconocido";
                $logData = array(
                    "event_type" => "Edición Marca Fallida",
                    "description" => "Intento de editar marca con datos inválidos (ID: " . (isset($_POST["idMarca"]) ? $_POST["idMarca"] : 'N/A') . ", nuevo nombre: " . (isset($_POST["editarMarca"]) ? $_POST["editarMarca"] : 'N/A') . ").",
                    "employee_cedula" => $empleadoCedula,
                    "affected_table" => "marcas",
                    "affected_row_id" => (isset($_POST["idMarca"])) ? $_POST["idMarca"] : 'N/A'
                );
                ModeloEventoLog::mdlGuardarEvento($logData);

				echo'<script>

					swal({
						type: "error",
						title: "¡La marca no puede ir vacía o llevar caracteres especiales!",
						showConfirmButton: true,
						confirmButtonText: "Cerrar"
						}).then(function(result){
							if (result.value) {

							window.location = "marcas";

							}
						})

			 	</script>';

			}

		}

	}

	/*=============================================
	BORRAR MARCA
	=============================================*/

	static public function ctrBorrarMarca(){

		if(isset($_GET["idMarca"])){

			$tabla ="marcas";
			$datos = $_GET["idMarca"];

            // Obtener el nombre de la marca antes de eliminar para el log
            // Asegúrate de que ModeloMarcas::mdlMostrarMarcas puede buscar por ID y devuelve el array completo
            $marcaAEliminar = ModeloMarcas::mdlMostrarMarcas($tabla, "id", $datos);
            $nombreMarcaBorrada = (isset($marcaAEliminar['marca'])) ? $marcaAEliminar['marca'] : 'N/A';

			$respuesta = ModeloMarcas::mdlBorrarMarca($tabla, $datos);

			if($respuesta == "ok"){
                // =============================================
                // REGISTRAR ELIMINACIÓN DE MARCA EN EL LOG
                // =============================================
                $empleadoCedula = (isset($_SESSION["cedula"])) ? $_SESSION["cedula"] : "Sistema/Desconocido";
                $logData = array(
                    "event_type" => "Marca Eliminada",
                    "description" => "Marca '{$nombreMarcaBorrada}' (ID: {$datos}) eliminada.",
                    "employee_cedula" => $empleadoCedula,
                    "affected_table" => $tabla,
                    "affected_row_id" => $datos
                );
                ModeloEventoLog::mdlGuardarEvento($logData);

				echo'<script>

					swal({
						type: "success",
						title: "La marca ha sido borrada correctamente",
						showConfirmButton: true,
						confirmButtonText: "Cerrar"
						}).then(function(result){
									if (result.value) {

									window.location = "marcas";

									}
								})

					</script>';
			} else {
                // =============================================
                // REGISTRAR FALLO EN ELIMINACIÓN DE MARCA
                // =============================================
                $empleadoCedula = (isset($_SESSION["cedula"])) ? $_SESSION["cedula"] : "Sistema/Desconocido";
                $logData = array(
                    "event_type" => "Eliminación Marca Fallida",
                    "description" => "Error al intentar eliminar marca (ID: {$datos}).",
                    "employee_cedula" => $empleadoCedula,
                    "affected_table" => $tabla,
                    "affected_row_id" => $datos
                );
                ModeloEventoLog::mdlGuardarEvento($logData);
            }
		}
		
	}
}