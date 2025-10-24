<?php

ini_set("display_errors", 0);
error_reporting(E_ALL);

// Controladores y modelos
require_once "../controladores/ventas.controlador.php";
require_once "../modelos/ventas.modelo.php";
require_once "../controladores/venta_producto.controlador.php";
require_once "../modelos/venta_producto.modelo.php";
require_once "../controladores/productos.controlador.php";
require_once "../modelos/productos.modelo.php";

class AjaxVentas {

    public $codigoFacturaDetalle;

    public function ajaxMostrarDetalleVenta() {

        if (!isset($this->codigoFacturaDetalle)) {
            header('Content-Type: application/json');
            echo json_encode(["error" => "No se recibió el código de factura."]);
            return;
        }

        $codigoFactura = $this->codigoFacturaDetalle;

        $productosVenta = ControladorVentasProductos::ctrMostrarVentasProductos("factura", $codigoFactura);

        $detallesProductos = [];

        if (!empty($productosVenta)) {
            foreach ($productosVenta as $item) {
                $codigoProducto = $item["producto"] ?? null;

                if ($codigoProducto) {
                    $productoMaestro = ControladorProductos::ctrMostrarProductos("codigo", $codigoProducto, "codigo");

                    $descripcion = $productoMaestro["descripcion"] ?? "Sin descripción";
                    $precioUnitario = $item["precio_unitario"] ?? 0;
                    $cantidad = $item["cantidad"] ?? 0;
                    $totalLinea = $item["total"] ?? ($cantidad * $precioUnitario);

                    $detallesProductos[] = [
                        "codigo" => $codigoProducto,
                        "descripcion" => $descripcion,
                        "cantidad" => $cantidad,
                        "precio_unitario" => $precioUnitario,
                        "precio_total_en_linea" => $totalLinea
                    ];
                }
            }
        }

        $ventaPrincipal = ControladorVentas::ctrMostrarVentas("factura", $codigoFactura);

        $respuesta = [
            "productos" => $detallesProductos,
            "netoTotal" => $ventaPrincipal["pago"] ?? 0,
            "impuestoTotal" => $ventaPrincipal["impuesto"] ?? 0,
            "totalGeneral" => $ventaPrincipal["total"] ?? 0
        ];

        header('Content-Type: application/json');
        echo json_encode($respuesta);
    }
}

// Proceso
if (isset($_POST["codigoFacturaDetalle"])) {
    $ajaxVentas = new AjaxVentas();
    $ajaxVentas->codigoFacturaDetalle = $_POST["codigoFacturaDetalle"];
    $ajaxVentas->ajaxMostrarDetalleVenta();
} else {
    header('Content-Type: application/json');
    echo json_encode(["error" => "Acceso no permitido directamente."]);
}
