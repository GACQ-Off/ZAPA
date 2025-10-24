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

class PDF extends FPDF {
    function Header() {
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(90, 6, utf8_decode("RECIBO DE COMPRA"), 0, 1, 'C');
        $this->Ln(2);

        $logoPath = 'logo.png';
        if (file_exists($logoPath)) {
            $this->Image($logoPath, 5, 15, 18);
        }
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, utf8_decode('Página ').$this->PageNo().'/{nb}', 0, 0, 'C');
    }
}

class imprimirFactura {
    public $codigo;

    public function traerImpresionFactura() {
        $itemVenta = "factura";
        $valorVenta = $this->codigo;
        $respuestaVenta = ControladorVentas::ctrMostrarVentas($itemVenta, $valorVenta);

        $fecha = new DateTime($respuestaVenta["fecha"]);
        $fechaFormateada = $fecha->format("d/m/Y H:i");

        $productosEnVenta = ControladorVentasProductos::ctrMostrarVentasProductos("factura", $respuestaVenta["factura"]);

        $respuestaCliente = ControladorClientes::ctrMostrarClientes("cedula", $respuestaVenta["cliente"]);
        $respuestaVendedor = ControladorEmpleados::ctrMostrarEmpleados("cedula", $respuestaVenta["vendedor"]);
        $empresa = ControladorConfiguracion::ctrMostrarConfigracion(null, null);

        $nombreEmpresa = utf8_decode($empresa["nombre"]);
        $rifEmpresa = $empresa["rif"];
        $direccionEmpresa = utf8_decode($empresa["direccion"]);
        $telefonoEmpresa = $empresa["telefono"];
        $metodoPago = utf8_decode(strtoupper($respuestaVenta["metodo_pago"]));

        $neto = number_format($respuestaVenta["pago"], 2, ',', '.');
        $impuesto = number_format($respuestaVenta["impuesto"], 2, ',', '.');
        $totalVenta = number_format($respuestaVenta["total"], 2, ',', '.');

        $pdf = new PDF('P', 'mm', array(90, 200));
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetMargins(5, 0, 5);
        $pdf->SetAutoPageBreak(true, 15);
        $pdf->SetTitle("Factura de Venta");

        $pdf->SetFont('Arial', '', 8);
        $pdf->SetXY(25, 21);
        $pdf->MultiCell(60, 4, "$nombreEmpresa\nRIF: $rifEmpresa\n$direccionEmpresa\nTel: $telefonoEmpresa", 0, 'L');

        $pdf->Ln(3);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(80, 4, utf8_decode("FACTURA N°: ") . $valorVenta, 0, 1, 'L');
        $pdf->Cell(80, 4, utf8_decode("FECHA: ") . $fechaFormateada, 0, 1, 'L');

        $nombreCliente = utf8_decode(ucwords($respuestaCliente["nombre"] . " " . $respuestaCliente["apellido"]));
        $nombreVendedor = utf8_decode($respuestaVendedor["nombre"] . " " . $respuestaVendedor["apellido"]);

        $pdf->Cell(80, 4, utf8_decode("CLIENTE: ") . $nombreCliente, 0, 1, 'L');
        $pdf->Cell(80, 4, utf8_decode("CI: ") . $respuestaCliente["cedula"], 0, 1, 'L');
        $pdf->MultiCell(80, 4, utf8_decode("DIRECCIÓN: ") . $respuestaCliente["direccion"], 0, 'L');
        $pdf->Cell(80, 4, utf8_decode("VENDEDOR: ") . $nombreVendedor, 0, 1, 'L');
        $pdf->Cell(80, 4, utf8_decode("MÉTODO DE PAGO: ") . $metodoPago, 0, 1, 'L');

        $pdf->Ln(2);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetFillColor(230,230,230);
        $pdf->Cell(80, 5, utf8_decode("DETALLE DE PRODUCTOS"), 1, 1, 'C', true);

        $pdf->SetFont('Arial', 'B', 7);
        $anchoCodigo = 15;
        $anchoDescripcion = 30;
        $anchoCantidad = 10;
        $anchoPU = 12;
        $anchoTotalLinea = 13;

        $pdf->Cell($anchoCodigo, 5, utf8_decode('CÓDIGO'), 1, 0, 'C', true);
        $pdf->Cell($anchoDescripcion, 5, utf8_decode('DESCRIPCIÓN'), 1, 0, 'C', true);
        $pdf->Cell($anchoCantidad, 5, 'CANT.', 1, 0, 'C', true);
        $pdf->Cell($anchoPU, 5, 'P.UNIT.', 1, 0, 'C', true);
        $pdf->Cell($anchoTotalLinea, 5, 'TOTAL', 1, 1, 'C', true);

        $pdf->SetFont('Arial', '', 7);
        foreach ($productosEnVenta as $item) {
            $producto = ControladorProductos::ctrMostrarProductos("codigo", $item["producto"], "codigo");
            $codigo = $producto["codigo"];
            $descripcion = utf8_decode($producto["descripcion"]);
            $cantidad = $item["cantidad"];
            $precio = number_format($item["precio_unitario"], 2, ',', '.');
            $totalLinea = number_format($cantidad * $item["precio_unitario"], 2, ',', '.');

            $pdf->Cell($anchoCodigo, 5, $codigo, 1, 0, 'L');
            $pdf->Cell($anchoDescripcion, 5, $descripcion, 1, 0, 'L');
            $pdf->Cell($anchoCantidad, 5, $cantidad, 1, 0, 'C');
            $pdf->Cell($anchoPU, 5, $precio, 1, 0, 'R');
            $pdf->Cell($anchoTotalLinea, 5, $totalLinea, 1, 1, 'R');
        }

        $pdf->Ln(1);
        $pdf->SetFont('Arial', 'B', 8);
        $anchoEtiquetaTotal = 45;
        $anchoMontoTotal = 35;

        $pdf->Cell($anchoEtiquetaTotal, 5, utf8_decode('TOTAL NETO:'), 0, 0, 'L');
        $pdf->Cell($anchoMontoTotal, 5, $neto . " Bs", 0, 1, 'R');

        $pdf->Cell($anchoEtiquetaTotal, 5, utf8_decode('IMPUESTO:'), 0, 0, 'L');
        $pdf->Cell($anchoMontoTotal, 5, $impuesto . " Bs", 0, 1, 'R');

        $pdf->Cell($anchoEtiquetaTotal, 5, utf8_decode('TOTAL A PAGAR:'), 0, 0, 'L');
        $pdf->Cell($anchoMontoTotal, 5, $totalVenta . " Bs", 0, 1, 'R');

        $pdf->Ln(5);
        $pdf->SetFont('Arial', 'I', 8);
        $pdf->Cell(80, 5, utf8_decode("Gracias por su compra"), 0, 1, 'C');
        $pdf->Cell(80, 5, utf8_decode("Sin derecho a crédito fiscal"), 0, 1, 'C');

        $pdf->Output("factura_" . $valorVenta . ".pdf", "I");
    }
}

if (isset($_GET["codigo"])) {
    $factura = new imprimirFactura();
    $factura->codigo = $_GET["codigo"];
    $factura->traerImpresionFactura();
}
