<?php

require_once "../../../controladores/ventas.controlador.php";
require_once "../../../modelos/ventas.modelo.php";
require_once "../../../controladores/configuracion.controlador.php";
require_once "../../../modelos/configuracion.modelo.php";


require_once "../../../controladores/venta_producto.controlador.php";
require_once "../../../modelos/venta_producto.modelo.php";


require_once "../../../controladores/clientes.controlador.php";
require_once "../../../modelos/clientes.modelo.php";

require_once "../../../controladores/empleados.controlador.php";
require_once "../../../modelos/empleados.modelo.php";

require_once "../../../controladores/productos.controlador.php";
require_once "../../../modelos/productos.modelo.php";

class imprimirFactura{

public $codigo;

public function traerImpresionFactura(){

//TRAEMOS LA INFORMACIÓN DE LA VENTA

$itemVenta = "factura";
$valorVenta = $this->codigo;

$respuestaVenta = ControladorVentas::ctrMostrarVentas($itemVenta, $valorVenta);

$fecha = new DateTime($respuestaVenta["fecha"]);
$fecha = $fecha->format("d/m/Y H:i");

$productos = ControladorVentasProductos::ctrMostrarVentasProductos("factura",$respuestaVenta["factura"]);


//$neto = 0;

//$impuesto = number_format($respuestaVenta["impuesto"]/100*$neto, 2);
$total = number_format($respuestaVenta["total"],2);

//$cambio = $respuestaVenta["total"] - $respuestaVenta["pago"];
//$tipodepago = ($respuestaVenta["metodo_pago"]);

//TRAEMOS LA INFORMACIÓN DEL CLIENTE

$itemCliente = "cedula";
$valorCliente = $respuestaVenta["cliente"];


$respuestaCliente = ControladorClientes::ctrMostrarClientes($itemCliente, $valorCliente);

//TRAEMOS LA INFORMACIÓN DEL VENDEDOR

$itemVendedor = "cedula";
$valorVendedor = $respuestaVenta["vendedor"];



$respuestaVendedor = ControladorEmpleados::ctrMostrarEmpleados($itemVendedor, $valorVendedor);

$empresa = ControladorConfiguracion::ctrMostrarConfigracion(null,null);

$localizacionEmpresa = $empresa["direccion"];
$rifEmpresa = $empresa["rif"];
$nombreEmpresa = $empresa["nombre"];
$dolar =$empresa["precio_dolar"];
$telefonoEmpresa = $empresa["telefono"];
$neto = $respuestaVenta["pago"];
$impuesto = $respuestaVenta["impuesto"];
$totalVenta = $respuestaVenta["total"];

//REQUERIMOS LA CLASE TCPDF

require_once('tcpdf_include.php');

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

$pdf->AddPage('P', 'A7');

$nombreClienteCompleto = ucwords($respuestaCliente["nombre"] . " " . $respuestaCliente["apellido"]);



//---------------------------------------------------------

$bloque1 = <<<EOF

<table style="font-size:6px; text-align:center;">

	<tr>
		<td style="width:160px;">
	
			<div>
				$nombreEmpresa
				
				<br>
				Dirección: $localizacionEmpresa
				
				<br>
				RIF: $rifEmpresa

				

				<br>
				Teléfono: $telefonoEmpresa
				<br>
				<br>


				FACTURA N.$valorVenta  <br>     Fecha: $fecha
				<br><br>
				Vendedor: $respuestaVendedor[nombre] $respuestaVendedor[apellido] 
                <br>
                Cédula:$respuestaVendedor[cedula] 
				<br>
                 
				<br>					
				Cliente: Cliente: $nombreClienteCompleto
				<br>
				Cédula: $respuestaCliente[cedula]
				<br>
                Dirección: $respuestaCliente[direccion]
                <br>
                Tipo de Pago: $respuestaVenta[metodo_pago]
				<br>
			</div>

		</td>

	</tr>


</table>

<table style="font-size:6px;text-align:center;width:100%;">
<thead>
<tr>
    <td style="width:30px">Código</td>
	<td style="width:40px">Descripción</td>
	
	<td style="width:43px">Cantidad</td>
	<td style="width:50px">Total</td>
</tr>
<tr><td style="width:160px">-----------------------------------------------------------------------------</td></tr>
</thead>
</thead>
</table>

EOF;

$pdf->writeHTML($bloque1, false, false, false, false, '');

// ---------------------------------------------------------


foreach ($productos as $key => $item) {

$productoInfo = ControladorProductos::ctrMostrarProductos("codigo",$item["producto"],"codigo");

$valorUnitario = number_format($productoInfo["precio_venta"] * $dolar, 2);

$precioTotal = number_format(str_replace(",","",$valorUnitario) * $item["cantidad"], 2);
$bloque2 = <<<EOF

<table style="font-size:6px;text-align:center;">
	<tbody>
	<tr>
	
		<td style="width:30px">
		$productoInfo[codigo]
		</td>
		<td style="width:43px">
		$productoInfo[descripcion] 
		</td>
		<td style="width:43px">$item[cantidad] * $valorUnitario Bs</td>
		<td style="width:50px">$precioTotal Bs
		</td>
	</tr>
	</tbody>

</table>

EOF;

$pdf->writeHTML($bloque2, false, false, false, false, '');

}

// ---------------------------------------------------------

$bloque3 = <<<EOF
<br>
<br>
<table style="font-size:6px; text-align:right">

	<tr>
	
		<td style="width:80px;">
			 Neto: 
		</td>

		<td style="width:80px;">
			$neto Bs
		</td>

	</tr>

	<tr>
	
		<td style="width:80px;">
			 Impuesto: 
		</td>

		<td style="width:80px;">
			$impuesto Bs
		</td>

	</tr>

	<tr>
	
		<td style="width:80px;">
			 Total: 
		</td>

		<td style="width:80px;">
			$totalVenta Bs
		</td>

	</tr>
	<tr>
	
		<td style="width:160px;">
			<br>
			<br>
			Sin derecho a credito fiscal
		</td>

	</tr>

</table>



EOF;

$pdf->writeHTML($bloque3, false, false, false, false, '');

// ---------------------------------------------------------
//SALIDA DEL ARCHIVO 

//$pdf->Output('factura.pdf', 'D');
$pdf->Output('factura.pdf');

}

}

$factura = new imprimirFactura();
$factura -> codigo = $_GET["codigo"];
$factura -> traerImpresionFactura();
