<?php
require_once 'reports_template.php'; 
require_once '../../_con.php'; 
require_once '../includes/logic/logic_g.php'; 

$bd = new logica_bd($conexion);

if (empty($_POST['_Categoria'])) {
    die("Filtro de Categoría no especificado.");
}

$id_categoria = (int)filter_var($_POST['_Categoria'], FILTER_SANITIZE_NUMBER_INT);
$sql_trapiche = "SELECT * FROM trapiche LIMIT 1";
$trapiche_data = $bd->obtenerRegistro($sql_trapiche, '', []);
$datos_trapiche = $trapiche_data[0] ?? [];
$sql_entidad = "SELECT nombre_categoria FROM categoria WHERE id_categoria = ?";
$entidad_data = $bd->obtenerRegistro($sql_entidad, 'i', [$id_categoria]);
$nombre_entidad = $entidad_data[0]['nombre_categoria'] ?? 'Categoría Desconocida';

$sql_obras = "
SELECT 
        o.cod_obra, 
        o.titulo_obra, 
        o.f_ingreso_obra,
        o.procedencia_obra,
        o.estado_obra
    FROM obra o
    WHERE o.categoria_id = ?
    ORDER BY o.titulo_obra ASC
";
$obras = $bd->obtenerRegistro($sql_obras, 'i', [$id_categoria]);

$pdf = new PDF();
$pdf->setTrapicheData($datos_trapiche);
$pdf->setReportTitle("Reporte de Obras por Categoría: {$nombre_entidad} (ID: {$id_categoria})"); 
$pdf->AliasNbPages();
$pdf->AddPage('L'); 

$header = array('N°', 'Código', 'Título de la Obra', 'F.Ingreso', 'Procedencia', 'E.Conservación'); 
$pdf->SetFont('Arial','',10);
$w = array(10, 40, 90, 30, 50, 50); 
$pdf->TablaHeader($header, $w); 

$contador = 1; 
$fill = false;

if (!empty($obras)) {
    foreach($obras as $obra) {
        $pdf->SetFont('Arial','',10);
        $pdf->SetFillColor($fill ? 240 : 255);

        $pdf->Cell($w[0], 6, $contador++, 'LR', 0, 'C', $fill); 
        $pdf->Cell($w[1], 6, utf8_decode($obra['cod_obra']), 'LR', 0, 'C', $fill);
        $pdf->Cell($w[2], 6, utf8_decode($obra['titulo_obra']), 'LR', 0, 'L', $fill);
        
        $f_ingreso = $obra['f_ingreso_obra'] ? date('d/m/Y', strtotime($obra['f_ingreso_obra'])) : 'N/D';
        $pdf->Cell($w[3], 6, $f_ingreso, 'LR', 0, 'C', $fill);
        
        $pdf->Cell($w[4], 6, utf8_decode($obra['procedencia_obra']), 'LR', 0, 'L', $fill);
        $pdf->Cell($w[5], 6, utf8_decode($obra['estado_obra']), 'LR', 0, 'C', $fill);
        
        $pdf->Ln();
        $fill = !$fill;
    }
} else {
    $pdf->SetFillColor($fill ? 240 : 255);
    $pdf->Cell(array_sum($w), 6, utf8_decode("No se encontraron obras para esta categoría."), 1, 1, 'C', true);
}
$pdf->Cell(array_sum($w), 0, '', 'T'); 
$pdf->Output('I', "Reporte_Obras_Categoria_{$id_categoria}.pdf");
?>
