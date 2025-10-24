<?php
ini_set('display_errors',1);
error_reporting(E_ALL);

class ControladorColores{

	/*=============================================
	CREAR TipoS
	=============================================*/

	static public function ctrCrearColor(){

		if(isset($_POST["nuevaColor"])){

			if(preg_match('/^[a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["nuevaColor"])){

				$tabla = "colores";

				$datos = $_POST["nuevaColor"];

				$respuesta = ModeloColores::mdlIngresarColor($tabla, $datos);

				if($respuesta == "ok"){

					echo'<script>

					swal({
						  type: "success",
						  title: "El Color ha sido guardada correctamente",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
									if (result.value) {

									window.location = "colores";

									}
								})

					</script>';

				}else if($respuesta = "repetido"){
					echo'<script>

					swal({
						  type: "error",
						  title: "El color ya existe",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result) {
									if (result.value) {

									window.location = "colores";

									}
								})

					</script>';
				}


			}else{

				echo'<script>

					swal({
						  type: "error",
						  title: "¡El Color no puede ir vacío , llevar caracteres especiales o números!",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
							if (result.value) {

							window.location = "colores";

							}
						})

			  	</script>';

			}

		}

	}

	/*=============================================
	MOSTRAR TipoS
	=============================================*/

	static public function ctrMostrarColor($item, $valor){

		$tabla = "colores";

		$respuesta = ModeloColores::mdlMostrarColores($tabla, $item, $valor);

		return $respuesta;
	
	}

	/*=============================================
	EDITAR Tipo
	=============================================*/

	static public function ctrEditarColor(){

		if(isset($_POST["editarColor"])){

			if(preg_match('/^[a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["editarColor"])){

				$tabla = "colores";

				$datos = array("color"=>$_POST["editarColor"],
							   "id"=>$_POST["idColor"]);

				$respuesta = ModeloColores::mdlEditarColor($tabla, $datos);
				if($respuesta == "ok"){

					echo'<script>

					swal({
						  type: "success",
						  title: "El color ha sido cambiado correctamente",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
									if (result.value) {

									window.location = "colores";

									}
								})

					</script>';

				}


			}else{

				echo'<script>

					swal({
						  type: "error",
						  title: "¡El color no puede ir vacío , llevar caracteres especiales o números!",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
							if (result.value) {

							window.location = "colores";

							}
						})

			  	</script>';

			}

		}

	}

	/*=============================================
	BORRAR Tipo
	=============================================*/

	static public function ctrBorrarColor(){

		if(isset($_GET["idColor"])){

			$tabla ="colores";
			$datos = $_GET["idColor"];

			$respuesta = ModeloColores::mdlBorrarColor($tabla, $datos);

			if($respuesta == "ok"){

				echo'<script>

					swal({
						  type: "success",
						  title: "El Color ha sido borrado correctamente",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
									if (result.value) {

									window.location = "colores";

									}
								})

					</script>';
			}
		}
		
	}
}
