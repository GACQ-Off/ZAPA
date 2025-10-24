<?php
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

date_default_timezone_set('America/Caracas');

class PDF extends FPDF
{
    // Definimos la posición inicial y el ancho de la tabla como propiedades de la clase
    public $table_start_x = 7; // Posición X donde inicia la tabla
    public $table_width = 195; // Ancho total de la tabla (12+28+55+35+28+37)
    public $report_title_range = ''; // Propiedad para almacenar el rango de fechas para el título

    function Header()
    {
        // Logo - SE MANTIENE LA RUTA ORIGINAL Y TAMAÑO ESTANDARIZADO DE REPORTE DIARIO
        $this->Image('img/logo.png', 10, 6, 40); // Posición y tamaño ajustados

        // Información de la empresa
        $empresa = ControladorConfiguracion::ctrMostrarConfigracion(null, null);
        $nombreEmpresa = utf8_decode($empresa["nombre"] ?? 'Nombre Empresa');
        
        // --- MODIFICACIÓN INICIO: Manejo de RIF y Teléfono de la EMPRESA con campos separados ---
        $rifEmpresa = '';
        if (isset($empresa["tipo_rif"]) && isset($empresa["num_rif"])) {
            $rifEmpresa = utf8_decode($empresa["tipo_rif"] . '-' . $empresa["num_rif"]);
        } else {
            // Fallback if individual fields are not set, but 'rif' might be available
            $rifEmpresa = utf8_decode($empresa["rif"] ?? 'N/A');
        }

        $localizacionEmpresa = utf8_decode($empresa["direccion"] ?? 'Dirección no disponible');
        
        $telefonoEmpresa = '';
        if (isset($empresa["prefijo_telefono"]) && isset($empresa["numero_telefono"])) {
            $telefonoEmpresa = utf8_decode($empresa["prefijo_telefono"] . '-' . $empresa["numero_telefono"]);
        } else {
            // Fallback if individual fields are not set, but 'telefono' might be available
            $telefonoEmpresa = utf8_decode($empresa["telefono"] ?? 'N/A');
        }
        // --- MODIFICACIÓN FIN: Manejo de RIF y Teléfono de la EMPRESA con campos separados ---

        // Estilo de Bloque Pulido
        $this->SetTextColor(0, 0, 0); // Texto negro

        // Nombre de la Empresa (Más grande y negrita)
        $this->SetFont('Arial', 'B', 16);
        $this->SetXY(55, 12);
        $this->Cell(0, 7, $nombreEmpresa, 0, 1, 'L');

        // RIF (Tamaño intermedio y negrita)
        $this->SetFont('Arial', 'B', 11);
        $this->SetX(55);
        $this->Cell(0, 5, utf8_decode("RIF: ") . $rifEmpresa, 0, 1, 'L');

        // Dirección (Tamaño estándar, sin negrita)
        $this->SetFont('Arial', '', 10);
        $this->SetX(55);
        $this->Cell(0, 5, utf8_decode("Dirección: ") . $localizacionEmpresa, 0, 1, 'L');

        // Teléfono (Tamaño estándar, sin negrita)
        $this->SetFont('Arial', '', 10);
        $this->SetX(55);
        $this->Cell(0, 5, utf8_decode("Teléfono: ") . $telefonoEmpresa, 0, 1, 'L');

        // Título del reporte - Con rango de fechas dinámico
        $this->Ln(15); // Espacio adicional antes del título del reporte
        $this->SetFillColor(70, 130, 180); // Color de fondo para el título (Azul acero)
        $this->SetTextColor(255); // Texto blanco
        $this->SetFont('Arial', 'B', 14);

        // La barra azul ahora coincide con la tabla
        $this->SetX($this->table_start_x); // Inicia la barra azul donde inicia la tabla
        $reportTitle = utf8_decode('REPORTE DE VENTAS POR RANGO DE FECHA');
        if (!empty($this->report_title_range)) {
            $reportTitle .= utf8_decode(' | ') . $this->report_title_range;
        }
        $this->Cell($this->table_width, 10, $reportTitle, 0, 1, 'C', true);
        
        $this->SetTextColor(0); // Restaura el color del texto a negro
        $this->Ln(5); // Espacio después del título
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 9); // Estilo de fuente consistente con otros reportes
        $this->Cell(95, 5, utf8_decode('Página ') . $this->PageNo() . ' / {nb}', 0, 0, 'L');
        $this->Cell(95, 5, date('d/m/Y | g:i:a'), 0, 1, 'R'); // Formato de fecha consistente
        //$this->Line(10, $this->GetY() - 3, 205, $this->GetY() - 3); // Línea horizontal dinámica
    }
}

$totalCompleto = 0;

