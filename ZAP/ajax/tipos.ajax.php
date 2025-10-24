<?php

require_once "../controladores/tipos.controlador.php";
require_once "../modelos/tipos.modelo.php";

class AjaxTipos{

	/*=============================================
	EDITAR CATEGORÍA
	=============================================*/	

	public $idTipo;

	public function ajaxEditarTipo(){

		$item = "id";
		$valor = $this->idTipo;

		$respuesta = ControladorTipos::ctrMostrarTipos($item, $valor);

		echo json_encode($respuesta);

	}
}

/*=============================================
EDITAR CATEGORÍA
=============================================*/	
if(isset($_POST["idTipo"])){

	$tipo = new AjaxTipos();
	$tipo -> idTipo = $_POST["idTipo"];
	$tipo -> ajaxEditarTipo();
}
