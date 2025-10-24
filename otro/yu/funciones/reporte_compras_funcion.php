<?php
require_once('../assets/fpdf/tfpdf.php');
include "../conexion/conexion.php";

date_default_timezone_set('America/Caracas');

$rif_proveedor = isset($_GET['rif_proveedor']) ? $_GET['rif_proveedor'] : '';
$fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : '';
$fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : '';

if (empty($rif_proveedor) || empty($fecha_inicio) || empty(date('Y-m-d', strtotime($fecha_fin)))) {
    die("Error: Debes seleccionar un proveedor y un rango de fechas válidos para generar el reporte.");
}

$empresa_info = null;
$stmt_empresa = $conn->prepare("SELECT RIF_empresa, nombre_empresa, cedula_representante, nombre_representante, apellido_representante, telefono_representante, logo_empresa, direccion_empresa FROM empresa LIMIT 1");
if ($stmt_empresa) {
    $stmt_empresa->execute();
    $result_empresa = $stmt_empresa->get_result();
    if ($result_empresa->num_rows > 0) {
        $empresa_info = $result_empresa->fetch_assoc();
    }
    $stmt_empresa->close();
}

$info_proveedor = null;
$stmt_prov_nombre = $conn->prepare("SELECT RIF, nombre_provedor, telefono_pro, correo_pro FROM proveedor WHERE RIF = ?");
if ($stmt_prov_nombre) {
    $stmt_prov_nombre->bind_param("s", $rif_proveedor);
    $stmt_prov_nombre->execute();
    $result_prov_nombre = $stmt_prov_nombre->get_result();
    if ($result_prov_nombre->num_rows > 0) {
        $info_proveedor = $result_prov_nombre->fetch_assoc();
    }
    $stmt_prov_nombre->close();
} else {
    die("Error al preparar la consulta del proveedor: " . $conn->error);
}

$sql_compras = "SELECT
                    pp.id_producto_proveedor,
                    pp.RIF,
                    prod.nombre_producto,
                    prod.id_iva,
                    prod.descrip_prod,
                    pp.costo_compra,
                    pp.cantidad_compra,
                    (pp.costo_compra * pp.cantidad_compra) AS subtotal_calculado,
                    iva.nombre_iva,
                    iva.valor_iva,
                    tc.nom_cuenta AS tipo_stock,
                    pp.fecha,
                    pp.id_tipo_pago_contado,
                    pp.monto_moneda_pago_contado,
                    pp.codigo_moneda_pago_contado,
                    pp.tasa_cambio_aplicada_contado
                FROM
                    producto_proveedor pp
                JOIN
                    producto prod ON pp.id_pro = prod.id_pro
                JOIN
                    iva ON prod.id_iva = iva.id_iva
                JOIN
                    tipo_cuenta tc ON prod.id_tipo_cuenta = tc.id_tipo_cuenta
                WHERE
                    pp.RIF = ? AND pp.fecha BETWEEN ? AND ?
                ORDER BY
                    pp.fecha ASC, prod.nombre_producto ASC";

$stmt_compras = $conn->prepare($sql_compras);
if ($stmt_compras) {
    $stmt_compras->bind_param("sss", $rif_proveedor, $fecha_inicio, $fecha_fin);
    $stmt_compras->execute();
    $result_compras = $stmt_compras->get_result();
} else {
    die("Error al preparar la consulta de compras: " . $conn->error);
}

$pdf = new tFPDF();
$pdf->AddFont('dejavusans', '', 'DejaVuSansCondensed.ttf', true);
$pdf->AddFont('dejavusans', 'B', 'DejaVuSansCondensed-Bold.ttf', true);
$pdf->AddFont('dejavusans', 'I', 'DejaVuSansCondensed-Oblique.ttf', true);
$pdf->AddPage();
$pdf->SetMargins(10, 10, 10);
$pdf->SetAutoPageBreak(true, 10);

$logo_width = 30;
$logo_height = 0;
$logo_padding_right = 5;

$initial_x = $pdf->GetX();
$initial_y = $pdf->GetY();

