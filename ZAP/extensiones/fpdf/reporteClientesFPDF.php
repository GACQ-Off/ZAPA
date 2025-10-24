<?php
require('fpdf.php');

// Rutas
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR);
}

require_once ROOT_PATH . "controladores/configuracion.controlador.php";
require_once ROOT_PATH . "modelos/configuracion.modelo.php";
require_once ROOT_PATH . "controladores/clientes.controlador.php";
require_once ROOT_PATH . "modelos/clientes.modelo.php";
require_once ROOT_PATH . "controladores/eventolog.controlador.php";

date_default_timezone_set('America/Caracas');

class PDF extends FPDF
{
    function Header()
    {
        $this->Image('img/logo.png', 10, 6, 40);
        $this->SetFont('Arial', 'B', 14);
        $this->SetXY(55, 13);
        $this->SetTextColor(0, 0, 0);

        $empresa = ControladorConfiguracion::ctrMostrarConfigracion(null, null);
        $nombreEmpresa = utf8_decode($empresa["nombre"] ?? 'Empresa');
        $rif = ($empresa["tipo_rif"] ?? '') . '-' . ($empresa["num_rif"] ?? '');
        $direccion = utf8_decode($empresa["direccion"] ?? 'Dirección no disponible');
        $telefono = ($empresa["prefijo_telefono"] ?? '') . '-' . ($empresa["numero_telefono"] ?? '');

        $this->Cell(0, 6, $nombreEmpresa, 0, 1, 'L');
        $this->SetX(55);
        $this->Cell(0, 5, utf8_decode("RIF: $rif"), 0, 1, 'L');
        $this->SetX(55);
        $this->Cell(0, 5, utf8_decode("Dirección: $direccion"), 0, 1, 'L');
        $this->SetX(55);
        $this->Cell(0, 5, utf8_decode("Teléfono: $telefono"), 0, 1, 'L');

        $this->Ln(15);
        $this->SetFillColor(70, 130, 180);
        $this->SetTextColor(255);
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(0, 10, utf8_decode('REPORTE DE CLIENTES'), 0, 1, 'C', true);
        $this->SetTextColor(0);
        $this->Ln(5);
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 9);
        $this->Cell(95, 5, utf8_decode('Página ') . $this->PageNo() . ' / {nb}', 0, 0, 'L');
        $this->Cell(172, 5, date('d/m/Y | g:i:a'), 0, 1, 'R');
    }
}

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage('L');
$pdf->SetAutoPageBreak(true, 20);
$pdf->SetMargins(10, 10, 10);

// Encabezado de tabla
$pdf->SetFont('Arial', 'B', 11);
$pdf->SetFillColor(70, 130, 180);
$pdf->SetTextColor(255);
$pdf->SetDrawColor(200, 200, 200);

$pdf->SetX(10);
$pdf->Cell(12, 10, utf8_decode('N°'), 1, 0, 'C', 1);
$pdf->Cell(40, 10, utf8_decode('Cédula'), 1, 0, 'C', 1);
$pdf->Cell(70, 10, utf8_decode('Nombre y Apellido'), 1, 0, 'C', 1);
$pdf->Cell(35, 10, utf8_decode('Teléfono'), 1, 0, 'C', 1);
$pdf->Cell(60, 10, utf8_decode('Correo Electrónico'), 1, 0, 'L', 1);
$pdf->Cell(60, 10, utf8_decode('Dirección'), 1, 1, 'C', 1);

$pdf->SetTextColor(0);
$pdf->SetFont('Arial', '', 10);
$clientes = ControladorClientes::ctrMostrarClientes(null, null);
$rowCounter = 0;

if (is_array($clientes) && !empty($clientes)) {
    foreach ($clientes as $cliente) {
        $rowCounter++;
        $pdf->SetFillColor($rowCounter % 2 == 0 ? 255 : 245);

        $pdf->SetDrawColor(200, 200, 200);
        $pdf->SetX(10);

        // Datos formateados
        $cedula = ($cliente["tipo_ced"] ?? '') . '-' . ($cliente["num_ced"] ?? '');
        $telefono = ($cliente["prefijo_telefono"] ?? '') . '-' . ($cliente["numero_telefono"] ?? '');
        $nombreCompleto = ucwords($cliente["nombre"] . " " . $cliente["apellido"]);

        $yAntes = $pdf->GetY();
        $pdf->SetX(10 + 12 + 40 + 70 + 35 + 60);
        $pdf->MultiCell(60, 8, utf8_decode($cliente["direccion"]), 0, 'L', 0, true);
        $altura = $pdf->GetY() - $yAntes;
        $altura = max(8, $altura);
        $pdf->SetY($yAntes);
        $pdf->SetX(10);

        $pdf->Cell(12, $altura, $rowCounter, 'LRB', 0, 'C', 1);
        $pdf->Cell(40, $altura, utf8_decode($cedula), 'RB', 0, 'C', 1);
        $pdf->Cell(70, $altura, utf8_decode($nombreCompleto), 'RB', 0, 'L', 1);
        $pdf->Cell(35, $altura, utf8_decode($telefono), 'RB', 0, 'C', 1);
        $pdf->Cell(60, $altura, utf8_decode($cliente["email"]), 'RB', 0, 'L', 1);

        $pdf->SetX(10 + 12 + 40 + 70 + 35 + 60);
        $pdf->MultiCell(60, 8, utf8_decode($cliente["direccion"]), 'RB', 'L', 1);
        $pdf->SetY($yAntes + $altura);
    }
} else {
    $pdf->SetX(10);
    $pdf->Cell(277, 10, utf8_decode('No se encontraron clientes para mostrar.'), 1, 1, 'C');
}

// Registrar en log
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$empleadoCedula = $_SESSION["cedula"] ?? "Sistema/Desconocido";

ControladorEventoLog::ctrGuardarEventoLog(
    "Generación de Reporte PDF",
    "Se generó un reporte de Clientes en formato PDF.",
    $empleadoCedula,
    "clientes",
    "N/A"
);

$pdf->Output();
?>
