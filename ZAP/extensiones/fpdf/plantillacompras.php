<?php
require('fpdf.php');

// --- 1. GESTIÓN DE RUTAS (CORRECCIÓN CLAVE PARA EL ERROR 'Class not found') ---
// Define ROOT_PATH: Subimos dos niveles desde /extensiones/fpdf/ hasta /ventaslog120/.
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR);
}

// Compras
$rutaControladorCompras = ROOT_PATH . 'controladores/compras.controlador.php';

// Verificación y corrección del error de inclusión
if (!file_exists($rutaControladorCompras)) {
    // Si la ruta es incorrecta, detiene y muestra el problema de la ruta
    die("Error de Ruta: No se encontró el Controlador de Compras en: " . $rutaControladorCompras);
}

// Inclusiones
require_once ROOT_PATH . 'modelos/compras.modelo.php';
require_once $rutaControladorCompras; 
require_once ROOT_PATH . "controladores/configuracion.controlador.php";
require_once ROOT_PATH . "modelos/configuracion.modelo.php";
require_once ROOT_PATH . 'modelos/proveedor.modelo.php';
require_once ROOT_PATH . 'controladores/proveedor.controlador.php';
require_once ROOT_PATH . 'modelos/empleados.modelo.php';
require_once ROOT_PATH . 'controladores/empleados.controlador.php';
require_once ROOT_PATH . 'controladores/eventolog.controlador.php';

date_default_timezone_set('America/Caracas');

class PDF extends FPDF
{
    // Definimos la posición inicial y el ancho de la tabla como propiedades de la clase
    public $table_start_x = 7; // Posición X donde inicia la tabla
    // Nuevos Anchos: N°(10) + Factura(25) + Proveedor(50) + Total$(25) + TotalBs(25) + Fecha(20) + Obs(40) = 195
    public $table_width = 195; 

    function Header()
    {
        // ... (Header se mantiene igual) ...
        // Logo - SE MANTIENE LA RUTA ORIGINAL Y TAMAÑO ESTANDARIZADO DE REPORTE DIARIO
        $this->Image('img/logo.png', 10, 6, 40); 

        // Información de la empresa
        $empresa = ControladorConfiguracion::ctrMostrarConfigracion(null, null);
        $nombreEmpresa = utf8_decode($empresa["nombre"] ?? 'Nombre Empresa');
        
        // --- Manejo de RIF y Teléfono de la EMPRESA ---
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
        // --- FIN: Manejo de RIF y Teléfono de la EMPRESA ---

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

        // Título del reporte - REPORTE DE COMPRAS
        $this->Ln(15); 
        $this->SetFillColor(70, 130, 180); // Azul acero para el fondo
        $this->SetTextColor(255); // Texto blanco
        $this->SetFont('Arial', 'B', 14);

        $this->SetX($this->table_start_x); 
        $this->Cell($this->table_width, 10, utf8_decode('REPORTE TOTAL DE COMPRAS'), 0, 1, 'C', true); 
        
        $this->SetTextColor(0); // Restaura el color del texto a negro
        $this->Ln(5); 
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 9); // Estilo de fuente consistente
        $this->Cell(95, 5, utf8_decode('Página ') . $this->PageNo() . ' / {nb}', 0, 0, 'L');
        $this->Cell(95, 5, date('d/m/Y | g:i:a'), 0, 1, 'R'); // Formato de fecha consistente
    }
}
$totalCompleto = 0;
$totalCompletoDolar = 0; 


$pdf = new PDF();

$pdf->AliasNbPages();
$pdf->AddPage();

$pdf->SetAutoPageBreak(true, 20);

$pdf->SetTopMargin(50); 
$pdf->SetLeftMargin(10);
$pdf->SetRightMargin(10);

// =========================================================================
// 2. AJUSTE DE ANCHOS Y ENCABEZADOS DE LA TABLA (Mismas columnas solicitadas)
// =========================================================================
$pdf->SetFont('Arial', 'B', 10); 
$pdf->SetFillColor(70, 130, 180); 
$pdf->SetTextColor(255); 
$pdf->SetDrawColor(200, 200, 200); 

$pdf->SetX($pdf->table_start_x); 
// N°(10) + Factura(25) + Proveedor(50) + Total$(25) + TotalBs(25) + Fecha(20) + Obs(40) = 195
$pdf->Cell(10, 10, utf8_decode('N°'), 1, 0, 'C', 1);
$pdf->Cell(25, 10, utf8_decode('Factura'), 1, 0, 'C', 1);
$pdf->Cell(50, 10, utf8_decode('Proveedor'), 1, 0, 'C', 1); 
$pdf->Cell(25, 10, utf8_decode('Total ($)'), 1, 0, 'C', 1); 
$pdf->Cell(25, 10, utf8_decode('Total (Bs)'), 1, 0, 'C', 1); 
$pdf->Cell(20, 10, utf8_decode('Fecha'), 1, 0, 'C', 1); 
$pdf->Cell(40, 10, utf8_decode('Observaciones'), 1, 1, 'C', 1); 

// Llamada al controlador de Compras
$compras = ControladorCompras::ctrMostrarCompras(null, null); 

