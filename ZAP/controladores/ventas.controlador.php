<?php



// Asume que estas rutas ya están correctamente definidas en tu archivo del controlador de ventas
require_once __DIR__ . "/../modelos/ventas.modelo.php";
require_once __DIR__ . "/../modelos/clientes.modelo.php";
require_once __DIR__ . "/../modelos/productos.modelo.php";
require_once __DIR__ . "/../modelos/usuarios.modelo.php";
require_once __DIR__ . "/../modelos/configuracion.modelo.php";
require_once __DIR__ . "/../modelos/eventolog.modelo.php"; // ¡Asegúrate de incluir tu modelo de log de eventos!

class ControladorVentas{

	/*=============================================
	MOSTRAR VENTAS
	=============================================*/
	static public function ctrMostrarVentas($item, $valor, $month = null){
		$tabla = "ventas";
		$respuesta = ModeloVentas::mdlMostrarVentas($tabla, $item, $valor, $month);
		return $respuesta;
	}

	/*=============================================
	CREAR VENTA
	=============================================*/
	static public function ctrCrearVenta(){
		if(isset($_POST["nuevaVenta"])){
			/*=============================================
			ACTUALIZAR LAS COMPRAS DEL CLIENTE Y REDUCIR EL STOCK Y AUMENTAR LAS VENTAS DE LOS PRODUCTOS
			=============================================*/
			if($_POST["listaProductos"] == ""){
				// =============================================
				// REGISTRAR FALLO DE CREACIÓN DE VENTA (PRODUCTOS VACÍOS)
				// =============================================
				$empleadoCedula = (isset($_SESSION["cedula"])) ? $_SESSION["cedula"] : "Sistema/Desconocido";
				$logData = array(
					"event_type" => "Creación Venta Fallida",
					"description" => "Intento de crear venta fallido: lista de productos vacía. Factura: " . (isset($_POST["nuevaVenta"]) ? $_POST["nuevaVenta"] : 'N/A') . ".",
					"employee_cedula" => $empleadoCedula,
					"affected_table" => "ventas",
					"affected_row_id" => (isset($_POST["nuevaVenta"]) ? $_POST["nuevaVenta"] : 'N/A')
				);
				ModeloEventoLog::mdlGuardarEvento($logData);

				echo'<script>
				swal({
					type: "error",
					title: "La venta no se ha ejecuta si no hay productos",
					showConfirmButton: true,
					confirmButtonText: "Cerrar"
					}).then(function(result){
								if (result.value) {
								window.location = "ventas";
								}
							})
				</script>';
				return;
			}

			//$tablaClientes = "clientes";
			//$item = "cedula";
			//$valor = $_POST["seleccionarCliente"];
			//$traerCliente = ModeloClientes::mdlMostrarClientes($tablaClientes, $item, $valor);
			$listaProductos = json_decode($_POST["listaProductos"], true);
			$tablaClientes = "clientes";
			$cedulaCompuesta = $_POST["seleccionarCliente"]; // Ej: E-14600272
			list($tipo_ced, $num_ced) = explode("-", $cedulaCompuesta);

			// Usar nueva función para buscar cliente
			// Usar nueva función para buscar cliente
			$traerCliente = ModeloClientes::mdlMostrarClienteDosClaves($tablaClientes, "tipo_ced", "num_ced", $tipo_ced, $num_ced);
			/*=============================================
			GUARDAR LA COMPRA
			=============================================*/	
			$tabla = "ventas";
			$configuracion = ControladorConfiguracion::ctrMostrarConfigracion(null,null);
			$rif = $configuracion["tipo_rif"] . "-" . $configuracion["num_rif"];
			$dollar =$configuracion["precio_dolar"];

			$pago = isset($_POST["nuevoPago"]) ? $_POST["nuevoPago"] : 0;
			
			// Preparar los datos antes de pasar al modelo, para poder usarlos en el log si la venta se guarda.
			$datosVenta = array(
			  "tipo_ced_cliente" => $tipo_ced,
			  "num_ced_cliente" => $num_ced,
			  "vendedor" => $_POST["idVendedor"],
			  "factura" => $_POST["nuevaVenta"],
			  "tipo_rif_empresa" => $configuracion["tipo_rif"],
			  "num_rif_empresa" => $configuracion["num_rif"],
			  "iva" => $_POST["nuevoTotalVentaIVA"],
			  "nuevoNeto" => $_POST["nuevoNeto"],
			  "impuesto" => $_POST["nuevoImpuestoVenta"],
			  "pago" => number_format($pago * $dollar, 2),
			  "total" => $_POST["totalVenta"] * $dollar,
			  "metodo_pago" => $_POST["listaMetodoPago"]
			);


			$respuesta = ModeloVentas::mdlIngresarVenta($tabla, $datosVenta, $listaProductos);

			if($respuesta == "ok"){
				// =============================================
				// REGISTRAR CREACIÓN DE VENTA EN EL LOG
				// =============================================
				$empleadoCedula = (isset($_SESSION["cedula"])) ? $_SESSION["cedula"] : "Sistema/Desconocido";
				
				$traerCliente = ModeloClientes::mdlMostrarClienteDosClaves($tablaClientes, "tipo_ced", "num_ced", $tipo_ced, $num_ced);

				// Si el cliente no existe, usamos la cédula compuesta para los registros del log
				if($traerCliente){
				    $clienteNombre = $traerCliente["nombre"] . " " . $traerCliente["apellido"];
				} else {
				    $clienteNombre = "Cliente no encontrado (" . $tipo_ced . "-" . $num_ced . ")";
				}

				$logData = array(
				    "event_type" => "Venta Creada",
				    "description" => "Nueva venta registrada. Factura: {$datosVenta['factura']}, Cliente: {$clienteNombre}, Total: {$datosVenta['total']}.",
				    "employee_cedula" => $empleadoCedula,
				    "affected_table" => "ventas",
				    "affected_row_id" => $datosVenta['factura']
				);
				ModeloEventoLog::mdlGuardarEvento($logData);


				// La sección de impresión de ticket está comentada en tu código original,
				// la mantengo así.
				// ... (código de impresión de ticket)

				echo'<script>
				localStorage.removeItem("rango");
				swal({
					type: "success",
					title: "La venta ha sido guardada correctamente",
					showConfirmButton: true,
					confirmButtonText: "Cerrar"
					}).then(function(result){
								if (result.value) {
								window.location = "ventas";
								}
							})
				</script>';
			} else {
				// =============================================
				// REGISTRAR FALLO DE CREACIÓN DE VENTA (ERROR EN BASE DE DATOS)
				// =============================================
				$empleadoCedula = (isset($_SESSION["cedula"])) ? $_SESSION["cedula"] : "Sistema/Desconocido";
				$logData = array(
					"event_type" => "Creación Venta Fallida",
					"description" => "Error al intentar guardar la venta. Factura: " . (isset($datosVenta['factura']) ? $datosVenta['factura'] : 'N/A') . ".",
					"employee_cedula" => $empleadoCedula,
					"affected_table" => "ventas",
					"affected_row_id" => (isset($datosVenta['factura']) ? $datosVenta['factura'] : 'N/A')
				);
				ModeloEventoLog::mdlGuardarEvento($logData);
			}
		}
	}

