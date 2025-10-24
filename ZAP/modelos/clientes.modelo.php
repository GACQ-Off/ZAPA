
<?php

require_once "conexion.php";

class ModeloClientes{

	/*=============================================
	CREAR CLIENTE
	=============================================*/

	
	static public function mdlIngresarCliente($tabla, $datos){

    $stmt = Conexion::conectar()->prepare("INSERT INTO $tabla 
        (tipo_ced, num_ced, nombre, apellido, direccion, email, prefijo_telefono, numero_telefono)
        VALUES (:tipo_ced, :num_ced, :nombre, :apellido, :direccion, :email, :prefijo_telefono, :numero_telefono)");

    $stmt->bindParam(":tipo_ced", $datos["tipo_ced"], PDO::PARAM_STR);
    $stmt->bindParam(":num_ced", $datos["num_ced"], PDO::PARAM_STR);
    $stmt->bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);
    $stmt->bindParam(":apellido", $datos["apellido"], PDO::PARAM_STR);
    $stmt->bindParam(":direccion", $datos["direccion"], PDO::PARAM_STR);
    $stmt->bindParam(":email", $datos["email"], PDO::PARAM_STR);
    $stmt->bindParam(":prefijo_telefono", $datos["prefijo_telefono"], PDO::PARAM_STR);
    $stmt->bindParam(":numero_telefono", $datos["numero_telefono"], PDO::PARAM_STR);

    // Validación para evitar duplicados por PK compuesta
    $verificar = Conexion::conectar()->prepare("SELECT COUNT(*) FROM $tabla WHERE tipo_ced = :tipo AND num_ced = :num");
    $verificar->bindParam(":tipo", $datos["tipo_ced"], PDO::PARAM_STR);
    $verificar->bindParam(":num", $datos["num_ced"], PDO::PARAM_STR);
    $verificar->execute();

    if ($verificar->fetchColumn() > 0) {
        return "repetido";
    }

    if($stmt->execute()){
        return "ok";
    } else {
        return "error";
    }

    $stmt = null;
    $verificar = null;
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

	static public function mdlEditarCliente($tabla, $datos) {

    $stmt = Conexion::conectar()->prepare("UPDATE $tabla 
        SET 
            tipo_ced = :tipo_ced,
            num_ced = :num_ced,
            nombre = :nombre,
            apellido = :apellido,
            email = :email,
            direccion = :direccion,
            prefijo_telefono = :prefijo_telefono,
            numero_telefono = :numero_telefono
        WHERE 
            tipo_ced = :tipo_ced_original AND 
            num_ced = :num_ced_original
    ");

    // Nuevos valores
    $stmt->bindParam(":tipo_ced", $datos["tipo_ced"], PDO::PARAM_STR);
    $stmt->bindParam(":num_ced", $datos["num_ced"], PDO::PARAM_STR);
    $stmt->bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);
    $stmt->bindParam(":apellido", $datos["apellido"], PDO::PARAM_STR);
    $stmt->bindParam(":email", $datos["email"], PDO::PARAM_STR);
    $stmt->bindParam(":direccion", $datos["direccion"], PDO::PARAM_STR);
    $stmt->bindParam(":prefijo_telefono", $datos["prefijo_telefono"], PDO::PARAM_STR);
    $stmt->bindParam(":numero_telefono", $datos["numero_telefono"], PDO::PARAM_STR);

    // Clave primaria original
    $stmt->bindParam(":tipo_ced_original", $datos["tipo_ced_original"], PDO::PARAM_STR);
    $stmt->bindParam(":num_ced_original", $datos["num_ced_original"], PDO::PARAM_STR);

    $resultado = $stmt->execute() ? "ok" : "error";

    $stmt = null;
    return $resultado;
}



  static public function mdlEliminarCliente($tabla, $datos){
    try {
        $stmt = Conexion::conectar()->prepare(
            "DELETE FROM $tabla WHERE tipo_ced = :tipo_ced AND num_ced = :num_ced"
        );

        $stmt->bindParam(":tipo_ced", $datos["tipo_ced"], PDO::PARAM_STR);
        $stmt->bindParam(":num_ced", $datos["num_ced"], PDO::PARAM_STR);

        if($stmt->execute()){
            return "ok";
        }

    } catch (PDOException $e) {
        // Verifica si es error por restricción (código SQLSTATE '23000')
        if ($e->getCode() == '23000') {
            error_log("Restricción de integridad al eliminar cliente: " . $e->getMessage());
            return "restrict";
        } else {
            error_log("Error al eliminar cliente: " . $e->getMessage());
            return "error";
        }
    }

    return "error";
}






	

}