<?php
require_once('../assets/fpdf/tfpdf.php');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "vertex";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Error de conexión a la base de datos: " . $conn->connect_error);
}

date_default_timezone_set('America/Caracas');

$fecha_inicio_str = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : '';
$fecha_fin_str = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : '';
$categoria_id = isset($_GET['id_categoria']) ? intval($_GET['id_categoria']) : 0;

if (empty($fecha_inicio_str) || empty($fecha_fin_str) || $categoria_id === 0) {
    echo "<script>alert('¡Por favor, selecciona un rango de fechas y una categoría de producto válidos!'); window.location.href = 'reporte_perdida.php';</script>";
    $conn->close();
    exit;
}

$fecha_inicio = date('Y-m-d', strtotime($fecha_inicio_str));
$fecha_fin = date('Y-m-d', strtotime($fecha_fin_str));

$nombre_categoria_reporte = "Categoría Desconocida";
$stmt_cat = $conn->prepare("SELECT nombre_categoria FROM categoria WHERE id_categoria = ? AND estado_categoria = '1'");
if ($stmt_cat) {
    $stmt_cat->bind_param("i", $categoria_id);
    $stmt_cat->execute();
    $result_cat = $stmt_cat->get_result();
    if ($result_cat->num_rows > 0) {
        $row_cat = $result_cat->fetch_assoc();
        $nombre_categoria_reporte = htmlspecialchars($row_cat['nombre_categoria']);
    }
    $stmt_cat->close();
}

$sql = "SELECT
            p.fecha_perdida,
            prod.nombre_producto AS nombre_producto,
            p.cant AS cantidad_perdida,
            p.precio_perdida AS precio_perdida
        FROM
            perdida p
        JOIN
            producto prod ON p.id_pro = prod.id_pro
        JOIN
            categoria cat ON prod.id_categoria = cat.id_categoria
        WHERE
            cat.id_categoria = ?
            AND p.fecha_perdida BETWEEN ? AND ?
        ORDER BY
            p.fecha_perdida ASC, prod.nombre_producto ASC";

$stmt = $conn->prepare($sql);
if ($stmt) {
    $stmt->bind_param("iss", $categoria_id, $fecha_inicio, $fecha_fin);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    error_log("Error al preparar la consulta de pérdidas en reporte_perdida_funcion.php: " . $conn->error);
    die("Error interno del servidor al generar el reporte. Por favor, inténtalo más tarde.");
}

if ($result->num_rows === 0) {
    echo "<script>alert('¡No se encontraron pérdidas para esta categoría en el rango de fechas seleccionado!'); window.location.href = 'reporte_perdida.php';</script>";
    $stmt->close();
    $conn->close();
    exit;
}

$pdf = new tFPDF();
$pdf->AddFont('dejavusans', '', 'DejaVuSansCondensed.ttf', true);
$pdf->AddFont('dejavusans', 'B', 'DejaVuSansCondensed-Bold.ttf', true);
$pdf->AddFont('dejavusans', 'I', 'DejaVuSansCondensed-Oblique.ttf', true);
$pdf->AddPage();
$pdf->SetMargins(20, 20, 20);
$bottom_margin = 20;
$pdf->SetAutoPageBreak(true, $bottom_margin);

$empresa_info = null;
$stmt_empresa = $conn->prepare("SELECT RIF_empresa, nombre_empresa, cedula_representante, nombre_representante, apellido_representante, telefono_representante, direccion_empresa, logo_empresa FROM empresa LIMIT 1");
if ($stmt_empresa) {
    $stmt_empresa->execute();
    $result_empresa = $stmt_empresa->get_result();
    if ($result_empresa->num_rows > 0) {
        $empresa_info = $result_empresa->fetch_assoc();
    }
    $stmt_empresa->close();
}

$temp_logo_path = '';
$logo_width = 30;
$logo_height = 0;
$logo_padding_right = 5;

$initial_x = $pdf->GetX();
$initial_y = $pdf->GetY();

if ($empresa_info && !empty($empresa_info['logo_empresa'])) {
    $logo_data = $empresa_info['logo_empresa'];
    $temp_logo_path = '../temp/logo_' . uniqid() . '.png';

    if (!is_dir('../temp')) {
        mkdir('../temp', 0777, true);
    }

    if (file_put_contents($temp_logo_path, $logo_data) === false) {
        error_log("Error: No se pudo escribir el logo en el archivo temporal: " . $temp_logo_path);
        $temp_logo_path = '';
    } else {
        $pdf->Image($temp_logo_path, $initial_x, $initial_y, $logo_width, $logo_height);
    }
}

$text_start_x = $initial_x;
if (!empty($temp_logo_path)) {
    $text_start_x += $logo_width + $logo_padding_right;
}

$pdf->SetXY($text_start_x, $initial_y);

$pdf->SetFont('dejavusans', 'B', 14);
$pdf->SetTextColor(50, 50, 50);

