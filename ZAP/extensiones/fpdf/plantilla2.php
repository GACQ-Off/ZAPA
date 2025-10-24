<?php

// Asegúrate de que fpdf.php está en el mismo directorio o la ruta es correcta
require('fpdf.php');

// --- INICIO DE GESTIÓN DE RUTAS (CRÍTICO PARA LA CONSISTENCIA) ---
// Define ROOT_PATH si no está ya definido.
// Si este archivo está en 'extensiones/fpdf/', sube dos niveles para llegar a 'ventaslog/'.
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR);
}
// --- FIN DE GESTIÓN DE RUTAS ---


// Ahora usa ROOT_PATH para todas las inclusiones
require_once ROOT_PATH . "controladores/configuracion.controlador.php";
require_once ROOT_PATH . "modelos/configuracion.modelo.php";

require_once ROOT_PATH . 'modelos/productos.modelo.php';
require_once ROOT_PATH . 'controladores/productos.controlador.php';

require_once ROOT_PATH . 'modelos/categorias.modelo.php';
require_once ROOT_PATH . 'controladores/categorias.controlador.php';

require_once ROOT_PATH . 'modelos/tipos.modelo.php';
require_once ROOT_PATH . 'controladores/tipos.controlador.php';

require_once ROOT_PATH . 'modelos/marcas.modelo.php';
require_once ROOT_PATH . 'controladores/marcas.controlador.php';

// INCLUSIONES NECESARIAS PARA EL REGISTRO DE EVENTOS LOG
// Solo incluimos el controlador; el controlador se encarga de su modelo.
require_once ROOT_PATH . "controladores/eventolog.controlador.php";
// FIN DE INCLUSIONES PARA EL REGISTRO DE EVENTOS LOG

date_default_timezone_set('America/El_Salvador');

