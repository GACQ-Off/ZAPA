<?php

// Asegúrate de que fpdf.php está en el mismo directorio o la ruta es correcta
require('fpdf.php');

// --- INICIO DE GESTIÓN DE RUTAS (CRÍTICO PARA LA CONSISTENCIA) ---
// Define ROOT_PATH si no está ya definido.
// Si este archivo está en 'vistas/modulos/reportes/fpdf/', sube tres niveles para llegar a la raíz del proyecto.
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(dirname(dirname(__DIR__))) . DIRECTORY_SEPARATOR);
}
// --- FIN DE GESTIÓN DE RUTAS ---

// Ahora usa ROOT_PATH para todas las inclusiones
require_once '../../modelos/ventas.modelo.php';
require_once '../../controladores/ventas.controlador.php';

require_once "../../controladores/configuracion.controlador.php";
require_once "../../modelos/configuracion.modelo.php";


require_once '../../modelos/clientes.modelo.php';
require_once '../../controladores/clientes.controlador.php';

require_once '../../modelos/empleados.modelo.php';
require_once '../../controladores/empleados.controlador.php';

// INCLUSIONES NECESARIAS PARA EL REGISTRO DE EVENTOS LOG
require_once '../../controladores/eventolog.controlador.php';

// FIN DE INCLUSIONES PARA EL REGISTRO DE EVENTOS LOG


date_default_timezone_set('America/El_Salvador');

class PDF extends FPDF
{
// Cabecera de página
function Header()
{
    $this->Image('img/logo.png',0,1,62);


    $this->SetY(5);
    $this->SetX(60);
    $this->SetFont('Arial','B',15);

    $empresa = ControladorConfiguracion::ctrMostrarConfigracion(null,null);
    $nombreEmpresa = $empresa["nombre"];
    $rifEmpresa = $empresa["rif"];
    $localizacionEmpresa = $empresa["direccion"];
    $telefonoEmpresa = $empresa["telefono"];

    $this->SetTextColor(0, 0, 0 );
    $this->Cell (50, 25, utf8_decode($nombreEmpresa),0,1);


    $this->SetY(15);
    $this->SetX(60);
    $this->SetFont('Arial','B',15);
    $this->Cell(80, 20, "Rif: " .$rifEmpresa,0,1);
    $this->SetY(21);
    $this->SetX(60);
    $this->SetFont('Arial','B',15);
    $this->Cell(80, 20, utf8_decode("Dirección: ".$localizacionEmpresa),0,1);
    $this->SetY(28);
    $this->SetX(60);
    $this->SetFont('Arial','B',15);
    $this->Cell(80, 20, utf8_decode("Telefonó: ".$telefonoEmpresa),0,1);
    $this->SetY(55);
    $this->SetX(70);
    $this->SetFont('Arial','',22);
    $this->Cell(40, 8,"Reporte Diario de Ventas");
    $this->SetTextColor(30,10,32);
    $this->Ln(30);




}

function Footer()
{
    $this->SetFont('helvetica', 'B', 12);
        $this->SetY(-15);
        $this->Cell(95,5,utf8_decode('Página ').$this->PageNo().' / {nb}',0,0,'L');
        $this->Cell(95,5,date('d/m/Y | g:i:a') ,00,1,'R');
        $this->Line(10,287,200,287);
        //$this->Cell(0,5,utf8_decode("Kodo Sensei © Todos los derechos reservados."),0,0,"C");

}


}

$totalCompleto = 0;


$pdf = new PDF();

$pdf->AliasNbPages();
$pdf->AddPage();

$pdf->SetAutoPageBreak(true, 20);

$pdf->SetTopMargin(500);
$pdf->SetLeftMargin(10);
$pdf->SetRightMargin(10);
$pdf->SetX(15);
$pdf->SetFillColor(070, 130, 180);
$pdf->SetDrawColor(255, 255, 255);
// Cell(ancho , alto,texto,borde(0/1),salto(0/1),alineacion(L,C,R),rellenar(0/1)