if ($empresa_info) {
    $pdf->Cell(0, 8, htmlspecialchars($empresa_info['nombre_empresa']), 0, 1, 'L');
    $pdf->SetX($text_start_x);
    $pdf->SetFont('dejavusans', '', 9);
    $pdf->SetTextColor(80, 80, 80);
    $pdf->Cell(0, 6, htmlspecialchars($empresa_info['RIF_empresa']), 0, 1, 'L');
    $pdf->SetX($text_start_x);
    $pdf->Cell(0, 6, htmlspecialchars($empresa_info['nombre_representante']) . ' ' . htmlspecialchars($empresa_info['apellido_representante']), 0, 1, 'L');
    $pdf->SetX($text_start_x);
    $pdf->Cell(0, 6, htmlspecialchars($empresa_info['cedula_representante']), 0, 1, 'L');
    $pdf->SetX($text_start_x);
    $pdf->Cell(0, 6, htmlspecialchars($empresa_info['telefono_representante']), 0, 1, 'L');
    $pdf->Cell(0, 6, htmlspecialchars($empresa_info['direccion_empresa']), 0, 1, 'L');
} else {
    $pdf->Cell(0, 8, 'Nombre de Empresa (No encontrado)', 0, 1, 'L');
    $pdf->SetX($text_start_x);
    $pdf->SetFont('dejavusans', '', 9);
    $pdf->SetTextColor(80, 80, 80);
    $pdf->Cell(0, 6, 'RIF: No disponible', 0, 1, 'L');
    $pdf->SetX($text_start_x);
    $pdf->Cell(0, 6, 'Representante: No disponible', 0, 1, 'L');
    $pdf->SetX($text_start_x);
    $pdf->Cell(0, 6, 'CI Representante: No disponible', 0, 1, 'L');
    $pdf->SetX($text_start_x);
    $pdf->Cell(0, 6, 'Teléfono Representante: No disponible', 0, 1, 'L');
    $pdf->Cell(0, 6, 'Dirección Empresa: No disponible', 0, 1, 'L');
}

$max_y_after_header_block = $pdf->GetY();
if (!empty($temp_logo_path) && $logo_height > 0) {
    $max_y_after_header_block = max($max_y_after_header_block, $initial_y + $logo_height);
} else if (!empty($temp_logo_path) && $logo_width > 0) {
     $max_y_after_header_block = max($max_y_after_header_block, $initial_y + $logo_width * 0.75);
}

$pdf->SetY($max_y_after_header_block + 7);
$pdf->SetX(20);
$pdf->SetFont('dejavusans', 'B', 16);
$pdf->SetTextColor(50, 50, 50);
$pdf->Cell(0, 10, 'Reporte de Pérdidas', 0, 1, 'L');
$pdf->SetX(20);
$pdf->SetFont('dejavusans', '', 12);
$pdf->SetTextColor(80, 80, 80);
$pdf->Cell(0, 7, 'Categoría: ' . $nombre_categoria_reporte, 0, 1, 'L');
$pdf->SetX(20);
$pdf->Cell(0, 7, 'Período: ' . date('d/m/Y', strtotime($fecha_inicio)) . ' al ' . date('d/m/Y', strtotime($fecha_fin)), 0, 1, 'L');
$pdf->SetX(20);
$pdf->Cell(0, 7, 'Fecha de Generación: ' . date('d/m/Y H:i:s'), 0, 1, 'L');
$pdf->Ln(10);

$pdf->SetFont('dejavusans', 'B', 10);
$pdf->SetFillColor(230, 230, 230);
$pdf->SetTextColor(50, 50, 50);

$page_width = $pdf->GetPageWidth();
$left_margin_pdf = $pdf->GetX();
$right_margin_value = 20;
$available_width = $page_width - $left_margin_pdf - $right_margin_value;

$width_fecha = 25;
$width_producto = 80;
$width_cantidad = 25;

$width_precio_perdida_usd = $available_width - $width_fecha - $width_producto - $width_cantidad;

if ($width_precio_perdida_usd <= 0) {
    $width_precio_perdida_usd = 20;
    error_log("Advertencia: El ancho de la columna 'Precio Perdida USD' fue negativo/cero. Ajustado a un valor mínimo.");
}

$column_widths = [
    $width_fecha,
    $width_producto,
    $width_cantidad,
    $width_precio_perdida_usd
];

$cell_line_height = 5;

$pdf->Cell($column_widths[0], 10, 'Fecha', 'B', 0, 'C', true);
$pdf->Cell($column_widths[1], 10, 'Producto', 'B', 0, 'L', true);
$pdf->Cell($column_widths[2], 10, 'Cantidad', 'B', 0, 'C', true);
$pdf->Cell($column_widths[3], 10, 'Precio Perdida USD', 'B', 1, 'R', true);

