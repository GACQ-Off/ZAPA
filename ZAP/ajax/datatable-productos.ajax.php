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

require_once "../controladores/configuracion.controlador.php";
require_once "../modelos/configuracion.modelo.php";

class TablaProductos {

    public function mostrarTablaProductos() {

        $item = null;
        $valor = null;
        $orden = "codigo";

        $productos = ControladorProductos::ctrMostrarProductos($item, $valor, $orden);
        $configuracion = ControladorConfiguracion::ctrMostrarConfigracion("id", 1);

        $precioDolar = isset($configuracion["precio_dolar"]) ? $configuracion["precio_dolar"] : 1;

        if (count($productos) == 0) {
            echo '{"data": []}';
            return;
        }

        $datos = [];

        for ($i = 0; $i < count($productos); $i++) {

            $imagen = "<img src='" . $productos[$i]["imagen"] . "' width='40px'>";

            // Categoría
            $item = "id";
            $valor = $productos[$i]["id_categoria"];
            $categorias = ControladorCategorias::ctrMostrarCategorias($item, $valor);

            // Marca
            $valor = $productos[$i]["id_marca"];
            $marca = ControladorMarcas::ctrMostrarMarcas($item, $valor);

            // Tipo
            $valor = $productos[$i]["id_tipo"];
            $tipo = ControladorTipos::ctrMostrarTipos($item, $valor);

            // Color
            $valor = $productos[$i]["id_color"];
            $color = ControladorColores::ctrMostrarColor($item, $valor);

            // Stock
            if ($productos[$i]["stock"] <= 10) {
                $stock = "<button class='btn btn-danger'>" . $productos[$i]["stock"] . "</button>";
            } elseif ($productos[$i]["stock"] <= 15) {
                $stock = "<button class='btn btn-warning'>" . $productos[$i]["stock"] . "</button>";
            } else {
                $stock = "<button class='btn btn-success'>" . $productos[$i]["stock"] . "</button>";
            }

            $precioBolivares = $productos[$i]["precio_venta"] * $precioDolar;
            $precioBolivaresFormateado = number_format($precioBolivares, 2, '.', ',');

            // Botones
            if (isset($_GET["perfilOculto"]) && $_GET["perfilOculto"] == "Especial") {
                $botones = "<div class='btn-group'><button class='btn btn-warning btnEditarProducto' idProducto='" . $productos[$i]["codigo"] . "' data-toggle='modal' data-target='#modalEditarProducto'><i class='fa fa-pencil'></i></button></div>";
            } else {
                $botones = "<div class='btn-group'>
                    <button class='btn btn-warning btnEditarProducto' idProducto='" . $productos[$i]["codigo"] . "' data-toggle='modal' data-target='#modalEditarProducto'><i class='fa fa-pencil'></i></button>
                    <button class='btn btn-danger btnEliminarProducto' idProducto='" . $productos[$i]["codigo"] . "' codigo='" . $productos[$i]["codigo"] . "' imagen='" . $productos[$i]["imagen"] . "'><i class='fa fa-times'></i></button>
                   </div>";
            }

            $datos[] = [
                ($i + 1),
                $imagen,
                $productos[$i]["codigo"],
                $productos[$i]["descripcion"],
                $categorias["categoria"],
                $tipo["tipo"],
                $marca["marca"],
                $stock,
                $productos[$i]["precio_compra"],
                $productos[$i]["precio_venta"],
                $precioBolivaresFormateado,
                $botones
            ];
        }

        echo json_encode(["data" => $datos]);
    }
}

$activarProductos = new TablaProductos();
$activarProductos->mostrarTablaProductos();
