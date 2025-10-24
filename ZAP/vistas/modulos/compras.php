


   
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
    </ol><?php
// Asegurarse de que el perfil es válido
if (isset($_SESSION["perfil"]) && $_SESSION["perfil"] == "Especial") {
    echo '<script>window.location = "inicio";</script>';
    return;
}

// ====================================================================
// CRÍTICO: Cargar SOLO los controladores necesarios
// ====================================================================
require_once "controladores/proveedor.controlador.php";
require_once "controladores/productos.controlador.php";
require_once "controladores/compras.controlador.php"; 
require_once "controladores/configuracion.controlador.php"; 
?>
<?php
$item_prod = null;
$valor_prod = null;
$orden_prod = "descripcion"; 

// 1. Llama al controlador de productos y genera el array de datos
$productos_data = ControladorProductos::ctrMostrarProductos($item_prod, $valor_prod, $orden_prod);
$opciones_productos_html = '<option value="">Seleccionar producto</option>';

// 2. Generar las opciones HTML
if (is_array($productos_data) && !empty($productos_data)) {
    foreach ($productos_data as $producto) {
        $texto_visible = $producto["codigo"] . ' - ' . html_entity_decode($producto["descripcion"], ENT_QUOTES, 'UTF-8');
        
        $descripcion_escapada = addslashes(html_entity_decode($producto["descripcion"], ENT_QUOTES, 'UTF-8'));

        $opciones_productos_html .= '<option
            value="'.$producto["codigo"].'"
            data-nombre="'.$descripcion_escapada.'"
            data-precio-compra-usd="'.$producto["precio_compra"].'" // ✅ CORRECTO: Precio base en Dólares

        >
            '.$texto_visible.'
        </option>';
    }
}
?>

  </section>

  <section class="content">
    <div class="box">
      <div class="box-header with-border">
      <button class="btn-lg btn-primary" onclick="window.location.href = 'extensiones/fpdf/plantillacompras.php';">
          <i class="fa fa-file-pdf-o"></i> Reporte de Compras
        </button>
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
            <th>Proveedor</th>
            <th>RIF</th>
            <th>Factura</th>
            <th>Total Bs</th>
            <th>Total $</th>
            <th>Tasa Cambio</th>
            <th>Observaciones</th>
            <th>Fecha Compra</th> 
            <th>Acciones</th> </tr>
    </thead>
    <tbody>
        <?php
        // =================================================================================
        // ✅ LÓGICA DE FILTRADO POR RANGO DE FECHAS CORREGIDA
        // =================================================================================

        $fechaInicial = $_GET["fechaInicial"] ?? null;
        $fechaFinal = $_GET["fechaFinal"] ?? null;

        if (!empty($fechaInicial) && !empty($fechaFinal)) {
            // Asume que este método existe y filtra por fecha
            $compras = ControladorCompras::ctrRangoFechasCompras($fechaInicial, $fechaFinal);
        } else {
            // Carga todas las compras si no hay filtro de fechas en la URL
            $compras = ControladorCompras::ctrMostrarCompras(null, null);
        }

        foreach ($compras as $key => $value) {

            // Construir RIF completo
            $rifProveedor = $value["tipo_rif"] . "-" . $value["num_rif"];
            $nombreProveedor = $value["nombre"] ?? 'N/A'; 

            // Formato de totales
            $totalBs = number_format($value["total_compra_bs"], 2, ',', '.');
            $totalUsd = number_format($value["total_compra_usd"], 2, ',', '.');
            $tasaCambioDisplay = number_format($value["tasa_cambio_usd"], 4, ',', '.');
            
            // Formato de fecha y hora
            $fechaCompra = date("d-m-Y H:i:s", strtotime($value["fecha_compra"]));


            echo ' <tr>
                <td>' . ($key + 1) . '</td>
                <td>' . ucwords($nombreProveedor) . '</td>
                <td>' . $rifProveedor . '</td>
                <td>' . $value["numero_factura_proveedor"] . '</td>
                <td>Bs. ' . $totalBs . '</td>
                <td>$. ' . $totalUsd . '</td>
                <td>' . $tasaCambioDisplay . '</td>
                <td>' . $value["observaciones"] . '</td>
                
                <td>' . $fechaCompra . '</td> 
                
                <td>
                    <div class="btn-group">
                        <button class="btn-lg btn-primary btnVerDetalleVenta" idCompra="' . $value["id_compra"] . '" title="Detalles de Compra"><i class="fa fa-list"></i></button>';
                        

            // Botón de eliminar solo si el perfil es Administrador
           // //  $buttons = ($_SESSION["perfil"] == "Administrador")
              //   ? '<button class="btn-lg btn-danger btnEliminarCompra" idCompra="' . $value["id_compra"] . '"><i class="fa fa-times"></i></button>'
               //  : '';

            echo $buttons;
            echo '
                    </div>  
                </td>
            </tr>';
        }
        ?>
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
        <input 
            type="text" 
            class="form-control" 
            name="numero_factura_proveedor" 
            id="numero_factura_proveedor" 
            maxlength="15" 
            pattern="[0-9A-Za-z-]+" 
            title="Solo se permiten números, letras y guiones (ej: 00-12345678)"
            required
        >
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
                        <input type="hidden" name="listaProductos" id="listaProductos">

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
         
            const inputPrecioUSD = row.querySelector('.precio-usd');
            const inputPrecioBS = row.querySelector('.precio-bs');
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
        document.getElementById("listaProductos").value = JSON.stringify(listaProductos);
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
        // 🚨 CAMBIO 1: Leer el nuevo atributo de Dólares
        const precioCompraUSD = selectedOption.getAttribute('data-precio-compra-usd') || 0;
        
        // 🚨 CAMBIO 2: Asignamos el valor al input de USD
        inputPrecioUSD.value = parseFloat(precioCompraUSD).toFixed(2);
        
        // 🚨 CAMBIO 3: Sincronizamos a partir del USD, lo que calcula el BS
        sincronizarPrecios('usd'); 
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
                 <?php echo $opciones_productos_html; ?>   
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

