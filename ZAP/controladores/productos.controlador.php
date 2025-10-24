<?php

require_once __DIR__ . "/../modelos/productos.modelo.php";
require_once __DIR__ . "/../modelos/eventolog.modelo.php";

class ControladorProductos{
    /*=============================================
MOSTRAR PRODUCTOS PARA SELECT
=============================================*/
static public function ctrMostrarProductosSelect() {
    $tabla = "productos";
    $productos = ModeloProductos::mdlMostrarProductos($tabla, null, null, null);
    
    $resultado = [];
    if ($productos) {
        foreach ($productos as $prod) {
            $resultado[] = [
                "codigo" => $prod["codigo"],
                "descripcion" => $prod["descripcion"],
                "precio_compra" => $prod["precio_compra"]
            ];
        }
    }
    return $resultado;
}


    /*=============================================
    MOSTRAR PRODUCTOS
    =============================================*/
    static public function ctrMostrarProductos($item, $valor, $orden){
        $tabla = "productos";
        $respuesta = ModeloProductos::mdlMostrarProductos($tabla, $item, $valor, $orden);
        return $respuesta;
    }

    /*=============================================
    CREAR PRODUCTO
    =============================================*/
    static public function ctrCrearProducto(){
        if(isset($_POST["nuevaDescripcion"])){

            if(preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["nuevaDescripcion"]) &&
                   
               preg_match('/^[0-9.]+$/', $_POST["nuevoPrecioCompra"]) &&
               preg_match('/^[0-9.]+$/', $_POST["nuevoPrecioVenta"])){

                $ruta = "vistas/img/productos/default/anonymous.png";

                if(isset($_FILES["nuevaImagen"]["tmp_name"])){

                    list($ancho, $alto) = getimagesize($_FILES["nuevaImagen"]["tmp_name"]);
                    $nuevoAncho = 500;
                    $nuevoAlto = 500;
                    $directorio = "vistas/img/productos/".$_POST["nuevoCodigo"];
                    mkdir($directorio, 0755);

                    if($_FILES["nuevaImagen"]["type"] == "image/jpeg"){
                        $aleatorio = mt_rand(100,999);
                        $ruta = "vistas/img/productos/".$_POST["nuevoCodigo"]."/".$aleatorio.".jpg";
                        $origen = imagecreatefromjpeg($_FILES["nuevaImagen"]["tmp_name"]);
                        $destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);
                        imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);
                        imagejpeg($destino, $ruta);
                    }

                    if($_FILES["nuevaImagen"]["type"] == "image/png"){
                        $aleatorio = mt_rand(100,999);
                        $ruta = "vistas/img/productos/".$_POST["nuevoCodigo"]."/".$aleatorio.".png";
                        $origen = imagecreatefrompng($_FILES["nuevaImagen"]["tmp_name"]);
                        $destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);
                        imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);
                        imagepng($destino, $ruta);
                    }
                }

                $tabla = "productos";

                $datos = array(
                    "id_categoria" => $_POST["nuevaCategoria"],
                    "codigo" => $_POST["nuevoCodigo"],
                    // NUEVOS CAMPOS POST
                    "tipo_rif_proveedor" => $_POST["nuevoTipoRifProveedor"], // Campo oculto del formulario
                    "num_rif_proveedor" => $_POST["nuevoNumRifProveedor"],   // Campo oculto del formulario
                    "descripcion" => $_POST["nuevaDescripcion"],
                    "stock" => $_POST["nuevoStock"],
                    "precio_compra" => $_POST["nuevoPrecioCompra"],
                    "id_marca" => $_POST["nuevaMarca"],
                    "id_color" => $_POST["nuevoColor"],
                    "id_tipo" => $_POST["nuevoTipo"],
                    "precio_venta" => $_POST["nuevoPrecioVenta"],
                    "imagen" => $ruta
                );

                $respuesta = ModeloProductos::mdlIngresarProducto($tabla, $datos);

                if($respuesta == "ok"){
                    $empleado = $_SESSION["cedula"] ?? "Sistema/Desconocido";
                    ModeloEventoLog::mdlGuardarEvento(array(
                        "event_type" => "Producto Creado",
                        "description" => "Producto '{$datos["descripcion"]}' (Código: {$datos["codigo"]}) creado.",
                        "employee_cedula" => $empleado,
                        "affected_table" => "productos",
                        "affected_row_id" => $datos["codigo"]
                    ));

                    echo'<script>
                    swal({
                          type: "success",
                          title: "El producto ha sido guardado correctamente",
                          showConfirmButton: true,
                          confirmButtonText: "Cerrar"
                          }).then(function(result){
                                if (result.value) {
                                window.location = "productos";
                                }
                            })
                    </script>';
                }else if($respuesta == "repetido"){
                    $empleado = $_SESSION["cedula"] ?? "Sistema/Desconocido";
                    ModeloEventoLog::mdlGuardarEvento(array(
                        "event_type" => "Creación Producto Fallida",
                        "description" => "Intento de crear producto con código '{$datos["codigo"]}' fallido por duplicado.",
                        "employee_cedula" => $empleado,
                        "affected_table" => "productos",
                        "affected_row_id" => $datos["codigo"]
                    ));

                    echo'<script>
                    swal({
                          type: "error",
                          title: "El codigo de producto ya existe",
                          showConfirmButton: true,
                          confirmButtonText: "Cerrar"
                          }).then(function(result) {
                                if (result.value) {
                                window.location = "productos";
                                }
                            })
                    </script>';
                }
            }else{
                echo'<script>
                swal({
                      type: "error",
                      title: "¡El producto no puede ir con los campos vacíos o llevar caracteres especiales!",
                      showConfirmButton: true,
                      confirmButtonText: "Cerrar"
                      }).then(function(result){
                        if (result.value) {
                            window.location = "productos";
                        }
                    })
                </script>';
            }
        }
    }

    /*=============================================
    EDITAR PRODUCTO
    =============================================*/
    static public function ctrEditarProducto(){
        if(isset($_POST["editarDescripcion"])){

            if(preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["editarDescripcion"]) &&
               preg_match('/^[0-9]+$/', $_POST["editarStock"]) &&   
               preg_match('/^[0-9.]+$/', $_POST["editarPrecioCompra"]) &&
               preg_match('/^[0-9.]+$/', $_POST["editarPrecioVenta"])){

                $ruta = $_POST["imagenActual"];

                if(isset($_FILES["editarImagen"]["tmp_name"]) && !empty($_FILES["editarImagen"]["tmp_name"])){

                    list($ancho, $alto) = getimagesize($_FILES["editarImagen"]["tmp_name"]);
                    $nuevoAncho = 500;
                    $nuevoAlto = 500;
                    $directorio = "vistas/img/productos/".$_POST["editarCodigo"];

                    if(!empty($_POST["imagenActual"]) && $_POST["imagenActual"] != "vistas/img/productos/default/anonymous.png"){
                        unlink($_POST["imagenActual"]);
                    }else{
                        mkdir($directorio, 0755);
                    }

                    if($_FILES["editarImagen"]["type"] == "image/jpeg"){
                        $aleatorio = mt_rand(100,999);
                        $ruta = "vistas/img/productos/".$_POST["editarCodigo"]."/".$aleatorio.".jpg";
                        $origen = imagecreatefromjpeg($_FILES["editarImagen"]["tmp_name"]);
                        $destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);
                        imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);
                        imagejpeg($destino, $ruta);
                    }

                    if($_FILES["editarImagen"]["type"] == "image/png"){
                        $aleatorio = mt_rand(100,999);
                        $ruta = "vistas/img/productos/".$_POST["editarCodigo"]."/".$aleatorio.".png";
                        $origen = imagecreatefrompng($_FILES["editarImagen"]["tmp_name"]);
                        $destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);
                        imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);
                        imagepng($destino, $ruta);
                    }
                }

                $tabla = "productos";

                $datos = array(
                    "id_categoria" => $_POST["editarCategoria"],
                    "stock" => $_POST["editarStock"],
                    "id_color" => $_POST["editarColor"],
                    "id_tipo" => $_POST["editarTipo"],
                    // NUEVOS CAMPOS POST
                    "tipo_rif_proveedor" => $_POST["editarTipoRifProveedor"], // Campo oculto del formulario
                    "num_rif_proveedor" => $_POST["editarNumRifProveedor"],   // Campo oculto del formulario
                    "id_marca" => $_POST["editarMarca"],
                    "codigo" => $_POST["editarCodigo"],
                    "descripcion" => $_POST["editarDescripcion"],
                    "precio_compra" => $_POST["editarPrecioCompra"],
                    "precio_venta" => $_POST["editarPrecioVenta"],
                    "imagen" => $ruta
                );

                $respuesta = ModeloProductos::mdlEditarProducto($tabla, $datos);

                if($respuesta == "ok"){
                    $empleado = $_SESSION["cedula"] ?? "Sistema/Desconocido";
                    ModeloEventoLog::mdlGuardarEvento(array(
                        "event_type" => "Producto Editado",
                        "description" => "Producto '{$datos["descripcion"]}' (Código: {$datos["codigo"]}) editado.",
                        "employee_cedula" => $empleado,
                        "affected_table" => "productos",
                        "affected_row_id" => $datos["codigo"]
                    ));

                    echo'<script>
                    swal({
                          type: "success",
                          title: "El producto ha sido editado correctamente",
                          showConfirmButton: true,
                          confirmButtonText: "Cerrar"
                          }).then(function(result){
                                if (result.value) {
                                window.location = "productos";
                                }
                            })
                    </script>';
                }
            }else{
                echo'<script>
                swal({
                      type: "error",
                      title: "¡El producto no puede ir con los campos vacíos o llevar caracteres especiales!",
                      showConfirmButton: true,
                      confirmButtonText: "Cerrar"
                      }).then(function(result){
                        if (result.value) {
                        window.location = "productos";
                        }
                    })
                </script>';
            }
        }
    }

    /*=============================================
    ACTUALIZAR STOCK
    =============================================*/
    static public function ctrStockProducto(){
        if(isset($_POST["editarStockCodigo"])){

            if(preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ -]+$/', $_POST["editarStockCodigo"]) &&
               preg_match('/^[0-9]+$/', $_POST["editarStockNum"])){

                $tabla = "productos";

                $datos = array(
                    "codigo" => $_POST["editarStockCodigo"],
                    "proveedor" => $_POST["editarStockProveedor"],
                    "stock" => $_POST["editarStockNum"]
                );

                $respuesta = ModeloProductos::mdlEditarStock($tabla, $datos);

                if($respuesta == "ok"){
                    $empleado = $_SESSION["cedula"] ?? "Sistema/Desconocido";
                    ModeloEventoLog::mdlGuardarEvento(array(
                        "event_type" => "Stock Actualizado",
                        "description" => "Stock del producto (Código: {$datos["codigo"]}) actualizado a {$datos["stock"]} unidades.",
                        "employee_cedula" => $empleado,
                        "affected_table" => "productos",
                        "affected_row_id" => $datos["codigo"]
                    ));

                    echo'<script>
                    swal({
                          type: "success",
                          title: "El stock del producto ha sido editado correctamente",
                          showConfirmButton: true,
                          confirmButtonText: "Cerrar"
                          }).then(function(result){
                                if (result.value) {
                                window.location = "productos";
                                }
                            })
                    </script>';
                }
            }else{
                echo'<script>
                swal({
                      type: "error",
                      title: "¡El stock no puede ir con los campos vacíos o llevar caracteres especiales!",
                      showConfirmButton: true,
                      confirmButtonText: "Cerrar"
                      }).then(function(result){
                        if (result.value) {
                        window.location = "productos";
                        }
                    })
                </script>';
            }
        }
    }

    /*=============================================
    ELIMINAR PRODUCTO
    =============================================*/
    static public function ctrEliminarProducto(){
        if(isset($_GET["idProducto"])){

            $tabla ="productos";
            $datos = $_GET["idProducto"];

            if($_GET["imagen"] != "" && $_GET["imagen"] != "vistas/img/productos/default/anonymous.png"){
                unlink($_GET["imagen"]);
                rmdir('vistas/img/productos/'.$_GET["codigo"]);
            }

            $respuesta = ModeloProductos::mdlEliminarProducto($tabla, $datos);

            if($respuesta == "ok"){
                $empleado = $_SESSION["cedula"] ?? "Sistema/Desconocido";
                ModeloEventoLog::mdlGuardarEvento(array(
                    "event_type" => "Producto Eliminado",
                    "description" => "Producto (Código: {$_GET["codigo"]}) eliminado.",
                    "employee_cedula" => $empleado,
                    "affected_table" => "productos",
                    "affected_row_id" => $_GET["idProducto"]
                ));

                echo'<script>
                swal({
                      type: "success",
                      title: "El producto ha sido borrado correctamente",
                      showConfirmButton: true,
                      confirmButtonText: "Cerrar"
                      }).then(function(result){
                            if (result.value) {
                            window.location = "productos";
                            }
                        })
                </script>';
            }
        }
    }

    /*=============================================
    MOSTRAR SUMA VENTAS
    =============================================*/
    static public function ctrMostrarSumaVentas(){
        $tabla = "productos";
        $respuesta = ModeloProductos::mdlMostrarSumaVentas($tabla);
        return $respuesta;
    }
}
