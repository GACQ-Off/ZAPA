<?php
//llamado a la libreria
require('fpdf.php');

require_once '../../modelos/ventas.modelo.php';
require_once '../../controladores/ventas.controlador.php';

require_once '../../modelos/clientes.modelo.php';
require_once '../../controladores/clientes.controlador.php';

require_once '../../modelos/empleados.modelo.php';
require_once '../../controladores/empleados.controlador.php';

// Crear un nuevo PDF
$pdf = new FPDF('P', 'cm', array(10, 13)); // P=Portrait, cm=centímetros, tamaño personalizado
$pdf->AddPage();

// Configuración de márgenes
$pdf->SetMargins(1, 1, 1);

// Encabezado
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(0, 1, 'Ventas del Mes' , 0, 1, 'C');

$ventas = ControladorVentas::ctrMostrarVentas(null,null,1);

// Tabla de productos o servicios
$pdf->SetFont('Arial', 'B', 5);
$pdf->Cell(.8, 0.5, '#', 1, 0, 'C');
$pdf->Cell(1.5, 0.5, 'Factura', 1, 0, 'C');
$pdf->Cell(1.7, 0.5, 'Cliente', 1, 0, 'C');
$pdf->Cell(1.7, 0.5, 'Vendedor', 1, 0, 'C');
$pdf->Cell(1.5, 0.5, 'Fecha', 1, 0, 'C');
$pdf->Cell(1, 0.5, 'Total', 1, 1, 'C');

$pdf->SetFont('Arial', '', 5);
// Filas vacías
for ($i = 0; $i < count($ventas); $i++) {

    $factura = $ventas[$i]["factura"];
    $cliente = ControladorClientes::ctrMostrarClientes("cedula",$ventas[$i]["cliente"]);
    $cliente = $cliente["nombre"]." ".$cliente["apellido"];
    $vendedor = ControladorEmpleados::ctrMostrarEmpleados("cedula",$ventas[$i]["vendedor"]);
    $vendedor = $vendedor["nombre"]." ".$vendedor["apellido"];
    $fecha = (new DateTime($ventas[$i]["fecha"]))->format("d-m-Y");
    $total = $ventas[$i]["total"];


    $pdf->Cell(.8, 0.5, $i + 1, 1, 0, 'C');
    $pdf->Cell(1.5, 0.5, $factura, 1, 0, 'C');
    $pdf->Cell(1.7, 0.5, $cliente, 1, 0, 'C');
    $pdf->Cell(1.7, 0.5, $vendedor, 1, 0, 'C');
    $pdf->Cell(1.5, 0.5, $fecha, 1, 0, 'C');
    $pdf->Cell(1, 0.5, $total, 1, 1, 'C');
}



$pdf->Output();
