<?php
session_start();
require('../assets/fpdf/tfpdf.php');

ob_start();

$host = 'localhost';
$dbname = 'vertex';
$username = 'root';
$password = '';

try {
    $conexion = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conexion->exec("set names utf8");
} catch (PDOException $e) {
    die("Error en la conexión a la base de datos: " . $e->getMessage());
}

if (!isset($_GET['Empleado'])) {
    die("Error: El parámetro 'Empleado' (cédula) es necesario para generar el recibo.");
}

$cedula_empleado = $_GET['Empleado'];
$id_pago_especifico = $_GET['id_pago'] ?? '';
$fecha_pago_desde = $_GET['fecha_pago_desde'] ?? '';
$fecha_pago_hasta = $_GET['fecha_pago_hasta'] ?? '';
$monto_pago_especifico = $_GET['monto_pago_exacto'] ?? '';

$es_recibo_individual = false;
$titulo_pdf = "Recibo de Pago";
$filtros_aplicados_texto = "";

if (!empty($id_pago_especifico)) {
    $es_recibo_individual = true;
} else {
    $titulo_pdf = "Historial de Pagos del Empleado";
    $filtros_aplicados_texto = "Filtros Aplicados: ";
    $tiene_filtros = false;
    if (!empty($fecha_pago_desde)) {
        $filtros_aplicados_texto .= "Desde: " . date("d/m/Y", strtotime($fecha_pago_desde)) . " ";
        $tiene_filtros = true;
    }
    if (!empty($fecha_pago_hasta)) {
        $filtros_aplicados_texto .= "Hasta: " . date("d/m/Y", strtotime($fecha_pago_hasta)) . " ";
        $tiene_filtros = true;
    }
    if (!empty($monto_pago_especifico)) {
        $filtros_aplicados_texto .= "Monto: $" . number_format((float)$monto_pago_especifico, 2, ',', '.') . " ";
        $tiene_filtros = true;
    }
    if (!$tiene_filtros) {
        $filtros_aplicados_texto = "Filtros Aplicados: Ninguno";
    }
}

$queryEmpleado = "SELECT e.cedula_emple, e.nombre_emp, e.apellido_emp, c.nom_cargo
                         FROM empleado e
                         JOIN cargo c ON e.id_cargo = c.id_cargo
                         WHERE e.cedula_emple = :cedula_empleado";
$stmtEmpleado = $conexion->prepare($queryEmpleado);
$stmtEmpleado->bindParam(':cedula_empleado', $cedula_empleado, PDO::PARAM_STR);
$stmtEmpleado->execute();
$empleado_data = $stmtEmpleado->fetch(PDO::FETCH_ASSOC);

if (!$empleado_data) {
    die("Error: No se encontró el empleado con la cédula: " . htmlspecialchars($cedula_empleado));
}

$queryDeduccionesValores = "SELECT id_deduccion, valor_deduccion FROM deducciones WHERE id_deduccion IN (1, 2)";
$stmtDeduccionesValores = $conexion->query($queryDeduccionesValores);
$deducciones_valores = [];
while ($row = $stmtDeduccionesValores->fetch(PDO::FETCH_ASSOC)) {
    $deducciones_valores[$row['id_deduccion']] = $row['valor_deduccion'];
}
$sso_valor_deduccion_db = $deducciones_valores[1] ?? 0;
$faov_valor_deduccion_db = $deducciones_valores[2] ?? 0;

$queryNomina = "
    SELECT
        n.*,
        td.tasa_dolar,
        td.fecha_dolar
    FROM
        nomina n
    LEFT JOIN
        tasa_dolar td ON n.id_tasa_dolar = td.id_tasa_dolar
    WHERE
        n.cedula_emple = :cedula_empleado";
if (!empty($id_pago_especifico)) {
    $queryNomina .= " AND n.id_nomina = :id_pago_especifico";
}

$stmtNomina = $conexion->prepare($queryNomina);
$stmtNomina->bindParam(':cedula_empleado', $cedula_empleado, PDO::PARAM_STR);
if (!empty($id_pago_especifico)) {
    $stmtNomina->bindParam(':id_pago_especifico', $id_pago_especifico, PDO::PARAM_INT);
}
$stmtNomina->execute();
$nomina_data = $stmtNomina->fetch(PDO::FETCH_ASSOC);

