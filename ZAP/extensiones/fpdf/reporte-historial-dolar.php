<?php

// INICIO: CONFIGURACIÓN DE DEPURACIÓN (DESACTIVAR EN PRODUCCIÓN)
ini_set("display_errors", 1);
error_reporting(E_ALL);
// FIN: CONFIGURACIÓN DE DEPURACIÓN

// =========================================================
// 1. INCLUIR LIBRERÍAS Y CONTROLADORES/MODELOS NECESARIOS
// =========================================================

// --- INICIO DE GESTIÓN DE RUTAS (CRÍTICO PARA LA CONSISTENCIA) ---
// Define ROOT_PATH si no está ya definido.
// Si este archivo (reporte-historial-dolar.php) está en 'extensiones/fpdf/',
// necesita subir DOS niveles para llegar a la carpeta 'ventaslog/',
// que es la raíz de tu aplicación.
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR); // Sube DOS directorios
}
// --- FIN DE GESTIÓN DE RUTAS ---

// Incluir FPDF (ajusta la ruta si tu carpeta FPDF está en otro lugar)
// Asume que fpdf.php está en el mismo directorio que este script.
require('fpdf.php'); 

// Ahora usa ROOT_PATH para todas las inclusiones
require_once ROOT_PATH . 'controladores/historialDolar.controlador.php';
require_once ROOT_PATH . 'modelos/historialDolar.modelo.php';

// Incluir configuración de la empresa (para la cabecera unificada)
require_once ROOT_PATH . "controladores/configuracion.controlador.php";
require_once ROOT_PATH . "modelos/configuracion.modelo.php";

// Establece la zona horaria a Venezuela
date_default_timezone_set('America/Caracas');

// Asegurarse de que la sesión esté iniciada si vas a validar permisos aquí
// (Aunque no se usa para el log, puede ser necesario para otras validaciones de acceso)
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Opcional: Validación de permisos (copiada de tu vista, adapta si es necesario)
// if(isset($_SESSION["iniciarSesion"]) && $_SESSION["iniciarSesion"] == "ok"){
//      // Si el perfil no es el adecuado para ver reportes, redirigir
//      // if($_SESSION["perfil"] == "Especial" || $_SESSION["perfil"] == "Vendedor"){
//      //    // Puedes redirigir a una página de error o simplemente no generar el PDF
//      //    echo "Acceso denegado.";
//      //    exit();
//      // }
// } else {
//      // Si no hay sesión iniciada, redirige al login o maneja el error
//      // header("Location: ../login"); // Redirige al login si no hay sesión
//      // exit();
// }

// =========================================================
// 2. OBTENER LOS DATOS DEL HISTORIAL DEL DÓLAR
// =========================================================
$historialDolar = ControladorHistorialDolar::ctrMostrarHistorialDolarCompleto();

// =========================================================
// 3. CLASE PERSONALIZADA PARA EL REPORTE FPDF (Opcional, pero buena práctica)
// =========================================================
class PDF extends FPDF
{
    // Cabecera de página
    function Header()
    {
        // Logo - Adaptado a la posición y tamaño de tu reporte de inventario/clientes de referencia
        // Asumiendo que 'img/logo.png' está en la carpeta raíz 'ventaslog/'
        // Si este script está en 'ventaslog/extensiones/fpdf/', la ruta relativa es '../../img/logo.png'
        $this->Image('../../img/logo.png', 10, 6, 40); // Ajustado para un logo común

        // Información de la empresa - Adoptando el estilo de tu reporte de proveedores
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
        $this->SetXY(55, 12); // Ajusta la posición Y ligeramente para que no choque con el logo
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
        // Usamos 'REPORTE DE HISTORIAL DEL PRECIO DEL DÓLAR' en mayúsculas y centrado con fondo
        $this->Cell(0, 10, utf8_decode('REPORTE DE HISTORIAL DEL PRECIO DEL DÓLAR'), 0, 1, 'C', true); // Título centrado con fondo
        $this->SetTextColor(0); // Restaura el color del texto a negro
        $this->Ln(5); // Espacio después del título

        // Encabezados de la tabla de datos (ajustados a tu reporte de proveedores)
        $this->SetFont('Arial', 'B', 11); // Fuente ajustada a 11 para consistencia
        $this->SetFillColor(70, 130, 180); // Azul acero para el fondo
        $this->SetTextColor(255); // Texto blanco
        $this->SetDrawColor(200, 200, 200); // Color de borde suave para la tabla

        // Anchos de las celdas (ajusta según tus necesidades y tamaño de página Letter en Portrait)
        // Página Letter Portrait: Ancho aprox. 215mm. Márgenes 10mm izq y der = 195mm de área útil.
        // Ajustamos para usar el ancho disponible.
        $this->SetX(10); // Inicia la tabla en el margen izquierdo
        $this->Cell(15, 10, utf8_decode('Id'), 1, 0, 'C', 1);
        $this->Cell(45, 10, utf8_decode('Precio Actualizado'), 1, 0, 'C', 1); // Aumentado a 45
        $this->Cell(45, 10, utf8_decode('Precio Anterior'), 1, 0, 'C', 1); // Aumentado a 45
        $this->Cell(30, 10, utf8_decode('Estado'), 1, 0, 'C', 1); // Reducido a 30 (siempre son "Aumento", "Disminucion", "Mantener")
        $this->Cell(60, 10, utf8_decode('Fecha y Hora'), 1, 1, 'C', 1); // Ajustado a 60, Salto de línea al final de la cabecera
    }

