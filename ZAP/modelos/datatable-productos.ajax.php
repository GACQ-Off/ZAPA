<?php

require_once "../controladores/productos.controlador.php";
require_once "../modelos/productos.modelo.php";

require_once "../controladores/categorias.controlador.php";
require_once "../modelos/categorias.modelo.php";


require_once "../controladores/marcas.controlador.php";
require_once "../modelos/marcas.modelo.php";

require_once "../controladores/tipos.controlador.php";
require_once "../modelos/tipos.modelo.php";

require_once "../controladores/colores.controlador.php";
require_once "../modelos/colores.modelo.php";


class TablaProductos{

 	/*=============================================
 	 MOSTRAR LA TABLA DE PRODUCTOS
  	=============================================*/ 

	public function mostrarTablaProductos(){

		$item = null;
    	$valor = null;
    	$orden = "codigo";

  		$productos = ControladorProductos::ctrMostrarProductos($item, $valor, $orden);


  		if(count($productos) == 0){

  			echo '{"data": []}';
		  	return;
  		}
		
  		$datosJson = '{
		  "data": [';

		  for($i = 0; $i < count($productos); $i++){

		  	/*=============================================
 	 		TRAEMOS LA IMAGEN
  			=============================================*/ 

		  	$imagen = "<img src='".$productos[$i]["imagen"]."' width='40px'>";

		  	/*=============================================
 	 		TRAEMOS LA CATEGORÍA
  			=============================================*/ 

		  	$item = "id";
		  	$valor = $productos[$i]["id_categoria"];

		  	$categorias = ControladorCategorias::ctrMostrarCategorias($item, $valor);

			  $item = "id";
		  	$valor = $productos[$i]["id_marca"];

		  	$marca = ControladorMarcas::ctrMostrarMarcas($item, $valor);

			$tipo = ControladorTipos::ctrMostrarTipos($item,$productos[$i]["id_tipo"]);
			$color = ControladorColores::ctrMostrarColor($item,$productos[$i]["id_color"]);

		  	/*=============================================
             STOCK
=============================================*/

			if ($productos[$i]["stock"] <= 10) {
			    $stock = "<button class='btn btn-danger'>".$productos[$i]["stock"]."</button>";
			} else if ($productos[$i]["stock"] >= 11 && $productos[$i]["stock"] <= 15) {
			    $stock = "<button class='btn btn-warning'>".$productos[$i]["stock"]."</button>";
			} else {
			    $stock = "<button class='btn btn-success'>".$productos[$i]["stock"]."</button>";
}
		  	/*=============================================
 	 		TRAEMOS LAS ACCIONES
  			=============================================*/ 

  			if(isset($_GET["perfilOculto"]) && $_GET["perfilOculto"] == "Especial"){

  				$botones =  "<div class='btn-group'><button class='btn btn-warning btnEditarProducto' idProducto='".$productos[$i]["codigo"]."' data-toggle='modal' data-target='#modalEditarProducto'><i class='fa fa-pencil'></i></button></div>"; 

  			}else{

  				 $botones =  "<div class='btn-group'><button class='btn btn-warning btnEditarProducto' idProducto='".$productos[$i]["codigo"]."' data-toggle='modal' data-target='#modalEditarProducto'><i class='fa fa-pencil'></i></button><button class='btn btn-danger btnEliminarProducto' idProducto='".$productos[$i]["codigo"]."' codigo='".$productos[$i]["codigo"]."' imagen='".$productos[$i]["imagen"]."'><i class='fa fa-times'></i></button><button id='btnStock' idProducto='".$productos[$i]["codigo"]."' data-toggle='modal' data-target='#modalStockProducto' class='btn btn-info'><i class='fa fa-plus'></i></button></div>"; 

  			}
		  	$datosJson .='[
			      "'.($i+1).'",
			      "'.$imagen.'",
			      "'.$productos[$i]["codigo"].'",
			      "'.$productos[$i]["descripcion"].'",
			      "'.$categorias["categoria"].'",
				  "'.$tipo["tipo"].'",
				  "'.$marca["marca"].'",
			
			      "'.$stock.'",
			      "'.$productos[$i]["precio_compra"].'",
			      "'.$productos[$i]["precio_venta"].'",
			      "'.$botones.'"
			    ],';

		  }

		  $datosJson = substr($datosJson, 0, -1);

		 $datosJson .=   '] 

		 }';
		
		echo $datosJson;


	}



}

/*=============================================
ACTIVAR TABLA DE PRODUCTOS
=============================================*/ 
$activarProductos = new TablaProductos();
$activarProductos -> mostrarTablaProductos();

