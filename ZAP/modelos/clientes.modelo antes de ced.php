<?php

require_once "conexion.php";

class ModeloClientes{

	/*=============================================
	CREAR CLIENTE
	=============================================*/

	static public function mdlIngresarCliente($tabla, $datos){

		$stmt = Conexion::conectar()->prepare("SELECT * from clientes where cedula = :cedula");
		$stmt->bindParam(":cedula",$datos["cedula"]);
		$stmt->execute();
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

		if(count($result) > 0){
			return "repetido";
		}

		// MODIFICACIÓN: La consulta INSERT ahora usa prefijo_telefono y numero_telefono
		$stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(nombre, cedula, email, apellido, direccion, prefijo_telefono, numero_telefono) VALUES (:nombre, :cedula, :email,:apellido, :direccion, :prefijo_telefono, :numero_telefono)");

		$stmt->bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);
		$stmt->bindParam(":cedula", $datos["cedula"], PDO::PARAM_STR);
		$stmt->bindParam(":email", $datos["email"], PDO::PARAM_STR);
		$stmt->bindParam(":apellido", $datos["apellido"], PDO::PARAM_STR);
		$stmt->bindParam(":direccion", $datos["direccion"], PDO::PARAM_STR);
		
		// VINCULACIÓN DE LOS NUEVOS CAMPOS DE TELÉFONO
		$stmt->bindParam(":prefijo_telefono", $datos["prefijo_telefono"], PDO::PARAM_STR);
		$stmt->bindParam(":numero_telefono", $datos["numero_telefono"], PDO::PARAM_STR);

		if($stmt->execute()){

			return "ok";

		}else{

			return "error";
		
		}

		$stmt->close();
		$stmt = null;

	}

	/*=============================================
	MOSTRAR CLIENTES
	=============================================*/

	static public function mdlMostrarClientes($tabla, $item, $valor){

		if($item != null && $valor != null){

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item");

			$stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);

			$stmt -> execute();
			$result = $stmt -> fetch();
			return $result;

		}else{
			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla");

			$stmt -> execute();
			$result = $stmt -> fetchAll();
			return $result;

		}

		$stmt -> close();

		$stmt = null;

	}

	static public function mdlMostrarClienteDosClaves($tabla, $item1, $item2, $valor1, $valor2) {
    $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item1 = :valor1 AND $item2 = :valor2");
    $stmt->bindParam(":valor1", $valor1, PDO::PARAM_STR);
    $stmt->bindParam(":valor2", $valor2, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetch();
	}


	/*=============================================
	EDITAR CLIENTE
	=============================================*/

	static public function mdlEditarCliente($tabla, $datos){

		// MODIFICACIÓN: La consulta UPDATE ahora actualiza prefijo_telefono y numero_telefono
		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET nombre = :nombre, email = :email, prefijo_telefono = :prefijo_telefono, numero_telefono = :numero_telefono, direccion = :direccion, apellido = :apellido WHERE cedula = :cedula");
		
		$stmt->bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);
		$stmt->bindParam(":cedula", $datos["cedula"], PDO::PARAM_STR);
		$stmt->bindParam(":email", $datos["email"], PDO::PARAM_STR);
		$stmt->bindParam(":direccion", $datos["direccion"], PDO::PARAM_STR);
		$stmt->bindParam(":apellido", $datos["apellido"], PDO::PARAM_STR);
		
		// VINCULACIÓN DE LOS NUEVOS CAMPOS
		$stmt->bindParam(":prefijo_telefono", $datos["prefijo_telefono"], PDO::PARAM_STR);
		$stmt->bindParam(":numero_telefono", $datos["numero_telefono"], PDO::PARAM_STR);

		if($stmt->execute()){

			return "ok";

		}else{

			return "error";
		
		}

		$stmt->close();
		$stmt = null;

	}

	/*=============================================
	ELIMINAR CLIENTE
	=============================================*/

	static public function mdlEliminarCliente($tabla, $datos){

		$stmt = Conexion::conectar()->prepare("DELETE FROM $tabla WHERE cedula = :id");

		$stmt -> bindParam(":id", $datos, PDO::PARAM_STR);

		if($stmt -> execute()){

			return "ok";
		
		}else{

			return "error";	

		}

		$stmt -> close();

		$stmt = null;

	}

	/*=============================================
	ACTUALIZAR CLIENTE
	=============================================*/

	static public function mdlActualizarCliente($tabla, $item1, $valor1, $valor){

		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET $item1 = :$item1 WHERE id = :id");

		$stmt -> bindParam(":".$item1, $valor1, PDO::PARAM_STR);
		$stmt -> bindParam(":id", $valor, PDO::PARAM_STR);

		if($stmt -> execute()){

			return "ok";
		
		}else{

			return "error";	

		}

		$stmt -> close();

		$stmt = null;

	}

}