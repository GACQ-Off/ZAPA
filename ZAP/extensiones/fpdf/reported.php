<?php
require('fpdf.php');

if (!defined('ROOT_PATH')) {
    // SE MANTIENE LA RUTA ORIGINAL DE TU CÓDIGO (LA QUE PODRÍA NECESITAR REVISIÓN MANUAL)
    define('ROOT_PATH', dirname(dirname(dirname(__DIR__))) . DIRECTORY_SEPARATOR);
}

require_once '../../modelos/ventas.modelo.php';
require_once '../../controladores/ventas.controlador.php';
require_once "../../controladores/configuracion.controlador.php";
require_once "../../modelos/configuracion.modelo.php";
require_once '../../modelos/clientes.modelo.php';
require_once '../../controladores/clientes.controlador.php';
require_once '../../modelos/empleados.modelo.php';
require_once '../../controladores/empleados.controlador.php';
require_once '../../controladores/eventolog.controlador.php';

// Establece la zona horaria a Venezuela
date_default_timezone_set('America/Caracas');

class PDF extends FPDF {

    // Definimos la posición inicial y el ancho de la tabla como propiedades de la clase
    public $table_start_x = 7; // Posición X donde inicia la tabla
    public $table_width = 195; // Ancho total de la tabla (12+28+55+35+28+37)

    function Header() {
        // Logo - SE MANTIENE LA RUTA ORIGINAL DE TU CÓDIGO
        // Si el logo no se muestra, esta ruta es la que necesitas verificar.
        $this->Image('img/logo.png', 10, 6, 40); // Logo más grande

        // Información de la empresa - Adoptando el estilo estético de tu reporte de proveedores
        $empresa = ControladorConfiguracion::ctrMostrarConfigracion(null, null);
        $nombreEmpresa = utf8_decode($empresa["nombre"] ?? 'Nombre Empresa'); // Defensive check
        
        // --- MODIFICACIÓN INICIO: Manejo de RIF y Teléfono de la EMPRESA con campos separados ---
        $rifEmpresa = '';
        if (isset($empresa["tipo_rif"]) && isset($empresa["num_rif"])) {
            $rifEmpresa = utf8_decode($empresa["tipo_rif"] . '-' . $empresa["num_rif"]);
        } else {
            // Fallback if individual fields are not set, but 'rif' might be available
            $rifEmpresa = utf8_decode($empresa["rif"] ?? 'N/A');
        }

        $localizacionEmpresa = utf8_decode($empresa["direccion"] ?? 'Dirección no disponible'); // Defensive check
        
        $telefonoEmpresa = '';
        if (isset($empresa["prefijo_telefono"]) && isset($empresa["numero_telefono"])) {
            $telefonoEmpresa = utf8_decode($empresa["prefijo_telefono"] . '-' . $empresa["numero_telefono"]);
        } else {
            // Fallback if individual fields are not set, but 'telefono' might be available
            $telefonoEmpresa = utf8_decode($empresa["telefono"] ?? 'N/A');
        }
        // --- MODIFICACIÓN FIN: Manejo de RIF y Teléfono de la EMPRESA con campos separados ---

        // Información de la empresa - Estilo de Bloque Pulido (Proveedor Reporte)
        $this->SetTextColor(0, 0, 0); // Texto negro

        // Nombre de la Empresa (Más grande y negrita)
        $this->SetFont('Arial', 'B', 16); // Tamaño más grande
        $this->SetXY(55, 12); // Ajusta la posición Y ligeramente
        $this->Cell(0, 7, $nombreEmpresa, 0, 1, 'L'); // 7mm de alto para este texto

        // RIF (Tamaño intermedio y negrita)
        $this->SetFont('Arial', 'B', 11); // Tamaño intermedio
        $this->SetX(55); // Mantiene la alineación X
        $this->Cell(0, 5, utf8_decode("RIF: ") . $rifEmpresa, 0, 1, 'L'); // 5mm de alto

        // Dirección (Tamaño estándar, sin negrita)
        $this->SetFont('Arial', '', 10); // Tamaño estándar, sin negrita
        $this->SetX(55);
        $this->Cell(0, 5, utf8_decode("Dirección: ") . $localizacionEmpresa, 0, 1, 'L');

        // Teléfono (Tamaño estándar, sin negrita)
        $this->SetFont('Arial', '', 10); // Tamaño estándar, sin negrita
        $this->SetX(55);
        $this->Cell(0, 5, utf8_decode("Teléfono: ") . $telefonoEmpresa, 0, 1, 'L');

        // Título del reporte - Adoptando el estilo de tu reporte de proveedores
        $this->Ln(15); // Espacio adicional antes del título del reporte
        $this->SetFillColor(70, 130, 180); // Color de fondo para el título (Azul acero)
        $this->SetTextColor(255); // Texto blanco
        $this->SetFont('Arial', 'B', 14);

        // *** CAMBIO CLAVE AQUÍ: La barra azul ahora coincide con la tabla ***
        $this->SetX($this->table_start_x); // Inicia la barra azul donde inicia la tabla
        $this->Cell($this->table_width, 10, utf8_decode('REPORTE DIARIO DE VENTAS'), 0, 1, 'C', true); // Usa el ancho de la tabla
        // *******************************************************************
        
        $this->SetTextColor(0); // Restaura el color del texto a negro
        $this->Ln(5); // Espacio después del título
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 9); // Adoptando el estilo de fuente de tu reporte de inventario/clientes
        $this->Cell(95, 5, utf8_decode('Página ') . $this->PageNo() . ' / {nb}', 0, 0, 'L');
        $this->Cell(95, 5, date('d/m/Y | g:i:a'), 0, 1, 'R'); // Formato de fecha consistente con otros reportes
    }
}

