<?php
require_once 'reports_template.php'; 
require_once '../../_con.php'; 
require_once '../includes/logic/logic_g.php'; 

$bd = new logica_bd($conexion);

if (empty($_POST['_UsuarioRegColeccion'])) {
    header("Location: ../list_co.php");
    die();
}

$ci_registrante = filter_var($_POST['_UsuarioRegColeccion'], FILTER_SANITIZE_STRING);

$sql_trapiche = "SELECT * FROM trapiche LIMIT 1";
$trapiche_data = $bd->obtenerRegistro($sql_trapiche, '', []);
$datos_trapiche = $trapiche_data[0] ?? [];

$sql_reg = "SELECT nombres_usuario, apellidos_usuario FROM usuario WHERE ci_usuario = ?";
$reg_data = $bd->obtenerRegistro($sql_reg, 's', [$ci_registrante]);
$nombre_registrante = 'Usuario Desconocido';
if (!empty($reg_data)) {
    $nombres = $reg_data[0]['nombres_usuario'];
    $apellidos = $reg_data[0]['apellidos_usuario'];
    $nombre_registrante = trim("{$nombres} {$apellidos}");
}

$sql_colecciones = "
    SELECT 
        c.cod_coleccion, 
        c.titulo_coleccion, 
        c.f_creacion_coleccion,
        c.naturaleza_coleccion,
        c.estado_coleccion
    FROM coleccion c
    WHERE c.usuario_ci = ? AND c.status_coleccion = 1
    ORDER BY c.titulo_coleccion ASC
";
$colecciones = $bd->obtenerRegistro($sql_colecciones, 's', [$ci_registrante]);

$pdf = new PDF();
$pdf->setTrapicheData($datos_trapiche);
$pdf->setReportTitle("Reporte de Colecciones Registradas por: {$nombre_registrante} (CI: {$ci_registrante})"); 
$pdf->AliasNbPages();
$pdf->AddPage('L'); 

$header = array('N°', 'Código', 'Título', 'F.Creación', 'Naturaleza', 'Estado'); 
$pdf->SetFont('Arial','',10);
$w = array(10, 40, 100, 40, 40, 40); 
$pdf->TablaHeader($header, $w); 

$contador = 1; 
$fill = false;

if (!empty($colecciones)) {
    foreach($colecciones as $coleccion) {
        $pdf->SetFont('Arial','',10);
        $pdf->SetFillColor($fill ? 240 : 255);

        $pdf->Cell($w[0], 6, $contador++, 'LR', 0, 'C', $fill); 
        $pdf->Cell($w[1], 6, utf8_decode($coleccion['cod_coleccion']), 'LR', 0, 'C', $fill);
        $pdf->Cell($w[2], 6, utf8_decode($coleccion['titulo_coleccion']), 'LR', 0, 'L', $fill);
        
        $f_creacion = $coleccion['f_creacion_coleccion'] ? date('d/m/Y', strtotime($coleccion['f_creacion_coleccion'])) : 'N/D';
        $pdf->Cell($w[3], 6, $f_creacion, 'LR', 0, 'C', $fill);
        
        $pdf->Cell($w[4], 6, utf8_decode($coleccion['naturaleza_coleccion']), 'LR', 0, 'C', $fill);
        $pdf->Cell($w[5], 6, utf8_decode($coleccion['estado_coleccion']), 'LR', 0, 'C', $fill);
        
        $pdf->Ln();
        $fill = !$fill;
    }
} else {
    $pdf->SetFillColor($fill ? 240 : 255);
    $pdf->Cell(array_sum($w), 6, utf8_decode("No se encontraron colecciones registradas por este CI."), 1, 1, 'C', true);
}
$pdf->Cell(array_sum($w), 0, '', 'T'); 
$pdf->Output('I', "Reporte_Colecciones_Reg_{$ci_registrante}.pdf");
?>