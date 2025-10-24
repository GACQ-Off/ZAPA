<?php

require_once "../../controladores/ventas.controlador.php";
require_once "../../modelos/ventas.modelo.php";
require_once "../../controladores/configuracion.controlador.php";
require_once "../../modelos/configuracion.modelo.php";
require_once "../../controladores/venta_producto.controlador.php";
require_once "../../modelos/venta_producto.modelo.php";
require_once "../../controladores/clientes.controlador.php";
require_once "../../modelos/clientes.modelo.php";
require_once "../../controladores/empleados.controlador.php";
require_once "../../modelos/empleados.modelo.php";
require_once "../../controladores/productos.controlador.php";
require_once "../../modelos/productos.modelo.php";

require('fpdf.php');

class imprimirFactura {

    public $codigo;

    public function traerImpresionFactura() {
        $itemVenta = "factura";
        $valorVenta = $this->codigo;
        $respuestaVenta = ControladorVentas::ctrMostrarVentas($itemVenta, $valorVenta);

        $fecha = new DateTime($respuestaVenta["fecha"]);
        $fecha = $fecha->format("d/m/Y H:i");

        $productosEnVenta = ControladorVentasProductos::ctrMostrarVentasProductos("factura", $respuestaVenta["factura"]);

        $respuestaCliente = ControladorClientes::ctrMostrarClientes("cedula", $respuestaVenta["cliente"]);
        $respuestaVendedor = ControladorEmpleados::ctrMostrarEmpleados("cedula", $respuestaVenta["vendedor"]);
        $empresa = ControladorConfiguracion::ctrMostrarConfigracion(null, null);

        $nombreEmpresa = $empresa["nombre"];
        $rifEmpresa = $empresa["rif"];
        $direccionEmpresa = $empresa["direccion"];
        $telefonoEmpresa = $empresa["telefono"];
        $metodoPago = $respuestaVenta["metodo_pago"];

        $neto = number_format($respuestaVenta["pago"], 2, ',', '.');
        $impuesto = number_format($respuestaVenta["impuesto"], 2, ',', '.');
        $totalVenta = number_format($respuestaVenta["total"], 2, ',', '.');

        $pdf = new FPDF('P', 'mm', array(90, 200));
        $pdf->AddPage();
        $pdf->SetMargins(5, 0, 0);
        $pdf->SetTitle("Factura");

        // ENCABEZADO
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(80, 5, "FACTURA", 0, 1, 'C');

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(80, 5, utf8_decode($nombreEmpresa), 0, 1, 'C');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(80, 5, utf8_decode("RIF: $rifEmpresa"), 0, 1, 'C');
        $pdf->Cell(80, 5, utf8_decode($direccionEmpresa), 0, 1, 'C');
        $pdf->Cell(80, 5, utf8_decode("Tel: $telefonoEmpresa"), 0, 1, 'C');

        $pdf->Ln(2);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(80, 5, "Factura N°: " . $valorVenta, 0, 1, 'L');
        $pdf->Cell(80, 5, "Fecha: " . $fecha, 0, 1, 'L');
        $pdf->Cell(80, 5, "Cliente: " . ucwords($respuestaCliente["nombre"] . " " . $respuestaCliente["apellido"]), 0, 1, 'L');
        $pdf->Cell(80, 5, "CI: " . $respuestaCliente["cedula"], 0, 1, 'L');
        $pdf->Cell(80, 5, "Dirección: " . $respuestaCliente["direccion"], 0, 1, 'L');
        $pdf->Cell(80, 5, "Vendedor: " . $respuestaVendedor["nombre"] . " " . $respuestaVendedor["apellido"], 0, 1, 'L');
        $pdf->Cell(80, 5, "Pago: " . strtoupper($metodoPago), 0, 1, 'L');

        $pdf->Ln(2);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetFillColor(0, 0, 0);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->Cell(80, 5, "DETALLE DE PRODUCTOS", 1, 1, 'C', true);
        $pdf->SetTextColor(0, 0, 0);

        // ENCABEZADOS DE TABLA
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(15, 5, 'Código', 0, 0, 'L');
        $pdf->Cell(3, 5, '', 0, 0); // espacio para empujar descripción
        $pdf->Cell(27, 5, 'Descripción', 0, 0, 'L');
        $pdf->Cell(10, 5, 'Cant.', 0, 0, 'L');
        $pdf->Cell(12, 5, 'P.Unit.', 0, 0, 'L');
        $pdf->Cell(13, 5, 'Neto', 0, 1, 'L');

        // DETALLE DE PRODUCTOS
        $pdf->SetFont('Arial', '', 7);
        foreach ($productosEnVenta as $item) {
            $producto = ControladorProductos::ctrMostrarProductos("codigo", $item["producto"], "codigo");

            $codigo = $producto["codigo"];
            $descripcion = utf8_decode(substr($producto["descripcion"], 0, 25));
            $cantidad = $item["cantidad"];
            $precio = number_format($item["precio_unitario"], 2, ',', '.');
            $totalLinea = number_format($cantidad * $item["precio_unitario"], 2, ',', '.');

            $pdf->Cell(15, 5, $codigo, 0, 0, 'L');
            $pdf->Cell(3, 5, '', 0, 0); // mismo espacio que arriba
            $pdf->Cell(27, 5, $descripcion, 0, 0, 'L');
            $pdf->Cell(10, 5, $cantidad, 0, 0, 'L');
            $pdf->Cell(12, 5, $precio, 0, 0, 'L');
            $pdf->Cell(13, 5, $totalLinea, 0, 1, 'L');
        }

        // TOTALES
        $pdf->Ln(2);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(40, 5, 'Total Neto:', 0, 0, 'L');
        $pdf->Cell(35, 5, $neto . " Bs", 0, 1, 'R');
        $pdf->Cell(40, 5, 'Impuesto:', 0, 0, 'L');
        $pdf->Cell(35, 5, $impuesto . " Bs", 0, 1, 'R');
        $pdf->Cell(40, 5, 'Total:', 0, 0, 'L');
        $pdf->Cell(35, 5, $totalVenta . " Bs", 0, 1, 'R');

        $pdf->Ln(4);
        $pdf->SetFont('Arial', 'I', 8);
        $pdf->Cell(80, 5, utf8_decode("Sin derecho a crédito fiscal"), 0, 1, 'C');

        $pdf->Output("factura_$valorVenta.pdf", "I");
    }
}

if (isset($_GET["codigo"])) {
    $factura = new imprimirFactura();
    $factura->codigo = $_GET["codigo"];
    $factura->traerImpresionFactura();
}
