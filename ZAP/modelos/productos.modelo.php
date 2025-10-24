<?php

require_once "conexion.php";

class ModeloProductos{

	/*=============================================
	MOSTRAR PRODUCTOS
	=============================================*/

	static public function mdlMostrarProductos($tabla, $item, $valor, $orden){

		if($item != null && $valor != null){
			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item ORDER BY codigo DESC");

			$stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);
			$stmt->execute();
			$result = $stmt -> fetch();
			return $result;

		}else{

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla ORDER BY $orden DESC");

			$stmt -> execute();

			return $stmt -> fetchAll();

		}

		$stmt -> closeCursor(); // Corregido: close() debe ser closeCursor()

		$stmt = null;

	}

	/*=============================================
	REGISTRO DE PRODUCTO
	=============================================*/
	static public function mdlIngresarProducto($tabla, $datos){

		$stmt = Conexion::conectar()->prepare("SELECT * from productos where codigo = :codigo");
		$stmt->bindParam(":codigo",$datos["codigo"]);
		$stmt->execute();
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

		if(count($result) > 0){
			return "repetido";
		}

		$stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(id_categoria,id_tipo,id_color,id_marca, codigo, descripcion, imagen, stock, precio_compra, precio_venta,tipo_rif_proveedor, num_rif_proveedor) VALUES (:id_categoria,:id_tipo,:id_color,:id_marca, :codigo, :descripcion, :imagen, :stock, :precio_compra, :precio_venta,:tipo_rif_proveedor, :num_rif_proveedor)");

		$stmt->bindParam(":id_categoria", $datos["id_categoria"], PDO::PARAM_INT);
		$stmt->bindParam(":id_tipo", $datos["id_tipo"], PDO::PARAM_INT);
		$stmt->bindParam(":id_color", $datos["id_color"], PDO::PARAM_INT);
		$stmt->bindParam(":id_marca", $datos["id_marca"], PDO::PARAM_INT);
		$stmt->bindParam(":codigo", $datos["codigo"], PDO::PARAM_STR);
		$stmt->bindParam(":descripcion", $datos["descripcion"], PDO::PARAM_STR);
		$stmt->bindParam(":tipo_rif_proveedor", $datos["tipo_rif_proveedor"], PDO::PARAM_STR);
		$stmt->bindParam(":num_rif_proveedor", $datos["num_rif_proveedor"], PDO::PARAM_STR);
		$stmt->bindParam(":imagen", $datos["imagen"], PDO::PARAM_STR);
		// 🛑 CAMBIO CRÍTICO: Forzar stock a 0 al crear el producto
	    $stockInicial = 0; 
	    $stmt->bindParam(":stock", $stockInicial, PDO::PARAM_INT); // Usamos $stockInicial en lugar de $datos["stock"]
		$stmt->bindParam(":precio_compra", $datos["precio_compra"], PDO::PARAM_STR);
		$stmt->bindParam(":precio_venta", $datos["precio_venta"], PDO::PARAM_STR);

		if($stmt->execute()){

			return "ok";

		}else{

			return "error";
		
		}

		$stmt->closeCursor();
		$stmt = null;

	}

	/*=============================================
	EDITAR PRODUCTO
	=============================================*/
	static public function mdlEditarProducto($tabla, $datos){

		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET id_categoria = :id_categoria,id_color = :id_color,id_marca = :id_marca,id_tipo = :id_tipo, descripcion = :descripcion, imagen = :imagen, stock = :stock, precio_compra = :precio_compra, precio_venta = :precio_venta, tipo_rif_proveedor = :tipo_rif_proveedor, num_rif_proveedor = :num_rif_proveedor WHERE codigo = :codigo");

		$stmt->bindParam(":id_categoria", $datos["id_categoria"], PDO::PARAM_INT);
		$stmt->bindParam(":id_color", $datos["id_color"], PDO::PARAM_INT);
		$stmt->bindParam(":id_tipo", $datos["id_tipo"], PDO::PARAM_INT);
		$stmt->bindParam(":id_marca", $datos["id_marca"], PDO::PARAM_INT);
		$stmt->bindParam(":codigo", $datos["codigo"], PDO::PARAM_STR);
		$stmt->bindParam(":tipo_rif_proveedor", $datos["tipo_rif_proveedor"], PDO::PARAM_STR);
		$stmt->bindParam(":num_rif_proveedor", $datos["num_rif_proveedor"], PDO::PARAM_STR);
		$stmt->bindParam(":descripcion", $datos["descripcion"], PDO::PARAM_STR);
		$stmt->bindParam(":imagen", $datos["imagen"], PDO::PARAM_STR);
		$stmt->bindParam(":stock", $datos["stock"], PDO::PARAM_STR);
		$stmt->bindParam(":precio_compra", $datos["precio_compra"], PDO::PARAM_STR);
		$stmt->bindParam(":precio_venta", $datos["precio_venta"], PDO::PARAM_STR);

		if($stmt->execute()){

			return "ok";

		}else{

			return "error";
		
		}

		$stmt->closeCursor();
		$stmt = null;

	}


	/*=============================================
	EDITAR STOCK (EXISTENTE en tu código, ligeramente adaptado para referencia)
	=============================================*/
	static public function mdlEditarStock($tabla, $datos){

		// Esta función no se usará en el flujo de Compras, pero se mantiene si es usada en otra parte.
		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET stock = stock + :stock, rif_proveedor = :proveedor WHERE codigo = :codigo");
		$stmt->bindParam(":codigo", $datos["codigo"], PDO::PARAM_STR);
		$stmt->bindParam(":proveedor", $datos["proveedor"], PDO::PARAM_STR);
		$stmt->bindParam(":stock", $datos["stock"], PDO::PARAM_INT);

		if($stmt->execute()){

			return "ok";

		}else{

			return "error";
		
		}

		$stmt->closeCursor();
		$stmt = null;

	}

	/*=============================================
	BORRAR PRODUCTO
	=============================================*/

	static public function mdlEliminarProducto($tabla, $datos){

		$stmt = Conexion::conectar()->prepare("DELETE FROM $tabla WHERE codigo = :id");

		$stmt -> bindParam(":id", $datos, PDO::PARAM_INT);

		if($stmt -> execute()){

			return "ok";
		
		}else{

			return "error";	

		}

		$stmt -> closeCursor();

		$stmt = null;

	}

	/*=============================================
	ACTUALIZAR PRODUCTO (Usado para ventas/cambios de stock)
	=============================================*/

	static public function mdlActualizarProducto($tabla, $item1, $valor1, $valor){

		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET $item1 = :$item1 WHERE id = :id");

		$stmt -> bindParam(":".$item1, $valor1, PDO::PARAM_STR);
		$stmt -> bindParam(":id", $valor, PDO::PARAM_STR);

		if($stmt -> execute()){

			return "ok";
		
		}else{

			return "error";	

		}

		$stmt -> closeCursor();

		$stmt = null;

	}

	/*=============================================
	MOSTRAR SUMA VENTAS
	=============================================*/	

	static public function mdlMostrarSumaVentas($tabla){

		$stmt = Conexion::conectar()->prepare("SELECT SUM(ventas) as total FROM $tabla");

		$stmt -> execute();

		return $stmt -> fetch();

		$stmt -> closeCursor();

		$stmt = null;
	}

    /*======================================================
	✅ NUEVO MÉTODO: ACTUALIZAR SOLO EL STOCK DE UN PRODUCTO (SUMAR COMPRA)
	======================================================*/
	static public function mdlActualizarStockPorCompra($tabla, $datos) {

		$conexion = Conexion::conectar();

		// 1. Obtener el stock actual del producto (Para asegurar la suma correcta)
		$stmtConsulta = $conexion->prepare("SELECT stock FROM $tabla WHERE codigo = :codigo");
		$stmtConsulta->bindParam(":codigo", $datos["codigo"], PDO::PARAM_STR);
		$stmtConsulta->execute();
		$productoActual = $stmtConsulta->fetch(PDO::FETCH_ASSOC);
		$stmtConsulta->closeCursor();

		if (!$productoActual) {
			error_log("ERROR STOCK: Producto con código " . $datos["codigo"] . " no encontrado para actualizar stock.");
			return "error_no_encontrado";
		}

		$stockActual = (int)$productoActual["stock"];
		$cantidadASumar = (int)$datos["cantidad_sumar"];
		$nuevoStock = $stockActual + $cantidadASumar;
		
		// 2. Actualizar solo la columna stock
		$stmt = $conexion->prepare("UPDATE $tabla 
									SET stock = :nuevo_stock
									WHERE codigo = :codigo");

		$stmt->bindParam(":nuevo_stock", $nuevoStock, PDO::PARAM_INT);
		$stmt->bindParam(":codigo", $datos["codigo"], PDO::PARAM_STR);

		if ($stmt->execute()) {
			$stmt->closeCursor();
			$stmt = null;
			return "ok";
		} else {
			error_log("ERROR DB - mdlActualizarStockPorCompra: " . print_r($stmt->errorInfo(), true));
			$stmt->closeCursor();
			$stmt = null;
			return "error_actualizacion";
		}
	}


}