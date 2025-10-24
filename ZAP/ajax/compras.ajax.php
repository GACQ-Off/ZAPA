<?php
/**
 * Clase para manejar peticiones AJAX relacionadas con los detalles de Compras.
 * AHORA INCLUYE LA SUMA DE TODOS LOS PRECIOS UNITARIOS EN USD.
 */

// ... (Inclusiones de controladores y modelos) ...
require_once "../controladores/compras.controlador.php";
require_once "../modelos/compras.modelo.php";

require_once "../controladores/productos.controlador.php";
require_once "../modelos/productos.modelo.php";

class AjaxCompras {

    public $idCompraDetalle;

    public function ajaxMostrarDetalleCompra() {

        // ... (Validación de entrada y obtención de cabecera) ...
        if (!isset($this->idCompraDetalle) || empty($this->idCompraDetalle)) {
            header('Content-Type: application/json');
            echo json_encode(["error" => "No se recibió el ID de la compra."]);
            return;
        }

        $idCompra = $this->idCompraDetalle;

        $compraPrincipal = ControladorCompras::ctrMostrarCompras("id_compra", $idCompra);

        if (empty($compraPrincipal)) {
            header('Content-Type: application/json');
            echo json_encode(["error" => "Compra no encontrada."]);
            return;
        }

        $compraPrincipal = $compraPrincipal[0];
        $tasaCambio = (float)($compraPrincipal["tasa_cambio_usd"] ?? 1.0); 

        // 3. Obtener los productos asociados a la compra (Detalle de la tabla)
        $respuestaDetalle = ControladorCompras::ctrMostrarDetalleCompra("id_compra", $idCompra);
        $productosCompra = $respuestaDetalle["productos"] ?? []; 

        $detallesProductos = [];
        $netoTotalCalculadoBS = 0; 
        $totalUSDCalculado = 0;
        
        // 🚀 NUEVA VARIABLE PARA SUMAR SOLO LOS PRECIOS UNITARIOS
        $sumaPreciosUnitariosUSD = 0; 

        // 4. Procesar los detalles de los productos de la compra
        if (!empty($productosCompra)) {
            foreach ($productosCompra as $item) {
                
                $codigoProducto = $item["codigo_producto"] ?? null;
                $descripcion = $item["descripcion_producto"] ?? "Sin descripción";
                $cantidad = (float)($item["cantidad"] ?? 0);
                
                $precioUnitarioUSD = (float)($item["precio_compra_unitario_usd"] ?? 0); 
                $precioUnitarioBS = (float)($item["precio_compra_unitario_bs"] ?? 0);
                
                $totalLineaBS = (float)($item["total_linea_bs_calculado"] ?? ($cantidad * $precioUnitarioBS));
                $totalLineaUSD = $cantidad * $precioUnitarioUSD; 

                // Acumular totales
                $netoTotalCalculadoBS += $totalLineaBS; 
                $totalUSDCalculado += $totalLineaUSD;
                
                // 🚀 SUMAR SOLO EL PRECIO UNITARIO
                $sumaPreciosUnitariosUSD += $precioUnitarioUSD; 

                $detallesProductos[] = [
                    "codigo" => $codigoProducto,
                    "descripcion" => $descripcion,
                    "cantidad" => $cantidad,
                    "precio_compra_unitario_usd" => $precioUnitarioUSD, 
                    "precio_unitario" => $precioUnitarioBS,
                    "precio_total_en_linea" => $totalLineaBS
                ];
            }
        }

        // 5. Preparar la respuesta final
        $respuesta = [
            "productos" => $detallesProductos,
            
            // Totales reales (Total de la compra)
            "total_compra_bs" => (float)($compraPrincipal["total_compra_bs"] ?? $netoTotalCalculadoBS),
            "total_compra_usd" => (float)($compraPrincipal["total_compra_usd"] ?? $totalUSDCalculado),
            
            // Claves para el footer del modal (Neto=BS, Impuesto=USD)
            "netoTotal" => (float)($compraPrincipal["total_compra_bs"] ?? $netoTotalCalculadoBS), 
            "impuestoTotal" => (float)($compraPrincipal["total_compra_usd"] ?? $totalUSDCalculado),
            "totalGeneral" => (float)($compraPrincipal["total_compra_bs"] ?? $netoTotalCalculadoBS),
            
            // 🚀 NUEVO CAMPO AGREGADO
            "suma_precios_unitarios_usd" => $sumaPreciosUnitariosUSD 
        ];

        // 6. Enviar la respuesta JSON
        header('Content-Type: application/json');
        echo json_encode($respuesta);
    }
}
// ... (Ejecución del Proceso AJAX) ...
if (isset($_POST["idCompraDetalle"])) {
    $ajaxCompras = new AjaxCompras();
    $ajaxCompras->idCompraDetalle = $_POST["idCompraDetalle"];
    $ajaxCompras->ajaxMostrarDetalleCompra();    
} else {
    header('Content-Type: application/json');
    echo json_encode(["error" => "Acceso no permitido directamente o falta de datos (ID de compra)."]);
}
?>