	/*=============================================
	EDITAR VENTA
	=============================================*/
	static public function ctrEditarVenta(){
		if(isset($_POST["editarVenta"])){
			/*=============================================
			FORMATEAR TABLA DE PRODUCTOS Y LA DE CLIENTES
			=============================================*/
			$tabla = "ventas";
			$item = "codigo";
			$valor = $_POST["editarVenta"];

			// Obtener la venta antes de editar para el log
			$traerVenta = ModeloVentas::mdlMostrarVentas($tabla, $item, $valor);

			if(!$traerVenta){
				// Si la venta no existe, registrar el intento fallido
				$empleadoCedula = (isset($_SESSION["cedula"])) ? $_SESSION["cedula"] : "Sistema/Desconocido";
				$logData = array(
					"event_type" => "Edición Venta Fallida",
					"description" => "Intento de edición fallido: Venta con código '{$valor}' no encontrada.",
					"employee_cedula" => $empleadoCedula,
					"affected_table" => "ventas",
					"affected_row_id" => $valor
				);
				ModeloEventoLog::mdlGuardarEvento($logData);

				echo'<script>
				swal({
					type: "error",
					title: "La venta a editar no fue encontrada",
					showConfirmButton: true,
					confirmButtonText: "Cerrar"
					}).then((result) => {
								if (result.value) {
								window.location = "ventas";
								}
							})
				</script>';
				return;
			}

			/*=============================================
			REVISAR SI VIENE PRODUCTOS EDITADOS
			=============================================*/
			if($_POST["listaProductos"] == ""){
				$listaProductos = $traerVenta["productos"];
				$cambioProducto = false;
			}else{
				$listaProductos = $_POST["listaProductos"];
				$cambioProducto = true;
			}

			if($cambioProducto){
				$productos = json_decode($traerVenta["productos"], true);
				$totalProductosComprados = array();

				foreach ($productos as $key => $value) {
					array_push($totalProductosComprados, $value["cantidad"]);
					
					$tablaProductos = "productos";
					$item = "id";
					$valor = $value["id"];
					$orden = "id";

					$traerProducto = ModeloProductos::mdlMostrarProductos($tablaProductos, $item, $valor, $orden);

					$item1a = "ventas";
					$valor1a = $traerProducto["ventas"] - $value["cantidad"];
					$nuevasVentas = ModeloProductos::mdlActualizarProducto($tablaProductos, $item1a, $valor1a, $valor);

					$item1b = "stock";
					$valor1b = $value["cantidad"] + $traerProducto["stock"];
					$nuevoStock = ModeloProductos::mdlActualizarProducto($tablaProductos, $item1b, $valor1b, $valor);
				}

				$tablaClientes = "clientes";
				$itemCliente = "id";
				$valorCliente = $_POST["seleccionarCliente"];
				$traerCliente = ModeloClientes::mdlMostrarClientes($tablaClientes, $itemCliente, $valorCliente);

				$item1a = "compras";
				$valor1a = $traerCliente["compras"] - array_sum($totalProductosComprados);		
				$comprasCliente = ModeloClientes::mdlActualizarCliente($tablaClientes, $item1a, $valor1a, $valorCliente);

				/*=============================================
				ACTUALIZAR LAS COMPRAS DEL CLIENTE Y REDUCIR EL STOCK Y AUMENTAR LAS VENTAS DE LOS PRODUCTOS
				=============================================*/
				$listaProductos_2 = json_decode($listaProductos, true);
				$totalProductosComprados_2 = array();

				foreach ($listaProductos_2 as $key => $value) {
					array_push($totalProductosComprados_2, $value["cantidad"]);
					
					$tablaProductos_2 = "productos";
					$item_2 = "id";
					$valor_2 = $value["id"];
					$orden = "id";

					$traerProducto_2 = ModeloProductos::mdlMostrarProductos($tablaProductos_2, $item_2, $valor_2, $orden);

					$item1a_2 = "ventas";
					$valor1a_2 = $value["cantidad"] + $traerProducto_2["ventas"];
					$nuevasVentas_2 = ModeloProductos::mdlActualizarProducto($tablaProductos_2, $item1a_2, $valor1a_2, $valor_2);

					$item1b_2 = "stock";
					$valor1b_2 = $value["stock"];
					$nuevoStock_2 = ModeloProductos::mdlActualizarProducto($tablaProductos_2, $item1b_2, $valor1b_2, $valor_2);
				}

				$tablaClientes_2 = "clientes";
				$item_2 = "id";
				$valor_2 = $_POST["seleccionarCliente"];
				$traerCliente_2 = ModeloClientes::mdlMostrarClientes($tablaClientes_2, $item_2, $valor_2);

				$item1a_2 = "compras";
				$valor1a_2 = array_sum($totalProductosComprados_2) + $traerCliente_2["compras"];
				$comprasCliente_2 = ModeloClientes::mdlActualizarCliente($tablaClientes_2, $item1a_2, $valor1a_2, $valor_2);

				$item1b_2 = "ultima_compra";
				date_default_timezone_set('America/Bogota');
				$fecha = date('Y-m-d');
				$hora = date('H:i:s');
				$valor1b_2 = $fecha.' '.$hora;
				$fechaCliente_2 = ModeloClientes::mdlActualizarCliente($tablaClientes_2, $item1b_2, $valor1b_2, $valor_2);
			}

			/*=============================================
			GUARDAR CAMBIOS DE LA COMPRA
			=============================================*/	
			$datos = array("id_vendedor"=>$_POST["idVendedor"],
						"id_cliente"=>$_POST["seleccionarCliente"],
						"codigo"=>$_POST["editarVenta"],
						"productos"=>$listaProductos,
						"impuesto"=>$_POST["nuevoPrecioImpuesto"],
						"neto"=>$_POST["nuevoPrecioNeto"],
						"total"=>$_POST["totalVenta"],
						"metodo_pago"=>$_POST["listaMetodoPago"]);

			$respuesta = ModeloVentas::mdlEditarVenta($tabla, $datos);

			if($respuesta == "ok"){
				// =============================================
				// REGISTRAR EDICIÓN DE VENTA EN EL LOG
				// =============================================
				$empleadoCedula = (isset($_SESSION["cedula"])) ? $_SESSION["cedula"] : "Sistema/Desconocido";
				$logData = array(
					"event_type" => "Venta Editada",
					"description" => "Venta (Código: {$datos['codigo']}) editada. Detalles originales: Total " . $traerVenta['total'] . ". Nuevos detalles: Total " . $datos['total'] . ".",
					"employee_cedula" => $empleadoCedula,
					"affected_table" => "ventas",
					"affected_row_id" => $datos['codigo']
				);
				ModeloEventoLog::mdlGuardarEvento($logData);

				echo'<script>
				localStorage.removeItem("rango");
				swal({
					type: "success",
					title: "La venta ha sido editada correctamente",
					showConfirmButton: true,
					confirmButtonText: "Cerrar"
					}).then((result) => {
								if (result.value) {
								window.location = "ventas";
								}
							})
				</script>';
			} else {
				// =============================================
				// REGISTRAR FALLO EN EDICIÓN DE VENTA
				// =============================================
				$empleadoCedula = (isset($_SESSION["cedula"])) ? $_SESSION["cedula"] : "Sistema/Desconocido";
				$logData = array(
					"event_type" => "Edición Venta Fallida",
					"description" => "Error al intentar editar la venta (Código: " . (isset($datos['codigo']) ? $datos['codigo'] : 'N/A') . ").",
					"employee_cedula" => $empleadoCedula,
					"affected_table" => "ventas",
					"affected_row_id" => (isset($datos['codigo']) ? $datos['codigo'] : 'N/A')
				);
				ModeloEventoLog::mdlGuardarEvento($logData);
			}
		}
	}

