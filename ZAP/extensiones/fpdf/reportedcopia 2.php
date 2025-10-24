<?php
require('fpdf.php');

// --- INICIO DE GESTIÓN DE RUTAS (CRÍTICO PARA LA CONSISTENCIA) ---
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(dirname(dirname(__DIR__))) . DIRECTORY_SEPARATOR);
}
// --- FIN DE GESTIÓN DE RUTAS ---

require_once '../../modelos/ventas.modelo.php';
require_once '../../controladores/ventas.controlador.php';
require_once "../../controladores/configuracion.controlador.php";
require_once "../../modelos/configuracion.modelo.php";
require_once '../../modelos/clientes.modelo.php';
require_once '../../controladores/clientes.controlador.php';
require_once '../../modelos/empleados.modelo.php';
require_once '../../controladores/empleados.controlador.php';
require_once '../../controladores/eventolog.controlador.php';

date_default_timezone_set('America/Caracas');

class PDF extends FPDF {
    public $table_start_x = 7; // Posición X donde inicia la tabla
    public $table_width = 195; // Ancho total de la tabla

    function Header() {
        $this->Image('img/logo.png', 10, 6, 40); // Logo
        $empresa = ControladorConfiguracion::ctrMostrarConfigracion(null, null);
        $nombreEmpresa = utf8_decode($empresa["nombre"] ?? 'Nombre Empresa');
        
        $rifEmpresa = '';
        if (isset($empresa["tipo_rif"]) && isset($empresa["num_rif"])) {
            $rifEmpresa = utf8_decode($empresa["tipo_rif"] . '-' . $empresa["num_rif"]);
        } else {
            $rifEmpresa = utf8_decode($empresa["rif"] ?? 'N/A');
        }

        $localizacionEmpresa = utf8_decode($empresa["direccion"] ?? 'Dirección no disponible');
        
        $telefonoEmpresa = '';
        if (isset($empresa["prefijo_telefono"]) && isset($empresa["numero_telefono"])) {
            $telefonoEmpresa = utf8_decode($empresa["prefijo_telefono"] . '-' . $empresa["numero_telefono"]);
        } else {
            $telefonoEmpresa = utf8_decode($empresa["telefono"] ?? 'N/A');
        }

        $this->SetTextColor(0, 0, 0); // Texto negro
        $this->SetFont('Arial', 'B', 16);
        $this->SetXY(55, 12);
        $this->Cell(0, 7, $nombreEmpresa, 0, 1, 'L');
        $this->SetFont('Arial', 'B', 11);
        $this->SetX(55);
        $this->Cell(0, 5, utf8_decode("RIF: ") . $rifEmpresa, 0, 1, 'L');
        $this->SetFont('Arial', '', 10);
        $this->SetX(55);
        $this->Cell(0, 5, utf8_decode("Dirección: ") . $localizacionEmpresa, 0, 1, 'L');
        $this->SetX(55);
        $this->Cell(0, 5, utf8_decode("Teléfono: ") . $telefonoEmpresa, 0, 1, 'L');
        $this->Ln(15);
        
        $this->SetFillColor(70, 130, 180); // Color de fondo
        $this->SetTextColor(255); // Texto blanco
        $this->SetFont('Arial', 'B', 14);
        $this->SetX($this->table_start_x);
        $this->Cell($this->table_width, 10, utf8_decode('REPORTE TOTAL DE VENTAS'), 0, 1, 'C', true);
        $this->SetTextColor(0); // Restaura el color del texto a negro
        $this->Ln(5);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 9);
        $this->Cell(95, 5, utf8_decode('Página ') . $this->PageNo() . ' / {nb}', 0, 0, 'L');
        $this->Cell(95, 5, date('d/m/Y | g:i:a'), 0, 1, 'R');
    }
}

$totalCompleto = 0;
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetAutoPageBreak(true, 20);
$pdf->SetTopMargin(50);
$pdf->SetLeftMargin(10);
$pdf->SetRightMargin(10);

$pdf->SetFont('Arial', 'B', 11);
$pdf->SetFillColor(70, 130, 180);
$pdf->SetTextColor(255);
$pdf->SetDrawColor(200, 200, 200);