    // Pie de página
    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 9); // Adoptando el estilo de fuente de tu reporte de inventario/clientes
        $this->Cell(95, 5, utf8_decode('Página ') . $this->PageNo() . ' / {nb}', 0, 0, 'L');
        $this->Cell(95, 5, date('d/m/Y | g:i:a'), 0, 1, 'R'); // Ancho ajustado para que quepa en una página 'Letter'
        // Se elimina la línea horizontal redundante, consistente con tus otros reportes mejorados.
    }
}

// =========================================================
// 4. GENERAR EL REPORTE
// =========================================================
// Creación del objeto de la clase heredada FPDF
$pdf = new PDF('P', 'mm', 'Letter'); // 'P' para Portrait (vertical), 'mm' para milímetros, 'Letter' para tamaño carta
$pdf->AliasNbPages(); // Necesario para el pie de página {nb}
$pdf->AddPage(); // Añade la primera página

$pdf->SetAutoPageBreak(true, 20); // Margen inferior para salto de página
$pdf->SetMargins(10, 10, 10); // Márgenes consistentes (Izquierda, Arriba, Derecha)

// Contenido de la tabla de datos
$pdf->SetFont('Arial', '', 10); // Fuente ajustada a 10 para consistencia
$pdf->SetTextColor(0, 0, 0); // Texto negro para los datos

// Restaura colores para las filas de datos
$pdf->SetTextColor(0); // Texto negro
$pdf->SetFont('Arial', '', 10); // Fuente ajustada a 10 para consistencia

$rowCounter = 0; // Para el número de fila (Id)

if (is_array($historialDolar) && !empty($historialDolar)) {
    foreach ($historialDolar as $key => $value) {
        $rowCounter++; // Incrementar el contador para el N° de fila

        // Alternar colores de fila para mejor legibilidad, como en tu referencia de inventario/clientes
        if ($rowCounter % 2 == 0) {
            $pdf->SetFillColor(255, 255, 255); // Blanco
        } else {
            $pdf->SetFillColor(245, 245, 245); // Gris muy claro
        }
        $pdf->SetDrawColor(200, 200, 200); // Borde suave para las filas de datos

        // Recuperar y formatear los datos de la base de datos
        // Usamos $rowCounter para el 'Id' en el reporte para una secuencia numérica limpia
        $id_display = $rowCounter;
        $precio_actual = number_format(floatval($value["precio_dolar"]), 4, ',', '.'); // Formato numérico
        
        // Manejar precios nulos y formatear
        $precio_anterior_display = "N/A";
        if (isset($value["precio_anterior"]) && $value["precio_anterior"] !== null && $value["precio_anterior"] !== '') {
            $precio_anterior_display = number_format(floatval($value["precio_anterior"]), 4, ',', '.');
        }

        $estado_cambio_display = utf8_decode($value["estado_cambio"] ?? "N/A"); // Asegurar utf8_decode
        $fecha_hora = utf8_decode($value["fecha_cambio"] ?? "N/A"); // Asegurar utf8_decode

        // Imprimir cada celda de la fila con la altura calculada y bordes 'LRB' o 'RB'
        // Anchos de las celdas: Id(15), Precio Actualizado(45), Precio Anterior(45), Estado(30), Fecha y Hora(60)
        $pdf->SetX(10); // Restaura la posición X al inicio de la fila
        $pdf->Cell(15, 8, $id_display, 'LRB', 0, 'C', 1); // N°
        $pdf->Cell(45, 8, $precio_actual, 'RB', 0, 'C', 1); // Precio Actualizado
        $pdf->Cell(45, 8, $precio_anterior_display, 'RB', 0, 'C', 1); // Precio Anterior
        $pdf->Cell(30, 8, $estado_cambio_display, 'RB', 0, 'C', 1); // Estado
        $pdf->Cell(60, 8, $fecha_hora, 'RB', 1, 'C', 1); // Fecha y Hora (salto de línea al final)
    }
} else {
    $pdf->SetX(10);
    // Ancho total de las columnas: 15+45+45+30+60 = 195
    $pdf->Cell(195, 10, utf8_decode('No se encontraron datos de historial del dólar para mostrar.'), 1, 1, 'C');
}

// Salida del PDF
$pdf->Output('I', 'Reporte_Historial_Dolar_'.date('YmdHis').'.pdf');
// 'I' para enviar el archivo al navegador (Inline)
// 'D' para descargar el archivo
// 'F' para guardar en un archivo local
// 'S' para devolver como string

?>