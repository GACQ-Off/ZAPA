<?php


// Asegúrate de que fpdf.php está en el mismo directorio o la ruta es correcta
require('fpdf.php');

// --- INICIO DE GESTIÓN DE RUTAS (CRÍTICO PARA LA CONSISTENCIA) ---
// Define ROOT_PATH si no está ya definido.
// Si este archivo está en 'extensiones/fpdf/', sube dos niveles para llegar a 'ventaslog/'.
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR);
}
// --- FIN DE GESTIÓN DE RUTAS ---


// Ahora usa ROOT_PATH para todas las inclusiones
require_once ROOT_PATH . "controladores/configuracion.controlador.php";
require_once ROOT_PATH . "modelos/configuracion.modelo.php";

require_once ROOT_PATH . 'modelos/productos.modelo.php';
require_once ROOT_PATH . 'controladores/productos.controlador.php';

require_once ROOT_PATH . 'modelos/categorias.modelo.php';
require_once ROOT_PATH . 'controladores/categorias.controlador.php';

require_once ROOT_PATH . 'modelos/tipos.modelo.php';
require_once ROOT_PATH . 'controladores/tipos.controlador.php';

require_once ROOT_PATH . 'modelos/marcas.modelo.php';
require_once ROOT_PATH . 'controladores/marcas.controlador.php';

// INCLUSIONES NECESARIAS PARA EL REGISTRO DE EVENTOS LOG
// Solo incluimos el controlador; el controlador se encarga de su modelo.
require_once ROOT_PATH . "controladores/eventolog.controlador.php";
// FIN DE INCLUSIONES PARA EL REGISTRO DE EVENTOS LOG

//require_once ROOT_PATH . "controladores/colores.controlador.php";
//require_once ROOT_PATH . "modelos/colores.modelo.php";


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
$this->Image('img/logo.png',10,1,60);


$this->SetY(5);
$this->SetX(70);
$this->SetFont('Arial','B',20);

$empresa = ControladorConfiguracion::ctrMostrarConfigracion(null,null);
$nombreEmpresa = $empresa["nombre"];
$rifEmpresa = $empresa["rif"];  
$localizacionEmpresa = $empresa["direccion"];
$telefonoEmpresa = $empresa["telefono"];

$this->SetTextColor(0, 0, 0 );
$this->Cell(50, 25, utf8_decode($nombreEmpresa),0,1);

$this->SetY(15);
$this->SetX(70);
$this->SetFont('Arial','B',20);
$this->Cell(80, 20, "Rif: " .$rifEmpresa,0,1);
$this->SetY(21);
$this->SetX(70);
$this->SetFont('Arial','B',20);
$this->Cell(80, 20, utf8_decode("Dirección: ".$localizacionEmpresa),0,1);
$this->SetY(28);
$this->SetX(70);
$this->SetFont('Arial','B',20);
$this->Cell(80, 20, utf8_decode("Telefonó: ".$telefonoEmpresa),0,1);
$this->SetY(55);
$this->SetX(96);
$this->SetFont('Arial','',30);
$this->Cell(60, 8,"Reporte de Inventario");
$this->SetTextColor(30,10,32);
$this->Ln(20);




}

function Footer()
{
      $this->SetFont('helvetica', 'B', 12);
        $this->SetY(-15);
        $this->Cell(95,5,utf8_decode('Página ').$this->PageNo().' / {nb}',0,0,'L');
        $this->Cell(172,5,date('d/m/Y | g:i:a') ,00,1,'R');
        $this->Line(10,287,200,287);
        //$this->Cell(0,5,utf8_decode("Kodo Sensei © Todos los derechos reservados."),0,0,"C");
        
}


}



$pdf = new PDF();

$pdf->AliasNbPages();
$pdf->AddPage('L'); // Set landscape orientation

$pdf->SetAutoPageBreak(true, 20);

$pdf->SetTopMargin(500);
$pdf->SetLeftMargin(10);
$pdf->SetRightMargin(10);
$pdf->SetX(15);
$pdf->SetFillColor(070, 130, 180);
$pdf->SetDrawColor(255, 255, 255);
// Cell(ancho , alto,texto,borde(0/1),salto(0/1),alineacion(L,C,R),rellenar(0/1)

$productos = ControladorProductos::ctrMostrarProductos(null,null,1);

