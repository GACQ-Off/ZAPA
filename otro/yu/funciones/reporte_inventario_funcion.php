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

$categoria_id = isset($_GET['categoria_id']) ? intval($_GET['categoria_id']) : 0;

if ($categoria_id === 0) {
    die("Error: No se ha seleccionado una categoría válida para el reporte.");
}

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
            p.nombre_producto,
            p.cantidad,
            p.precio,
            p.descrip_prod,
            c.nombre_categoria,
            tc.nom_cuenta
        FROM
            producto p
        JOIN
            categoria c ON p.id_categoria = c.id_categoria
        JOIN
            tipo_cuenta tc ON p.id_tipo_cuenta = tc.id_tipo_cuenta
        WHERE
            p.id_categoria = ? AND p.estado_producto = '1'
        ORDER BY
            p.nombre_producto ASC";

$stmt = $conn->prepare($sql);
if ($stmt) {
    $stmt->bind_param("i", $categoria_id);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    die("Error al preparar la consulta: " . $conn->error);
}

if ($result->num_rows === 0) {
    echo "<script>alert('¡Esta categoría no tiene productos activos para reportar!'); window.location.href = 'reporte_inventario.php';</script>";
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
$pdf->SetAutoPageBreak(true, 20);
$bottom_margin = 20;

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
$pdf->Cell(0, 10, 'Reporte de Inventario', 0, 1, 'L');
$pdf->SetX(20);
$pdf->SetFont('dejavusans', '', 12);
$pdf->SetTextColor(80, 80, 80);
$pdf->Cell(0, 7, 'Categoría: ' . $nombre_categoria_reporte, 0, 1, 'L');
$pdf->SetX(20);
$pdf->Cell(0, 7, 'Fecha de Generación: ' . date('d/m/Y H:i:s'), 0, 1, 'L');
$pdf->Ln(10);

$pdf->SetFont('dejavusans', 'B', 10);
$pdf->SetFillColor(230, 230, 230);
$pdf->SetTextColor(50, 50, 50);

$column_widths = [45, 30, 30, 25, 40];
$cell_line_height = 5;

$pdf->Cell($column_widths[0], 10, 'Producto', 'B', 0, 'L', true);
$pdf->Cell($column_widths[1], 10, 'Categoría', 'B', 0, 'L', true);
$pdf->Cell($column_widths[2], 10, 'Precio Unitario', 'B', 0, 'R', true);
$pdf->Cell($column_widths[3], 10, 'Stock', 'B', 0, 'C', true);
$pdf->Cell($column_widths[4], 10, 'Descripción', 'B', 1, 'L', true);

$pdf->SetTextColor(50, 50, 50);
$pdf->SetFont('dejavusans', '', 9);

while($row = $result->fetch_assoc()) {
    $precio_pro = '$' . number_format($row["precio"], 2);

    $stock_display = '';
    if ($row["nom_cuenta"] == 'Stock Contable') {
        $stock_display = htmlspecialchars($row["cantidad"]);
    } elseif ($row["nom_cuenta"] == 'Stock Siempre en Existencia') {
        $stock_display = 'En Existencia';
    } else {
        $stock_display = htmlspecialchars($row["cantidad"]) . ' (' . htmlspecialchars($row["nom_cuenta"]) . ')';
    }

    $descripcion_producto = htmlspecialchars($row["descrip_prod"] ?? '');

    $start_y_row = $pdf->GetY();
    $start_x_row = $pdf->GetX();

    $pdf->SetX($start_x_row + array_sum(array_slice($column_widths, 0, 4)));
    $pdf->MultiCell($column_widths[4], $cell_line_height, $descripcion_producto, 0, 'L', false, 0); 
    $desc_height = $pdf->GetY() - $start_y_row;

    $pdf->SetXY($start_x_row, $start_y_row);

    $min_cell_height = $cell_line_height * 1.5;
    if (empty($descripcion_producto)) {
        $row_height = $min_cell_height;
    } else {
        $row_height = max($min_cell_height, $desc_height);
    }
    
    if ($pdf->GetY() + $row_height > $pdf->GetPageHeight() - $bottom_margin) {
        $pdf->AddPage();
        $pdf->SetFont('dejavusans', 'B', 10);
        $pdf->SetFillColor(230, 230, 230);
        $pdf->SetTextColor(50, 50, 50);
        $pdf->Cell($column_widths[0], 10, 'Producto', 'B', 0, 'L', true);
        $pdf->Cell($column_widths[1], 10, 'Categoría', 'B', 0, 'L', true);
        $pdf->Cell($column_widths[2], 10, 'Precio Unitario', 'B', 0, 'R', true);
        $pdf->Cell($column_widths[3], 10, 'Stock', 'B', 0, 'C', true);
        $pdf->Cell($column_widths[4], 10, 'Descripción', 'B', 1, 'L', true);
        $pdf->SetTextColor(50, 50, 50);
        $pdf->SetFont('dejavusans', '', 9);
        $start_y_row = $pdf->GetY();
        $start_x_row = $pdf->GetX();
    }

    $pdf->Cell($column_widths[0], $cell_line_height, htmlspecialchars($row["nombre_producto"]), 0, 0, 'L');
    $pdf->Cell($column_widths[1], $cell_line_height, htmlspecialchars($row["nombre_categoria"]), 0, 0, 'L');
    $pdf->Cell($column_widths[2], $cell_line_height, $precio_pro, 0, 0, 'R');
    $pdf->Cell($column_widths[3], $cell_line_height, $stock_display, 0, 0, 'C');

    $pdf->SetX($start_x_row + array_sum(array_slice($column_widths, 0, 4)));
    $pdf->MultiCell($column_widths[4], $cell_line_height, $descripcion_producto, 0, 'L');

    $pdf->SetY($start_y_row + $row_height);
    $pdf->SetX($start_x_row);
}

$stmt->close();
$conn->close();

$nombre_limpio_categoria = str_replace(array(' ', '/', '\\', ':', '*', '?', '"', '<', '>', '|'), '_', $nombre_categoria_reporte);
$fecha_actual_formato = date('Y-m-d_His');

$nombre_archivo_pdf = 'Inventario_' . $nombre_limpio_categoria . '_' . $fecha_actual_formato . '.pdf';

$pdf->Output($nombre_archivo_pdf, 'I');

if (!empty($temp_logo_path) && file_exists($temp_logo_path)) {
    unlink($temp_logo_path);
}

exit;