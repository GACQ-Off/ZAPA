<?php

// ESTA DEBE SER LA RUTA EN categorias.controlador.php
require_once __DIR__ . "/../modelos/categorias.modelo.php";
require_once __DIR__ . "/../modelos/eventolog.modelo.php"; // ¡Importante para el log!

class ControladorCategorias{

	/*=============================================
	CREAR CATEGORIAS
	=============================================*/
	static public function ctrCrearCategoria(){

		if(isset($_POST["nuevaCategoria"])){

			if(preg_match('/^[a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["nuevaCategoria"])){

				$tabla = "categorias";

				$datos = $_POST["nuevaCategoria"];

				$respuesta = ModeloCategorias::mdlIngresarCategoria($tabla, $datos);

				if($respuesta == "ok"){
                    // =============================================
                    // REGISTRAR CREACIÓN DE CATEGORÍA EN EL LOG
                    // =============================================
                    $empleadoCedula = (isset($_SESSION["cedula"])) ? $_SESSION["cedula"] : "Sistema/Desconocido";
                    $logData = array(
                        "event_type" => "Categoría Creada",
                        "description" => "Nueva categoría '{$datos}' registrada.",
                        "employee_cedula" => $empleadoCedula,
                        "affected_table" => $tabla,
                        "affected_row_id" => $datos // En este caso, el nombre de la categoría es el ID lógico
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

									window.location = "categorias";

									}
								})

					</script>';

				}else if($respuesta == "repetido"){ // Corregí el operador de asignación '=' a comparación '=='
                    // =============================================
                    // REGISTRAR INTENTO DE CREACIÓN DE CATEGORÍA REPETIDA
                    // =============================================
                    $empleadoCedula = (isset($_SESSION["cedula"])) ? $_SESSION["cedula"] : "Sistema/Desconocido";
                    $logData = array(
                        "event_type" => "Creación Categoría Fallida",
                        "description" => "Intento de crear categoría con nombre '{$datos}' fallido por ser repetida.",
                        "employee_cedula" => $empleadoCedula,
                        "affected_table" => $tabla,
                        "affected_row_id" => $datos
                    );
                    ModeloEventoLog::mdlGuardarEvento($logData);

					echo'<script>

					swal({
						type: "error",
						title: "La categoria ya existe",
						showConfirmButton: true,
						confirmButtonText: "Cerrar"
						}).then(function(result) {
									if (result.value) {

									window.location = "categorias";

									}
								})

					</script>';
				} else {
                    // =============================================
                    // REGISTRAR FALLO DESCONOCIDO EN LA CREACIÓN DE CATEGORÍA
                    // =============================================
                    $empleadoCedula = (isset($_SESSION["cedula"])) ? $_SESSION["cedula"] : "Sistema/Desconocido";
                    $logData = array(
                        "event_type" => "Creación Categoría Fallida",
                        "description" => "Error desconocido al intentar crear la categoría '{$datos}'.",
                        "employee_cedula" => $empleadoCedula,
                        "affected_table" => $tabla,
                        "affected_row_id" => $datos
                    );
                    ModeloEventoLog::mdlGuardarEvento($logData);
                }


			}else{
                // =============================================
                // REGISTRAR INTENTO DE CREACIÓN DE CATEGORÍA CON DATOS INVÁLIDOS
                // =============================================
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
						title: "¡La categoría no puede ir vacía, llevar caracteres especiales o números!",
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
	MOSTRAR CATEGORIAS
	=============================================*/
    // Este método es solo para mostrar, no necesita logeo.
	static public function ctrMostrarCategorias($item, $valor){

		$tabla = "categorias";

		$respuesta = ModeloCategorias::mdlMostrarCategorias($tabla, $item, $valor);

		return $respuesta;
	
	}

	/*=============================================
	EDITAR CATEGORIA
	=============================================*/
	static public function ctrEditarCategoria(){

		if(isset($_POST["editarCategoria"])){

			if(preg_match('/^[a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["editarCategoria"])){

				$tabla = "categorias";

				$datos = array(
                    "categoria" => $_POST["editarCategoria"],
				    "id"        => $_POST["idCategoria"]
                );

                // Obtener el nombre anterior de la categoría para el log
                $categoriaAnterior = ModeloCategorias::mdlMostrarCategorias($tabla, "id", $datos["id"]);
                $nombreCategoriaAnterior = (isset($categoriaAnterior['categoria'])) ? $categoriaAnterior['categoria'] : 'N/A';


				$respuesta = ModeloCategorias::mdlEditarCategoria($tabla, $datos);

				if($respuesta == "ok"){
                    // =============================================
                    // REGISTRAR EDICIÓN DE CATEGORÍA EN EL LOG
                    // =============================================
                    $empleadoCedula = (isset($_SESSION["cedula"])) ? $_SESSION["cedula"] : "Sistema/Desconocido";
                    $logData = array(
                        "event_type" => "Categoría Editada",
                        "description" => "Categoría '{$nombreCategoriaAnterior}' (ID: {$datos["id"]}) modificada a '{$datos["categoria"]}'.",
                        "employee_cedula" => $empleadoCedula,
                        "affected_table" => $tabla,
                        "affected_row_id" => $datos["id"]
                    );
                    ModeloEventoLog::mdlGuardarEvento($logData);

					echo'<script>

					swal({
						type: "success",
						title: "La categoría ha sido cambiada correctamente",
						showConfirmButton: true,
						confirmButtonText: "Cerrar"
						}).then(function(result){
									if (result.value) {

									window.location = "categorias";

									}
								})

					</script>';

				} else {
                    // =============================================
                    // REGISTRAR FALLO EN EDICIÓN DE CATEGORÍA
                    // =============================================
                    $empleadoCedula = (isset($_SESSION["cedula"])) ? $_SESSION["cedula"] : "Sistema/Desconocido";
                    $logData = array(
                        "event_type" => "Edición Categoría Fallida",
                        "description" => "Error al intentar modificar la categoría '{$nombreCategoriaAnterior}' (ID: {$datos["id"]}) a '{$datos["categoria"]}'.",
                        "employee_cedula" => $empleadoCedula,
                        "affected_table" => $tabla,
                        "affected_row_id" => $datos["id"]
                    );
                    ModeloEventoLog::mdlGuardarEvento($logData);
                }


			}else{
                // =============================================
                // REGISTRAR INTENTO DE EDICIÓN DE CATEGORÍA CON DATOS INVÁLIDOS
                // =============================================
                $empleadoCedula = (isset($_SESSION["cedula"])) ? $_SESSION["cedula"] : "Sistema/Desconocido";
                $logData = array(
                    "event_type" => "Edición Categoría Fallida",
                    "description" => "Intento de editar categoría con datos inválidos (ID: " . (isset($_POST["idCategoria"]) ? $_POST["idCategoria"] : 'N/A') . ", nuevo nombre: " . (isset($_POST["editarCategoria"]) ? $_POST["editarCategoria"] : 'N/A') . ").",
                    "employee_cedula" => $empleadoCedula,
                    "affected_table" => "categorias",
                    "affected_row_id" => (isset($_POST["idCategoria"])) ? $_POST["idCategoria"] : 'N/A'
                );
                ModeloEventoLog::mdlGuardarEvento($logData);

				echo'<script>

					swal({
						type: "error",
						title: "¡La categoría no puede ir vacía , llevar caracteres especiales o números!",
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
	BORRAR CATEGORIA
	=============================================*/
	static public function ctrBorrarCategoria(){

		if(isset($_GET["idCategoria"])){

			$tabla ="categorias";
			$datos = $_GET["idCategoria"];

            // Obtener el nombre de la categoría antes de eliminar para el log
            $categoriaAEliminar = ModeloCategorias::mdlMostrarCategorias($tabla, "id", $datos);
            $nombreCategoriaBorrada = (isset($categoriaAEliminar['categoria'])) ? $categoriaAEliminar['categoria'] : 'N/A';

			$respuesta = ModeloCategorias::mdlBorrarCategoria($tabla, $datos);

			if($respuesta == "ok"){
                // =============================================
                // REGISTRAR ELIMINACIÓN DE CATEGORÍA EN EL LOG
                // =============================================
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
                // =============================================
                // REGISTRAR FALLO EN ELIMINACIÓN DE CATEGORÍA
                // =============================================
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