$totalCompleto = 0;

$pdf = new PDF(); // Instanciamos la clase PDF
$pdf->AliasNbPages();
$pdf->AddPage('P'); // Orientación Vertical (Portrait)

$pdf->SetAutoPageBreak(true, 20); // Margen inferior para salto de página
$pdf->SetMargins(10, 10, 10); // Márgenes consistentes (Izquierda, Arriba, Derecha)

// Encabezados de la tabla - Adoptando el estilo de tu reporte de proveedores
$pdf->SetFont('Arial', 'B', 11); // Fuente ajustada a 11 para consistencia
$pdf->SetFillColor(70, 130, 180); // Azul acero para el fondo
$pdf->SetTextColor(255); // Texto blanco
$pdf->SetDrawColor(200, 200, 200); // Color de borde suave para la tabla

$pdf->SetX($pdf->table_start_x); // Usamos la propiedad de la clase para el inicio de la tabla
$pdf->Cell(12, 10, utf8_decode('N°'), 1, 0, 'C', 1);
$pdf->Cell(28, 10, utf8_decode('Factura'), 1, 0, 'C', 1);
$pdf->Cell(55, 10, utf8_decode('Cliente'), 1, 0, 'C', 1);
$pdf->Cell(35, 10, utf8_decode('Vendedor'), 1, 0, 'C', 1);
$pdf->Cell(28, 10, utf8_decode('Fecha'), 1, 0, 'C', 1);
$pdf->Cell(37, 10, utf8_decode('Total (Bs)'), 1, 1, 'C', 1);
// Suma de anchos: 12 + 28 + 55 + 35 + 28 + 37 = 195mm

// Restaura colores para las filas de datos
$pdf->SetTextColor(0); // Texto negro
$pdf->SetFont('Arial', '', 10); // Fuente ajustada a 10 para consistencia

$ventas = ControladorVentas::ctrMostrarVentas(null, null, 1);
$fecha_actual = date('Y-m-d');
$i = 1;

