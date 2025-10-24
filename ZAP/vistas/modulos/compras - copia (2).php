<?php
if ($_SESSION["perfil"] == "Especial") {
    echo '<script>window.location = "inicio";</script>';
    return;
}

?>
<?php
// Incluir controladores necesarios para cargar datos
require_once "controladores/proveedor.controlador.php"; 
require_once "controladores/productos.controlador.php";
require_once "controladores/compras.controlador.php"; // ¡El controlador principal!

?>

<div class="content-wrapper">
  <section class="content-header">
    <h1>Administrar Compras</h1>
    <ol class="breadcrumb">
      <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      <li class="active">Administrar Compras</li>
    </ol>
  </section>

  <section class="content">
    <div class="box">
      <div class="box-header with-border">
        <button class="btn-lg btn-primary" data-toggle="modal" data-target="#modalAgregarCompra">Agregar Compra</button>

        <div class="pull-right">
          <button type="button" class="btn-lg btn-default" id="daterange-btn">
            <span>
              <i class="fa fa-calendar"></i>
              <?php
              if (isset($_GET["fechaInicial"])) {
                echo $_GET["fechaInicial"] . " - " . $_GET["fechaFinal"];
              } else {
                echo 'Rango de fecha';
              }
              ?>
            </span>
            <i class="fa fa-caret-down"></i>
          </button>

          <a href="compras" class="btn btn-lg btn-primary" title="Actualizar tabla" style="padding: 10px 20px;">
            <i class="fa fa-refresh"></i>
          </a>

          <button type="button" class="btn-lg btn-primary" id="generarReporteRangoPDF">
            <i class="fa fa-file-pdf-o"></i> Reporte por Rango de Fecha
          </button>
        </div>
      </div>

      <div class="box-body">
        <table class="table table-bordered table-striped dt-responsive tablas" width="100%">
          <thead>
            <tr>
              <th style="width:10px">#</th>
              <th>ID Compra</th>
              <th>Proveedor</th>
              <th>RIF</th>
              <th>Factura</th>
              <th>Total Bs</th>
              <th>Total $</th>
              <th>Tasa Cambio</th>
              <th>Observaciones</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <!-- Aquí se mostrarán las compras desde el controlador -->
          </tbody>
        </table>
      </div>
    </div>
  </section>
</div>


<div id="modalAgregarCompra" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <form method="post" id="formAgregarCompra">
                <div class="modal-header" style="background:#3c8dbc; color:white">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Agregar Compra</h4>
                </div>

                <div class="modal-body">
                    <div class="box-body">

                        <div class="row">
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label>Proveedor</label>
                                    <select class="form-control" name="nuevoProveedor" id="nuevoProveedor" required>
                                         <?php

                                              $item = null;
                                              $valor = null;

                                              // Asumiendo que esta línea obtiene los proveedores con sus campos tipo_rif y num_rif
                                              $proveedores = ControladorProveedor::ctrMostrarProveedor($item, $valor); // Cambiado $categorias a $proveedores para mayor claridad

                                              foreach ($proveedores as $key => $value) {

                                                  // Concatena tipo_rif y num_rif para el atributo 'value' y el texto a mostrar
                                                  $rifCompleto = $value["tipo_rif"] . "-" . $value["num_rif"];

                                                  echo '<option value="'.$rifCompleto.'">'.$rifCompleto.' - '.$value["nombre"].'</option>';
                                              }

                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label>N° Factura Proveedor</label>
                                    <input type="text" class="form-control" name="numero_factura_proveedor" required>
                                </div>
                            </div>
                        </div>
                         <?php
            $config = ControladorConfiguracion::ctrMostrarConfigracion(null, null);
            $tasaCambio = $config["precio_dolar"];
          ?>

                        <div class="row">
                             <div class="col-xs-6">
                                <div class="form-group">
                                    <label>Tasa de Cambio (USD a BS)</label>
                                    <input type="number" class="form-control" name="tasa_cambio_usd" id="tasa_cambio_usd" value="<?php echo $tasaCambio; ?>" step="0.01" readonly required>
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label>Observaciones</label>
                                    <textarea name="observaciones" class="form-control"></textarea>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="tablaDetalleCompra">
                                <thead>
                                    <tr>
                                        <th style="width:35%">Producto</th>
                                        <th style="width:10%">Cantidad</th>
                                        <th style="width:15%">P/U (Bs)</th>
                                        <th style="width:15%">P/U (USD)</th>
                                        <th style="width:15%">Subtotal (Bs)</th>
                                        <th style="width:10%">Acción</th>
                                    </tr>
                                </thead>
                                <tbody id="detalleProductos">
                                    </tbody>
                            </table>
                            <button type="button" class="btn btn-default pull-right" id="btnAgregarProducto">Agregar Producto</button>
                        </div>
                        <hr>

                        <div class="row">
                            <div class="col-xs-6 pull-right">
                                <table class="table">
                                    <tr>
                                        <th style="width:50%">TOTAL (BS):</th>
                                        <td><input type="text" class="form-control input-lg" id="total_display_bs" value="0.00" readonly></td>
                                    </tr>
                                    <tr>
                                        <th style="width:50%">TOTAL (USD):</th>
                                        <td><input type="text" class="form-control input-lg" id="total_display_usd" value="0.00" readonly></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        
                        <input type="hidden" name="total_compra_bs" id="total_compra_bs_input">
                        <input type="hidden" name="total_compra_usd" id="total_compra_usd_input">
                        <input type="hidden" name="listaProductosCompra" id="listaProductosCompra">

                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>
                    <button type="submit" class="btn btn-primary">Guardar Compra</button>
                </div>
            </form>

            <?php
            // 2. EJECUCIÓN DEL CONTROLADOR PARA PROCESAR EL FORMULARIO
            ControladorCompras::ctrCrearCompra();
            ?>

        </div>
    </div>