<div id="modalDetalleVenta" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

      <div class="modal-header" style="background:#3c8dbc; color:white">
        <h4 class="modal-title">Detalle de Compra <span id="detalleVentaFactura"></span></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <div class="modal-body">
        <div class="box-body">
           <table id="tablaDetalleVenta" class="table table-bordered table-striped dt-responsive" width="100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Código</th>
                                <th>Descripción</th>
                                <th>Cantidad</th>
                                <th>P/U (USD)</th>
                                <th>P/U (Bs)</th>
                                <th class="text-right">Total Línea (Bs)</th>
                            </tr>
                        </thead>
                        <tbody id="tablaDetalleVentaBody"></tbody>
                        <tfoot>
                            <tr>
                                <td colspan="5"></td>
                                <td class="text-right"><strong>TOTAL (BS):</strong></td>
                                <td id="detalleNetoTotal" class="text-right"></td>
                            </tr>
                            <tr>
                                <td colspan="5"></td>
                                <td class="text-right"><strong>TOTAL (USD):</strong></td>
                                <td id="detalleImpuestoTotal" class="text-right"></td>
                            </tr>
                        </tfoot>
                    </table>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
      </div>

    </div>
  </div>
</div>

<script>
$(document).ready(function () {
    
     // =====================================================================
    // LÓGICA PARA MOSTRAR DETALLE DE COMPRA (AJAX)
    // =====================================================================
    $(document).on("click", ".btnVerDetalleVenta", function () {

        var idCompra = $(this).attr("idCompra");
    // 💡 CAMBIO CRÍTICO: Obtener el número de factura de la columna adyacente en la tabla
    var fila = $(this).closest("tr");
    // La columna de la factura es la 4ª columna (índice 3, contando desde el 0)
    // <th>Factura</th> es la 4ª columna. El .text() obtiene el contenido.
    var numeroFactura = fila.find("td:eq(3)").text().trim();

        // 2. LIMPIEZA Y SETUP DEL MODAL
        $("#detalleVentaFactura").text("Factura N° " + numeroFactura);
        $("#tablaDetalleVentaBody").empty();
        $("#detalleNetoTotal, #detalleImpuestoTotal, #detalleTotalGeneral").text("");

        // 3. PREPARAR DATOS AJAX
        var datos = new FormData();
        datos.append("idCompraDetalle", idCompra);

        // 4. PETICIÓN AJAX
        $.ajax({
            url: "ajax/compras.ajax.php",
            method: "POST",
            data: datos,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (respuesta) {
                let html = "";

                if (respuesta && respuesta.productos && respuesta.productos.length > 0) {
                    $.each(respuesta.productos, function (i, producto) {
                        let codigo = producto.codigo ?? "Sin código";
                        let descripcion = producto.descripcion ?? "Sin descripción";
                        let cantidad = producto.cantidad ?? "0";

                        let precio_unitario_usd = parseFloat(producto.precio_compra_unitario_usd ?? 0).toFixed(2);
                        let precio_unitario_bs = parseFloat(producto.precio_unitario ?? 0).toFixed(2);
                        let total_linea = parseFloat(producto.precio_total_en_linea ?? 0).toFixed(2);

                        html += `
                            <tr>
                                <td>${i + 1}</td>
                                <td>${codigo}</td>
                                <td>${descripcion}</td>
                                <td>${cantidad}</td>
                                <td>$. ${precio_unitario_usd}</td>
                                <td>Bs. ${precio_unitario_bs}</td>
                                <td class="text-right">Bs. ${total_linea}</td>
                            </tr>`;
                    });
                } else {
                    html = '<tr><td colspan="7">Esta compra no tiene productos registrados.</td></tr>';
                }

                // 5. Mostrar datos de los productos y totales
                $("#tablaDetalleVentaBody").html(html);

                $("#detalleNetoTotal").text(parseFloat(respuesta.total_compra_bs ?? 0).toFixed(2) + " Bs");
                $("#detalleImpuestoTotal").text(parseFloat(respuesta.total_compra_usd ?? 0).toFixed(2) + " $");

                $('#modalDetalleVenta').modal('show');
            },
            // ... manejo de errores ...
        });
    });

    // =====================================================================
    // LÓGICA PARA GENERAR REPORTE DE COMPRAS POR RANGO DE FECHA
    // =====================================================================
    $("#generarReporteRangoPDF").on("click", function() {
        var fechaInicial = "<?php echo isset($_GET["fechaInicial"]) ? $_GET["fechaInicial"] : ''; ?>";
        var fechaFinal = "<?php echo isset($_GET["fechaFinal"]) ? $_GET["fechaFinal"] : ''; ?>";

        // Si no hay fechas en la URL, se usan las del mes actual por defecto
        if (!fechaInicial || !fechaFinal) {
            var today = new Date();
            var firstDayOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
            var lastDayOfMonth = new Date(today.getFullYear(), today.getMonth() + 1, 0);

            var formatDate = function(date) {
                var d = new Date(date),
                    month = '' + (d.getMonth() + 1),
                    day = '' + d.getDate(),
                    year = d.getFullYear();

                if (month.length < 2) month = '0' + month;
                if (day.length < 2) day = '0' + day;

                return [year, month, day].join('-');
            };

            fechaInicial = formatDate(firstDayOfMonth);
            fechaFinal = formatDate(lastDayOfMonth);
        }

        // Construir la URL del reporte de COMPRAS
        // ⚠️ IMPORTANTE: Se asume que el reporte para COMPRAS se llama diferente
        // para evitar conflictos con reportes de Venta (ej: plantilla_rango_fecha_compras.php)
        var urlReporte = "extensiones/fpdf/plantilla_rango_fecha_compras.php?fechaInicial=" + fechaInicial + "&fechaFinal=" + fechaFinal;

        // Abrir el reporte en una nueva pestaña
        window.open(urlReporte, "_blank");
    });
    // --- FIN LÓGICA DE REPORTE ---

});
</script>
<script>
// ... (Tus funciones actualizarTotalesYJSON, actualizarEventosFila, etc., deben estar aquí arriba) ...

