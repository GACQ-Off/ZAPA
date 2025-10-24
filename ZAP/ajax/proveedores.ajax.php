<?php

require_once "../controladores/proveedor.controlador.php";
require_once "../modelos/proveedor.modelo.php";

class AjaxProveedores{

	/*=============================================
	EDITAR CLIENTE
	=============================================*/	

	public $idCliente;

	public function ajaxEditarCliente(){

		$item = "rif";
		$valor = $this->idCliente;

		$respuesta = ControladorProveedor::ctrMostrarProveedor($item, $valor);

		echo json_encode($respuesta);


	}

}

/*=============================================
EDITAR CLIENTE
=============================================*/	

if(isset($_POST["idProveedor"])){

	$cliente = new AjaxProveedores();
	$cliente -> idCliente = $_POST["idProveedor"];
	$cliente -> ajaxEditarCliente();

}