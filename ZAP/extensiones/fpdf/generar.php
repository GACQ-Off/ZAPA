<?php
require_once '../../conexion.php';
require_once 'fpdf/fpdf.php';

$pdf = new FPDF('P', 'mm', array(80, 200));
$pdf->AddPage();
$pdf->SetMargins(5, 0, 0);
$pdf->SetTitle("Ventas");
$pdf->SetFont('Arial', 'B', 12);

// Invoice details (replace with actual data retrieval)
$invoiceId = "INV-000001"; // Replace with actual invoice ID
$clientId = 1; // Replace with actual client ID
$companyName = "Your Company Name"; // Replace with actual company name
$companyAddress = "Your Company Address"; // Replace with actual company address
$clientName = "Client Name"; // Replace with actual client name
$clientCedula = "V-12345678"; // Replace with actual client ID number
$paymentMethod = "CONTADO"; // Replace with actual payment method
$issueDate = date("d-m-Y"); // Replace with actual issue date

// Invoice items (replace with actual data retrieval)
$items = array(
  array(
    "description" => "Product Description 1",
    "quantity" => 1,
    "price" => 10.00,
  ),
  array(
    "description" => "Product Description 2",
    "quantity" => 2,
    "price" => 5.00,
  ),
);

// Calculations (replace with actual calculations based on retrieved data)
$subTotal = 20.00;
$discount = 0.00;
$total = 20.00;
$iva = 3.20; // Replace with actual IVA calculation
$exento = 0.00; // Replace with actual exento calculation
$grandTotal = 23.20; // Replace with actual grand total calculation

// Generate invoice content

$pdf->SetFont('Arial', 'B', 25);
$pdf->Cell(60, 5, utf8_decode("FACTURA"), 0, 1, 'C');

$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(30, 5, utf8_decode($companyName), 0, 1, 'L');
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(15, 5, utf8_decode($companyAddress), 0, 0, 'L');
$pdf->Ln();

$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(25, 5, utf8_decode("Nro de Factura. "), 0, 0, 'L');
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(15, 5, $invoiceId, 0, 1, 'L');
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(15, 5, utf8_decode($clientName), 0, 1, 'L');
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(5, 5, utf8_decode("CI. "), 0, 0, 'L');
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(15, 5, $clientCedula, 0, 1, 'L');
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(20, 5, utf8_decode($paymentMethod), 0, 0, 'L');
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(30, 5, utf8_decode("Fecha de Emisión"), 0, 0, 'L');
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(10, 5, $issueDate, 0, 0, 'L');
$pdf->Ln();

$pdf->SetFont('Arial', 'B', 8);
$pdf->SetTextColor(255, 255, 255);
$pdf->Cell(70, 5, "Detalle de Producto", 1, 1, 'C', 1);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(27, 5, utf8_decode



