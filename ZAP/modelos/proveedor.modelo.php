<?php
ini_set('display_errors',1);
error_reporting(E_ALL);

require_once "conexion.php";

class ModeloProveedor{

	/*=============================================
	CREAR PROVEEDOR
	=============================================*/

	static public function mdlIngresarProveedor($tabla, $datos){

		// Se verifica si el proveedor ya existe utilizando tipo_rif y num_rif
		$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE tipo_rif = :tipo_rif AND num_rif = :num_rif");
		$stmt->bindParam(":tipo_rif", $datos["tipo_rif"], PDO::PARAM_STR);
		$stmt->bindParam(":num_rif", $datos["num_rif"], PDO::PARAM_STR);
		$stmt->execute();
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

		if(count($result) > 0){
			return "repetido";
		}

		$stmt = Conexion::conectar()->prepare(
			"INSERT INTO $tabla(
				tipo_rif,
				num_rif,
				nombre,
				nombre_representante,
				tipo_ced,
				num_ced,
				correo,
				apellido_representante,
				direccion,
				prefijo_telefono,
				numero_telefono
			) VALUES (
				:tipo_rif,
				:num_rif,
				:nombre,
				:nombre_representante,
				:tipo_ced,
				:num_ced,
				:correo,
				:apellido_representante,
				:direccion,
				:prefijo_telefono,
				:numero_telefono
			)"
		);

		$stmt->bindParam(":tipo_rif", $datos["tipo_rif"], PDO::PARAM_STR);
		$stmt->bindParam(":num_rif", $datos["num_rif"], PDO::PARAM_STR);
		$stmt->bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);
		$stmt->bindParam(":nombre_representante", $datos["nombre_representante"], PDO::PARAM_STR);
		$stmt->bindParam(":tipo_ced", $datos["tipo_ced"], PDO::PARAM_STR);
		$stmt->bindParam(":num_ced", $datos["num_ced"], PDO::PARAM_STR);
		$stmt->bindParam(":correo", $datos["correo"], PDO::PARAM_STR);
		$stmt->bindParam(":apellido_representante", $datos["apellido_representante"], PDO::PARAM_STR);
		$stmt->bindParam(":direccion", $datos["direccion"], PDO::PARAM_STR);
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
	MOSTRAR PROVEEDORES
	=============================================*/

	static public function mdlMostrarProveedor($tabla, $item, $valor){

	    if($item != null && $valor != null){
	        // Si el "item" es "rif", se asume que el "valor" es el RIF completo (ej. "J-123456789")
	        // Se divide para usar en la consulta WHERE
	        if ($item == "rif") {
	            list($tipo_rif, $num_rif) = explode('-', $valor, 2);
	            $stmt = Conexion::conectar()->prepare(
	                "SELECT *, tipo_ced, num_ced, prefijo_telefono, numero_telefono
	                 FROM $tabla
	                 WHERE tipo_rif = :tipo_rif AND num_rif = :num_rif"
	            );
	            $stmt -> bindParam(":tipo_rif", $tipo_rif, PDO::PARAM_STR);
	            $stmt -> bindParam(":num_rif", $num_rif, PDO::PARAM_STR);
	        } else {
	            // Para otros items, se mantiene la lógica original
	            $stmt = Conexion::conectar()->prepare(
	                "SELECT *, tipo_ced, num_ced, prefijo_telefono, numero_telefono FROM $tabla WHERE $item = :$item"
	            );
	            $stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);
	        }

	        $stmt -> execute();
	        $result = $stmt -> fetch(PDO::FETCH_ASSOC);
	        return $result;

	    }else{
	        $stmt = Conexion::conectar()->prepare(
	            "SELECT *, tipo_ced, num_ced, prefijo_telefono, numero_telefono FROM $tabla"
	        );

	        $stmt -> execute();
	        $result = $stmt -> fetchAll(PDO::FETCH_ASSOC);
	        return $result;

	    }

	    $stmt -> close();

	    $stmt = null;
	}


	/*=============================================
	EDITAR PROVEEDOR
	=============================================*/

	public static function mdlEditarProveedor($tabla, $datos) {
    $stmt = Conexion::conectar()->prepare("UPDATE $tabla SET 
        nombre = :nombre,
        nombre_representante = :nombre_representante,
        apellido_representante = :apellido_representante,
        tipo_ced = :tipo_ced,
        num_ced = :num_ced,
        correo = :correo,
        prefijo_telefono = :prefijo_telefono,
        numero_telefono = :numero_telefono,
        direccion = :direccion
        WHERE tipo_rif = :tipo_rif AND num_rif = :num_rif
    ");
    
    $stmt->bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);
    $stmt->bindParam(":nombre_representante", $datos["nombre_representante"], PDO::PARAM_STR);
    $stmt->bindParam(":apellido_representante", $datos["apellido_representante"], PDO::PARAM_STR);
    $stmt->bindParam(":tipo_ced", $datos["tipo_ced"], PDO::PARAM_STR);
    $stmt->bindParam(":num_ced", $datos["num_ced"], PDO::PARAM_STR);
    $stmt->bindParam(":correo", $datos["correo"], PDO::PARAM_STR);
    $stmt->bindParam(":prefijo_telefono", $datos["prefijo_telefono"], PDO::PARAM_STR);
    $stmt->bindParam(":numero_telefono", $datos["numero_telefono"], PDO::PARAM_STR);
    $stmt->bindParam(":direccion", $datos["direccion"], PDO::PARAM_STR);
    $stmt->bindParam(":tipo_rif", $datos["tipo_rif"], PDO::PARAM_STR);
    $stmt->bindParam(":num_rif", $datos["num_rif"], PDO::PARAM_STR);

    return $stmt->execute() ? "ok" : "error";
}

	/*=============================================
	ELIMINAR PROVEEDOR
	=============================================*/

	static public function mdlEliminarProveedor($tabla, $datos){
    try {
        list($tipo_rif, $num_rif) = explode('-', $datos, 2);

        $stmt = Conexion::conectar()->prepare("DELETE FROM $tabla WHERE tipo_rif = :tipo_rif AND num_rif = :num_rif");
        $stmt->bindParam(":tipo_rif", $tipo_rif, PDO::PARAM_STR);
        $stmt->bindParam(":num_rif", $num_rif, PDO::PARAM_STR);

        if ($stmt->execute()) {
            return "ok";
        }

    } catch (PDOException $e) {
        if ($e->getCode() == '23000') {
            error_log("Restricción de integridad al eliminar proveedor: " . $e->getMessage());
            return "restrict";
        } else {
            error_log("Error al eliminar proveedor: " . $e->getMessage());
            return "error";
        }
    }

    return "error";
}


	/*=============================================
	ACTUALIZAR CLIENTE (Este método parece no ser para proveedor, lo dejo sin cambios)
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