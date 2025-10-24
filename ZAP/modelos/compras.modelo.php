<?php

require_once "conexion.php"; 

class ModeloCompras {
    
    /*=============================================
    MOSTRAR COMPRAS (Incluye el JOIN con Proveedores)
    =============================================*/
    static public function mdlMostrarCompras($tabla, $item, $valor) {

        if ($item != null) {
            $stmt = Conexion::conectar()->prepare("
                SELECT c.*, p.nombre 
                FROM $tabla c
                INNER JOIN proveedores p ON c.tipo_rif = p.tipo_rif AND c.num_rif = p.num_rif
                WHERE c.$item = :$item
                ORDER BY c.fecha_compra DESC
            ");
            $stmt->bindParam(":" . $item, $valor, PDO::PARAM_STR);
            $stmt->execute();
            $respuesta = $stmt->fetch();
        } else {
            $stmt = Conexion::conectar()->prepare("
                SELECT c.*, p.nombre 
                FROM $tabla c
                INNER JOIN proveedores p ON c.tipo_rif = p.tipo_rif AND c.num_rif = p.num_rif
                ORDER BY c.fecha_compra DESC
            ");
            $stmt->execute();
            $respuesta = $stmt->fetchAll();
        }

        $stmt->closeCursor();
        $stmt = null;
        return $respuesta;
    }

    /*=============================================
    INSERTAR COMPRA (CABECERA) — AHORA MANEJA DUPLICADOS
    =============================================*/
    static public function mdlIngresarCompra($tabla, $datos) {

        try {
            $conexion = Conexion::conectar();

            $stmt = $conexion->prepare("
                INSERT INTO $tabla (
                    numero_factura_proveedor, 
                    total_compra_bs, 
                    total_compra_usd, 
                    tasa_cambio_usd, 
                    observaciones, 
                    tipo_rif, 
                    num_rif
                ) VALUES (
                    :factura, :totalbs, :totalusd, :tasa, :observaciones, :tiporif, :numrif
                )
            ");

            $stmt->bindParam(":factura", $datos["numero_factura_proveedor"], PDO::PARAM_STR);
            $stmt->bindParam(":totalbs", $datos["total_compra_bs"], PDO::PARAM_STR);
            $stmt->bindParam(":totalusd", $datos["total_compra_usd"], PDO::PARAM_STR);
            $stmt->bindParam(":tasa", $datos["tasa_cambio_usd"], PDO::PARAM_STR);
            $stmt->bindParam(":observaciones", $datos["observaciones"], PDO::PARAM_STR);
            $stmt->bindParam(":tiporif", $datos["tipo_rif"], PDO::PARAM_STR);
            $stmt->bindParam(":numrif", $datos["num_rif"], PDO::PARAM_STR);

            $stmt->execute();

            $lastId = $conexion->lastInsertId();
            $stmt->closeCursor();
            $stmt = null;
            return $lastId;

        } catch (PDOException $e) {
            // 🔁 Propagar el error hacia el controlador
            throw $e;
        }
    }

    /*=============================================
    INSERTAR DETALLE DE COMPRA
    =============================================*/
    static public function mdlIngresarDetalleCompra($tabla, $datos) {

        $stmt = Conexion::conectar()->prepare("
            INSERT INTO $tabla (
                id_compra, 
                codigo_producto, 
                cantidad, 
                precio_compra_unitario_bs, 
                precio_compra_unitario_usd
            ) VALUES (
                :idcompra, :codigoprod, :cantidad, :preciobs, :preciousd
            )
        ");

        $stmt->bindParam(":idcompra", $datos["id_compra"], PDO::PARAM_INT);
        $stmt->bindParam(":codigoprod", $datos["codigo_producto"], PDO::PARAM_STR);
        $stmt->bindParam(":cantidad", $datos["cantidad"], PDO::PARAM_INT);
        $stmt->bindParam(":preciobs", $datos["precio_compra_unitario_bs"], PDO::PARAM_STR); 
        $stmt->bindParam(":preciousd", $datos["precio_compra_unitario_usd"], PDO::PARAM_STR);

        if ($stmt->execute()) {
            $stmt->closeCursor();
            $stmt = null;
            return "ok";
        } else {
            error_log("ERROR DB - mdlIngresarDetalleCompra: " . print_r($stmt->errorInfo(), true));
            $stmt->closeCursor();
            $stmt = null;
            return "error";
        }
    }

    /*=============================================
    MOSTRAR DETALLES DE UNA COMPRA (JOIN CON PRODUCTOS)
    =============================================*/
    static public function mdlMostrarDetalleCompra($tablaDetalle, $tablaProductos, $item, $valor) {
        $stmt = Conexion::conectar()->prepare("
            SELECT 
                dc.*, 
                p.descripcion AS descripcion_producto,
                (dc.cantidad * dc.precio_compra_unitario_bs) AS total_linea_bs_calculado
            FROM $tablaDetalle dc
            INNER JOIN $tablaProductos p ON dc.codigo_producto = p.codigo
            WHERE dc.$item = :$item
        ");
        $stmt->bindParam(":" . $item, $valor, PDO::PARAM_INT);
        $stmt->execute();
        $respuesta = $stmt->fetchAll();
        $stmt->closeCursor();
        $stmt = null;
        return $respuesta;
    }
}