// Restaura colores para las filas de datos
$pdf->SetTextColor(0); 
$pdf->SetFont('Arial', '', 9); 

if (is_array($compras) && !empty($compras)) {
    for ($i = 0; $i < count($compras); $i++) {
        // Alternar colores de fila
        if (($i + 1) % 2 == 0) { 
            $pdf->SetFillColor(255, 255, 255); // Blanco
        } else {
            $pdf->SetFillColor(245, 245, 245); // Gris muy claro
        }
        $pdf->SetDrawColor(200, 200, 200); 

        // =========================================================================
        // 3. OBTENCIÓN Y PREPARACIÓN DE DATOS (CLAVES CORREGIDAS)
        // =========================================================================
        
        // Factura: CLAVE CORREGIDA a 'numero_factura_proveedor'
        $factura = $compras[$i]["numero_factura_proveedor"] ?? 'N/A';
        
        // Proveedor: Usamos el nombre adjunto por el JOIN del Modelo ('nombre')
        $proveedor = (isset($compras[$i]["nombre"]))
        ? ucwords(utf8_decode($compras[$i]["nombre"])) 
        : utf8_decode('Proveedor Desconocido');
        
        // Totales: CLAVES CORREGIDAS
        $totalBs = floatval($compras[$i]["total_compra_bs"] ?? 0);     
        $totalDolar = floatval($compras[$i]["total_compra_usd"] ?? 0); 
        
        // Fecha: CLAVE CORREGIDA a 'fecha_compra'
        $fecha = (isset($compras[$i]["fecha_compra"]))                 
                 ? (new DateTime($compras[$i]["fecha_compra"]))->format("d-m-Y") 
                 : 'N/A';
                 
        // Observaciones (La clave 'observaciones' sí coincide)
        $observaciones = substr(utf8_decode($compras[$i]["observaciones"] ?? 'Sin obs.'), 0, 25) 
                       . (strlen($compras[$i]["observaciones"] ?? '') > 25 ? '...' : '');

        $totalCompleto += $totalBs;
        $totalCompletoDolar += $totalDolar;


        // =========================================================================
        // 4. IMPRESIÓN DE CELDAS
        // =========================================================================
        $pdf->SetX($pdf->table_start_x); 
        // N°
        $pdf->Cell(10, 8, utf8_decode($i + 1), 'LRB', 0, 'C', 1);
        // Factura
        $pdf->Cell(25, 8, utf8_decode($factura), 'RB', 0, 'C', 1);
        // Proveedor
        $pdf->Cell(50, 8, $proveedor, 'RB', 0, 'L', 1); 
        // Total ($)
        $pdf->Cell(25, 8, number_format($totalDolar, 2, ',', '.') . ' $', 'RB', 0, 'R', 1); 
        // Total (Bs)
        $pdf->Cell(25, 8, number_format($totalBs, 2, ',', '.') . ' Bs', 'RB', 0, 'R', 1); 
        // Fecha
        $pdf->Cell(20, 8, utf8_decode($fecha), 'RB', 0, 'C', 1);
        // Observaciones
        $pdf->Cell(40, 8, $observaciones, 'RB', 1, 'L', 1); 
    }
} else {
    $pdf->SetX($pdf->table_start_x); 
    $pdf->Cell($pdf->table_width, 10, utf8_decode('No se encontraron compras en el sistema.'), 1, 1, 'C');
}

$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 10);

// Posicionamiento del total general
$totalColumnStartX = $pdf->table_start_x + 10 + 25 + 50; 
$totalDolarWidth = 25;
$totalBsWidth = 25;
$labelWidth = 25; 

// TOTAL EN DÓLARES
$pdf->SetX($totalColumnStartX); 
$pdf->Cell($labelWidth, 8, 'TOTAL $:', 0, 0, 'R'); 
$pdf->SetFont('Arial', '', 10); 
$pdf->Cell($totalDolarWidth, 8, number_format($totalCompletoDolar, 2, ',', '.') . ' $', 1, 0, 'R'); 

// TOTAL EN BOLÍVARES
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(0, 8, '', 0, 0, 'R'); 
$pdf->SetX($totalColumnStartX + $totalDolarWidth + $labelWidth); 
$pdf->Cell($labelWidth, 8, 'TOTAL Bs:', 0, 0, 'R'); 
$pdf->SetFont('Arial', '', 10); 
$pdf->Cell($totalBsWidth, 8, number_format($totalCompleto, 2, ',', '.') . ' Bs', 1, 1, 'R'); 

// --- INICIO DEL BLOQUE PARA REGISTRAR EL EVENTO DE LOG ---
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$empleadoCedula = (isset($_SESSION["cedula"])) ? $_SESSION["cedula"] : "Sistema/Desconocido";

$logData = array(
    "event_type" => "Generación de Reporte PDF",
    "description" => "Se generó un reporte Total de Compras en formato PDF. Total Bs: " . number_format($totalCompleto, 2, ',', '.') . " Bs. Total $: " . number_format($totalCompletoDolar, 2, ',', '.') . " $.",
    "employee_cedula" => $empleadoCedula,
    "affected_table" => "compras", 
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