if (is_array($ventas) && !empty($ventas)) {
    foreach ($ventas as $venta) {
        if (date('Y-m-d', strtotime($venta["fecha"])) === $fecha_actual) {
            // Alternar colores de fila para mejor legibilidad
            if ($i % 2 == 0) {
                $pdf->SetFillColor(255, 255, 255); // Blanco
            } else {
                $pdf->SetFillColor(245, 245, 245); // Gris muy claro
            }
            $pdf->SetDrawColor(200, 200, 200); // Borde suave para las filas de datos

            $factura = $venta["factura"] ?? 'N/A';
            $clienteObj = ControladorClientes::ctrMostrarClientesDosClaves(
                "tipo_ced", "num_ced",
                $venta["tipo_ced_cliente"] ?? '',
                $venta["num_ced_cliente"] ?? ''
            );

        

        $cliente = (isset($clienteObj["nombre"]) && isset($clienteObj["apellido"]))
        ? ucwords(utf8_decode($clienteObj["nombre"] . " " . $clienteObj["apellido"]))
        : utf8_decode('Cliente Desconocido');

            // Obtener datos del vendedor
            $empleadoObj = ControladorEmpleados::ctrMostrarEmpleados("cedula", $venta["vendedor"] ?? '');
            $vendedor = (isset($empleadoObj["nombre"]) && isset($empleadoObj["apellido"])) ? ucwords(utf8_decode($empleadoObj["nombre"] . " " . $empleadoObj["apellido"])) : utf8_decode('Vendedor Desconocido');
            
            $fecha = (isset($venta["fecha"])) ? (new DateTime($venta["fecha"]))->format("d-m-Y") : 'N/A';
            $total = floatval($venta["total"] ?? 0); // Asegurarse de que sea flotante para el cálculo
            $totalCompleto += $total;

            $pdf->SetX($pdf->table_start_x); // Usamos la propiedad de la clase para el inicio de la fila de datos
            $pdf->Cell(12, 8, $i, 'LRB', 0, 'C', 1);
            $pdf->Cell(28, 8, $factura, 'RB', 0, 'C', 1);
            $pdf->Cell(55, 8, $cliente, 'RB', 0, 'L', 1);
            $pdf->Cell(35, 8, $vendedor, 'RB', 0, 'L', 1);
            $pdf->Cell(28, 8, $fecha, 'RB', 0, 'C', 1);
            $pdf->Cell(37, 8, number_format($total, 2, ',', '.') . ' Bs', 'RB', 1, 'R', 1);

            $i++;
        }
    }
} else {
    $pdf->SetX($pdf->table_start_x); // POSICIÓN DEL MENSAJE "NO SE ENCONTRARON VENTAS" DESPLAZADA
    $pdf->Cell($pdf->table_width, 10, utf8_decode('No se encontraron ventas para el día de hoy.'), 1, 1, 'C');
}

$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 11);

// Posicionar el total general alineado con la columna "Total (Bs)" y la tabla en general
$totalColumnStartX = $pdf->table_start_x + 12 + 28 + 55 + 35 + 28; // Calcula la nueva X de inicio de la columna Total (Bs)
$totalColumnWidth = 37;

$labelWidth = 28;
$pdf->SetX($totalColumnStartX - $labelWidth); // Posiciona la X para que la celda de "TOTAL Bs:" termine donde empieza la de valor
$pdf->Cell($labelWidth, 8, 'TOTAL Bs:', 0, 0, 'R'); // Etiqueta Total

$pdf->SetFont('Arial', '', 11); // Fuente normal para el valor
// La celda con el valor total se coloca exactamente donde comienza la columna "Total (Bs)"
$pdf->Cell($totalColumnWidth, 8, number_format($totalCompleto, 2, ',', '.') . ' Bs', 1, 1, 'R'); // Valor del Total, borde y alineación derecha

// Registro en EventLog
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$empleadoCedula = isset($_SESSION["cedula"]) ? $_SESSION["cedula"] : "Sistema/Desconocido";

$logData = array(
    "event_type" => "Generación de Reporte PDF",
    "description" => "Se generó un reporte Diario de Ventas en formato PDF. Total: " . number_format($totalCompleto, 2, ',', '.') . " Bs.",
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

$pdf->Output();
?>