class PDF extends FPDF
{
    function Header()
    {
        // Logo
        $this->Image('img/logo.png', 10, 6, 40);

        // Información de la empresa
        $this->SetFont('Arial', 'B', 14);
        $this->SetXY(55, 13); // Posiciona el texto junto al logo

        $empresa = ControladorConfiguracion::ctrMostrarConfigracion(null, null);
        $nombreEmpresa = utf8_decode($empresa["nombre"]);
        
        // --- MODIFICACIÓN INICIO: Manejo de RIF y Teléfono de la EMPRESA con campos separados ---
        $rifEmpresa = '';
        if (isset($empresa["tipo_rif"]) && isset($empresa["num_rif"])) {
            $rifEmpresa = utf8_decode($empresa["tipo_rif"] . '-' . $empresa["num_rif"]);
        }

        $localizacionEmpresa = utf8_decode($empresa["direccion"]);
        
        $telefonoEmpresa = '';
        if (isset($empresa["prefijo_telefono"]) && isset($empresa["numero_telefono"])) {
            $telefonoEmpresa = utf8_decode($empresa["prefijo_telefono"] . '-' . $empresa["numero_telefono"]);
        }
        // --- MODIFICACIÓN FIN: Manejo de RIF y Teléfono de la EMPRESA con campos separados ---

        $this->SetTextColor(0, 0, 0); // Texto negro
        $this->Cell(0, 6, $nombreEmpresa, 0, 1, 'L'); // Nombre de la empresa
        $this->SetX(55);
        $this->Cell(0, 5, utf8_decode("RIF: " . $rifEmpresa), 0, 1, 'L');
        $this->SetX(55);
        $this->Cell(0, 5, utf8_decode("Dirección: " . $localizacionEmpresa), 0, 1, 'L');
        $this->SetX(55);
        $this->Cell(0, 5, utf8_decode("Teléfono: " . $telefonoEmpresa), 0, 1, 'L');

        // Título del reporte
        $this->Ln(15); // Espacio adicional antes del título del reporte
        $this->SetFillColor(70, 130, 180); // Color de fondo para el título
        $this->SetTextColor(255); // Texto blanco
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(0, 10, utf8_decode('REPORTE DE INVENTARIO'), 0, 1, 'C', true); // Título centrado con fondo
        $this->SetTextColor(0); // Restaura el color del texto a negro
        $this->Ln(5); // Espacio después del título
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
$pdf->AddPage('L'); // Set landscape orientation

$pdf->SetAutoPageBreak(true, 20);
$pdf->SetMargins(10, 10, 10); // Márgenes más consistentes (Izquierda, Arriba, Derecha)

// Encabezados de la tabla
$pdf->SetFont('Arial', 'B', 11);
$pdf->SetFillColor(70, 130, 180); // Color de fondo para los encabezados
$pdf->SetTextColor(255); // Texto blanco
$pdf->SetDrawColor(200, 200, 200); // Color de borde suave para la tabla

$pdf->SetX(10);
$pdf->Cell(12, 10, utf8_decode('N°'), 1, 0, 'C', 1);
$pdf->Cell(30, 10, utf8_decode('Código'), 1, 0, 'C', 1);
$pdf->Cell(50, 10, utf8_decode('Descripción'), 1, 0, 'C', 1);
$pdf->Cell(32, 10, utf8_decode('Categoría'), 1, 0, 'C', 1);
$pdf->Cell(25, 10, utf8_decode('Tipo'), 1, 0, 'C', 1);
$pdf->Cell(30, 10, utf8_decode('Marca'), 1, 0, 'C', 1);
$pdf->Cell(20, 10, utf8_decode('Stock'), 1, 0, 'C', 1);
$pdf->Cell(26, 10, utf8_decode('P. Compra ($)'), 1, 0, 'C', 1);
$pdf->Cell(26, 10, utf8_decode('P. Venta ($)'), 1, 0, 'C', 1);
$pdf->Cell(26, 10, utf8_decode('P. Venta (Bs)'), 1, 1, 'C', 1); // Nueva columna

// Restaura colores para las filas de datos
$pdf->SetTextColor(0); // Texto negro
$pdf->SetFont('Arial', '', 10);

$productos = ControladorProductos::ctrMostrarProductos(null, null, 1);

// --- OBTENCIÓN DE LA TASA DE CAMBIO ---
// Aquí obtenemos la configuración general, que debería incluir la tasa de cambio.
$configuracion = ControladorConfiguracion::ctrMostrarConfigracion(null, null);

// Asegúrate de que el nombre del campo sea correcto según tu base de datos
// Por ejemplo, si el campo se llama 'tasa_dolar_bs', úsalo aquí.
// Si no existe, define un valor por defecto o maneja el error.
$tasaCambioUSD_BS = isset($configuracion["precio_dolar"]) ? $configuracion["precio_dolar"] : 36.5; // Valor por defecto si no se encuentra


$rowCounter = 0; // Para el número de fila en el reporte
foreach ($productos as $i => $producto) {
    $codigo = $producto["codigo"];
    $descripcion = $producto["descripcion"];
    $stock = $producto["stock"];
    $pcompraUSD = $producto["precio_compra"];
    $pventaUSD = $producto["precio_venta"];

    // --- FÓRMULA PARA CALCULAR EL PRECIO EN BOLÍVARES ---
    $pventaBS = $pventaUSD * $tasaCambioUSD_BS;

    // Determina el color de fondo para la fila
    if ($stock < 10) {
        $pdf->SetFillColor(255, 204, 204); // Rojo claro para stock bajo
    } else {
        // Alternar colores de fila para mejor legibilidad
        if ($rowCounter % 2 == 0) {
            $pdf->SetFillColor(255, 255, 255); // Blanco
        } else {
            $pdf->SetFillColor(245, 245, 245); // Gris muy claro
        }
    }

    $categoria = ControladorCategorias::ctrMostrarCategorias("id", $producto["id_categoria"])["categoria"];
    $tipos = ControladorTipos::ctrMostrarTipos("id", $producto["id_tipo"])["tipo"];
    $marca = ControladorMarcas::ctrMostrarMarcas("id", $producto["id_marca"])["marca"];

    $pdf->SetX(10);
    $pdf->Cell(12, 8, utf8_decode($rowCounter + 1), 'LR', 0, 'C', 1);
    $pdf->Cell(30, 8, utf8_decode($codigo), 'R', 0, 'C', 1);
    $pdf->Cell(50, 8, utf8_decode(ucwords($descripcion)), 'R', 0, 'C', 1);
    $pdf->Cell(32, 8, utf8_decode($categoria), 'R', 0, 'C', 1);
    $pdf->Cell(25, 8, utf8_decode($tipos), 'R', 0, 'C', 1);
    $pdf->Cell(30, 8, utf8_decode($marca), 'R', 0, 'C', 1);
    $pdf->Cell(20, 8, utf8_decode($stock), 'R', 0, 'C', 1);
    $pdf->Cell(26, 8, number_format($pcompraUSD, 2, ',', '.') . ' $ ', 'R', 0, 'C', 1);
    $pdf->Cell(26, 8, number_format($pventaUSD, 2, ',', '.') . ' $ ', 'R', 0, 'C', 1);
    $pdf->Cell(26, 8, number_format($pventaBS, 2, ',', '.') . ' Bs', 'R', 1, 'C', 1); // Nueva columna con el valor en Bs

    $rowCounter++;
}

// Dibujar una línea inferior para la tabla
// El ancho total de la tabla (282) se mantiene
$pdf->SetX(10);
$pdf->Cell(277, 0, '', 'T', 1, 'C'); // Línea inferior de la tabla

// INICIO DEL BLOQUE PARA REGISTRAR EL EVENTO DE LOG
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$empleadoCedula = (isset($_SESSION["cedula"])) ? $_SESSION["cedula"] : "Sistema/Desconocido";

$logData = array(
    "event_type" => "Generación de Reporte PDF",
    "description" => "Se generó un reporte de Inventario en formato PDF.",
    "employee_cedula" => $empleadoCedula,
    "affected_table" => "productos",
    "affected_row_id" => "N/A"
);

ControladorEventoLog::ctrGuardarEventoLog(
    $logData["event_type"],
    $logData["description"],
    $logData["employee_cedula"],
    $logData["affected_table"],
    $logData["affected_row_id"]
);

// FIN DEL BLOQUE PARA REGISTRAR EL EVENTO DE LOG

$pdf->Output();
?>