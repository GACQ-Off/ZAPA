<?php
ini_set('display_errors',1);
error_reporting(E_ALL);

require_once "conexion.php";

class ModeloVentas{

	/*=============================================
	MOSTRAR VENTAS
	=============================================*/

	static public function mdlMostrarVentas($tabla, $item, $valor,$month=null){

		if($item != null){

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item ORDER BY factura ASC");

			$stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);

			$stmt -> execute();

			return $stmt -> fetch();

		}else{

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla ORDER BY factura ASC");

			if($month){
				$stmt = Conexion::conectar()->prepare("SELECT * FROM `ventas` WHERE month(fecha) = month(fecha);");
			}
			

			$stmt -> execute();

			return $stmt -> fetchAll();

		}
		
		$stmt -> close();

		$stmt = null;

	}
	/* =============================================
    * MOSTRAR VENTAS POR RANGO DE FECHAS
    * =============================================*/
    static public function mdlMostrarVentasPorRangoFechas($fechaInicio, $fechaFin){

        // Para incluir la fecha fin en el rango, puedes ajustar el operador
        // o añadir un día a la fecha fin si la columna 'fecha' en tu DB incluye la hora (TIMESTAMP/DATETIME)
        // Ejemplo: fecha >= '2025-06-01 00:00:00' AND fecha <= '2025-06-30 23:59:59'
        // Si tu columna 'fecha' es solo DATE, 'BETWEEN' funciona bien directamente.

        $stmt = Conexion::conectar()->prepare("SELECT * FROM ventas WHERE fecha BETWEEN :fechaInicio AND :fechaFin ORDER BY fecha ASC");

        $stmt->bindParam(":fechaInicio", $fechaInicio, PDO::PARAM_STR);
        $stmt->bindParam(":fechaFin", $fechaFin, PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch as associative array
    }


	/*=============================================
	REGISTRO DE VENTA
	=============================================*/

	static public function mdlIngresarVenta($tabla, $datos, $productos){

    // ----------------------------------------------------
    // PUNTO DE CHECK 1: ¿Llega hasta aquí y qué datos trae?
    // var_dump("Punto 1: Inicio de mdlIngresarVenta");
    // var_dump("Tabla:", $tabla);
    // var_dump("Datos:", $datos);
    // var_dump("Productos:", $productos);
    // die(); // Detiene la ejecución aquí para ver esta salida

   $stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(factura, cliente, vendedor, tipo_rif_empresa, num_rif_empresa, total, metodo_pago, fecha, pago, impuesto) VALUES (:factura, :cliente, :vendedor, :tipo_rif, :num_rif, :total, :metodo_pago, NOW(), :pago, :impuesto)");

    // Limpiar valores numéricos de comas antes de enlazarlos a la consulta principal
    $datos["total"] = str_replace(",", "", $datos["total"]);
    $datos["nuevoNeto"] = str_replace(",", "", $datos["nuevoNeto"]);
    $datos["iva"] = str_replace(",", "", $datos["iva"]);

    $stmt->bindParam(":factura", $datos["factura"], PDO::PARAM_INT);
    $stmt->bindParam(":cliente", $datos["cliente"], PDO::PARAM_STR);
    $stmt->bindParam(":vendedor", $datos["vendedor"], PDO::PARAM_STR);
    $stmt->bindParam(":tipo_rif", $datos["tipo_rif_empresa"], PDO::PARAM_STR);
	$stmt->bindParam(":num_rif", $datos["num_rif_empresa"], PDO::PARAM_STR);

    $stmt->bindParam(":total", $datos["total"], PDO::PARAM_STR);
    $stmt->bindParam(":pago", $datos["nuevoNeto"], PDO::PARAM_STR);
    $stmt->bindParam(":impuesto", $datos["iva"], PDO::PARAM_STR);
    $stmt->bindParam(":metodo_pago", $datos["metodo_pago"], PDO::PARAM_STR);

    // ----------------------------------------------------
     //PUNTO DE CHECK 2: ¿Falla la primera inserción?
     var_dump("Punto 2: Antes de ejecutar INSERT principal");
     die();

    if($stmt->execute()){
        $sqlVentaProducto = "";
        $sqlUpdateProductoStock = "";

        // ----------------------------------------------------
        // PUNTO DE CHECK 3: ¿Se obtiene el dólar correctamente?
        // Asegúrate de que ControladorConfiguracion está accesible y funciona.
        $configuracion = ControladorConfiguracion::ctrMostrarConfigracion(null, null);
        // var_dump("Punto 3: Configuración:", $configuracion);
        // var_dump("Valor del Dólar:", $configuracion["precio_dolar"]);
        // die(); // Si se congela antes de este die(), el problema está en ctrMostrarConfiguracion

        $dollar = $configuracion["precio_dolar"];

        foreach($productos as $producto){
            // ----------------------------------------------------
            // PUNTO DE CHECK 4: ¿Se obtiene la información de CADA producto?
            // Si el problema está dentro del bucle, esta es la clave.
            // var_dump("Punto 4: Procesando producto:", $producto["codigo"]);
            // die(); // Detén aquí para el primer producto

            $productoInfo = ControladorProductos::ctrMostrarProductos("codigo", $producto["codigo"], "codigo");

            // ----------------------------------------------------
            // PUNTO DE CHECK 5: ¿Se obtiene la información correcta del producto?
            // var_dump("Punto 5: Info del producto:", $productoInfo);
            // var_dump("Precio de venta del producto:", $productoInfo["precio_venta"]);
            // die(); // Si $productoInfo es null o no tiene "precio_venta", aquí lo verás.

            $precioUnitarioParaDB = $productoInfo["precio_venta"] * $dollar;

            $sqlVentaProducto .= "INSERT INTO venta_producto (factura, cantidad, producto, precio_unitario) VALUES(
                '".$datos["factura"]."',
                '".$producto["cantidad"]."',
                '".$producto["codigo"]."',
                '".$precioUnitarioParaDB."'
            );";

            $sqlUpdateProductoStock .= "UPDATE productos SET stock = stock - '".$producto["cantidad"]."' WHERE codigo = '".$producto["codigo"]."';";
        }

        // ----------------------------------------------------
        // PUNTO DE CHECK 6: ¿Las sentencias SQL se construyen correctamente?
        // var_dump("Punto 6: SQL para Venta Producto:", $sqlVentaProducto);
        // var_dump("Punto 6: SQL para Actualizar Stock:", $sqlUpdateProductoStock);
        // die(); // Revisa si las cadenas SQL son válidas.

        $stmtVentaProducto = Conexion::conectar()->prepare($sqlVentaProducto);

        // ----------------------------------------------------
        // PUNTO DE CHECK 7: ¿Se ejecutan las inserciones de venta_producto?
        // var_dump("Punto 7: Antes de ejecutar Venta Producto");
        // die();

        $stmtVentaProducto->execute();

        $stmtUpdateStock = Conexion::conectar()->prepare($sqlUpdateProductoStock);

        // ----------------------------------------------------
        // PUNTO DE CHECK 8: ¿Se ejecutan las actualizaciones de stock?
        // var_dump("Punto 8: Antes de ejecutar Actualización de Stock");
        // die();

        $stmtUpdateStock->execute();

        // ----------------------------------------------------
        // PUNTO DE CHECK 9: ¿Llega hasta el final con éxito?
        // var_dump("Punto 9: Función completada con éxito");
        // die();

        return "ok";
	   }else{

			return "error";
		
		}

		$stmt->close();
		$stmt = null;

	}

	/*=============================================
	EDITAR VENTA
	=============================================*/

	static public function mdlEditarVenta($tabla, $datos){

		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET  id_cliente = :id_cliente, id_vendedor = :id_vendedor, productos = :productos, impuesto = :impuesto, neto = :neto, total= :total, metodo_pago = :metodo_pago WHERE codigo = :codigo");

		$stmt->bindParam(":codigo", $datos["codigo"], PDO::PARAM_INT);
		$stmt->bindParam(":id_cliente", $datos["id_cliente"], PDO::PARAM_STR);
		$stmt->bindParam(":id_vendedor", $datos["id_vendedor"], PDO::PARAM_STR);
		$stmt->bindParam(":productos", $datos["productos"], PDO::PARAM_STR);
		$stmt->bindParam(":impuesto", $datos["impuesto"], PDO::PARAM_STR);
		$stmt->bindParam(":neto", $datos["neto"], PDO::PARAM_STR);
		$stmt->bindParam(":total", $datos["total"], PDO::PARAM_STR);
		$stmt->bindParam(":metodo_pago", $datos["metodo_pago"], PDO::PARAM_STR);

		if($stmt->execute()){

			return "ok";

		}else{

			return "error";
		
		}

		$stmt->close();
		$stmt = null;

	}

	/*=============================================
	ELIMINAR VENTA
	=============================================*/

	static public function mdlEliminarVenta($tabla, $datos){

		$stmt = Conexion::conectar()->prepare("DELETE FROM $tabla WHERE factura = :id");

		$stmt -> bindParam(":id", $datos, PDO::PARAM_INT);

		if($stmt -> execute()){

			return "ok";
		
		}else{

			return "error";	

		}

		$stmt -> close();

		$stmt = null;

	}

	/*=============================================
	RANGO FECHAS
	=============================================*/	

	static public function mdlRangoFechasVentas($tabla, $fechaInicial, $fechaFinal){

		if($fechaInicial == null){

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla ORDER BY factura ASC");

			$stmt -> execute();

			return $stmt -> fetchAll();	


		}else if($fechaInicial == $fechaFinal){

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE fecha like '%$fechaFinal%'");

			$stmt -> bindParam(":fecha", $fechaFinal, PDO::PARAM_STR);

			$stmt -> execute();

			return $stmt -> fetchAll();

		}else{

			$fechaActual = new DateTime();
			$fechaActual ->add(new DateInterval("P1D"));
			$fechaActualMasUno = $fechaActual->format("Y-m-d");

			$fechaFinal2 = new DateTime($fechaFinal);
			$fechaFinal2 ->add(new DateInterval("P1D"));
			$fechaFinalMasUno = $fechaFinal2->format("Y-m-d");

			if($fechaFinalMasUno == $fechaActualMasUno){

				$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE fecha BETWEEN '$fechaInicial' AND '$fechaFinalMasUno'");

			}else{


				$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE fecha BETWEEN '$fechaInicial' AND '$fechaFinal'");

			}
		
			$stmt -> execute();

			return $stmt -> fetchAll();

		}

	}

	/*=============================================
	SUMAR EL TOTAL DE VENTAS
	=============================================*/

	static public function mdlSumaTotalVentas($tabla){	

		$stmt = Conexion::conectar()->prepare("select COALESCE(sum(total),0) as total from $tabla");

		$stmt -> execute();

		return $stmt -> fetch();

		$stmt -> close();

		$stmt = null;

	}

	/*=============================================
    SUMAR EL TOTAL DE VENTAS POR DIVISAS
    =============================================*/
    static public function mdlSumaTotalVentasDivisas($tabla, $metodoPagoPattern){

        // SQL to extract the numeric part after 'divisas-' and sum it
        $stmt = Conexion::conectar()->prepare("SELECT COALESCE(SUM(CAST(SUBSTRING_INDEX(metodo_pago, '-', -1) AS DECIMAL(10, 2))), 0) AS total_divisas
                                                FROM $tabla
                                                WHERE metodo_pago LIKE :metodoPagoPattern");

        $stmt->bindParam(":metodoPagoPattern", $metodoPagoPattern, PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->fetch();

        // Note: The following lines are unreachable after a return statement
        $stmt->close();
        $stmt = null;
    }

	
}