if (!$nomina_data && $es_recibo_individual) {
    die("Error: No se encontraron datos de nómina para el ID de pago especificado.");
} elseif (!$nomina_data && !$es_recibo_individual) {
    $nomina_data = [];
}

$queryEmpresa = "SELECT RIF_empresa, nombre_empresa, nombre_representante, apellido_representante, telefono_representante, direccion_empresa, logo_empresa FROM empresa LIMIT 1";
$resultEmpresa = $conexion->query($queryEmpresa);
$empresa = $resultEmpresa->fetch(PDO::FETCH_ASSOC);

if (!$empresa) {
    $empresa = [
        'RIF_empresa' => 'N/A',
        'nombre_empresa' => 'Nombre de Empresa No Configurado',
        'nombre_representante' => 'N/A',
        'apellido_representante' => '',
        'telefono_representante' => 'N/A',
        'direccion_empresa' => 'N/A',
        'logo_empresa' => null
    ];
} else {
    $empresa['logo_empresa'] = $empresa['logo_empresa'] ?? null;
    $empresa['direccion_empresa'] = $empresa['direccion_empresa'] ?? 'N/A';
}

class PDF extends tFPDF
{
    public $titulo_pdf_dinamico;
    public $es_recibo_individual_dinamico;
    public $filtros_aplicados_texto_dinamico;
    public $nomina_data_dinamica;
    public $sso_valor_deduccion;
    public $faov_valor_deduccion;

    public $colorAzulClaroFondo = [230, 245, 255];
    public $colorAzulTextoHeader = [0, 86, 179];
    public $colorGrisOscuroTexto = [70, 70, 70];
    public $colorGrisMedioTexto = [120, 120, 120];
    public $colorGrisClaroLinea = [200, 200, 200];

    public $pageWidthUtil;

    function __construct($orientation='P', $unit='mm', $size='Letter') {
        parent::__construct($orientation, $unit, $size);
        $this->SetMargins(10, 10, 10);
        $this->SetAutoPageBreak(true, 15);
        $this->pageWidthUtil = $this->GetPageWidth() - ($this->lMargin + $this->rMargin);

        $this->AddFont('DejaVu', '', 'DejaVuSans.ttf', true);
        $this->AddFont('DejaVu', 'B', 'DejaVuSans-Bold.ttf', true);
        $this->AddFont('DejaVu', 'I', 'DejaVuSans-Oblique.ttf', true);
        $this->AddFont('DejaVu', 'BI', 'DejaVuSans-BoldOblique.ttf', true);

        $this->SetFont('DejaVu', '', 10);
    }

