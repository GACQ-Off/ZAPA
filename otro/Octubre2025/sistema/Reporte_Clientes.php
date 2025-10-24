<?
	include "../conexion.php";
	include "Plantilla_Reporte.php";

	$sql_query=mysqli_query($conexion, "SELECT Cedula_Cliente,Nombres_Cliente,Apellidos_Cliente,Telefono_Cliente,Correo_Cliente FROM Cliente");
/* se instancia un objeto de la clase PDF, la cual se encuentra en el archivo fpdf.php que se encuentra dentro de la carpeta fpdf186
    se pasa como parametros "P" o "L" para indicar la orientación vertical o horizontal, luego la medida en mm y por ultimo el tipo
    de papel si es carta, oficio, A4, entre otros*/
	$pdf=new PDF("P","mm","letter");
	$pdf->AliasNbPages();
	$pdf->SetMargins(10,10,10);
	$pdf->AddPage();
	$pdf->SetFont("Arial","B",9);
	$pdf->Cell(20,5,"Cedula",1,0,"C");
	$pdf->Cell(45,5,"Nombres",1,0,"C");
	$pdf->Cell(45,5,"Apellidos",1,0,"C");
	$pdf->Cell(25,5,"Telefono",1,0,"C");
	$pdf->Cell(60,5,"Correo",1,1,"C");
	$pdf->SetFont("Arial","",9);
	while ($fila=$sql_query->fetch_assoc()) {
		$pdf->Cell(20,5,$fila['Cedula_Cliente'],1,0,"C");
		$pdf->Cell(45,5,$fila['Nombres_Cliente'],1,0,"C");
		$pdf->Cell(45,5,utf8_decode($fila['Apellidos_Cliente']),1,0,"C");
		$pdf->Cell(25,5,$fila['Telefono_Cliente'],1,0,"C");
		$pdf->Cell(60,5,$fila['Correo_Cliente'],1,1,"C");
		# code...
	}
	$pdf->Output();
?>
