<?php

// Incluir la librería FPDF
require('fpdf.php');

// --- INICIO DE GESTIÓN DE RUTAS (CRÍTICO PARA LA CONSISTENCIA) ---
// Define ROOT_PATH si no está ya definido.
// Si este archivo (reporteProveedoresFPDF.php) está en 'extensiones/fpdf/',
// necesita subir DOS niveles para llegar a la carpeta 'ventaslog/',
// para ser consistente con la configuración de tus otros reportes.
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR); // Sube DOS directorios
}
// --- FIN DE GESTIÓN DE RUTAS ---

// Ahora usa ROOT_PATH para todas las inclusiones
// Usar ROOT_PATH para las inclusiones internas es más robusto
require_once ROOT_PATH . "controladores/configuracion.controlador.php";
require_once ROOT_PATH . "modelos/configuracion.modelo.php";

require_once ROOT_PATH . 'modelos/proveedor.modelo.php';
require_once ROOT_PATH . 'controladores/proveedor.controlador.php';

// INCLUSIONES NECESARIAS PARA EL REGISTRO DE EVENTOS LOG
require_once ROOT_PATH . "controladores/eventolog.controlador.php";

// Establece la zona horaria a Venezuela
date_default_timezone_set('America/Caracas');

class PDF extends FPDF
{
    // Cabecera de página
    function Header()
    {
        // Logo - Adaptado a la posición y tamaño de tu reporte de inventario/clientes de referencia
        $this->Image('img/logo.png', 10, 6, 40);

        // Información de la empresa - Adoptando el estilo de tu reporte de inventario/clientes
        $this->SetFont('Arial', 'B', 14);
        $this->SetXY(55, 13); // Posiciona el texto junto al logo
        $this->SetTextColor(0, 0, 0); // Texto negro

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

        // Información de la empresa - Estilo de Bloque Pulido
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

        // Título del reporte - Adoptando el estilo de tu reporte de inventario/clientes de referencia
        $this->Ln(15); // Espacio adicional antes del título del reporte
        $this->SetFillColor(70, 130, 180); // Color de fondo para el título (Azul acero)
        $this->SetTextColor(255); // Texto blanco
        $this->SetFont('Arial', 'B', 14);
        // Usamos 'REPORTE DE PROVEEDORES' en mayúsculas y centrado con fondo
        $this->Cell(0, 10, utf8_decode('REPORTE DE PROVEEDORES'), 0, 1, 'C', true); // Título centrado con fondo
        $this->SetTextColor(0); // Restaura el color del texto a negro
        $this->Ln(5); // Espacio después del título
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 9); // Adoptando el estilo de fuente de tu reporte de inventario/clientes
        $this->Cell(95, 5, utf8_decode('Página ') . $this->PageNo() . ' / {nb}', 0, 0, 'L');
        $this->Cell(172, 5, date('d/m/Y | g:i:a'), 0, 1, 'R');
        // Se elimina la línea horizontal redundante, consistente con tus otros reportes mejorados.
    }
}

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage('L'); // Orientación Horizontal (Landscape)

$pdf->SetAutoPageBreak(true, 20); // Margen inferior para salto de página
$pdf->SetMargins(10, 10, 10); // Márgenes consistentes (Izquierda, Arriba, Derecha)

// Encabezados de la tabla - Adoptando el estilo de tu reporte de inventario/clientes de referencia
$pdf->SetFont('Arial', 'B', 11); // Fuente ajustada a 11 para consistencia
$pdf->SetFillColor(70, 130, 180); // Azul acero para el fondo
$pdf->SetTextColor(255); // Texto blanco
$pdf->SetDrawColor(200, 200, 200); // Color de borde suave para la tabla