    function Header()
    {
        global $empresa, $empleado_data;

        $this->SetMargins(10, 10, 10);
        $this->SetAutoPageBreak(true, 15);
        $this->pageWidthUtil = $this->GetPageWidth() - ($this->lMargin + $this->rMargin);

        date_default_timezone_set("America/Caracas");

        $logo_y_pos = 8;
        $logo_width_mm = 40;
        $logo_max_height_mm = 25;

        $logo_x_pos = ($this->GetPageWidth() - $logo_width_mm) / 2;

        if (!empty($empresa['logo_empresa']) && function_exists('getimagesizefromstring')) {
            $logoData = $empresa['logo_empresa'];
            $imageInfo = @getimagesizefromstring($logoData);

            if ($imageInfo !== false && isset($imageInfo['mime'])) {
                $mime = $imageInfo['mime'];
                $imageType = '';
                switch ($mime) {
                    case 'image/jpeg':
                        $imageType = 'JPG';
                        break;
                    case 'image/png':
                        $imageType = 'PNG';
                        break;
                    case 'image/gif':
                        $imageType = 'GIF';
                        break;
                }

                if ($imageType) {
                    $originalWidthPX = $imageInfo[0];
                    $originalHeightPX = $imageInfo[1];
                    $calculatedHeightMM = $logo_width_mm * ($originalHeightPX / $originalWidthPX);

                    if ($calculatedHeightMM > $logo_max_height_mm) {
                        $calculatedHeightMM = $logo_max_height_mm;
                        $logo_width_mm = $calculatedHeightMM * ($originalWidthPX / $originalHeightPX);
                        $logo_x_pos = ($this->GetPageWidth() - $logo_width_mm) / 2;
                    }

                    $tempImageFile = tempnam(sys_get_temp_dir(), 'pdf_logo_') . '.' . strtolower($imageType);
                    if ($tempImageFile !== false) {
                        file_put_contents($tempImageFile, $logoData);
                        $this->Image($tempImageFile, $logo_x_pos, $logo_y_pos, $logo_width_mm, $calculatedHeightMM, $imageType);
                        unlink($tempImageFile);
                    } else {
                        error_log("Error: Could not create temporary file for PDF logo.");
                    }
                    $this->Ln($calculatedHeightMM + 3);
                } else {
                    $this->Ln(20);
                }
            } else {
                $this->Ln(20);
            }
        } else {
            $this->Ln(20);
        }

        $this->SetY($this->GetY());
        $this->SetFillColor($this->colorAzulClaroFondo[0], $this->colorAzulClaroFondo[1], $this->colorAzulClaroFondo[2]);
        $this->SetTextColor($this->colorAzulTextoHeader[0], $this->colorAzulTextoHeader[1], $this->colorAzulTextoHeader[2]);
        $this->SetFont('DejaVu', '', 13);
        $this->Cell($this->pageWidthUtil, 9, $this->titulo_pdf_dinamico, 0, 1, 'C', true);

        $this->SetTextColor(0,0,0);
        $this->SetFont('DejaVu', '', 9);
        $this->SetX($this->lMargin);
        $this->SetTextColor($this->colorGrisMedioTexto[0], $this->colorGrisMedioTexto[1], $this->colorGrisMedioTexto[2]);
        $this->Cell($this->pageWidthUtil, 6, "Fecha del Recibo: " . date("d/m/Y H:i"), 0, 1, 'C');
        $this->Ln(2);

        if ($this->PageNo() == 1) {
            $this->SetFont('DejaVu', 'B', 12);
            $this->SetTextColor($this->colorGrisOscuroTexto[0], $this->colorGrisOscuroTexto[1], $this->colorGrisOscuroTexto[2]);
            $this->Cell($this->pageWidthUtil, 6, ($empresa['nombre_empresa'] ?? 'N/A'), 0, 1, 'C');

            $this->SetFont('DejaVu', '', 9);
            $this->SetTextColor($this->colorGrisMedioTexto[0], $this->colorGrisMedioTexto[1], $this->colorGrisMedioTexto[2]);

            $this->Cell($this->pageWidthUtil, 6, ($empresa['RIF_empresa'] ?? 'N/A'), 0, 1, 'C');
            $this->Cell($this->pageWidthUtil, 6, ($empresa['nombre_representante'] ?? 'N/A') . " " . ($empresa['apellido_representante'] ?? 'N/A'), 0, 1, 'C');
            $this->Cell($this->pageWidthUtil, 6, ($empresa['telefono_representante'] ?? 'N/A'), 0, 1, 'C');
            $this->MultiCell($this->pageWidthUtil, 6, ($empresa['direccion_empresa'] ?? 'N/A'), 0, 'C');

            $this->Ln(2);

            $this->SetFont('DejaVu', '', 10);
            $this->SetTextColor($this->colorGrisOscuroTexto[0], $this->colorGrisOscuroTexto[1], $this->colorGrisOscuroTexto[2]);
            $this->Cell($this->pageWidthUtil, 6, "Datos del Empleado:", 0, 1, 'L');
            $this->SetFont('DejaVu', '', 9);
            $this->SetTextColor($this->colorGrisMedioTexto[0], $this->colorGrisMedioTexto[1], $this->colorGrisMedioTexto[2]);
            $this->Cell($this->pageWidthUtil, 6, "CI: " . $empleado_data['cedula_emple'], 0, 1, 'L');
            $this->Cell($this->pageWidthUtil, 6, "Nombre: " . $empleado_data['nombre_emp'] . " " . $empleado_data['apellido_emp'], 0, 1, 'L');
            $this->Cell($this->pageWidthUtil, 6, "Cargo: " . $empleado_data['nom_cargo'], 0, 1, 'L');
            if (!empty($this->nomina_data_dinamica['fecha_nom'])) {
                $this->Cell($this->pageWidthUtil, 6, "Día del Pago: " . date("d/m/Y", strtotime($this->nomina_data_dinamica['fecha_nom'])), 0, 1, 'L');

                if (isset($this->nomina_data_dinamica['tasa_dolar']) && $this->nomina_data_dinamica['tasa_dolar'] > 0) {
                    $tasa_dolar_text = "Tasa del Dólar (USD): " . number_format($this->nomina_data_dinamica['tasa_dolar'], 4, ',', '.') . " (Fecha de Aplicación de Tasa: " . date("d/m/Y", strtotime($this->nomina_data_dinamica['fecha_dolar'])) . ")";
                    $this->Cell($this->pageWidthUtil, 6, $tasa_dolar_text, 0, 1, 'L');
                } else {
                    $this->Cell($this->pageWidthUtil, 6, "Tasa del Dólar (USD): No disponible", 0, 1, 'L');
                }
            }
            $this->Ln(1);

            if (!$this->es_recibo_individual_dinamico) {
                $this->SetFont('DejaVu', '', 9);
                $this->SetTextColor($this->colorGrisMedioTexto[0], $this->colorGrisMedioTexto[1], $this->colorGrisMedioTexto[2]);
                $this->MultiCell($this->pageWidthUtil, 5, $this->filtros_aplicados_texto_dinamico, 0, 'L');
                $this->Ln(2);
            }
        } else {
            $this->Ln(5);
        }

        $this->SetDrawColor($this->colorGrisMedioTexto[0], $this->colorGrisMedioTexto[1], $this->colorGrisMedioTexto[2]);
        $current_y = $this->GetY();
        $this->Line($this->lMargin, $current_y, $this->lMargin + $this->pageWidthUtil, $current_y);
        $this->Ln(2);
        $this->SetTextColor(0,0,0);
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('DejaVu','',8);
        $this->SetTextColor($this->colorGrisMedioTexto[0], $this->colorGrisMedioTexto[1], $this->colorGrisMedioTexto[2]);
        $this->Cell(0,10, 'Página ' . $this->PageNo() . '/{nb}' ,0,0,'C');
    }

