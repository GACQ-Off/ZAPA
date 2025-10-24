<?php
setlocale(LC_CTYPE, 'es_MX');
include "fpdf.php";

$symbol = "$";

$logo = "tulogo.png";
$company = "EVILNAPSIS SOFT";
$address = "";
$phone = "";
$rfc = "";

$sell = 1001;
$cliente = "John Doe";
//////////// ARRAY CON LOS PRODUCTOS , CANTIDADES Y PRECIOS
$products = array(
        array("code"=>1233, "product"=>"Kilo de Azucar", "qty"=>2,"price"=>28),
        array("code"=>1234, "product"=>"Kilo de Arroz", "qty"=>1,"price"=>17),
        array("code"=>1235, "product"=>"Lata de Atun", "qty"=>1,"price"=>20),
        array("code"=>1236, "product"=>"Huevos", "qty"=>10,"price"=>3),
);


$pdf = new FPDF($orientation='P');

$pdf->AddPage();
$pdf->SetFont('Arial','B',8);    



$plusforimage =0;
if($logo!=""){
        $src = "images/".$logo;
        if(file_exists($src)){
                $pdf->Image($src,12,2,45, 25);          
                $plusforimage=25;
        }
}

$pdf->SetFont('Arial','B',22);    //Letra Arial, negrita (Bold), tam. 20

/////////////////////////////////////// DATOS FIJOS
$pdf->setY(10);
$pdf->setX(110);
$pdf->Cell(5,0,"FACTURA");
$pdf->SetFont('Arial','',12);    //Letra Arial, negrita (Bold), tam. 20

$pdf->setY(20);
$pdf->setX(140);
$pdf->Cell(5,0,"FECHA: ");
$pdf->setY(17);
$pdf->setX(160);
$pdf->Cell(30,5,date("d/m/Y",strtotime(time())),1);
$pdf->setY(23);
$pdf->setX(126);
$pdf->Cell(5,5,'FOLIO # ');
$pdf->setY(23);
$pdf->setX(160);
$pdf->Cell(30,5,$sell,1);

$pdf->setY(30);
$pdf->setX(126);
$pdf->Cell(65,5,"FORMA DE PAGO",1);
$pdf->setY(35);
$pdf->setX(126);
$pdf->Cell(65,5,"VENTA",1);

$pdf->SetTextColor(255,255,255); // CAMBIO DE COLOR
$pdf->setFillColor(0,0,100);  // BG COLOR DEL ENCABEZADO
$pdf->setY(45);
$pdf->setX(12);
$pdf->Cell(85, 35, "", 1, 1, 'C');
$pdf->setY(45);
$pdf->setX(12);

$pdf->Cell(85,5,"PROVEEDOR",0,1,'C',1); //your cell
$pdf->setY(45);
$pdf->setX(106);

$pdf->Cell(85, 35, "", 1, 1, 'C');
$pdf->setY(45);
$pdf->setX(106);

$pdf->Cell(85,5,"CLIENTE",0,1,'C',1); //your cell




$pdf->SetTextColor(0,0,0);

//////////////////////////////////////// DATOS FIJOS
$textypos = 35+$plusforimage;
$pdf->setY(2);
$pdf->setX(12);
$pdf->SetFont('Arial','B',26);    //Letra Arial, negrita (Bold), tam. 20
$pdf->Cell(5,$textypos,strtoupper($company));
//$pdf->SetFont('DejaVu','',5);    //Letra Arial, negrita (Bold), tam. 20
$pdf->SetFont('Arial','',10);    //Letra Arial, negrita (Bold), tam. 20
$textypos+=18;

$pdf->setY(50);
$pdf->setX(15);
$pdf->Cell(5,5,"Nombre de la empresa SA de CV");

$textypos+=8;
$pdf->setY(55);
$pdf->setX(15);
$pdf->Cell(5,5,"Calle y Numero ");
$pdf->setY(60);
$pdf->setX(15);
$pdf->Cell(5,5,"Colonia Y CP");
$pdf->setY(65);
$pdf->setX(15);
$pdf->Cell(5,5,"Ciudad y Estado.");
$pdf->setY(70);
$pdf->setX(15);
$pdf->Cell(5,5,"Telefono: 123 456 7897");
//////////////////// DAOS DEL CLIENTE 
$cliente="";
$empresa="";
$area="";
$ciudad="";
$descripcion="";

$pdf->setY(50);
$pdf->setX(110);
$pdf->Cell(5,5,"Usuario: $cliente");

$textypos+=8;
$pdf->setY(55);
$pdf->setX(110);
$pdf->Cell(5,5,"Empresa: $empresa");
$pdf->setY(60);
$pdf->setX(110);
$pdf->Cell(5,5,"Area: $area");
$pdf->setY(65);
$pdf->setX(110);
$pdf->Cell(5,5,"Ciudad: $ciudad");

$pdf->SetTextColor(255,255,255); // CAMBIO DE COLOR
$pdf->setFillColor(0,0,0); 
$textypos+=16;
$pdf->setX(15);
$pdf->setY(90);
$pdf->setX(10);

////////////////////////////////////////////////////////

