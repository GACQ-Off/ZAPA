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

date_default_timezone_set('America/Caracas'); // Zona horaria de Venezuela

class PDF extends FPDF
{
    // Definimos la posición inicial y el ancho de la tabla como propiedades de la clase
    public $table_start_x = 7; // Posición X donde inicia la tabla
    public $table_width = 195; // Ancho total de la tabla (12+28+55+35+28+37)
    public $report_title_range = ''; // Propiedad para almacenar el rango de fechas para el título (aunque para mensual no se usa como tal aquí)


    function Header()
    {
        // Logo - SE MANTIENE LA RUTA ORIGINAL Y TAMAÑO ESTANDARIZADO DE REPORTE DIARIO
        // Si el logo no se muestra, esta ruta es la que necesitas verificar.
        $this->Image('img/logo.png', 10, 6, 40); // Posición y tamaño ajustados

        // Información de la empresa - Adoptando el estilo estético de tu reporte de proveedores y diario
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

        // Estilo de Bloque Pulido (Proveedor Reporte)
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

        // Título del reporte - Adoptando el estilo de tu reporte de proveedores y diario
        $this->Ln(15); // Espacio adicional antes del título del reporte
        $this->SetFillColor(70, 130, 180); // Color de fondo para el título (Azul acero)
        $this->SetTextColor(255); // Texto blanco
        $this->SetFont('Arial', 'B', 14);

        // La barra azul ahora coincide con la tabla
        $this->SetX($this->table_start_x); // Inicia la barra azul donde inicia la tabla
        $this->Cell($this->table_width, 10, utf8_decode('REPORTE MENSUAL DE VENTAS'), 0, 1, 'C', true); // Usa el ancho de la tabla
        
        $this->SetTextColor(0); // Restaura el color del texto a negro
        $this->Ln(5); // Espacio después del título
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 9); // Estilo de fuente consistente con otros reportes
        $this->Cell(95, 5, utf8_decode('Página ') . $this->PageNo() . ' / {nb}', 0, 0, 'L');
        $this->Cell(95, 5, date('d/m/Y | g:i:a'), 0, 1, 'R'); // Formato de fecha consistente
        //$this->Line(10, $this->GetY() - 3, 205, $this->GetY() - 3); // Mantener la línea horizontal del footer
    }
}
$totalCompleto = 0;
// *** MODIFICACIÓN CLAVE AQUÍ: Calcular el rango de fechas para el mes actual ***
$primerDiaMes = date('Y-m-01'); // Primer día del mes actual (ej. '2025-06-01')
$ultimoDiaMes = date('Y-m-t'); // Último día del mes actual (ej. '2025-06-30')

$pdf = new PDF(); // Esta es la línea donde se crea la instancia de la clase PDF
$pdf->AliasNbPages();
$pdf->AddPage();

$pdf->SetAutoPageBreak(true, 20);

// SetTopMargin es importante para que el Header no se superponga con el contenido.
// Los márgenes left/right serán manejados por SetX($pdf->table_start_x)
$pdf->SetTopMargin(50); // Mantenemos el TopMargin actual de tu código
$pdf->SetLeftMargin(10); // Mantener por si acaso, aunque SetX lo domina para la tabla
$pdf->SetRightMargin(10); // Mantener por si acaso

// Encabezados de la tabla - Adoptando el estilo de tu reporte de proveedores y diario
$pdf->SetFont('Arial', 'B', 11); // Fuente ajustada a 11 para consistencia
$pdf->SetFillColor(70, 130, 180); // Azul acero para el fondo
$pdf->SetTextColor(255); // Texto blanco
$pdf->SetDrawColor(200, 200, 200); // Borde suave para la tabla

$pdf->SetX($pdf->table_start_x); // Usamos la propiedad de la clase para el inicio de la tabla
$pdf->Cell(12, 10, utf8_decode('N°'), 1, 0, 'C', 1);
$pdf->Cell(28, 10, utf8_decode('Factura'), 1, 0, 'C', 1);
$pdf->Cell(55, 10, utf8_decode('Cliente'), 1, 0, 'C', 1); // Ancho ajustado
$pdf->Cell(35, 10, utf8_decode('Vendedor'), 1, 0, 'C', 1); // Ancho ajustado
$pdf->Cell(28, 10, utf8_decode('Fecha'), 1, 0, 'C', 1); // Ancho ajustado
$pdf->Cell(37, 10, utf8_decode('Total (Bs)'), 1, 1, 'C', 1); // Ancho ajustado
// Suma de anchos: 12 + 28 + 55 + 35 + 28 + 37 = 195mm (misma que reporte diario)

