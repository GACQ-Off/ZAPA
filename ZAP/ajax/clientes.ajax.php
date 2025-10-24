<?php

require_once "../controladores/clientes.controlador.php";
require_once "../modelos/clientes.modelo.php";

class AjaxClientes {

    /*=============================================
    EDITAR CLIENTE (Usando clave primaria compuesta)
    =============================================*/
    public $tipo_ced;
    public $num_ced;

    public function ajaxEditarCliente() {

        if (!empty($this->tipo_ced) && !empty($this->num_ced)) {

            $item1 = "tipo_ced";
            $item2 = "num_ced";
            $valor1 = $this->tipo_ced;
            $valor2 = $this->num_ced;

            $respuesta = ControladorClientes::ctrMostrarClientesDosClaves($item1, $item2, $valor1, $valor2);

            echo json_encode($respuesta);

        } else {
            echo json_encode(["error" => "Cédula inválida"]);
        }
    }
}

/*=============================================
Instancia y ejecución al presionar el botón editar
=============================================*/
if (isset($_POST["tipo_ced"]) && isset($_POST["num_ced"])) {
    $cliente = new AjaxClientes();
    $cliente->tipo_ced = $_POST["tipo_ced"];
    $cliente->num_ced = $_POST["num_ced"];
    $cliente->ajaxEditarCliente();
}