$pdf->SetTextColor(50, 50, 50);
$pdf->SetFont('dejavusans', '', 9);

$total_unidades_perdidas = 0;
$total_valor_perdido_sum_precios = 0;

while($row = $result->fetch_assoc()) {
    $fecha_perdida_display = date('d/m/Y', strtotime($row["fecha_perdida"]));
    $nombre_producto = htmlspecialchars($row["nombre_producto"] ?? '');
    $cantidad_perdida = number_format($row["cantidad_perdida"], 2);
    $precio_perdida_item = $row["precio_perdida"];

    $total_unidades_perdidas += $row["cantidad_perdida"];
    $total_valor_perdido_sum_precios += $precio_perdida_item;

    $start_y_row = $pdf->GetY();
    $start_x_row = $pdf->GetX();

    $pdf->Cell($column_widths[0], $cell_line_height, $fecha_perdida_display, 0, 0, 'C');

    $pdf->SetX($start_x_row + $column_widths[0]);
    $pdf->MultiCell($column_widths[1], $cell_line_height, $nombre_producto, 0, 'L', false);
    $prod_end_y = $pdf->GetY();

    $row_height = $prod_end_y - $start_y_row;
    if ($row_height < $cell_line_height * 1.5) {
        $row_height = $cell_line_height * 1.5;
    }

    if ($start_y_row + $row_height > $pdf->GetPageHeight() - $bottom_margin) {
        $pdf->AddPage();
        $pdf->SetFont('dejavusans', 'B', 10);
        $pdf->SetFillColor(230, 230, 230);
        $pdf->SetTextColor(50, 50, 50);
        $pdf->Cell($column_widths[0], 10, 'Fecha', 'B', 0, 'C', true);
        $pdf->Cell($column_widths[1], 10, 'Producto', 'B', 0, 'L', true);
        $pdf->Cell($column_widths[2], 10, 'Cantidad', 'B', 0, 'C', true);
        $pdf->Cell($column_widths[3], 10, 'Precio Perdida USD', 'B', 1, 'R', true);
        $pdf->SetTextColor(50, 50, 50);
        $pdf->SetFont('dejavusans', '', 9);
        $start_y_row = $pdf->GetY();
        $start_x_row = $pdf->GetX();

        $pdf->SetXY($start_x_row, $start_y_row);
        $pdf->Cell($column_widths[0], $cell_line_height, $fecha_perdida_display, 0, 0, 'C');

        $pdf->SetX($start_x_row + $column_widths[0]);
        $pdf->MultiCell($column_widths[1], $cell_line_height, $nombre_producto, 0, 'L', false);
        $prod_end_y = $pdf->GetY();
        $row_height = $prod_end_y - $start_y_row;
        if ($row_height < $cell_line_height * 1.5) {
            $row_height = $cell_line_height * 1.5;
        }
    }

    $pdf->SetXY($start_x_row + $column_widths[0] + $column_widths[1], $start_y_row);
    $pdf->Cell($column_widths[2], $row_height, $cantidad_perdida, 0, 0, 'C');

    $pdf->SetX($start_x_row + $column_widths[0] + $column_widths[1] + $column_widths[2]);
    $pdf->Cell($column_widths[3], $row_height, number_format($precio_perdida_item, 2) . ' $', 0, 1, 'R');

    $pdf->SetY($start_y_row + $row_height);
}

$pdf->Ln(5);

$pdf->SetFillColor(240, 240, 240);
$pdf->SetTextColor(50, 50, 50);
$pdf->SetFont('dejavusans', 'B', 10);

$total_cell_height = 8;

$pdf->SetX(20);
$pdf->Cell($column_widths[0] + $column_widths[1] + $column_widths[2], $total_cell_height, 'Total Unidades Perdidas:', 'T', 0, 'R', true);
$pdf->Cell($column_widths[3], $total_cell_height, number_format($total_unidades_perdidas, 2), 'T', 1, 'R', true);

$pdf->SetX(20);
$pdf->Cell($column_widths[0] + $column_widths[1] + $column_widths[2], $total_cell_height, 'Total Valor Perdido:', 'T', 0, 'R', true);
$pdf->Cell($column_widths[3], $total_cell_height, number_format($total_valor_perdido_sum_precios, 2) . ' $', 'T', 1, 'R', true);

$stmt->close();
$conn->close();

$nombre_limpio_categoria = str_replace(array(' ', '/', '\\', ':', '*', '?', '"', '<', '>', '|'), '_', $nombre_categoria_reporte);
$fecha_actual_formato = date('Y-m-d_His');

$nombre_archivo_pdf = 'ReportePerdidas_' . $nombre_limpio_categoria . '_' . $fecha_actual_formato . '.pdf';

$pdf->Output($nombre_archivo_pdf, 'I');

if (!empty($temp_logo_path) && file_exists($temp_logo_path)) {
    unlink($temp_logo_path);
}

exit;
?>