    function generarTablaDetalles($nomina) {
        $this->SetFont('DejaVu', '', 9);
        $this->SetFillColor($this->colorAzulClaroFondo[0], $this->colorAzulClaroFondo[1], $this->colorAzulClaroFondo[2]);
        $this->SetTextColor($this->colorAzulTextoHeader[0], $this->colorAzulTextoHeader[1], $this->colorAzulTextoHeader[2]);

        $this->Cell(60, 7, 'Concepto', 0, 0, 'L', true);
        $this->Cell(30, 7, 'Asignación', 0, 0, 'R', true);
        $this->Cell(40, 7, 'Monto Deducción', 0, 0, 'R', true);
        $this->Cell(30, 7, 'Total (USD)', 0, 1, 'R', true);
        $this->SetTextColor(0,0,0);
        $this->SetFont('DejaVu', '', 9);

        $this->Cell(60, 6, 'Sueldo Base', 0, 0, 'L');
        $this->Cell(30, 6, number_format($nomina['pago_neto'] ?? 0, 2, ',', '.'), 0, 0, 'R');
        $this->Cell(40, 6, '', 0, 0, 'R');
        $this->Cell(30, 6, number_format($nomina['pago_neto'] ?? 0, 2, ',', '.'), 0, 1, 'R');

        $horas_extra_val = $nomina['horas_extra'] ?? 0;
        $horas_extra_display = ($horas_extra_val > 0) ? number_format($horas_extra_val, 2, ',', '.') : '';
        $this->Cell(60, 6, 'Horas Extras', 0, 0, 'L');
        $this->Cell(30, 6, $horas_extra_display, 0, 0, 'R');
        $this->Cell(40, 6, '', 0, 0, 'R');
        $this->Cell(30, 6, $horas_extra_display, 0, 1, 'R');

        if (isset($nomina['dias_feriados']) && $nomina['dias_feriados'] > 0) {
            $this->Cell(60, 6, 'Días Feriados', 0, 0, 'L');
            $this->Cell(30, 6, number_format($nomina['dias_feriados'] ?? 0, 2, ',', '.'), 0, 0, 'R');
            $this->Cell(40, 6, '', 0, 0, 'R');
            $this->Cell(30, 6, number_format($nomina['dias_feriados'] ?? 0, 2, ',', '.'), 0, 1, 'R');
        }

        if (isset($nomina['bono_ali']) && $nomina['bono_ali'] > 0) {
            $this->Cell(60, 6, 'Bono Alimentación', 0, 0, 'L');
            $this->Cell(30, 6, number_format($nomina['bono_ali'] ?? 0, 2, ',', '.'), 0, 0, 'R');
            $this->Cell(40, 6, '', 0, 0, 'R');
            $this->Cell(30, 6, number_format($nomina['bono_ali'] ?? 0, 2, ',', '.'), 0, 1, 'R');
        }

        if (isset($nomina['bono_produc']) && $nomina['bono_produc'] > 0) {
            $this->Cell(60, 6, 'Bono Productividad', 0, 0, 'L');
            $this->Cell(30, 6, number_format($nomina['bono_produc'] ?? 0, 2, ',', '.'), 0, 0, 'R');
            $this->Cell(40, 6, '', 0, 0, 'R');
            $this->Cell(30, 6, number_format($nomina['bono_produc'] ?? 0, 2, ',', '.'), 0, 1, 'R');
        }

        $sso_total_val = $nomina['SSO_total'] ?? 0;
        if ($this->sso_valor_deduccion !== null && is_numeric($this->sso_valor_deduccion)) {
            $this->Cell(60, 6, 'SSO', 0, 0, 'L');
            $this->Cell(30, 6, number_format((float)$this->sso_valor_deduccion, 2, ',', '.') . '%', 0, 0, 'R');
            $this->Cell(40, 6, number_format($sso_total_val, 4, ',', '.'), 0, 0, 'R');
            $this->Cell(30, 6, '', 0, 1, 'R');
        }

        $faov_total_val = $nomina['FAOV_total'] ?? 0;
        if ($this->faov_valor_deduccion !== null && is_numeric($this->faov_valor_deduccion)) {
            $this->Cell(60, 6, 'FAOV', 0, 0, 'L');
            $this->Cell(30, 6, number_format((float)$this->faov_valor_deduccion, 2, ',', '.') . '%', 0, 0, 'R');
            $this->Cell(40, 6, number_format($faov_total_val, 4, ',', '.'), 0, 0, 'R');
            $this->Cell(30, 6, '', 0, 1, 'R');
        }

        $this->SetDrawColor($this->colorGrisClaroLinea[0], $this->colorGrisClaroLinea[1], $this->colorGrisClaroLinea[2]);
        $this->Line($this->GetX(), $this->GetY(), $this->GetX() + $this->pageWidthUtil, $this->GetY());
        $this->Ln(1);

        $this->SetFont('DejaVu', 'B', 10);
        $this->SetTextColor($this->colorGrisOscuroTexto[0], $this->colorGrisOscuroTexto[1], $this->colorGrisOscuroTexto[2]);
        $this->Cell(60, 7, 'Total Deducciones', 0, 0, 'L');
        $this->Cell(30, 7, '', 0, 0, 'R');
        $this->Cell(40, 7, number_format($nomina['total_deduc'] ?? 0, 4, ',', '.'), 0, 0, 'R');
        $this->Cell(30, 7, '', 0, 1, 'R');
        $this->SetFont('DejaVu', '', 9);
        $this->SetTextColor(0,0,0);

        $this->Ln(3);

        $this->SetDrawColor($this->colorGrisMedioTexto[0], $this->colorGrisMedioTexto[1], $this->colorGrisMedioTexto[2]);
        $this->Line($this->GetX(), $this->GetY(), $this->GetX() + $this->pageWidthUtil, $this->GetY());
        $this->Ln(1);

        $total_asignaciones_calculado = ((float)($nomina['pago_neto'] ?? 0)) + ((float)($nomina['horas_extra'] ?? 0)) + ((float)($nomina['dias_feriados'] ?? 0)) + ((float)($nomina['bono_ali'] ?? 0)) + ((float)($nomina['bono_produc'] ?? 0));
        $total_deducciones_calculado = ((float)($nomina['SSO_total'] ?? 0)) + ((float)($nomina['FAOV_total'] ?? 0));
        $sueldo_neto_calculado_usd = $total_asignaciones_calculado - $total_deducciones_calculado;

        $pago_total_usd = isset($nomina['pago_total']) ? (float)$nomina['pago_total'] : $sueldo_neto_calculado_usd;

        $this->SetFont('DejaVu', 'B', 11);
        $this->SetTextColor($this->colorAzulTextoHeader[0], $this->colorAzulTextoHeader[1], $this->colorAzulTextoHeader[2]);
        $this->Cell(60, 8, 'PAGO TOTAL (USD)', 0, 0, 'L');
        $this->Cell(30, 8, '', 0, 0, 'R');
        $this->Cell(40, 8, '', 0, 0, 'R');
        $this->Cell(30, 8, number_format($pago_total_usd, 2, ',', '.'), 0, 1, 'R');

        if (isset($nomina['tasa_dolar']) && $nomina['tasa_dolar'] > 0 && $pago_total_usd > 0) {
            $pago_total_bs = $pago_total_usd * ((float)$nomina['tasa_dolar']);
            $this->SetFont('DejaVu', 'B', 11);
            $this->SetTextColor($this->colorAzulTextoHeader[0], $this->colorAzulTextoHeader[1], $this->colorAzulTextoHeader[2]);
            $this->Cell(60, 8, 'PAGO TOTAL (Bs.)', 0, 0, 'L');
            $this->Cell(30, 8, '', 0, 0, 'R');
            $this->Cell(40, 8, '', 0, 0, 'R');
            $this->Cell(30, 8, number_format($pago_total_bs, 2, ',', '.'), 0, 1, 'R');
        }

        $this->SetFont('DejaVu', '', 9);
        $this->SetTextColor(0,0,0);

        $this->Ln(15);
        
        $current_x = $this->GetX();
        $y_start_signatures = $this->GetY();

        $column_width = $this->pageWidthUtil / 2;
        $line_length_signatures = $column_width * 0.8;

        $y_after_first_line = $this->GetY();

        $this->SetFont('DejaVu', 'B', 9);
        $this->SetX($current_x);
        $this->Cell($column_width, 5, 'Recibe Conforme:', 0, 0, 'L');

        $this->SetX($current_x + $column_width);
        $this->Cell($column_width, 5, 'Firma del Representante de la empresa:', 0, 1, 'L');

        $this->Ln(5);

        $this->SetX($current_x);
        $y_line_empleado = $this->GetY();
        $this->Line($current_x + (($column_width - $line_length_signatures) / 2), $y_line_empleado, $current_x + (($column_width - $line_length_signatures) / 2) + $line_length_signatures, $y_line_empleado);
        $this->SetFont('DejaVu', '', 8);
        $this->Cell($column_width, 5, 'Firma del Empleado', 0, 0, 'C');

        $this->SetX($current_x + $column_width);
        $y_line_rep = $this->GetY();
        $this->Line($current_x + $column_width + (($column_width - $line_length_signatures) / 2), $y_line_rep, $current_x + $column_width + (($column_width - $line_length_signatures) / 2) + $line_length_signatures, $y_line_rep);
        $this->Cell($column_width, 5, 'Firma', 0, 1, 'C');

        $this->Ln(5);
    }
}