$(document).ready(function () {
    
    // =====================================================================
    // LÓGICA DE VALIDACIÓN DE FACTURA Y ENVÍO DEL FORMULARIO
    // =====================================================================
    $("#btnGuardarCompra").on("click", function(e) {
        
        // 1. OBTENER VALORES y VALIDAR CAMPOS BÁSICOS
        const facturaInput = document.getElementById("numero_factura_proveedor");
        const numeroFactura = facturaInput.value.trim();
        const proveedorRif = $("#nuevoProveedor").val(); 

        // Validación de campos obligatorios (proveedor, factura, y al menos un producto)
        if (proveedorRif === "" || numeroFactura === "" || listaProductos.length === 0 || parseFloat($("#total_compra_bs_input").val()) <= 0) {
             swal.fire({
                icon: "warning",
                title: "Campos Incompletos",
                text: "Asegúrate de seleccionar un proveedor, ingresar un número de factura y agregar al menos un producto válido.",
            });
            return;
        }

        // Validación de Formato Básico (letras, números y guiones)
        const formatoValido = /^[0-9A-Za-z-]+$/;
        if (!formatoValido.test(numeroFactura)) {
            swal.fire({
                icon: "warning",
                title: "Error de Formato de Factura",
                text: "El N° Factura solo debe contener números, letras y guiones (ej: 00-12345678).",
            });
            facturaInput.focus();
            return;
        }
        
        // 2. VALIDACIÓN DE DUPLICIDAD (AJAX)
        // Se envía el número de factura y el RIF del proveedor para verificar la unicidad
      

    // --- FIN LÓGICA DE VALIDACIÓN ---

    // ... (El resto de tu código JS: Detalle, Reportes, etc.) ...
});
</script>
<script>

</script>

<script>
    $("#logout").click(function(e) {
        e.preventDefault();

        swal({
            type: "warning", // Changed to warning for a more cautious tone
            title: "¿Estás seguro de salir?",
            text: "Esta acción cerrará tu sesión.",
            showCancelButton: true,
           confirmButtonColor: "#3498db", // Color azul
            confirmButtonText: "Sí, salir",
            cancelButtonText: "Cancelar",
            closeOnConfirm: false
        }).then(function(result) {
            if (result.value) {
                window.location = "salir";
            }
        });
    });
</script>
