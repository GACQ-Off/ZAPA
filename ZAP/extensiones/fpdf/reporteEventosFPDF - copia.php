<?php

// Incluir la librería FPDF
require('fpdf.php');

// --- INICIO DE GESTIÓN DE RUTAS (CRÍTICO PARA LA CONSISTENCIA) ---
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(dirname(dirname(__DIR__))) . DIRECTORY_SEPARATOR);
}
// --- FIN DE GESTIÓN DE RUTAS ---

// Se mantienen tus inclusiones existentes tal cual
require_once "../../controladores/configuracion.controlador.php";
require_once "../../modelos/configuracion.modelo.php";

// *******************************************************************
// Asegurarse de que estas inclusiones son correctas y los archivos existen
require_once '../../modelos/eventolog.modelo.php';
require_once '../../controladores/eventolog.controlador.php';
// *******************************************************************

// Establece la zona horaria a Venezuela
date_default_timezone_set('America/Caracas'); // Hora actual: 4:03:15 PM -04 en Guanare, Portuguesa, Venezuela

class PDF extends FPDF
{
    // Cabecera de página (se mantiene igual, solo se cambia el título del reporte)
    function Header()
    {
        // Ajusta la ruta a tu logo si es necesario. ROOT_PATH es relativo a la raíz del proyecto.
        $this->Image('img/logo.png',0,1,62);

        $this->SetY(5);
        $this->SetX(70);
        $this->SetFont('Arial', 'B', 20);

        $empresa = ControladorConfiguracion::ctrMostrarConfigracion(null, null);
        $nombreEmpresa = $empresa["nombre"];
        $rifEmpresa = $empresa["rif"];
        $localizacionEmpresa = $empresa["direccion"];
        $telefonoEmpresa = $empresa["telefono"];

        $this->SetTextColor(0, 0, 0);
        $this->Cell(50, 25, utf8_decode($nombreEmpresa), 0, 1);

        $this->SetY(15);
        $this->SetX(70);
        $this->SetFont('Arial', 'B', 20);
        $this->Cell(80, 20, "Rif: " . $rifEmpresa, 0, 1);
        $this->SetY(21);
        $this->SetX(70);
        $this->SetFont('Arial', 'B', 20);
        $this->Cell(80, 20, utf8_decode("Dirección: " . $localizacionEmpresa), 0, 1);
        $this->SetY(28);
        $this->SetX(70);
        $this->SetFont('Arial', 'B', 20);
        $this->Cell(80, 20, utf8_decode("Teléfono: " . $telefonoEmpresa), 0, 1);

        $this->SetY(55);
        $this->SetX(96);
        $this->SetFont('Arial', '', 30);
        // CAMBIO: Título del reporte para eventos
        $this->Cell(60, 8, utf8_decode("Reporte de Eventos del Sistema"));
        $this->SetTextColor(30, 10, 32);
        $this->Ln(20);
    }

    // Pie de página (se mantiene igual)
    function Footer()
    {
        $this->SetFont('helvetica', 'B', 12);
        $this->SetY(-15);
        $this->Cell(95, 5, utf8_decode('Página ') . $this->PageNo() . ' / {nb}', 0, 0, 'L');
        $this->Cell(172, 5, date('d/m/Y | g:i:a'), 0, 1, 'R');
        $this->Line(10, 287, 200, 287);
    }
}

$pdf = new PDF();

$pdf->AliasNbPages();
$pdf->AddPage('L'); // Orientación Horizontal (Landscape)

$pdf->SetAutoPageBreak(true, 20);

// Márgenes y posición inicial para la tabla
$pdf->SetTopMargin(70);
$pdf->SetLeftMargin(10);
$pdf->SetRightMargin(10);
$pdf->SetX(10);

// Colores de la cabecera de la tabla
$pdf->SetFillColor(70, 130, 180);
$pdf->SetDrawColor(255, 255, 255);

// CAMBIO: Cabecera de la tabla de eventos
$pdf->SetFont('Arial', 'B', 9); // Fuente un poco más pequeña para más columnas

$pdf->Cell(10, 12, utf8_decode('ID'), 1, 0, 'C', 1);
$pdf->Cell(35, 12, utf8_decode('Fecha y Hora'), 1, 0, 'C', 1);
$pdf->Cell(50, 12, utf8_decode('Tipo de Evento'), 1, 0, 'C', 1);
$pdf->Cell(85, 12, utf8_decode('Descripción'), 1, 0, 'C', 1);
$pdf->Cell(30, 12, utf8_decode('Cédula Emp.'), 1, 0, 'C', 1);
$pdf->Cell(30, 12, utf8_decode('Tabla Afectada'), 1, 0, 'C', 1);
$pdf->Cell(30, 12, utf8_decode('ID Fila Afectada'), 1, 1, 'C', 1); // Salto de línea al final de la cabecera


// *******************************************************************
// CAMBIO CRÍTICO: Se usa 'ctrMostrarEventosLog' (plural) según tu controlador
$eventos = ControladorEventoLog::ctrMostrarEventosLog(null, null);
// *******************************************************************

$pdf->SetFont('Arial', '', 7); // Fuente más pequeña para los datos

if (is_array($eventos) && !empty($eventos)) {
    foreach ($eventos as $evento) { // Itera sobre cada evento obtenido
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetDrawColor(65, 61, 61);

        $pdf->SetX(10);

        // Celdas con datos de eventos
        $pdf->Cell(10, 8, utf8_decode($evento["id"]), 'B', 0, 'C', 1);
        $pdf->Cell(35, 8, utf8_decode($evento["timestamp"]), 'B', 0, 'C', 1);
        $pdf->Cell(50, 8, utf8_decode($evento["event_type"]), 'B', 0, 'L', 1);

        // MultiCell para la descripción, ya que puede ser larga
        $xDesc = $pdf->GetX();
        $yDesc = $pdf->GetY();
        $pdf->MultiCell(85, 8, utf8_decode($evento["description"]), 'B', 'L', 1);

        // Reposicionar el cursor para las celdas restantes de la misma fila
        $currentY = $pdf->GetY();
        $pdf->SetXY($xDesc + 85, $yDesc);

        $pdf->Cell(30, 8, utf8_decode($evento["employee_cedula"]), 'B', 0, 'C', 1);
        $pdf->Cell(30, 8, utf8_decode($evento["affected_table"]), 'B', 0, 'C', 1);
        $pdf->Cell(30, 8, utf8_decode($evento["affected_row_id"]), 'B', 1, 'C', 1);

        // Asegurar que la siguiente fila comience en la Y correcta
        $pdf->SetY(max($currentY, $pdf->GetY()));
        $pdf->Ln(0.5);
    }
} else {
    $pdf->SetX(10);
    $pdf->Cell(280, 10, utf8_decode('No se encontraron eventos para mostrar.'), 1, 1, 'C');
}

// INICIO DEL BLOQUE PARA REGISTRAR EL EVENTO DE LOG (se mantiene igual)
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$empleadoCedula = (isset($_SESSION["cedula"])) ? $_SESSION["cedula"] : "Sistema/Desconocido";

$logData = array(
    "event_type" => "Generación de Reporte PDF",
    "description" => "Se generó un reporte de Eventos del Sistema en formato PDF.",
    "employee_cedula" => $empleadoCedula,
    "affected_table" => "event_log",
    "affected_row_id" => "N/A"
);

ControladorEventoLog::ctrGuardarEventoLog(
    $logData["event_type"],
    $logData["description"],
    $logData["employee_cedula"],
    $logData["affected_table"],
    $logData["affected_row_id"]
);

$pdf->Output();