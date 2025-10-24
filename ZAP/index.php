<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// ... el resto de tu código


require_once "controladores/plantilla.controlador.php";
require_once "controladores/usuarios.controlador.php";
require_once "controladores/empleados.controlador.php";
require_once "controladores/exportar.controlador.php";
require_once "controladores/importar.controlador.php";
require_once "controladores/configuracion.controlador.php";
require_once "controladores/categorias.controlador.php";
require_once "controladores/productos.controlador.php";
require_once "controladores/clientes.controlador.php";
require_once "controladores/ventas.controlador.php";
require_once "controladores/venta_producto.controlador.php";
require_once "controladores/marcas.controlador.php";
require_once "controladores/colores.controlador.php";
require_once "controladores/proveedor.controlador.php";
require_once "controladores/eventolog.controlador.php";

require_once "modelos/venta_producto.modelo.php";
require_once "modelos/usuarios.modelo.php";
require_once "modelos/proveedor.modelo.php";
require_once "modelos/configuracion.modelo.php";
require_once "modelos/empleados.modelo.php";
require_once "modelos/categorias.modelo.php";
require_once "modelos/productos.modelo.php";
require_once "modelos/clientes.modelo.php";
require_once "modelos/ventas.modelo.php";
require_once "extensiones/vendor/autoload.php";
require_once "modelos/tipos.modelo.php";
require_once "modelos/marcas.modelo.php";
require_once "modelos/colores.modelo.php";

require_once "controladores/tipos.controlador.php";

$plantilla = new ControladorPlantilla();
$plantilla -> ctrPlantilla();