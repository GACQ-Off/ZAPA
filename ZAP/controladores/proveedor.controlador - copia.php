<?php
ini_set('display_errors',1);
error_reporting(E_ALL);


class ControladorProveedor
{

    /*=============================================
	CREAR CLIENTES
	=============================================*/

    static public function ctrCrearProveedor()
    {

        if (isset($_POST["nuevoRif"])) {

            if (true) {

                $tabla = "proveedores";

                $datos = array(
                    "rif" => $_POST["nuevoRif"],
                    "nombre_representante" => $_POST["nuevoNombre"],
                    "nombre" => $_POST["nuevaEmpresa"],
                    "cedula_representante" => $_POST["nuevaCedula"],
                    "apellido_representante" => $_POST["nuevoApellido"],
                    "telefono" => $_POST["nuevoTelefono"],
                    "direccion" => $_POST["nuevaDireccion"],
                    "email" => $_POST["nuevoEmail"]
                );

                $respuesta = ModeloProveedor::mdlIngresarProveedor($tabla, $datos);

                if ($respuesta == "ok") {

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
                } else if ($respuesta = "repetido") {
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
                }
            } else {

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
	MOSTRAR CLIENTES
	=============================================*/

    static public function ctrMostrarProveedor($item, $valor)
    {

        $tabla = "proveedores";

        $respuesta = ModeloProveedor::mdlMostrarProveedor($tabla, $item, $valor);

        return $respuesta;
    }

    /*=============================================
	EDITAR CLIENTE
	=============================================*/

    static public function ctrEditarProveedor()
    {
        if (isset($_POST["editarRif"])) {

            if (
                preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["editarRif"]) &&
                preg_match('/^[a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["editarEmpresa"]) &&
                preg_match('/^[0-9]+$/', $_POST["editarTelefono"]) &&
                preg_match('/^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/', $_POST["editarEmail"]) &&
                preg_match('/^[()\-0-9 ]+$/', $_POST["editarTelefono"]) &&
                preg_match('/^[#\.\-a-zA-Z0-9 ]+$/', $_POST["editarDireccion"])
            ) {

                $tabla = "proveedores";

                $datos = array(
                    "rif" => $_POST["editarRif"],
                    "empresa" => $_POST["editarEmpresa"],
                    "nombre" => $_POST["editarNombre"],
                    "apellido" => $_POST["editarApellido"],
                    "cedula" => $_POST["editarCedula"],
                    "email" => $_POST["editarEmail"],
                    "telefono" => $_POST["editarTelefono"],
                    "direccion" => $_POST["editarDireccion"]
                );

                $respuesta = ModeloProveedor::mdlEditarProveedor($tabla, $datos);

                if ($respuesta == "ok") {

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
                }
            } else {

                echo '<script>

					swal({
						  type: "error",
						  title: "¡El proveedor no puede ir vacío o llevar caracteres especiales!",
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
	ELIMINAR CLIENTE
	=============================================*/

    static public function ctrEliminarProveedor()
    {

        if (isset($_GET["idCliente"])) {

            $tabla = "proveedores";
            $datos = $_GET["idCliente"];

            $respuesta = ModeloProveedor::mdlEliminarProveedor($tabla, $datos);

            if ($respuesta == "ok") {

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
            }
        }
    }
}