$pdf->setFillColor(0,0,100); // BG COLOR DEL ENCABEZADO
$pdf->Cell(20,5,"CANT.",1,1,1,'C');
$pdf->setY(90);
$pdf->setX(30);
$pdf->Cell(100,5,"PRODUCTO",1,1,1,'C');
$pdf->setY(90);
$pdf->setX(130);
$pdf->Cell(30,5,"P.U",1,1,1,'C');
$pdf->setY(90);
$pdf->setX(160);
$pdf->Cell(30,5,"TOTAL",1,1,1,'C');
$pdf->setY(90);
$pdf->setX(150);

$pdf->Ln();
$pdf->SetTextColor(0,0,0); // CAMBIO DE COLOR
$total =0;
$off = $textypos+8;
$pdf->setY(95);
$line = 87;
$ypos_static = 95;
foreach($products as $pro){
$line+=8;
$ypos_static +=5;
$pdf->setX(10);
$pdf->Cell(20,5,$pro["qty"],'LR');
$pdf->Cell(100,5,$pro["product"],'LR');
$pdf->Cell(30,5,"$ ".number_format($pro["price"],2,".",","),'LR',0,'R');
$pdf->Cell(30,5,"$ ".number_format($pro["price"]*$pro["qty"],2,".",","),'LR',0,'R');
$pdf->Ln();

//    ".."  ".number_format($op->q*$product->price_out,2,".",","));

$total += $pro["price"]*$pro["qty"];
$off+=8;
}
$pdf->setX(10);
    $pdf->Cell(180,0,'','T');
$line+=8;
$pdf->setY($line);
$textypos=$off;

//////////////////////////////////////////////
$ypos_static+=5;
$line+=5;
$pdf->setY($ypos_static);
$pdf->setX(150);
$pdf->Cell(5,6,"TOTAL: " );
$pdf->setY($ypos_static);
$pdf->setX(187);
$pdf->Cell(5,6,"$symbol ".number_format($total,2,".",","),0,0,"R");

//////////////////////////////////////////

$ypos_static+=10;
$pdf->SetTextColor(255,255,255); // CAMBIO DE COLOR
$pdf->setFillColor(0,0,100); // BG COLOR DEL ENCABEZADO
$pdf->setY($ypos_static);
$pdf->setX(12);
$pdf->Cell(130, 32, "", 1, 1, 'C');
$pdf->setY($ypos_static);
$pdf->setX(12);
$pdf->Cell(130,5,"TERMINOS Y CONDICIONES",0,1,'C',1); //your cell
$pdf->setY($ypos_static);
$pdf->SetTextColor(0,0,0); // CAMBIO DE COLOR

$ypos_static+=5;
$pdf->setY($ypos_static);
$pdf->setX(15);
$pdf->Cell(5,5,"Tiempo de Entrega acordado con el cliente");

$ypos_static+=5;
$pdf->setY($ypos_static);
$pdf->setX(15);
$pdf->Cell(5,5,"La vigencia de la presente cotizacion es por 15 dias.");
$ypos_static+=5;
$pdf->setY($ypos_static);
$pdf->setX(15);
$pdf->Cell(5,5,"Los costos son en moneda nacional");
$ypos_static+=5;
$pdf->setY($ypos_static);
$pdf->setX(15);
$pdf->Cell(5,5,"Los costos no incluyen IVA.");
$ypos_static+=5;
$pdf->setY($ypos_static);
$pdf->setX(15);
$pdf->Cell(5,5,"La informacion contenida en este documento es confidencial.");

//////////////////////////////////////////

$ypos_static+=10;
$pdf->SetTextColor(255,255,255);//  CAMBIO DE COLOR
$pdf->setFillColor(0,0,100); // BG COLOR DEL ENCABEZADO
$pdf->setY($ypos_static);
$pdf->setX(12);
$pdf->Cell(130, 22, "", 1, 1, 'C');
$pdf->setY($ypos_static);
$pdf->setX(12);
$pdf->Cell(130,5,"NOTAS",0,1,'C',1);
$pdf->setY($ypos_static);
$pdf->SetTextColor(0,0,0);  

$ypos_static+=5;
$pdf->setY($ypos_static);
$pdf->setX(15);
$pdf->Cell(5,5,"____________________________________________________________");

$ypos_static+=5;
$pdf->setY($ypos_static);
$pdf->setX(15);
$pdf->Cell(5,5,"____________________________________________________________");
$ypos_static+=5;
$pdf->setY($ypos_static);
$pdf->setX(15);
$pdf->Cell(5,5,"____________________________________________________________");

//////////////////////////////////////////

$ypos_static+=10;
$pdf->SetTextColor(255,255,255); // CAMBIO DE COLOR
$pdf->setFillColor(0,0,100); // BG COLOR DEL ENCABEZADO
$pdf->setY($ypos_static);
$pdf->setX(12);
$pdf->Cell(130, 22, "", 1, 1, 'C');
$pdf->setY($ypos_static);
$pdf->setX(12);
//$pdf->Cell(85,5,"PROVEEDOR DE SERVICIOS",1);
$pdf->Cell(130,5,"FIRMA Y FECHA DE ENTREGA",0,1,'C',1); //your cell
$pdf->setY($ypos_static);
$pdf->SetTextColor(0,0,0); // CAMBIO DE COLOR

$ypos_static+=5;

$ypos_static+=5;
$ypos_static+=5;
$pdf->setY($ypos_static);
$pdf->setX(15);
$pdf->Cell(5,5,"Nombre y Firma de Quien Recibe.");

$pdf->setX(12);
$pdf->setX(12);


$pdf->output();