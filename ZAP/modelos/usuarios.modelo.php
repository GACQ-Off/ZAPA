<?php

require_once "conexion.php";

class ModeloUsuarios{

	/*=============================================
	MOSTRAR USUARIOS
	=============================================*/
	// Se corrige un potencial error de binding en la primera consulta
	static public function mdlMostrarUsuarios($tabla, $item, $valor){
		if($item != null){
			
			// Si el item es 'usuario', buscamos por 'usuario'
			if($item == "usuario"){
				$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla u JOIN empleado e ON e.cedula = u.cedula WHERE u.usuario = :$item");
			} 
			// Si el item es 'cedula' o cualquier otro, buscamos por esa columna
            else {
				$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla u JOIN empleado e ON e.cedula = u.cedula WHERE u.$item = :$item");
			}
			
			$stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);

			$stmt -> execute();

			return $stmt -> fetch();

		}else{

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla u JOIN empleado e ON e.cedula = u.cedula");

			$stmt -> execute();

			return $stmt -> fetchAll();

		}
		

		$stmt -> closeCursor(); // Mejor usar closeCursor() en PDO
		$stmt = null;

	}

	/*=============================================
	REGISTRO DE USUARIO
	=============================================*/
    // (Mantenido, funciona bien)
	static public function mdlIngresarUsuario($tabla, $datos){

		$stmt = Conexion::conectar()->prepare("SELECT * from usuarios where usuario = :usuario OR cedula = :cedula");
		$stmt->bindParam(":usuario",$datos["usuario"]);
		$stmt->bindParam(":cedula",$datos["cedula"]);
		$stmt->execute();
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

		if(count($result) > 0){
			return "repetido";
		}

		$stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(cedula, usuario, password, perfil) VALUES (:cedula, :usuario, :password, :perfil)");


		$stmt->bindParam(":cedula", $datos["cedula"], PDO::PARAM_STR);
		$stmt->bindParam(":usuario", $datos["usuario"], PDO::PARAM_STR);
		$stmt->bindParam(":password", $datos["password"], PDO::PARAM_STR);
		$stmt->bindParam(":perfil", $datos["perfil"], PDO::PARAM_STR);

		if($stmt->execute()){

			return "ok";	

		}else{

			return "error";
		
		}

		$stmt->closeCursor();
		$stmt = null;

	}

	/*=============================================
	EDITAR USUARIO (CRÍTICO: CORREGIDO)
	=============================================*/

	static public function mdlEditarUsuario($tabla, $datos){
	
		// ✅ CRÍTICO: Se cambia la cláusula WHERE de 'usuario' a 'cedula'
		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET usuario = :usuario, password = :password, perfil = :perfil WHERE cedula = :cedula");
		
		$stmt -> bindParam(":usuario", $datos["usuario"], PDO::PARAM_STR); // Puede que también se actualice el nombre de usuario
		$stmt -> bindParam(":password", $datos["password"], PDO::PARAM_STR);
		$stmt -> bindParam(":perfil", $datos["perfil"], PDO::PARAM_STR);
        // ✅ CRÍTICO: Usamos la cédula para identificar qué fila actualizar
		$stmt -> bindParam(":cedula", $datos["cedula"], PDO::PARAM_STR); 

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
    // (Mantenido, es genérico)
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
	BORRAR USUARIO (VERIFICADO: CORRECTO)
	=============================================*/

	static public function mdlBorrarUsuario($tabla, $datos){
        // $datos contiene la cédula única.
		$stmt = Conexion::conectar()->prepare("DELETE FROM $tabla WHERE cedula = :cedula");

		// ✅ CRÍTICO: Se usa PDO::PARAM_STR en lugar de PDO::PARAM_INT, ya que las cédulas pueden tener guiones o ceros a la izquierda
		$stmt -> bindParam(":cedula", $datos, PDO::PARAM_STR); 

		if($stmt -> execute()){

			return "ok";
		
		}else{

			return "error";	

		}

		$stmt -> closeCursor();

		$stmt = null;
	}
}