	/*=============================================
	ELIMINAR VENTA
	=============================================*/
	static public function ctrEliminarVenta(){
		if(isset($_GET["idVenta"])){
			$tabla = "ventas";
			$item = "factura";
			$valor = $_GET["idVenta"];

			// =============================================
			// OBTENER DATOS DE LA VENTA ANTES DE ELIMINAR PARA EL LOG
			// =============================================
			$ventaAEliminar = self::ctrMostrarVentas($item, $valor); 
			
			$facturaNumeroBorrado = (isset($ventaAEliminar['factura'])) ? $ventaAEliminar['factura'] : 'N/A';
			$clienteVentaBorrada = (isset($ventaAEliminar['cliente'])) ? $ventaAEliminar['cliente'] : 'N/A'; 
			$totalVentaBorrada = (isset($ventaAEliminar['total'])) ? $ventaAEliminar['total'] : 'N/A';

			/*=============================================
			ELIMINAR VENTA
			=============================================*/
			$respuesta = ModeloVentas::mdlEliminarVenta($tabla, $_GET["idVenta"]);

			if($respuesta == "ok"){
				// =============================================
				// REGISTRAR ELIMINACIÓN DE VENTA EN EL LOG
				// =============================================
				$empleadoCedula = (isset($_SESSION["cedula"])) ? $_SESSION["cedula"] : "Sistema/Desconocido";
				$logData = array(
					"event_type" => "Venta Eliminada",
					"description" => "Venta (Factura: {$facturaNumeroBorrado}, Cliente: {$clienteVentaBorrada}, Total: {$totalVentaBorrada}) eliminada.",
					"employee_cedula" => $empleadoCedula,
					"affected_table" => "ventas",
					"affected_row_id" => $facturaNumeroBorrado
				);
				ModeloEventoLog::mdlGuardarEvento($logData);

				echo'<script>
				swal({
					type: "success",
					title: "La venta ha sido borrada correctamente",
					showConfirmButton: true,
					confirmButtonText: "Cerrar"
					}).then(function(result){
								if (result.value) {
								window.location = "ventas";
								}
							})
				</script>';
			} else {
				// =============================================
				// REGISTRAR FALLO EN ELIMINACIÓN DE VENTA
				// =============================================
				$empleadoCedula = (isset($_SESSION["cedula"])) ? $_SESSION["cedula"] : "Sistema/Desconocido";
				$logData = array(
					"event_type" => "Eliminación Venta Fallida",
					"description" => "Error al intentar eliminar la venta (Factura: {$valor}).",
					"employee_cedula" => $empleadoCedula,
					"affected_table" => "ventas",
					"affected_row_id" => $valor
				);
				ModeloEventoLog::mdlGuardarEvento($logData);
			}
		}
	}

