<?php
require_once 'reports_template.php'; 
require_once '../../_con.php'; 
require_once '../includes/logic/logic_g.php'; 
$bd = new logica_bd($conexion);
if (empty($_POST['_Autor'])) {
    die("Filtro de Autor no especificado.");}
$ci_autor = filter_var($_POST['_Autor'], FILTER_SANITIZE_STRING);
$sql_trapiche = "SELECT * FROM trapiche LIMIT 1";
$trapiche_data = $bd->obtenerRegistro($sql_trapiche, '', []);
$datos_trapiche = $trapiche_data[0] ?? [];
$sql_autor = "SELECT nombres_autor, apellidos_autor FROM autor WHERE ci_autor = ?";
$autor_data = $bd->obtenerRegistro($sql_autor, 's', [$ci_autor]);
$nombre_completo_autor = 'Autor Desconocido';
if (!empty($autor_data)) {
    $nombres = $autor_data[0]['nombres_autor'];
    $apellidos = $autor_data[0]['apellidos_autor'];
    $nombre_completo_autor = trim("{$nombres} {$apellidos}");
}
$sql_obras = "
    SELECT 
        o.cod_obra, 
        o.titulo_obra, 
        ao.f_elaboracion_obra,
        o.procedencia_obra,
        o.estado_obra
    FROM obra o
    INNER JOIN autor_obra ao ON o.cod_obra = ao.obra_cod
    WHERE ao.autor_ci = ?
    ORDER BY o.titulo_obra ASC
";
$obras = $bd->obtenerRegistro($sql_obras, 's', [$ci_autor]);
$pdf = new PDF();
$pdf->setTrapicheData($datos_trapiche);
$pdf->setReportTitle("Reporte de Obras del Autor: {$nombre_completo_autor} (CI: {$ci_autor})"); 
$pdf->AliasNbPages();
$pdf->AddPage('L'); 
$header = array('N°', 'Código', 'Título de la Obra', 'F.Elaboración', 'Procedencia', 'E.Conservación'); 
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
        
        $pdf->Cell($w[1], 6, $obra['cod_obra'], 'LR', 0, 'C', $fill);
        
        $pdf->Cell($w[2], 6, utf8_decode($obra['titulo_obra']), 'LR', 0, 'L', $fill);
        
        $f_elaboracion = $obra['f_elaboracion_obra'] ? date('d/m/Y', strtotime($obra['f_elaboracion_obra'])) : 'N/D';
        $pdf->Cell($w[3], 6, $f_elaboracion, 'LR', 0, 'C', $fill);
        
        $pdf->Cell($w[4], 6, utf8_decode($obra['procedencia_obra']), 'LR', 0, 'L', $fill);
        
        $pdf->Cell($w[5], 6, utf8_decode($obra['estado_obra']), 'LR', 0, 'C', $fill);
        
        $pdf->Ln();
        $fill = !$fill;
    }
} else {
    $pdf->SetFillColor($fill ? 240 : 255);
    $pdf->Cell(array_sum($w), 6, utf8_decode("No se encontraron obras asociadas a este autor."), 1, 1, 'C', true);
}
$pdf->Cell(array_sum($w), 0, '', 'T'); 
$pdf->Output('I', "Reporte_Obras_Autor_{$ci_autor}.pdf");
?>