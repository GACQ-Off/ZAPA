<?php

require_once "conexion.php";

class ModeloEmpleados{

	/*=============================================
	MOSTRAR EMPLEADOS
	=============================================*/

	static public function MdlMostrarEmpleados($tabla, $item, $valor){
		if($item != null){

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item");

			$stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);

			$stmt -> execute();

			return $stmt -> fetch();

		}else{

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla u JOIN empleado e ON e.cedula = u.cedula");

			$stmt -> execute();

			return $stmt -> fetchAll();

		}
		
		// ➡️ CORRECCIÓN DE SINTAXIS: Cambiado $stmt->close() por $stmt->closeCursor()
		$stmt -> closeCursor();

		$stmt = null;

	}

	/*=============================================
	REGISTRO DE EMPLEADO
	=============================================*/

	static public function mdlIngresarEmpleado($tabla, $datos){

		$stmt = Conexion::conectar()->prepare("SELECT * from empleado where cedula = :cedula");
		$stmt->bindParam(":cedula",$datos["cedula"]);
		$stmt->execute();
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

		if(count($result) > 0){
			return "repetido";
		}
		
		$stmt = Conexion::conectar()->prepare("INSERT INTO empleado (cedula,nombre,apellido,prefijo_telefono,numero_telefono,foto,direccion) VALUES(:cedula,:nombre,:apellido,:prefijo_telefono,:numero_telefono,:foto,:direccion)");

		$stmt->bindParam(":cedula", $datos["cedula"], PDO::PARAM_STR);
		$stmt->bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);
		$stmt->bindParam(":apellido", $datos["apellido"], PDO::PARAM_STR);
		$stmt->bindParam(":prefijo_telefono", $datos["prefijo_telefono"], PDO::PARAM_STR);
		$stmt->bindParam(":numero_telefono", $datos["numero_telefono"], PDO::PARAM_STR);
		$stmt->bindParam(":foto", $datos["foto"], PDO::PARAM_STR);
		$stmt->bindParam(":direccion", $datos["direccion"], PDO::PARAM_STR);
		
		$result = $stmt->execute();
		
		if($result){
			return "ok";	
		}else{
			return "error";
		}

		$stmt->closeCursor();
		$stmt = null;

	}

	/*=============================================
	EDITAR EMPLEADO
	=============================================*/

	static public function mdlEdiarEmpleado($tabla, $datos){

		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET nombre = :nombre, prefijo_telefono = :prefijo_telefono, numero_telefono = :numero_telefono, apellido = :apellido, direccion = :localizacion, foto = :foto WHERE cedula = :cedula");

		$stmt -> bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);
		$stmt -> bindParam(":apellido", $datos["apellido"], PDO::PARAM_STR);
		$stmt->bindParam(":prefijo_telefono", $datos["prefijo_telefono"], PDO::PARAM_STR);
		$stmt->bindParam(":numero_telefono", $datos["numero_telefono"], PDO::PARAM_STR);
		$stmt -> bindParam(":cedula", $datos["cedula"], PDO::PARAM_STR);
		$stmt -> bindParam(":foto", $datos["foto"], PDO::PARAM_STR);
		$stmt -> bindParam(":localizacion", $datos["direccion"], PDO::PARAM_STR);

		if($stmt -> execute()){

			return "ok";
		
		}else{

			return "error";	

		}

		$stmt -> closeCursor();
		$stmt = null;

	}

	/*=============================================
	ACTUALIZAR USUARIO
	=============================================*/

	static public function mdlActualizarUsuario($tabla, $item1, $valor1,$item2,$valor2){
		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET $item1 = :$item1 WHERE $item2 = :$item2");

		$stmt -> bindParam(":".$item1, $valor1, PDO::PARAM_STR);
		$stmt -> bindParam(":".$item2, $valor2, PDO::PARAM_STR);

		if($stmt -> execute()){

			return "ok";
		
		}else{

			return "error";	

		}

		$stmt -> closeCursor();

		$stmt = null;

	}

	/*=============================================
	BORRAR EMPLEADO (CAPTURA DE FK)
	=============================================*/

	static public function mdlBorrarEmpleado($tabla, $datos){

		$stmt = Conexion::conectar()->prepare("DELETE FROM $tabla WHERE cedula = :cedula");

		$stmt -> bindParam(":cedula", $datos, PDO::PARAM_STR);

		try {
            if($stmt -> execute()){
                return "ok";
            } else {
                // Captura el error 23000 (Violación de integridad) si no lanza excepción
                $errorInfo = $stmt->errorInfo();
                if (isset($errorInfo[0]) && $errorInfo[0] === '23000') {
                    return "fk_fail"; 
                }
                return "error";	
            }
        } catch (PDOException $e) {
            // Captura la excepción de violación de integridad referencial (SQLSTATE '23000')
            if ($e->getCode() === '23000') {
                return "fk_fail"; // Retorna el código de fallo FK
            }
            return "error";
        }

		$stmt -> closeCursor();
		$stmt = null;
	}
}