$temp_logo_path = '';
if ($empresa_info && !empty($empresa_info['logo_empresa'])) {
    $logo_data = $empresa_info['logo_empresa'];
    $temp_logo_path = '../temp/logo_' . uniqid() . '.png';

    if (!is_dir('../temp')) {
        mkdir('../temp', 0777, true);
    }
    if (file_put_contents($temp_logo_path, $logo_data) === false) {
        error_log("Error: No se pudo escribir el logo temporal: " . $temp_logo_path);
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
    $pdf->Cell(0, 7, htmlspecialchars($empresa_info['nombre_empresa']), 0, 1, 'L');
    $pdf->SetX($text_start_x);
    $pdf->SetFont('dejavusans', '', 9);
    $pdf->SetTextColor(80, 80, 80);
    $pdf->Cell(0, 5, htmlspecialchars($empresa_info['RIF_empresa']), 0, 1, 'L');
    $pdf->SetX($text_start_x);
    $pdf->Cell(0, 5, htmlspecialchars($empresa_info['nombre_representante']) . ' ' . htmlspecialchars($empresa_info['apellido_representante']), 0, 1, 'L');
    $pdf->SetX($text_start_x);
    $pdf->Cell(0, 5, htmlspecialchars($empresa_info['cedula_representante']), 0, 1, 'L');
    $pdf->SetX($text_start_x);
    $pdf->Cell(0, 5, htmlspecialchars($empresa_info['telefono_representante']), 0, 1, 'L');
    $pdf->SetX($text_start_x);
    $pdf->Cell(0, 5, htmlspecialchars($empresa_info['direccion_empresa']), 0, 1, 'L');
} else {
    $pdf->Cell(0, 7, 'Nombre de Empresa (No encontrado)', 0, 1, 'L');
    $pdf->SetX($text_start_x);
    $pdf->SetFont('dejavusans', '', 9);
    $pdf->SetTextColor(80, 80, 80);
    $pdf->Cell(0, 5, 'RIF: No disponible', 0, 1, 'L');
    $pdf->SetX($text_start_x);
    $pdf->Cell(0, 5, 'Representante: No disponible', 0, 1, 'L');
    $pdf->SetX($text_start_x);
    $pdf->Cell(0, 5, 'CI: No disponible', 0, 1, 'L');
    $pdf->SetX($text_start_x);
    $pdf->Cell(0, 5, 'Teléfono: No disponible', 0, 1, 'L');
    $pdf->SetX($text_start_x);
    $pdf->Cell(0, 5, 'Dirección: No disponible', 0, 1, 'L');
}

$max_y_after_header_block = $pdf->GetY();
if (!empty($temp_logo_path) && $logo_height > 0) {
    $max_y_after_header_block = max($max_y_after_header_block, $initial_y + $logo_height);
} else if (!empty($temp_logo_path) && $logo_width > 0) {
     $max_y_after_header_block = max($max_y_after_header_block, $initial_y + $logo_width * 0.75);
}
$pdf->SetY($max_y_after_header_block + 10);

$pdf->SetX(10);
$pdf->SetFont('dejavusans', 'B', 20);
$pdf->SetTextColor(30, 30, 30);
$pdf->Cell(0, 12, 'FACTURA DE COMPRA', 0, 1, 'R');

$pdf->SetDrawColor(200, 200, 200);
$pdf->Line(10, $pdf->GetY(), $pdf->GetPageWidth() - 10, $pdf->GetY());
$pdf->Ln(5);

$pdf->SetFont('dejavusans', '', 10);
$pdf->SetTextColor(70, 70, 70);
$pdf->Cell(0, 6, 'Período de Compra: ' . date('d/m/Y', strtotime($fecha_inicio)) . ' - ' . date('d/m/Y', strtotime($fecha_fin)), 0, 1, 'R');
$pdf->Ln(10);

$pdf->SetFont('dejavusans', 'B', 11);
$pdf->SetTextColor(50, 50, 50);
$pdf->Cell(0, 7, 'PROVEEDOR:', 0, 1, 'L');
$pdf->SetFont('dejavusans', '', 10);
$pdf->SetTextColor(0, 0, 0);

if ($info_proveedor) {
    $pdf->Cell(0, 6, htmlspecialchars($info_proveedor['nombre_provedor']), 0, 1, 'L');
    $pdf->Cell(0, 6, 'RIF: ' . htmlspecialchars($info_proveedor['RIF']), 0, 1, 'L');
    $pdf->Cell(0, 6, 'Teléfono: ' . htmlspecialchars($info_proveedor['telefono_pro']), 0, 1, 'L');
    $pdf->Cell(0, 6, 'Correo: ' . htmlspecialchars($info_proveedor['correo_pro']), 0, 1, 'L');
} else {
    $pdf->Cell(0, 6, 'Proveedor: No encontrado.', 0, 1, 'L');
}
$pdf->Ln(10);

$pdf->SetFont('dejavusans', 'B', 9);
$pdf->SetFillColor(235, 235, 235);
$pdf->SetTextColor(50, 50, 50);
$pdf->SetDrawColor(200, 200, 200);

$total_subtotal_sin_iva = 0;
$total_iva = 0;
$total_compras_final = 0;

$ancho_fecha = 20;
$ancho_producto = 40;
$ancho_cant = 15;
$ancho_costo_unit = 22;
$ancho_subtotal = 25;
$ancho_total_item = 25;
$ancho_descripcion = 43;

$altura_base_celda_datos = 6;
$line_height_multicell = 4.5;

$pdf->Cell($ancho_fecha, $altura_base_celda_datos + 2, 'Fecha', 'B', 0, 'C', true);
$pdf->Cell($ancho_producto, $altura_base_celda_datos + 2, 'Producto', 'B', 0, 'L', true);
$pdf->Cell($ancho_cant, $altura_base_celda_datos + 2, 'Cantidad', 'B', 0, 'C', true);
$pdf->Cell($ancho_costo_unit, $altura_base_celda_datos + 2, 'Costo', 'B', 0, 'R', true);
$pdf->Cell($ancho_subtotal, $altura_base_celda_datos + 2, 'Subtotal', 'B', 0, 'R', true);
$pdf->Cell($ancho_total_item, $altura_base_celda_datos + 2, 'Total Item', 'B', 0, 'R', true);
$pdf->Cell($ancho_descripcion, $altura_base_celda_datos + 2, 'Descripción', 'B', 1, 'L', true);

$pdf->SetFont('dejavusans', '', 8);
$pdf->SetFillColor(255, 255, 255);
$pdf->SetTextColor(0, 0, 0);

if ($result_compras->num_rows > 0) {
    while($row_compra = $result_compras->fetch_assoc()) {
        $fecha_formateada = date('d/m/Y', strtotime($row_compra["fecha"]));
        $nombre_producto = htmlspecialchars($row_compra["nombre_producto"]);
        $descripcion_producto = htmlspecialchars($row_compra["descrip_prod"]);
        $cantidad = number_format($row_compra["cantidad_compra"], 2);
        $costo_unitario = $row_compra["costo_compra"];
        $costo_unitario_display = number_format($costo_unitario, 2, '.', ',') . ' ' . htmlspecialchars($row_compra["codigo_moneda_pago_contado"] ?: 'USD');

        $valor_iva_porcentaje = $row_compra['valor_iva'];
        
        $subtotal_item_sin_iva = $row_compra['subtotal_calculado'];

        $display_cantidad = htmlspecialchars($row_compra["cantidad_compra"]);
        $monto_iva_item = 0;
        $total_item_con_iva = 0;

        if (trim($row_compra["tipo_stock"]) == 'Stock Siempre en Existencia') {
            $display_cantidad = 'En Existencia';
            $subtotal_item_sin_iva = $costo_unitario;
            $monto_iva_item = ($costo_unitario * $valor_iva_porcentaje) / 100;
            $total_item_con_iva = $costo_unitario + $monto_iva_item;
        } else {
            $monto_iva_item = ($subtotal_item_sin_iva * $valor_iva_porcentaje) / 100;
            $total_item_con_iva = $subtotal_item_sin_iva + $monto_iva_item;
        }

        $x_initial_row = $pdf->GetX();
        $y_initial_row = $pdf->GetY();

        $pdf_cloned = clone $pdf;
        $pdf_cloned->AddPage();
        $pdf_cloned->SetFont('dejavusans', '', 8);
        
        $pdf_cloned->SetXY($x_initial_row + $ancho_fecha, $y_initial_row);
        $pdf_cloned->MultiCell($ancho_producto, $line_height_multicell, $nombre_producto, 0, 'L', false, 0);
        $altura_real_producto_cell = $pdf_cloned->GetY() - $y_initial_row;
        
        $pdf_cloned->SetXY($x_initial_row + $ancho_fecha + $ancho_producto + $ancho_cant + $ancho_costo_unit + $ancho_subtotal + $ancho_total_item, $y_initial_row);
        $pdf_cloned->MultiCell($ancho_descripcion, $line_height_multicell, $descripcion_producto, 0, 'L', false, 0);
        $altura_real_descripcion_cell = $pdf_cloned->GetY() - $y_initial_row;

        unset($pdf_cloned);

        $altura_fila = max($altura_base_celda_datos, $altura_real_producto_cell, $altura_real_descripcion_cell);

        $pdf->Cell($ancho_fecha, $altura_fila, $fecha_formateada, 'LR', 0, 'C', false);

        $x_producto_start = $pdf->GetX();
        $pdf->MultiCell($ancho_producto, $line_height_multicell, $nombre_producto, 'R', 'L', false);
        $pdf->SetXY($x_producto_start + $ancho_producto, $y_initial_row);
        
        $pdf->Cell($ancho_cant, $altura_fila, $display_cantidad, 'R', 0, 'C', false);
        $pdf->Cell($ancho_costo_unit, $altura_fila, $costo_unitario_display, 'R', 0, 'R', false);
        $pdf->Cell($ancho_subtotal, $altura_fila, number_format($subtotal_item_sin_iva, 2, '.', ',') . ' USD', 'R', 0, 'R', false);
        $pdf->Cell($ancho_total_item, $altura_fila, number_format($total_item_con_iva, 2, '.', ',') . ' USD', 'R', 0, 'R', false);
        
        $x_descripcion_start = $pdf->GetX();
        $pdf->MultiCell($ancho_descripcion, $line_height_multicell, $descripcion_producto, 'R', 'L', false);
        $pdf->SetXY($x_descripcion_start + $ancho_descripcion, $y_initial_row);
        $pdf->SetY($y_initial_row + $altura_fila);

        $pdf->SetX(10);
        $pdf->SetDrawColor(230, 230, 230);
        $pdf->Cell(array_sum([$ancho_fecha, $ancho_producto, $ancho_cant, $ancho_costo_unit, $ancho_subtotal, $ancho_total_item, $ancho_descripcion]), 0, '', 'T', 1);

        $total_subtotal_sin_iva += $subtotal_item_sin_iva;
        $total_iva += $monto_iva_item;
        $total_compras_final += $total_item_con_iva;
    }
} else {
    $pdf->Cell(array_sum([$ancho_fecha, $ancho_producto, $ancho_cant, $ancho_costo_unit, $ancho_subtotal, $ancho_total_item, $ancho_descripcion]), 10, 'No se encontraron compras para el proveedor y rango de fechas seleccionados.', 1, 1, 'C');
}
$pdf->Ln(5);

$pdf->SetFont('dejavusans', '', 10);
$pdf->SetTextColor(50, 50, 50);

$total_ancho_cols_pre_totales = $pdf->GetPageWidth() - 10 - 10 - ($ancho_subtotal + $ancho_total_item + $ancho_descripcion);

$pdf->Cell($total_ancho_cols_pre_totales, 7, 'Subtotal neto:', 0, 0, 'R');
$pdf->SetFont('dejavusans', 'B', 10);
$pdf->Cell($ancho_subtotal + $ancho_total_item + $ancho_descripcion, 7, number_format($total_subtotal_sin_iva, 2, '.', ',') . ' USD', 0, 1, 'R');

$pdf->SetFont('dejavusans', '', 10);
$pdf->Cell($total_ancho_cols_pre_totales, 7, 'IVA total:', 0, 0, 'R');
$pdf->SetFont('dejavusans', 'B', 10);
$pdf->Cell($ancho_subtotal + $ancho_total_item + $ancho_descripcion, 7, number_format($total_iva, 2, '.', ',') . ' USD', 0, 1, 'R');

$pdf->Ln(3);

$pdf->SetFont('dejavusans', 'B', 14);
$pdf->SetFillColor(220, 230, 240);
$pdf->SetTextColor(20, 20, 20);
$pdf->Cell($total_ancho_cols_pre_totales, 10, 'TOTAL A PAGAR:', 0, 0, 'R');
$pdf->Cell($ancho_subtotal + $ancho_total_item + $ancho_descripcion, 10, number_format($total_compras_final, 2, '.', ',') . ' USD', 1, 1, 'R', true);

$altura_celda_pie = 10;
$margen_inferior_pdf = 10;

$fpdf_b_margin = 10;

$limite_y_automatico = $pdf->GetPageHeight() - $fpdf_b_margin;

$y_actual_contenido = $pdf->GetY();

if (($y_actual_contenido + $altura_celda_pie + 5) < $limite_y_automatico) {
    $pdf->SetY($pdf->GetPageHeight() - $margen_inferior_pdf - $altura_celda_pie);
} else {
    $pdf->AddPage();
    $pdf->SetY($pdf->GetPageHeight() - $margen_inferior_pdf - $altura_celda_pie);
}

$pdf->SetFont('dejavusans', 'I', 8);
$pdf->SetTextColor(150, 150, 150);
$pdf->Cell(0, $altura_celda_pie, 'Generado por el sistema el ' . date('d/m/Y H:i:s'), 0, 0, 'C');

$stmt_compras->close();
$conn->close();

if (!empty($temp_logo_path) && file_exists($temp_logo_path)) {
    unlink($temp_logo_path);
}

$nombre_base_proveedor = $info_proveedor['nombre_provedor'] ?? 'Proveedor';
$nombre_limpio_proveedor = iconv('UTF-8', 'ASCII//TRANSLIT', $nombre_base_proveedor);
$nombre_limpio_proveedor = preg_replace('/[^a-zA-Z0-9_.-]/', '', $nombre_limpio_proveedor);
$nombre_limpio_proveedor = str_replace(' ', '_', $nombre_limpio_proveedor);

$nombre_archivo_pdf = 'Factura_Compra_' . $nombre_limpio_proveedor . '_' . $fecha_inicio . '_a_' . $fecha_fin . '.pdf';

$pdf->Output($nombre_archivo_pdf, 'I');
exit;