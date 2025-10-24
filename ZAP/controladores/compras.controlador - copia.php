<?php

// ==============================================================================
// RUTAS DE INCLUSIÓN DENTRO DEL CONTROLADOR DE COMPRAS
// (Usando tu convención de carpetas en minúsculas)
// ==============================================================================

// 🛑 Modelo principal de Compras
require_once __DIR__ . "/../modelos/compras.modelo.php"; 

// 🛑 Otros Modelos necesarios para la carga de datos (Proveedores y Productos)
//    Asume que tienes un 'proveedor.modelo.php' y 'productos.modelo.php'
require_once __DIR__ . "/../modelos/productos.modelo.php"; 
require_once __DIR__ . "/../modelos/proveedor.modelo.php"; 
require_once __DIR__ . "/../modelos/eventolog.modelo.php"; 

// 🚫 NO incluimos otros Controladores aquí para evitar el bucle infinito.

class ControladorCompras {

    /*=============================================
    MÉTODO PARA MOSTRAR LAS COMPRAS
    =============================================*/
    static public function ctrMostrarCompras($item, $valor) {
        $tabla = "compras";
        
        // Llamada al nuevo método del modelo que incluye el JOIN con proveedores
        $respuesta = ModeloCompras::mdlMostrarCompras($tabla, $item, $valor);
        
        return $respuesta;
    }
    /*=============================================
    MÉTODO PARA CREAR UNA NUEVA COMPRA
    =============================================*/
    static public function ctrCrearCompra() {

        if (isset($_POST["nuevoProveedor"]) && isset($_POST["numero_factura_proveedor"])) {
            
            // 1. OBTENER Y PREPARAR DATOS DE LA CABECERA (compras)
            $rifCompleto = $_POST["nuevoProveedor"]; 
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
            
            // 2. GUARDAR LA CABECERA DE LA COMPRA
            $respuestaCabecera = ModeloCompras::mdlIngresarCompra("compras", $datosCompra);

            if ($respuestaCabecera === "ok") {
                
                $id_compra = ModeloCompras::mdlObtenerUltimoId(); 
                $listaProductosCompra = json_decode($_POST["listaProductosCompra"], true);

                // 3. GUARDAR LOS DETALLES (SIN ACTUALIZAR EL STOCK)
                foreach ($listaProductosCompra as $producto) {
                    
                    $datosDetalle = array(
                        "id_compra"                     => $id_compra,
                        "codigo_producto"               => $producto["codigo_producto"], 
                        "cantidad"                      => $producto["cantidad"],
                        "precio_compra_unitario_bs"     => $producto["precio_compra_unitario_bs"],
                        "precio_compra_unitario_usd"    => $producto["precio_compra_unitario_usd"]
                    );
                    
                    // i. Guardar en detalle_compra
                    ModeloCompras::mdlIngresarDetalleCompra("detalle_compra", $datosDetalle);
                    
                    // ii. PENDIENTE: Actualizar inventario (COMENTADO PARA IMPLEMENTACIÓN GRADUAL)
                    /*
                    // Descomentar y usar el modelo correcto cuando la lógica de stock esté lista.
                    ModeloProductos::mdlAumentarStock("productos", $producto["codigo_producto"], $producto["cantidad"]);
                    */
                }

                // 4. Mostrar Alerta de Éxito
                echo '<script>
                    swal({
                        type: "success",
                        title: "¡La compra ha sido registrada correctamente!",
                        text: "El stock de productos NO ha sido actualizado. Implemente la lógica de stock para completar el proceso.",
                        showConfirmButton: true,
                        confirmButtonText: "Cerrar"
                    }).then(function(result){
                        if (result.value) {
                            window.location = "compras";
                        }
                    })
                </script>';

            } else {
                // 4. Mostrar Alerta de Error
                echo '<script>
                    swal({
                        type: "error",
                        title: "¡Error al registrar la compra, intente de nuevo!",
                        showConfirmButton: true,
                        confirmButtonText: "Cerrar"
                    }).then(function(result){
                        if (result.value) {
                            window.location = "compras";
                        }
                    })
                </script>';
            }
        }
    }
}