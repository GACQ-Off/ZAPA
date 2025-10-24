<?php

// ==============================================================================
// RUTAS DE INCLUSIÓN DENTRO DEL CONTROLADOR DE COMPRAS
// ==============================================================================
require_once __DIR__ . "/../modelos/compras.modelo.php";
require_once __DIR__ . "/../modelos/productos.modelo.php"; 
require_once __DIR__ . "/../modelos/proveedor.modelo.php";
require_once __DIR__ . "/../modelos/eventolog.modelo.php"; 

class ControladorCompras {

    /*=============================================
    MÉTODO PARA MOSTRAR LAS COMPRAS
    =============================================*/
    static public function ctrMostrarCompras($item, $valor) {
        $tabla = "compras";
        $respuesta = ModeloCompras::mdlMostrarCompras($tabla, $item, $valor);
        return $respuesta;
    }

    /*=============================================
    MÉTODO PARA CREAR UNA NUEVA COMPRA (CON DETALLE)
    =============================================*/
    static public function ctrCrearCompra() {

        if (isset($_POST["nuevoProveedor"]) && isset($_POST["numero_factura_proveedor"])) {

            // 1️⃣ OBTENER Y PREPARAR DATOS DE LA CABECERA (compras)
            $rifCompleto = $_POST["nuevoProveedor"]; 
            
            // Dividir RIF (tipo - número)
            if (strpos($rifCompleto, '-') !== false) {
                list($tipoRif, $numRif) = explode("-", $rifCompleto, 2);
            } else {
                $tipoRif = '';
                $numRif = '';
            }

            $datosCompra = array(
                "numero_factura_proveedor" => $_POST["numero_factura_proveedor"],
                "total_compra_bs"          => $_POST["total_compra_bs"], 
                "total_compra_usd"         => $_POST["total_compra_usd"], 
                "tasa_cambio_usd"          => $_POST["tasa_cambio_usd"],
                "observaciones"            => $_POST["observaciones"] ?? null,
                "tipo_rif"                 => $tipoRif,
                "num_rif"                  => $numRif
            );

            try {

                // 2️⃣ GUARDAR LA CABECERA DE LA COMPRA
                $idCompra = ModeloCompras::mdlIngresarCompra("compras", $datosCompra); 

                // 3️⃣ PROCESAR E INSERTAR DETALLE DE COMPRA
                if (is_numeric($idCompra) && $idCompra > 0) {

                    $listaProductos = json_decode($_POST["listaProductos"], true);

                    if (is_array($listaProductos) && !empty($listaProductos)) {
                        foreach ($listaProductos as $detalle) {
                            $datosDetalle = array(
                                "id_compra"                  => $idCompra,
                                "codigo_producto"            => $detalle["codigo_producto"],
                                "cantidad"                   => $detalle["cantidad"],
                                "precio_compra_unitario_bs"  => $detalle["precio_compra_unitario_bs"],
                                "precio_compra_unitario_usd" => $detalle["precio_compra_unitario_usd"]
                            );

                            ModeloCompras::mdlIngresarDetalleCompra("detalle_compra", $datosDetalle);

                            // 🔄 Actualizar stock
                            $datosStock = array(
                                "codigo"        => $detalle["codigo_producto"],
                                "cantidad_sumar"  => $detalle["cantidad"]
                            );
                            ModeloProductos::mdlActualizarStockPorCompra("productos", $datosStock);
                        }
                    }

                    // ✅ ÉXITO
                    echo '<script>
                        swal({
                            type: "success",
                            title: "¡La compra y su detalle han sido registrados correctamente!",
                            showConfirmButton: true,
                            confirmButtonText: "Cerrar"
                        }).then(function(result){
                            if (result.value) {
                                window.location = "compras";
                            }
                        });
                    </script>';
                }

            } catch (PDOException $e) {
                // ⚠️ CAPTURA DE ERROR DE DUPLICADO (23000)
                if ($e->getCode() == 23000) {
                    echo '<script>
                        swal({
                            type: "error",
                            title: "¡Error! La factura ya existe para este proveedor (datos duplicados).",
                            showConfirmButton: true,
                            confirmButtonText: "Cerrar"
                        });
                    </script>';
                } else {
                    // ⚠️ OTRO ERROR DE BASE DE DATOS
                    echo '<script>
                        swal({
                            type: "error",
                            title: "Error en la base de datos: '.addslashes($e->getMessage()).'",
                            showConfirmButton: true,
                            confirmButtonText: "Cerrar"
                        });
                    </script>';
                }
            }
        }
    }

    /*======================================================
    MÉTODO: MOSTRAR DETALLE DE UNA COMPRA (PARA AJAX)
    ======================================================*/
    static public function ctrMostrarDetalleCompra($item, $valor) {
        $tablaDetalle = "detalle_compra";
        $tablaProductos = "productos";
        $respuesta = ModeloCompras::mdlMostrarDetalleCompra($tablaDetalle, $tablaProductos, $item, $valor);
        return [ "productos" => $respuesta ];
    }
}
