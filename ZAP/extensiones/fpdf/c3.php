<?php
require('fpdf.php');

require_once '../../modelos/productos.modelo.php';
require_once '../../controladores/productos.controlador.php';

require_once '../../modelos/categorias.modelo.php';
require_once '../../controladores/categorias.controlador.php';

$pdf = new FPDF('L', 'mm', 'A4');
$pdf->AddPage();

// Título en negrita y centrado
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, 'Reporte de Inventario', 0, 1, 'C');
// Obtener la fecha actual formateada
// Obtener la fecha y hora actual en el formato deseado
$fechaHoraActual = date('d/m/Y H:i:s');

// Crear una celda y escribir la fecha y hora
$pdf->Cell(0, 10, 'Fecha y hora actual: ' . $fechaHoraActual, 0, 1, 'R');


$productos = ControladorProductos::ctrMostrarProductos(null,null,1);



// Tabla de ejemplo
$pdf->SetFillColor(224, 235, 255);
$pdf->Cell(15, 8, '#', 1, 0, 'C', true);
$pdf->Cell(30, 8, 'Codigo', 1, 0, 'C', true);
$pdf->Cell(50, 8, 'Descripcion', 1, 0, 'C', true);
$pdf->Cell(30, 8, 'Categoria', 1, 0, 'C', true);
$pdf->Cell(30, 8, 'Marca', 1, 0, 'C', true);
$pdf->Cell(30, 8, 'Tipo', 1, 0, 'C', true);
$pdf->Cell(30, 8, 'Stock', 1, 0, 'C', true);
$pdf->Cell(30, 8, 'P.de Compra', 1, 0, 'C', true);
$pdf->Cell(30, 8, 'P. de Venta', 1, 1, 'C', true);

$pdf->SetFont('Arial', '', 12);
for ($i = 0; $i < count($productos); $i++) {

 $codigo = $productos[$i]["codigo"];
 $descripcion = $productos[$i]["descripcion"];
 $stock = $productos[$i]["stock"];
 $pcompra = $productos[$i]["precio_compra"];
 $pventa = $productos[$i]["precio_venta"];



 $pdf->Cell(15, 8, $i + 1, 1, 0, 'C');
 $pdf->Cell(30, 8, $codigo, 1, 0, 'C');
 $pdf->Cell(50, 8, $descripcion, 1, 0, 'C');
 $pdf->Cell(30, 8, '', 1, 0, 'C');
$pdf->Cell(30, 8, '', 1, 0, 'C');
$pdf->Cell(30, 8, '', 1, 0, 'C');
 $pdf->Cell(30, 8, $stock, 1, 0, 'C');
 $pdf->Cell(30, 8, $pcompra, 1, 0, 'C');
 $pdf->Cell(30, 8, $pventa, 1, 1, 'C');

  
   
 
 
 
}

// ... Agregar más filas a la tabla

$pdf->Output('Reporte Asistencia.pdf', 'I');//nombreDescarga, Visor(I->visualizar - D->descargar);