// --- Obtener rango de fechas de los parámetros GET ---
$fechaInicial = null;
$fechaFinal = null;

if (isset($_GET["fechaInicial"]) && isset($_GET["fechaFinal"])) {
    $fechaInicial = $_GET["fechaInicial"];
    $fechaFinal = $_GET["fechaFinal"];

    // Validar formato de fechas (opcional, pero buena práctica)
    if (!DateTime::createFromFormat('Y-m-d', $fechaInicial) || !DateTime::createFromFormat('Y-m-d', $fechaFinal)) {
        // Manejar error si el formato es incorrecto, por ejemplo, redirigir o mostrar un mensaje
        die(utf8_decode("Error: Formato de fecha inválido. Use AAAA-MM-DD."));
    }
    
    // Formatear fechas para el título del reporte
    $fechaInicial_fmt = (new DateTime($fechaInicial))->format("d/m/Y");
    $fechaFinal_fmt = (new DateTime($fechaFinal))->format("d/m/Y");
    $reportTitleRange = "Del " . $fechaInicial_fmt . " al " . $fechaFinal_fmt;

} else {
    // Si no se proporcionan fechas, podrías establecer un rango predeterminado (ej. mes actual)
    // o simplemente mostrar un mensaje de error o generar un reporte vacío.
    // Para un reporte "por rango", lo ideal es que las fechas SIEMPRE se proporcionen.
    die(utf8_decode("Error: Debe proporcionar 'fechaInicial' y 'fechaFinal' en la URL (formato YYYY-MM-DD)."));
}

$pdf = new PDF();
$pdf->report_title_range = $reportTitleRange; // Asignar el rango de fechas al título de la cabecera

$pdf->AliasNbPages();
$pdf->AddPage();

$pdf->SetAutoPageBreak(true, 20);
$pdf->SetTopMargin(50); 
$pdf->SetLeftMargin(10);
$pdf->SetRightMargin(10);

// Encabezados de la tabla
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

// Restaura colores para las filas de datos
$pdf->SetTextColor(0);
$pdf->SetFont('Arial', '', 10);

// *** Modificar la llamada al controlador/modelo para usar el rango de fechas proporcionado ***
$ventas = ControladorVentas::ctrMostrarVentasPorRangoFechas($fechaInicial, $fechaFinal);

if (is_array($ventas) && !empty($ventas)) {
    for ($i = 0; $i < count($ventas); $i++) {
        // Alternar colores de fila para mejor legibilidad
        if (($i + 1) % 2 == 0) {
            $pdf->SetFillColor(255, 255, 255); // Blanco
        } else {
            $pdf->SetFillColor(245, 245, 245); // Gris muy claro
        }
        $pdf->SetDrawColor(200, 200, 200);

        $factura = $ventas[$i]["factura"] ?? 'N/A';
        $clienteObj = ControladorClientes::ctrMostrarClientesDosClaves(
            "tipo_ced", "num_ced",
            $ventas[$i]["tipo_ced_cliente"] ?? '',
            $ventas[$i]["num_ced_cliente"] ?? ''
        );

        $cliente = (isset($clienteObj["nombre"]) && isset($clienteObj["apellido"]))
        ? ucwords(utf8_decode($clienteObj["nombre"] . " " . $clienteObj["apellido"]))
        : utf8_decode('Cliente Desconocido');
        
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
    $pdf->Cell($pdf->table_width, 10, utf8_decode('No se encontraron ventas para el rango de fechas especificado.'), 1, 1, 'C');
}

$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 11);

// Posicionar el total general
$totalColumnStartX = $pdf->table_start_x + 12 + 28 + 55 + 35 + 28;
$totalColumnWidth = 37;

$labelWidth = 28;
$pdf->SetX($totalColumnStartX - $labelWidth);
$pdf->Cell($labelWidth, 8, 'TOTAL Bs:', 0, 0, 'R');

$pdf->SetFont('Arial', '', 11);
$pdf->Cell($totalColumnWidth, 8, number_format($totalCompleto, 2, ',', '.') . ' Bs', 1, 1, 'R');

// --- INICIO DEL BLOQUE PARA REGISTRAR EL EVENTO DE LOG ---
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$empleadoCedula = (isset($_SESSION["cedula"])) ? $_SESSION["cedula"] : "Sistema/Desconocido";

$logData = array(
    "event_type" => "Generación de Reporte PDF",
    "description" => "Se generó un reporte de Ventas por Rango de Fecha en formato PDF. Total: " . number_format($totalCompleto, 2, ',', '.') . " Bs. Rango: " . $reportTitleRange,
    "employee_cedula" => $empleadoCedula,
    "affected_table" => "ventas",
    "affected_row_id" => "N/A"
);

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