<?php
ini_set('display_errors', 0); // Mantén esto en 1 para ver errores FATALES y PARSING en desarrollo
ini_set('display_startup_errors', 0); // También para errores al inicio
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING); // Ignora solo NOTICES y WARNINGS, muestra el resto

if ($_SESSION["perfil"] == "Especial") {
    echo '<script>window.location = "inicio";</script>';
    return;
}

$xml = ControladorVentas::ctrDescargarXML();

if ($xml) {
    rename($_GET["xml"] . ".xml", "xml/" . $_GET["xml"] . ".xml");
    echo '<a class="btn btn-block btn-success abrirXML" archivo="xml/' . $_GET["xml"] . '.xml" href="ventas">Se ha creado correctamente el archivo XML <span class="fa fa-times pull-right"></span></a>';
}

?>

<div class="content-wrapper">
  <section class="content-header">
    <h1>Administrar ventas</h1>
    <ol class="breadcrumb">
      <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      <li class="active">Administrar ventas</li>
    </ol>
  </section>

  <section class="content">
    <div class="box">
      <div class="box-header with-border">
        <a href="crear-venta">
          <button class="btn-lg btn-primary">Agregar venta</button>
        </a>

        <div class="pull-right">
            <button type="button" class="btn-lg btn-default" id="daterange-btn">
                <span>
                    <i class="fa fa-calendar"></i>
                    <?php
                    if (isset($_GET["fechaInicial"])) {
                        // Aquí se muestra el rango de fecha actual de la URL
                        echo $_GET["fechaInicial"] . " - " . $_GET["fechaFinal"];
                    } else {
                        echo 'Rango de fecha';
                    }
                    ?>
                </span>
                <i class="fa fa-caret-down"></i>
            </button>
            
            
            <a href="ventas" class="btn btn-lg btn-primary" title="Actualizar tabla" style="padding: 10px 20px;">
              <i class="fa fa-refresh"></i>
            </a>    
            
            <?php
            if ($_SESSION["perfil"] == "Administrador") {
                echo '<button type="button" class="btn-lg btn-primary" id="generarReporteRangoPDF">
                          <i class="fa fa-file-pdf-o"></i> Reporte por Rango de Fecha
                      </button>';
            }
            ?>
            </div>
        </div>

      <div class="box-body">
        <table class="table table-bordered table-striped dt-responsive tablas" width="100%">
          <thead>
            <tr>
              <th style="width:10px">#</th>
              <th>Código Recibo</th>
              <th>Cliente</th>
              <th>Vendedor</th>
              <th>Forma de pago</th>
              <th>Neto</th>
              <th>Total</th>
              <th>Fecha</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php

            $fechaInicial = $_GET["fechaInicial"] ?? null;
            $fechaFinal = $_GET["fechaFinal"] ?? null;

            $respuesta = ControladorVentas::ctrRangoFechasVentas($fechaInicial, $fechaFinal);

            foreach ($respuesta as $key => $value) {
                $fecha = (new DateTime($value["fecha"]))->format("d/m/Y H:i");
                //echo "<pre>";
                //var_dump($value);
                //echo "</pre>";
                $cliente = ControladorClientes::ctrMostrarClientesDosClaves("tipo_ced", "num_ced", $value["tipo_ced_cliente"], $value["num_ced_cliente"]);


                $vendedor = ControladorEmpleados::ctrMostrarEmpleados("cedula", $value["vendedor"]);

                echo '<tr>
                        <td>' . ($key + 1) . '</td>
                        <td>' . $value["factura"] . '</td>
                        <td>' . ucwords($cliente["nombre"] . " " . $cliente["apellido"]) . '</td>
                        <td>' . ucwords($vendedor["nombre"] . " " . $vendedor["apellido"]) . '</td>
                       
                        <td>' . $value["metodo_pago"] . '</td>
                        <td>Bs ' . number_format($value["pago"], 2) . '</td>
                        <td>Bs ' . number_format($value["total"], 2) . '</td>
                        <td>' . $fecha . '</td>
                        <td>
                          <div class="btn-group">
                            <button class="btn-lg btn-info btnImprimirFactura" facturaVenta="' . $value["factura"] . '"><i class="fa fa-print"></i></button>
                            <button class="btn-lg btn-primary btnVerDetalleVenta" data-factura="' . $value["factura"] . '" title="Detalles de Venta"><i class="fa fa-list"></i></button>';

                if ($_SESSION["perfil"] == "Administrador") {
                 //   echo '<button class="btn-lg hidden btn-warning btnEditarVenta" idVenta="' . $value["factura"] . '"><i class="fa fa-pencil"></i></button>
                        //  <button class="btn-lg btn-danger btnEliminarVenta" facturaVenta="' . $value["factura"] . '"><i class="fa fa-times"></i></button>';
                }

                echo '</div></td></tr>';
            }

            ?>
          </tbody>
        </table>

        <?php
        $eliminarVenta = new ControladorVentas();
        $eliminarVenta->ctrEliminarVenta();
        ?>

      </div>
    </div>
  </section>