</div>

<script>

    // 1. OBTENER LAS OPCIONES DE PRODUCTO GENERADAS POR PHP
    const opcionesHTML = '<?php echo $opcionesProductosHTML; ?>'; 
    const tasaCambio = parseFloat(document.getElementById("tasa_cambio_usd").value) || 1; 

    // Lista global para almacenar los detalles de los productos para el JSON final
    let listaProductos = [];

    // =============================================
    // 2. FUNCIÓN PARA CALCULAR TOTALES Y ACTUALIZAR CAMPOS OCULTOS
    // =============================================
    function actualizarTotalesYJSON() {
        let totalBS = 0;
        listaProductos = [];

        document.querySelectorAll('#detalleProductos tr').forEach(row => {
            const selectProducto = row.querySelector('select');
            const inputCantidad = row.querySelector('input[name="cantidad[]"]');
            const inputPrecioBS = row.querySelector('.precio-bs');
            const inputPrecioUSD = row.querySelector('.precio-usd');
            const inputSubtotalBS = row.querySelector('.subtotal-bs');

            if (!selectProducto || !inputCantidad || !inputPrecioBS || !inputPrecioUSD) return;

            const codigo = selectProducto.value;
            const cantidad = parseFloat(inputCantidad.value) || 0;
            const precioBS = parseFloat(inputPrecioBS.value) || 0;
            
            const subtotalBS = cantidad * precioBS;
            inputSubtotalBS.value = subtotalBS.toFixed(2);

            totalBS += subtotalBS;

            if (codigo) {
                listaProductos.push({
                    "codigo_producto": codigo,
                    "cantidad": cantidad,
                    "precio_compra_unitario_bs": precioBS.toFixed(2),
                    "precio_compra_unitario_usd": (precioBS / tasaCambio).toFixed(2)
                });
            }
        });

        const totalUSD = totalBS / tasaCambio;

        document.getElementById("total_display_bs").value = totalBS.toFixed(2);
        document.getElementById("total_display_usd").value = totalUSD.toFixed(2);
        
        document.getElementById("total_compra_bs_input").value = totalBS.toFixed(2);
        document.getElementById("total_compra_usd_input").value = totalUSD.toFixed(2);
        document.getElementById("listaProductosCompra").value = JSON.stringify(listaProductos);
    }
    
    // =============================================
    // 3. FUNCIÓN PARA ENLAZAR EVENTOS A UNA NUEVA FILA
    // =============================================
    function actualizarEventosFila(fila) {
        
        const selectProducto = fila.querySelector('select');
        const inputCantidad = fila.querySelector('input[name="cantidad[]"]');
        const inputPrecioBS = fila.querySelector('.precio-bs');
        const inputPrecioUSD = fila.querySelector('.precio-usd');
        const btnEliminar = fila.querySelector('.btnEliminarFila');
        
        const sincronizarPrecios = (origen) => {
            const precioBS = parseFloat(inputPrecioBS.value) || 0;
            const precioUSD = parseFloat(inputPrecioUSD.value) || 0;

            if (origen === 'bs') {
                inputPrecioUSD.value = (precioBS / tasaCambio).toFixed(2);
            } else if (origen === 'usd') {
                inputPrecioBS.value = (precioUSD * tasaCambio).toFixed(2);
            }
            actualizarTotalesYJSON();
        };

        // Evento: Carga inicial de precio al seleccionar un producto
        selectProducto.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const precioCompraBS = selectedOption.getAttribute('data-precio-compra-bs') || 0;
            
            inputPrecioBS.value = parseFloat(precioCompraBS).toFixed(2);
            sincronizarPrecios('bs');
        });

        // Eventos: Cálculo al cambiar cantidad o precios
        inputCantidad.addEventListener('input', () => actualizarTotalesYJSON());
        inputPrecioBS.addEventListener('input', () => sincronizarPrecios('bs'));
        inputPrecioUSD.addEventListener('input', () => sincronizarPrecios('usd'));
        
        // Evento: Eliminar fila
        btnEliminar.addEventListener('click', function() {
            fila.remove();
            actualizarTotalesYJSON();
        });
    }

    // =============================================
    // 4. LÓGICA DE AGREGAR PRODUCTO
    // =============================================
    document.getElementById("btnAgregarProducto").addEventListener("click", function () {
        const fila = document.createElement("tr");

        fila.innerHTML = `
            <td>
                <select name="producto[]" class="form-control" required>
                    
                </select>
            </td>
            <td><input type="number" name="cantidad[]" class="form-control" value="1" min="1" required></td>
            <td><input type="number" name="precio_bs[]" class="form-control precio-bs" step="0.01" value="0.00" required></td>
            <td><input type="number" name="precio_usd[]" class="form-control precio-usd" step="0.01" value="0.00" required></td>
            <td><input type="text" class="form-control subtotal-bs" readonly value="0.00"></td>
            <td><button type="button" class="btn btn-danger btnEliminarFila">X</button></td>
        `;

        document.getElementById("detalleProductos").appendChild(fila);
        actualizarEventosFila(fila);
        actualizarTotalesYJSON();
    });
    
    // Cargar la primera fila al abrir el modal
    document.getElementById("btnAgregarProducto").click();

</script>