$pdf->SetFont('Arial','B',12);
$pdf->Cell(12, 12, utf8_decode('N°'),1,0,'C',1);
$pdf->Cell(30, 12, utf8_decode('Codigo'),1,0,'C',1);
$pdf->Cell(45, 12, utf8_decode('Descripcion'),1,0,'C',1);
$pdf->Cell(44, 12, utf8_decode('Categoria'),1,0,'C',1);
$pdf->Cell(30, 12, utf8_decode('Tipo'),1,0,'C',1);
$pdf->Cell(30, 12, utf8_decode('Marca'),1,0,'C',1);
//$pdf->Cell(25, 12, utf8_decode('Color'),1,0,'C',1);
$pdf->Cell(20, 12, utf8_decode('Stock'),1,0,'C',1);
$pdf->Cell(25, 12, utf8_decode('P. Compra'),0,0,'C',1);
$pdf->Cell(25, 12, utf8_decode('P. Venta'),1,1,'C',1);


$pdf->SetFont('Arial','',10);
for ($i = 0; $i < count($productos); $i++) {


 $codigo = $productos[$i]["codigo"];
 $descripcion = $productos[$i]["descripcion"];
 $stock = $productos[$i]["stock"];
 $pcompra = $productos[$i]["precio_compra"];
 $pventa = $productos[$i]["precio_venta"];
     if ($stock < 10) {
         $pdf->SetFillColor(255, 204, 204); // Color de fondo rojo claro
     } else {
         $pdf->SetFillColor(255, 255, 255); // Color de fondo blanco
     }

 $categoria = ControladorCategorias::ctrMostrarCategorias("id",$productos[$i]["id_categoria"])["categoria"];

 $tipos = ControladorTipos::ctrMostrarTipos("id",$productos[$i]["id_tipo"])["tipo"];


 $marca = ControladorMarcas::ctrMostrarMarcas("id",$productos[$i]["id_marca"])["marca"];

if ($stock < 10) {
         $pdf->SetFillColor(255, 204, 204); // Color de fondo rojo claro
     } else {
         $pdf->SetFillColor(255, 255, 255); // Color de fondo blanco
     }

    

 $pdf->SetX(15);
$pdf->SetFillColor(255,255,255);
$pdf->SetDrawColor(65, 61, 61);
$pdf->Cell(12, 8, utf8_decode($i+ 1),'B',0,'C',1);
$pdf->Cell(30, 8, utf8_decode($codigo),'B',0,'C',1);
$pdf->Cell(45, 8, utf8_decode(ucwords($descripcion)),'B',0,'C',1);
$pdf->Cell(44, 8, utf8_decode($categoria),'B',0,'C',1);
$pdf->Cell(30, 8, utf8_decode($tipos),'B',0,'C',1);
$pdf->Cell(30, 8, utf8_decode($marca),'B',0,'C',1);
//$pdf->Cell(25, 8, utf8_decode('Color'),'B',0,'C',1);

if ($stock < 10) {
         $pdf->SetFillColor(255, 204, 204); // Color de fondo rojo claro
     } else {
         $pdf->SetFillColor(255, 255, 255); // Color de fondo blanco
     }

$pdf->Cell(20, 8, utf8_decode($stock),'B',0,'C',1);
$pdf->Cell(25, 8, number_format($pcompra, 2, ',', '.').' $ ','B',0,'C',1);
$pdf->Cell(25, 8, number_format($pventa, 2, ',', '.').' $ ','B',1,'C',1);


$pdf->Ln(0.5);
}

// INICIO DEL BLOQUE PARA REGISTRAR EL EVENTO DE LOG
// Aquí se registra el evento de log UNA SOLA VEZ, al final de la generación del reporte.
// Esto asume que tienes una sesión iniciada y la cédula del empleado está disponible.
// Si no hay sesión, usará "Sistema/Desconocido" como cedula.
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$empleadoCedula = (isset($_SESSION["cedula"])) ? $_SESSION["cedula"] : "Sistema/Desconocido";

$logData = array(
    "event_type" => "Generación de Reporte PDF",
    "description" => "Se generó un reporte de Inventario en formato PDF.",
    "employee_cedula" => $empleadoCedula,
    "affected_table" => "productos", // La tabla principal de la que se reporta
    "affected_row_id" => "N/A" // No hay una fila específica afectada por la generación del reporte completo
);

// ¡CAMBIO CLAVE AQUÍ! Llama al controlador de eventos, no directamente al modelo.
ControladorEventoLog::ctrGuardarEventoLog(
    $logData["event_type"],
    $logData["description"],
    $logData["employee_cedula"],
    $logData["affected_table"],
    $logData["affected_row_id"]
);

// FIN DEL BLOQUE PARA REGISTRAR EL EVENTO DE LOG


$pdf->Output();
?>