	/*=============================================
	RANGO FECHAS
	=============================================*/	
	static public function ctrRangoFechasVentas($fechaInicial, $fechaFinal){
		$tabla = "ventas";
		$respuesta = ModeloVentas::mdlRangoFechasVentas($tabla, $fechaInicial, $fechaFinal);
		return $respuesta;
	}

	

	/*=============================================
	SUMA TOTAL VENTAS
	=============================================*/
	static public function ctrSumaTotalVentas(){
		$tabla = "ventas";
		$respuesta = ModeloVentas::mdlSumaTotalVentas($tabla);
		return $respuesta;
	}
	
	/*=============================================
	SUMA TOTAL VENTAS POR DIVISAS
	=============================================*/
	static public function ctrSumaTotalVentasDivisas(){
		$tabla = "ventas";
		$metodoPagoDivisas = "divisas%";
		$respuesta = ModeloVentas::mdlSumaTotalVentasDivisas($tabla, $metodoPagoDivisas);
		return $respuesta;
	}

	// Nuevo método para obtener ventas por rango de fechas
	static public function ctrMostrarVentasPorRangoFechas($fechaInicio, $fechaFin){
		return ModeloVentas::mdlMostrarVentasPorRangoFechas($fechaInicio, $fechaFin);
	}

	/*=============================================
	DESCARGAR XML
	=============================================*/
	static public function ctrDescargarXML(){
		if(isset($_GET["xml"])){
			$tabla = "ventas";
			$item = "codigo";
			$valor = $_GET["xml"];
			$ventas = ModeloVentas::mdlMostrarVentas($tabla, $item, $valor);

			// PRODUCTOS
			$listaProductos = json_decode($ventas["productos"], true);

			// CLIENTE
			$tablaClientes = "clientes";
			$item = "id";
			$valor = $ventas["id_cliente"];
			$traerCliente = ModeloClientes::mdlMostrarClientes($tablaClientes, $item, $valor);

			// VENDEDOR
			$tablaVendedor = "usuarios";
			$item = "id";
			$valor = $ventas["id_vendedor"];
			$traerVendedor = ModeloUsuarios::ctrMostrarUsuarios($tablaVendedor, $item, $valor);

			//http://php.net/manual/es/book.xmlwriter.php
			$objetoXML = new XMLWriter();
			$objetoXML->openURI($_GET["xml"].".xml");
			$objetoXML->setIndent(true);
			$objetoXML->setIndentString("\t");
			$objetoXML->startDocument('1.0', 'utf-8');
			
			// $objetoXML->startElement("etiquetaPrincipal");
			// $objetoXML->writeAttribute("atributoEtiquetaPPal", "valor atributo etiqueta PPal");
			// 	$objetoXML->startElement("etiquetaInterna");
			// 		$objetoXML->writeAttribute("atributoEtiquetaInterna", "valor atributo etiqueta Interna");
			// 		$objetoXML->text("Texto interno");
			// 	$objetoXML->endElement();
			// $objetoXML->endElement();

			
				
		}
	}
}