</div>

<div id="modalDetalleVenta" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

      <div class="modal-header" style="background:#3c8dbc; color:white">
        <h4 class="modal-title">Detalle de Venta <span id="detalleVentaFactura"></span></h4>
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
                <th>Precio Unitario (Bs)</th>
                <th class="text-right">Neto (Bs)</th>
              </tr>
            </thead>
            <tbody id="tablaDetalleVentaBody"></tbody>
            <tfoot>
              <tr>
                <td colspan="4"></td>
                <td class="text-right"><strong>Neto Total:</strong></td>
                <td id="detalleNetoTotal" class="text-right"></td>
              </tr>
              <tr>
                <td colspan="4"></td>
                <td class="text-right"><strong>Impuesto Total:</strong></td>
                <td id="detalleImpuestoTotal" class="text-right"></td>
              </tr>
              <tr>
                <td colspan="4"></td>
                <td class="text-right"><strong>Total General:</strong></td>
                <td id="detalleTotalGeneral" class="text-right"></td>
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
    // ... (Tu script existente para btnVerDetalleVenta va aquí) ...
    $(document).on("click", ".btnVerDetalleVenta", function () {
      var facturaCodigo = $(this).data("factura");
      $("#detalleVentaFactura").text("Recibo N° " + facturaCodigo);
      $("#tablaDetalleVentaBody").empty();
      $("#detalleNetoTotal, #detalleImpuestoTotal, #detalleTotalGeneral").text("");

      var datos = new FormData();
      datos.append("codigoFacturaDetalle", facturaCodigo);

      $.ajax({
        url: "ajax/ventas.ajax.php",
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
              let precio_unitario = parseFloat(producto.precio_unitario ?? 0).toFixed(2);
              let total_linea = parseFloat(producto.precio_total_en_linea ?? 0).toFixed(2);

              html += `
                <tr>
                  <td>${i + 1}</td>
                  <td>${codigo}</td>
                  <td>${descripcion}</td>
                  <td>${cantidad}</td>
                  <td>${precio_unitario}</td>
                  <td class="text-right">${total_linea}</td>
                </tr>`;
            });
          } else {
            html = '<tr><td colspan="6">Esta venta no tiene productos registrados.</td></tr>';
          }

          $("#tablaDetalleVentaBody").html(html);
          $("#detalleNetoTotal").text(parseFloat(respuesta.netoTotal ?? 0).toFixed(2) + " Bs");
          $("#detalleImpuestoTotal").text(parseFloat(respuesta.impuestoTotal ?? 0).toFixed(2) + " Bs");
          $("#detalleTotalGeneral").text(parseFloat(respuesta.totalGeneral ?? 0).toFixed(2) + " Bs");

          $('#modalDetalleVenta').modal('show');
        },
        error: function (jqXHR, textStatus, errorThrown) {
          console.error("Error AJAX:", textStatus, errorThrown);
          $("#tablaDetalleVentaBody").html('<tr><td colspan="6">Error al cargar los detalles.</td></tr>');
          $('#modalDetalleVenta').modal('show');
        }
      });
    });

    // --- NUEVO SCRIPT PARA GENERAR REPORTE POR RANGO DE FECHA ---
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

        // Construir la URL del reporte
        // ASEGÚRATE DE QUE LA RUTA A 'plantilla_rango_fecha.php' SEA CORRECTA DESDE LA RAÍZ DE TU PROYECTO
        var urlReporte = "extensiones/fpdf/plantilla_rango_fecha.php?fechaInicial=" + fechaInicial + "&fechaFinal=" + fechaFinal;

        // Abrir el reporte en una nueva pestaña
        window.open(urlReporte, "_blank");
    });
    // --- FIN NUEVO SCRIPT ---

});
</script>
