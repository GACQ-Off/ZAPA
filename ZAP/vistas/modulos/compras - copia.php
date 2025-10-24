<?php
if ($_SESSION["perfil"] == "Especial") {
    echo '<script>window.location = "inicio";</script>';
    return;
}
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


<!-- Modal Agregar Compra -->
<div class="modal fade" id="modalAgregarCompra" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-xl" role="document">
    <form method="post" id="formAgregarCompra">
      <div class="modal-content">
        <div class="modal-header bg-primary">
          <h5 class="modal-title text-white">Agregar Compra</h5>
          <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
        </div>

        <div class="modal-body">
          <!-- RIF Proveedor -->
          <div class="form-group">
            <label for="nuevoProveedor">Proveedor</label>
            <select class="form-control" id="nuevoProveedor" name="nuevoProveedor" required>
              <option value="">Seleccionar proveedor</option>

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


          <!-- Número de Factura -->
          <div class="form-group">
            <label>N° de Factura del Proveedor</label>
            <input type="text" name="numero_factura_proveedor" class="form-control">
          </div>
          <?php
            $config = ControladorConfiguracion::ctrMostrarConfigracion(null, null);
            $tasaCambio = $config["precio_dolar"];
          ?>

          <!-- Tasa de cambio (readonly) -->
          <div class="form-group">
            <label>Tasa de Cambio (USD/BS)</label>
            <input type="text" id="tasa_cambio_usd" name="tasa_cambio_usd" class="form-control" 
                   value="<?= $tasaCambio ?>" readonly>
          </div>

          <!-- Tabla de productos -->
          <table class="table table-bordered" id="tablaDetalleCompra">
            <thead>
              <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio Unitario (Bs)</th>
                <th>Precio Unitario (USD)</th>
                <th>Subtotal (Bs)</th>
                <th>Acción</th>
              </tr>
            </thead>
            <tbody id="detalleProductos">
              <!-- Filas dinámicas -->
            </tbody>
          </table>

          <button type="button" class="btn btn-secondary" id="btnAgregarProducto">+ Agregar Producto</button>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Guardar Compra</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
        </div>
      </div>
    </form>
  </div>
</div>

<script>
  const tasaCambio = parseFloat(document.getElementById("tasa_cambio_usd").value);

  document.getElementById("btnAgregarProducto").addEventListener("click", function () {
    const fila = document.createElement("tr");

    fila.innerHTML = `
      <td>
        <select name="producto[]" class="form-control">
          <!-- Aquí van tus opciones dinámicas con PHP si lo deseas -->
        </select>
      </td>
      <td><input type="number" name="cantidad[]" class="form-control" value="1" min="1"></td>
      <td><input type="number" name="precio_bs[]" class="form-control precio-bs" step="0.01"></td>
      <td><input type="number" name="precio_usd[]" class="form-control precio-usd" step="0.01"></td>
      <td><input type="text" class="form-control subtotal-bs" readonly></td>
      <td><button type="button" class="btn btn-danger btnEliminarFila">X</button></td>
    `;

    document.getElementById("detalleProductos").appendChild(fila);
    actualizarEventosFila(fila);
  });

  function actualizarEventosFila(fila) {
    const precioBsInput = fila.querySelector(".precio-bs");
    const precioUsdInput = fila.querySelector(".precio-usd");
    const cantidadInput = fila.querySelector("input[name='cantidad[]']");
    const subtotalBs = fila.querySelector(".subtotal-bs");

    precioBsInput.addEventListener("input", function () {
      const precioBs = parseFloat(precioBsInput.value) || 0;
      const cantidad = parseInt(cantidadInput.value) || 0;
      if (tasaCambio > 0) {
        precioUsdInput.value = (precioBs / tasaCambio).toFixed(2);
        subtotalBs.value = (precioBs * cantidad).toFixed(2);
      }
    });

    precioUsdInput.addEventListener("input", function () {
      const precioUsd = parseFloat(precioUsdInput.value) || 0;
      const cantidad = parseInt(cantidadInput.value) || 0;
      precioBsInput.value = (precioUsd * tasaCambio).toFixed(2);
      subtotalBs.value = (precioUsd * tasaCambio * cantidad).toFixed(2);
    });

    cantidadInput.addEventListener("input", function () {
      const precioBs = parseFloat(precioBsInput.value) || 0;
      const cantidad = parseInt(cantidadInput.value) || 0;
      subtotalBs.value = (precioBs * cantidad).toFixed(2);
    });

    fila.querySelector(".btnEliminarFila").addEventListener("click", function () {
      fila.remove();
    });
  }
</script>
