<?php
require_once 'reports_template.php'; 
require_once '../../_con.php'; 
require_once '../includes/logic/logic_g.php'; 

$bd = new logica_bd($conexion);

if (empty($_POST['_Tecnica'])) {
    die("Filtro de Técnica no especificado.");
}

$id_tecnica = (int)filter_var($_POST['_Tecnica'], FILTER_SANITIZE_NUMBER_INT);
$sql_trapiche = "SELECT * FROM trapiche LIMIT 1";
$trapiche_data = $bd->obtenerRegistro($sql_trapiche, '', []);
$datos_trapiche = $trapiche_data[0] ?? [];

$sql_entidad = "SELECT nombre_tecnica FROM tecnica WHERE id_tecnica = ?";
$entidad_data = $bd->obtenerRegistro($sql_entidad, 'i', [$id_tecnica]);
$nombre_entidad = $entidad_data[0]['nombre_tecnica'] ?? 'Técnica Desconocida';

$sql_obras = "
SELECT 
o.cod_obra, 
        o.titulo_obra, 
        o.f_ingreso_obra,
        o.procedencia_obra,
        o.estado_obra
    FROM obra o
    WHERE o.tecnica_id = ?
    ORDER BY o.titulo_obra ASC
";
$obras = $bd->obtenerRegistro($sql_obras, 'i', [$id_tecnica]);

$pdf = new PDF();
$pdf->setTrapicheData($datos_trapiche);
$pdf->setReportTitle("Reporte de Obras por Técnica: {$nombre_entidad} (ID: {$id_tecnica})"); 
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
    $pdf->Cell(array_sum($w), 6, utf8_decode("No se encontraron obras para esta técnica."), 1, 1, 'C', true);
}
$pdf->Cell(array_sum($w), 0, '', 'T'); 
$pdf->Output('I', "Reporte_Obras_Tecnica_{$id_tecnica}.pdf");
?>
        