// Restaura colores para las filas de datos
$pdf->SetTextColor(0); // Texto negro
$pdf->SetFont('Arial', '', 10); // Fuente ajustada a 10 para consistencia

// *** Modificar la llamada al controlador/modelo para usar el rango del mes actual ***
// Necesitarás que tu método ctrMostrarVentas (o uno nuevo) acepte estos dos parámetros.
// Asumo que tienes o crearás un método como ctrMostrarVentasPorRangoFechas
$ventas = ControladorVentas::ctrMostrarVentasPorRangoFechas($primerDiaMes, $ultimoDiaMes);

if (is_array($ventas) && !empty($ventas)) {
    for ($i = 0; $i < count($ventas); $i++) {
        // Alternar colores de fila para mejor legibilidad
        if (($i + 1) % 2 == 0) { // Usamos i+1 para que la primera fila sea impar
            $pdf->SetFillColor(255, 255, 255); // Blanco
        } else {
            $pdf->SetFillColor(245, 245, 245); // Gris muy claro
        }
        $pdf->SetDrawColor(200, 200, 200); // Borde suave para las filas de datos

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
        $total = floatval($ventas[$i]["total"] ?? 0); // Asegurarse de que sea flotante para el cálculo

        $totalCompleto += $total;

        $pdf->SetX($pdf->table_start_x); // Usamos la propiedad de la clase para el inicio de la fila de datos
        $pdf->Cell(12, 8, utf8_decode($i + 1), 'LRB', 0, 'C', 1);
        $pdf->Cell(28, 8, utf8_decode($factura), 'RB', 0, 'C', 1);
        $pdf->Cell(55, 8, $cliente, 'RB', 0, 'L', 1); // Alineación izquierda para nombres
        $pdf->Cell(35, 8, $vendedor, 'RB', 0, 'L', 1); // Alineación izquierda para nombres
        $pdf->Cell(28, 8, utf8_decode($fecha), 'RB', 0, 'C', 1);
        $pdf->Cell(37, 8, number_format($total, 2, ',', '.') . ' Bs', 'RB', 1, 'R', 1); // Alineación derecha para totales
    }
} else {
    $pdf->SetX($pdf->table_start_x); // POSICIÓN DEL MENSAJE "NO SE ENCONTRARON VENTAS" DESPLAZADA
    $pdf->Cell($pdf->table_width, 10, utf8_decode('No se encontraron ventas para este mes.'), 1, 1, 'C');
}

$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 11);

// Posicionar el total general alineado con la columna "Total (Bs)" y la tabla en general
$totalColumnStartX = $pdf->table_start_x + 12 + 28 + 55 + 35 + 28; // Calcula la X de inicio de la columna Total (Bs)
$totalColumnWidth = 37;

$labelWidth = 28; // Ancho para la etiqueta "Total Bs:"
$pdf->SetX($totalColumnStartX - $labelWidth); // Posiciona la X para que la celda de "TOTAL Bs:" termine donde empieza la de valor
$pdf->Cell($labelWidth, 8, 'TOTAL Bs:', 0, 0, 'R'); // Etiqueta Total

$pdf->SetFont('Arial', '', 11); // Fuente normal para el valor
// La celda con el valor total se coloca exactamente donde comienza la columna "Total (Bs)"
$pdf->Cell($totalColumnWidth, 8, number_format($totalCompleto, 2, ',', '.') . ' Bs', 1, 1, 'R'); // Valor del Total, borde y alineación derecha

// --- INICIO DEL BLOQUE PARA REGISTRAR EL EVENTO DE LOG ---
// Aquí se registra el evento de log UNA SOLA VEZ, al final de la generación del reporte.
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$empleadoCedula = (isset($_SESSION["cedula"])) ? $_SESSION["cedula"] : "Sistema/Desconocido";

$logData = array(
    "event_type" => "Generación de Reporte PDF",
    "description" => "Se generó un reporte Mensual de Ventas en formato PDF. Total: " . number_format($totalCompleto, 2, ',', '.') . " Bs. Período: " . date('m/Y'),
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