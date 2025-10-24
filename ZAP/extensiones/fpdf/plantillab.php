<?php
require('fpdf.php');
require_once '../../modelos/ventas.modelo.php';
require_once '../../controladores/ventas.controlador.php';

require_once "../../controladores/configuracion.controlador.php";
require_once "../../modelos/configuracion.modelo.php";


require_once '../../modelos/clientes.modelo.php';
require_once '../../controladores/clientes.controlador.php';

require_once '../../modelos/empleados.modelo.php';
require_once '../../controladores/empleados.controlador.php';
date_default_timezone_set('America/El_Salvador');

class PDF extends FPDF
{
// Cabecera de página
//Numeros de paginas
//SetTextColor(255,255,255);es RGB extraer colores con GIMP
//SetFillColor()
//SetDrawColor()
//Line(derecha-izquierda, arriba-abajo,ancho,arriba-abajo)
//Color line setDrawColor(61,174,233)
//GetX() || GetY() posiciones en cm
//Grosor SetLineWidth(1)
// SetFont(tipo{COURIER, HELVETICA,ARIAL,TIMES,SYMBOL, ZAPDINGBATS}, estilo[normal,B,I ,A], tamaño)
// Cell(ancho , alto,texto,borde(0/1),salto(0/1),alineacion(L,C,R),rellenar(0/1)
//AddPage(orientacion[PORTRAIT, LANDSCAPE], tamño[A3.A4.A5.LETTER,LEGAL],rotacion)
//Image(ruta, poscisionx,pocisiony,alto,ancho,tipo,link)
//SetMargins(10,30,20,20) luego de addpage
  
function Header()
{
$this->Image('img/logo.png',0,1,62);


$this->SetY(5);
$this->SetX(70);
$this->SetFont('Arial','B',15);

$empresa = ControladorConfiguracion::ctrMostrarConfigracion(null,null);
$nombreEmpresa = $empresa["nombre"];
$rifEmpresa = $empresa["rif"];  
$localizacionEmpresa = $empresa["direccion"];
$telefonoEmpresa = $empresa["telefono"];

$this->SetTextColor(0, 0, 0 );
$this->Cell(50, 25, $nombreEmpresa,0,1);

$this->SetY(15);
$this->SetX(70);
$this->SetFont('Arial','B',15);
$this->Cell(80, 20, "Rif:" .$rifEmpresa,0,1);
$this->SetY(21);
$this->SetX(70);
$this->SetFont('Arial','B',15);
$this->Cell(80, 20, "Direccion:".$localizacionEmpresa,0,1);
$this->SetY(28);
$this->SetX(70);
$this->SetFont('Arial','B',15);
$this->Cell(80, 20, "Telefono:".$telefonoEmpresa,0,1);
$this->SetY(55);
$this->SetX(70);
$this->SetFont('Arial','',22);
$this->Cell(40, 8,"Reporte Mensual de Ventas");
$this->SetTextColor(30,10,32);
$this->Ln(30);




}

function Footer()
{
     $this->SetFont('helvetica', 'B', 12);
        $this->SetY(-15);
        $this->Cell(95,5,utf8_decode('Página ').$this->PageNo().' / {nb}',0,0,'L');
        $this->Cell(95,5,date('d/m/Y | g:i:a') ,00,1,'R');
        $this->Line(10,287,200,287);
        //$this->Cell(0,5,utf8_decode("Kodo Sensei © Todos los derechos reservados."),0,0,"C");
        
}


}
$totalCompleto = 0;


$pdf = new PDF();

$pdf->AliasNbPages();
$pdf->AddPage();

$pdf->SetAutoPageBreak(true, 20);

$pdf->SetTopMargin(500);
$pdf->SetLeftMargin(10);
$pdf->SetRightMargin(10);
$pdf->SetX(15);
$pdf->SetFillColor(070, 130, 180);
$pdf->SetDrawColor(255, 255, 255);
// Cell(ancho , alto,texto,borde(0/1),salto(0/1),alineacion(L,C,R),rellenar(0/1)

$pdf->SetFont('Arial','B',12);
$pdf->Cell(12, 12, utf8_decode('N°'),1,0,'C',1);
$pdf->Cell(27, 12, utf8_decode('Factura'),1,0,'C',1);
$pdf->Cell(45, 12, utf8_decode('Cliente'),1,0,'C',1);
$pdf->Cell(45, 12, utf8_decode('Vendedor'),1,0,'C',1);
$pdf->Cell(25, 12, utf8_decode('Fecha'),1,0,'C',1);
$pdf->Cell(30, 12, utf8_decode('Total'),1,1,'C',1);
$ventas = ControladorVentas::ctrMostrarVentas(null,null,1);
$pdf->SetFont('Arial','',10);
for ($i = 0; $i < count($ventas); $i++) {

    $factura = $ventas[$i]["factura"];
    $cliente = ControladorClientes::ctrMostrarClientes("cedula",$ventas[$i]["cliente"]);
    $cliente = $cliente["nombre"]." ".$cliente["apellido"];
    $vendedor = ControladorEmpleados::ctrMostrarEmpleados("cedula",$ventas[$i]["vendedor"]);
    $vendedor = $vendedor["nombre"]." ".$vendedor["apellido"];
    $fecha = (new DateTime($ventas[$i]["fecha"]))->format("d-m-Y");
    $total = $ventas[$i]["total"];

    $totalCompleto += $total;

  $pdf->SetX(15);
$pdf->SetFillColor(255,255,255);
$pdf->SetDrawColor(65, 61, 61);
$pdf->Cell(12, 8, utf8_decode($i+ 1),'B',0,'C',1);
$pdf->Cell(27, 8, utf8_decode($factura),'B',0,'C',1);
$pdf->Cell(45, 8, utf8_decode($cliente),'B',0,'C',1);
$pdf->Cell(45, 8, utf8_decode($vendedor),'B',0,'C',1);
$pdf->Cell(25, 8, utf8_decode($fecha),'B',0,'C',1);
$pdf->Cell(30, 8, number_format($total, 2, ',', '.'),'B',1,'C',1);
$pdf->Ln(0.5);
}
$pdf->Ln(5);
$pdf->Cell(160, 8, utf8_decode("Total   "),0,0,'R');
$pdf->Cell(30,8,number_format("$totalCompleto", 2, ',', '.'),1,0,'C');


$pdf->Output();
?>