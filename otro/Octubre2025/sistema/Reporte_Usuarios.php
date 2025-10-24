<?
	include "../conexion.php";
	include "Plantilla_Reporte_Usuarios.php";

	$Tipo_Usuario=$_POST['Tipo_Usuario'];
	$sql_query=mysqli_query($conexion, "SELECT u.Cedula_Usuario,u.Nombres_Usuario,u.Apellidos_Usuario,u.Correo_Usuario, t.Descripcion_Tipo_Usuario FROM Usuario as u inner join Tipo_Usuario as t on u.Codigo_Tipo_Usuario = t.Id_Tipo_Usuario where t.Id_Tipo_Usuario=$Tipo_Usuario");
/* se instancia un objeto de la clase PDF, la cual se encuentra en el archivo fpdf.php que se encuentra dentro de la carpeta fpdf
    se pasa como parametros "P" o "L" para indicar la orientación vertical o horizontal, luego la medida en mm y por ultimo el tipo
    de papel si es carta, oficio, A4, entre otros*/
	$pdf=new PDF("P","mm","letter");
	$pdf->AliasNbPages();
	$pdf->SetMargins(10,10,10);
	$pdf->AddPage();
	$pdf->SetFont("Arial","B",9);
	$pdf->Cell(20,5,"Cedula",1,0,"C");
	$pdf->Cell(40,5,"Nombres",1,0,"C");
	$pdf->Cell(40,5,"Apellidos",1,0,"C");
	$pdf->Cell(60,5,"Correo",1,0,"C");
	$pdf->Cell(30,5,"Tipo Usuario",1,1,"C");
	$pdf->SetFont("Arial","",9);
	while ($fila=$sql_query->fetch_assoc()) {
		$pdf->Cell(20,5,$fila['Cedula_Usuario'],1,0,"C");
		$pdf->Cell(40,5,$fila['Nombres_Usuario'],1,0,"C");
		$pdf->Cell(40,5,$fila['Apellidos_Usuario'],1,0,"C");
		$pdf->Cell(60,5,$fila['Correo_Usuario'],1,0,"C");
		$pdf->Cell(30,5,$fila['Descripcion_Tipo_Usuario'],1,1,"C");
		# code...
	}
	$pdf->Output();
?>