$pdf->SetX(10); // Inicia la tabla en el margen izquierdo
$pdf->Cell(12, 10, utf8_decode('N°'), 1, 0, 'C', 1);
$pdf->Cell(40, 10, utf8_decode('Empresa'), 1, 0, 'C', 1); // Ancho ajustado
$pdf->Cell(30, 10, utf8_decode('RIF'), 1, 0, 'C', 1); // Ancho ajustado
$pdf->Cell(50, 10, utf8_decode('Representante'), 1, 0, 'C', 1); // Ancho ajustado
$pdf->Cell(30, 10, utf8_decode('Teléfono'), 1, 0, 'C', 1); // Ancho ajustado
$pdf->Cell(60, 10, utf8_decode('Correo Electrónico'), 1, 0, 'L', 1); // Alineación a la izquierda para correos
$pdf->Cell(55, 10, utf8_decode('Dirección'), 1, 1, 'C', 1); // Ancho ajustado, Salto de línea al final de la cabecera

// Restaura colores para las filas de datos
$pdf->SetTextColor(0); // Texto negro
$pdf->SetFont('Arial', '', 10); // Fuente ajustada a 10 para consistencia

// Obtener los datos de los proveedores
// Asumiendo que ctrMostrarProveedor ya trae tipo_rif, num_rif, prefijo_telefono, numero_telefono
$proveedores = ControladorProveedor::ctrMostrarProveedor(null, null);

$rowCounter = 0; // Para el número de fila