$pdf->SetFont('Arial','B',12);
$pdf->Cell(12, 12, utf8_decode('N°'),1,0,'C',1);
$pdf->Cell(27, 12, utf8_decode('Factura'),1,0,'C',1);
$pdf->Cell(45, 12, utf8_decode('Cliente'),1,0,'C',1);
$pdf->Cell(45, 12, utf8_decode('Vendedor'),1,0,'C',1);
$pdf->Cell(25, 12, utf8_decode('Fecha'),1,0,'C',1);
$pdf->Cell(30, 12, utf8_decode('Total'),1,1,'C',1);

$ventas = ControladorVentas::ctrMostrarVentas(null, null, 1);
$pdf->SetFont('Arial','',10);
$fecha_actual = date('Y-m-d'); // Obtiene la fecha actual en formato YYYY-MM-DD
$i=1;
foreach ($ventas as $venta) {
    // Verificar si la fecha de la venta coincide con la fecha actual
    if (date('Y-m-d', strtotime($venta["fecha"])) === $fecha_actual) {
        $factura = $venta["factura"];
        $cliente = ControladorClientes::ctrMostrarClientes("cedula",$venta["cliente"]);
        $cliente = $cliente["nombre"]." ".$cliente["apellido"];
        $vendedor = ControladorEmpleados::ctrMostrarEmpleados("cedula",$venta["vendedor"]);
        $vendedor = $vendedor["nombre"]." ".$vendedor["apellido"];
        $fecha = (new DateTime($venta["fecha"]))->format("d-m-Y");
        $total = $venta["total"];

        $totalCompleto += $total;


        $pdf->SetX(15);
        $pdf->SetFillColor(255,255,255);
        $pdf->SetDrawColor(65, 61, 61);
        $pdf->Cell(12, 8, utf8_decode($i),'B',0,'C',1);
        $pdf->Cell(27, 8, utf8_decode($factura),'B',0,'C',1);
        $pdf->Cell(45, 8, utf8_decode(ucwords($cliente)),'B',0,'C',1);
        $pdf->Cell(45, 8, utf8_decode(ucwords($vendedor)),'B',0,'C',1);
        $pdf->Cell(25, 8, utf8_decode($fecha),'B',0,'C',1);
        $pdf->Cell(30, 8, number_format($total, 2, ',', '.').' Bs','B',1,'C',1);

        $pdf->Ln(0.5);
        $i++; // Incrementar el contador
    }
}

$pdf->Ln(5);
$pdf->Cell(160, 8, utf8_decode("Total   "),0,0,'R');
$pdf->Cell(30,8,number_format("$totalCompleto", 2, ',', '.').' Bs',1,0,'C');

// --- INICIO DEL BLOQUE PARA REGISTRAR EL EVENTO DE LOG ---
// Aquí se registra el evento de log UNA SOLA VEZ, al final de la generación del reporte.
// Esto asume que tienes una sesión iniciada y la cédula del empleado está disponible.
// Si no hay sesión, usará "Sistema/Desconocido" como cedula.
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$empleadoCedula = (isset($_SESSION["cedula"])) ? $_SESSION["cedula"] : "Sistema/Desconocido";

$logData = array(
    "event_type" => "Generación de Reporte PDF",
    "description" => "Se generó un reporte Diario de Ventas en formato PDF. Total: " . number_format($totalCompleto, 2, ',', '.') . " Bs.",
    "employee_cedula" => $empleadoCedula,
    "affected_table" => "ventas", // La tabla principal de la que se reporta
    "affected_row_id" => "N/A" // No hay una fila específica afectada por la generación del reporte completo
);

// Llama al controlador de eventos
ControladorEventoLog::ctrGuardarEventoLog(
    $logData["event_type"],
    $logData["description"],
    $logData["employee_cedula"],
    $logData["affected_table"],
    $logData["affected_row_id"]
);
// --- FIN DEL BLOQUE PARA REGISTRAR EL EVENTO DE LOG ---

$pdf->Output();
?>