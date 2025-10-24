<?php
include"fpdf186/fpdf.php";

class PDF extends FPDF
{
// Page header
function Header()
{
    // Logo
    $this->Image('img/logo.png',10,5,13);
    // Arial bold 15
    $this->SetFont("Arial","B",12);
    // título
    $this->cell(25);
    $this->Cell(140,5,"Reporte de Usuarios",0,0,"C");
    //Fecha
    $this->SetFont("Arial","",10);
    $this->Cell(25,5,"Fecha: ".date("d/m/y"),0,1,"C");
    // salto de línea
    $this->Ln(10);
}

// Pie de página
function Footer()
{
    // Posición a 1,5 cm del Final
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('Arial','I',8);
    // Número de Página
    $this->Cell(0,10,'Pagina '.$this->PageNo().'/{nb}',0,0,'C');
}
}