$pdf = new PDF('P', 'mm', 'Letter');
$pdf->titulo_pdf_dinamico = $titulo_pdf;
$pdf->es_recibo_individual_dinamico = $es_recibo_individual;
$pdf->filtros_aplicados_texto_dinamico = $filtros_aplicados_texto;
$pdf->nomina_data_dinamica = $nomina_data;

$pdf->sso_valor_deduccion = $sso_valor_deduccion_db;
$pdf->faov_valor_deduccion = $faov_valor_deduccion_db;

$pdf->AliasNbPages();
$pdf->AddPage();

if (!empty($nomina_data)) {
    $pdf->generarTablaDetalles($nomina_data);
} else {
    $pdf->SetFont('DejaVu', '', 10);
    $pdf->Cell(0, 10, 'No se encontraron datos de nómina para este empleado.', 0, 1, 'C');
}

$nombre_empleado_archivo = '';
if (isset($empleado_data['nombre_emp']) && isset($empleado_data['apellido_emp'])) {
    $nombre_empleado_archivo = str_replace(' ', '_', $empleado_data['nombre_emp'] . '_' . $empleado_data['apellido_emp']);
    $nombre_empleado_archivo = preg_replace('/[^A-Za-z0-9_.-]/', '', $nombre_empleado_archivo);
}

$fecha_recibo_archivo = '';
if (isset($nomina_data['fecha_nom']) && !empty($nomina_data['fecha_nom'])) {
    $fecha_recibo_archivo = date("Y-m-d", strtotime($nomina_data['fecha_nom']));
} else {
    $fecha_recibo_archivo = date("Y-m-d");
}

$nombre_archivo = "Recibo_" . $nombre_empleado_archivo . "_Fecha_" . $fecha_recibo_archivo . ".pdf";

if (empty($nombre_archivo)) {
    $nombre_archivo = "recibo_pago.pdf";
}

$pdf->Output($nombre_archivo, 'I');

ob_end_flush();
?>