if (is_array($proveedores) && !empty($proveedores)) {
    foreach ($proveedores as $key => $proveedor) {
        $rowCounter++; // Incrementar el contador para el N° de fila

        // Alternar colores de fila para mejor legibilidad, como en tu referencia de inventario/clientes
        if ($rowCounter % 2 == 0) {
            $pdf->SetFillColor(255, 255, 255); // Blanco
        } else {
            $pdf->SetFillColor(245, 245, 245); // Gris muy claro
        }
        $pdf->SetDrawColor(200, 200, 200); // Borde suave para las filas de datos

        // Guarda la posición Y actual antes de las celdas de la fila para calcular la altura total
        $currentY = $pdf->GetY();
        $startPosX = 10; // Posición X de inicio de la fila

        // Construir el número de teléfono completo con prefijo y número para el PROVEEDOR
        $prefijo_telefono_proveedor = $proveedor["prefijo_telefono"] ?? '';
        $numero_telefono_proveedor = $proveedor["numero_telefono"] ?? '';
        $telefonoCompletoProveedor = '';
        if (!empty($prefijo_telefono_proveedor) && !empty($numero_telefono_proveedor)) {
            $telefonoCompletoProveedor = $prefijo_telefono_proveedor . '-' . $numero_telefono_proveedor;
        } elseif (!empty($prefijo_telefono_proveedor)) {
            $telefonoCompletoProveedor = $prefijo_telefono_proveedor;
        } elseif (!empty($numero_telefono_proveedor)) {
            $telefonoCompletoProveedor = $numero_telefono_proveedor;
        } else {
            $telefonoCompletoProveedor = 'N/A';
        }

        // Construir el RIF completo con tipo_rif y num_rif para el PROVEEDOR
        $tipo_rif_proveedor = $proveedor["tipo_rif"] ?? '';
        $num_rif_proveedor = $proveedor["num_rif"] ?? '';
        $rifCompletoProveedor = '';
        if (!empty($tipo_rif_proveedor) && !empty($num_rif_proveedor)) {
            $rifCompletoProveedor = utf8_decode($tipo_rif_proveedor . '-' . $num_rif_proveedor);
        } else {
            $rifCompletoProveedor = utf8_decode($proveedor["rif"] ?? 'N/A'); // Fallback to a single 'rif' field if exists
        }
        
        // --- Calcula la altura necesaria para la fila (por MultiCell de Dirección) ---
        // Almacena la posición Y actual antes de la simulación
        $yBeforeMultiCellSimulation = $pdf->GetY();
        
        // Mueve el cursor X a la posición donde la MultiCell de dirección comenzaría para la simulación
        $pdf->SetX($startPosX + 12 + 40 + 30 + 50 + 30 + 60); // Ajusta a los nuevos anchos de columnas
        
        // Simula MultiCell para obtener altura.
        $pdf->MultiCell(55, 8, utf8_decode($proveedor["direccion"] ?? ''), 0, 'L', 0, true); 
        $direccionCalculatedHeight = $pdf->GetY() - $yBeforeMultiCellSimulation; // Calcula la diferencia de altura

        // Restaura la posición Y a donde la fila comenzó antes de la simulación
        $pdf->SetY($yBeforeMultiCellSimulation);
        // Asegura que la altura mínima de la fila sea 8mm (altura de línea normal) o la calculada por MultiCell
        $alturaDeFila = max(8, $direccionCalculatedHeight); 

        // Restaura la posición X al inicio de la fila para dibujar las celdas
        $pdf->SetX($startPosX);

        // Dibuja las celdas con la altura calculada y bordes 'LRB' (Left/Right/Bottom) o 'RB' (Right/Bottom)
        // y con el fondo de color alterno (último parámetro '1')
        $pdf->Cell(12, $alturaDeFila, utf8_decode($rowCounter), 'LRB', 0, 'C', 1); // N°
        $pdf->Cell(40, $alturaDeFila, utf8_decode(ucwords($proveedor["nombre"] ?? '')), 'RB', 0, 'L', 1); // Empresa
        $pdf->Cell(30, $alturaDeFila, $rifCompletoProveedor, 'RB', 0, 'C', 1); // RIF (del PROVEEDOR)
        
        // Defensive checks for nombre_representante and apellido_representante
        $nombreRepresentante = $proveedor["nombre_representante"] ?? '';
        $apellidoRepresentante = $proveedor["apellido_representante"] ?? '';
        $nombreRepresentanteCompleto = ucwords($nombreRepresentante . " " . $apellidoRepresentante);
        $pdf->Cell(50, $alturaDeFila, utf8_decode($nombreRepresentanteCompleto), 'RB', 0, 'L', 1); // Representante
        
        // *** CAMBIO CRÍTICO AQUÍ: Usar $telefonoCompletoProveedor ***
        $pdf->Cell(30, $alturaDeFila, utf8_decode($telefonoCompletoProveedor), 'RB', 0, 'C', 1); // Teléfono (concatenado del PROVEEDOR)
        
        $pdf->Cell(60, $alturaDeFila, utf8_decode($proveedor["correo"] ?? ''), 'RB', 0, 'L', 1); // Correo Electrónico

        // Dibuja la MultiCell real para la Dirección
        // Mueve el cursor X a la posición correcta antes de dibujar la MultiCell
        $pdf->SetX($startPosX + 12 + 40 + 30 + 50 + 30 + 60); // Ajusta a los nuevos anchos de columnas
        $pdf->MultiCell(55, 8, utf8_decode($proveedor["direccion"] ?? ''), 'RB', 'L', 1); // Ancho 55mm para dirección

        // Mueve el cursor a la posición Y para el inicio de la *siguiente* fila,
        // basándose en la altura de la celda más alta (que ya está en $alturaDeFila)
        $pdf->SetY($currentY + $alturaDeFila);
    }
} else {
    $pdf->SetX(10);
    $pdf->Cell(277, 10, utf8_decode('No se encontraron proveedores para mostrar.'), 1, 1, 'C'); // Ancho ajustado al total de la tabla
}

// INICIO DEL BLOQUE PARA REGISTRAR EL EVENTO DE LOG
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$empleadoCedula = (isset($_SESSION["cedula"])) ? $_SESSION["cedula"] : "Sistema/Desconocido";

$logData = array(
    "event_type" => "Generación de Reporte PDF",
    "description" => "Se generó un reporte de Proveedores en formato PDF.",
    "employee_cedula" => $empleadoCedula,
    "affected_table" => "proveedores",
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