$pdf->SetX($pdf->table_start_x);
$pdf->Cell(12, 10, utf8_decode('N°'), 1, 0, 'C', 1);
$pdf->Cell(28, 10, utf8_decode('Factura'), 1, 0, 'C', 1);
$pdf->Cell(55, 10, utf8_decode('Cliente'), 1, 0, 'C', 1);
$pdf->Cell(35, 10, utf8_decode('Vendedor'), 1, 0, 'C', 1);
$pdf->Cell(28, 10, utf8_decode('Fecha'), 1, 0, 'C', 1);
$pdf->Cell(37, 10, utf8_decode('Total (Bs)'), 1, 1, 'C', 1);

// Obtener la fecha de hoy
$fecha_actual = date('Y-m-d');

// Filtramos las ventas solo para la fecha de hoy
$ventas = ControladorVentas::ctrMostrarVentas(null, null, 1, $fecha_actual); // Aquí se pasa la fecha actual a la consulta

$pdf->SetTextColor(0);
$pdf->SetFont('Arial', '', 10);

if (is_array($ventas) && !empty($ventas)) {
    for ($i = 0; $i < count($ventas); $i++) {
        if (($i + 1) % 2 == 0) {
            $pdf->SetFillColor(255, 255, 255);
        } else {
            $pdf->SetFillColor(245, 245, 245);
        }

        $factura = $ventas[$i]["factura"] ?? 'N/A';
        
        // Cambio para cliente: recuperar correctamente usando tipo_ced y num_ced
        $clienteObj = ControladorClientes::ctrMostrarClientesDosClaves("tipo_ced", "num_ced", $ventas[$i]["tipo_ced_cliente"] ?? '', $ventas[$i]["num_ced_cliente"] ?? '');
        $cliente = (isset($clienteObj["nombre"]) && isset($clienteObj["apellido"])) ? ucwords(utf8_decode($clienteObj["nombre"] . " " . $clienteObj["apellido"])) : utf8_decode('Cliente Desconocido');
        
        // Vendedor sigue igual
        $vendedorObj = ControladorEmpleados::ctrMostrarEmpleados("cedula", $ventas[$i]["vendedor"] ?? '');
        $vendedor = (isset($vendedorObj["nombre"]) && isset($vendedorObj["apellido"])) ? ucwords(utf8_decode($vendedorObj["nombre"] . " " . $vendedorObj["apellido"])) : utf8_decode('Vendedor Desconocido');
        
        $fecha = (isset($ventas[$i]["fecha"])) ? (new DateTime($ventas[$i]["fecha"]))->format("d-m-Y") : 'N/A';
        $total = floatval($ventas[$i]["total"] ?? 0);
        $totalCompleto += $total;

        $pdf->SetX($pdf->table_start_x);
        $pdf->Cell(12, 8, utf8_decode($i + 1), 'LRB', 0, 'C', 1);
        $pdf->Cell(28, 8, utf8_decode($factura), 'RB', 0, 'C', 1);
        $pdf->Cell(55, 8, $cliente, 'RB', 0, 'L', 1);
        $pdf->Cell(35, 8, $vendedor, 'RB', 0, 'L', 1);
        $pdf->Cell(28, 8, utf8_decode($fecha), 'RB', 0, 'C', 1);
        $pdf->Cell(37, 8, number_format($total, 2, ',', '.') . ' Bs', 'RB', 1, 'R', 1);
    }
} else {
    $pdf->SetX($pdf->table_start_x);
    $pdf->Cell($pdf->table_width, 10, utf8_decode('No se encontraron ventas para hoy.'), 1, 1, 'C');
}

$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 11);
$totalColumnStartX = $pdf->table_start_x + 12 + 28 + 55 + 35 + 28;
$totalColumnWidth = 37;

// Mostrar total de ventas
$pdf->SetX($totalColumnStartX);
$pdf->Cell($totalColumnWidth, 8, utf8_decode('TOTAL'), 1, 0, 'C');
$pdf->Cell($totalColumnWidth, 8, number_format($totalCompleto, 2, ',', '.') . ' Bs', 1, 1, 'R');

// Cerrar el PDF
$pdf->Output();
?>
