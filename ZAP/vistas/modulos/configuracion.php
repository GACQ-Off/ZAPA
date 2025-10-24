<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$guardarConfiguracion = new ControladorConfiguracion();
$guardarConfiguracion->ctrEditarConfiguracion();

$configuracion = ControladorConfiguracion::ctrMostrarConfigracion(null, null);
?>

<style>
  .contenedor-config {
    background: #ffffff;
    border: 1px solid #d2d6de;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    padding: 25px;
    margin-bottom: 30px;
  }

  .contenedor-config h3 {
    margin-top: 0;
    font-weight: bold;
    color: #3c8dbc;
    border-bottom: 1px solid #d2d6de;
    padding-bottom: 10px;
    margin-bottom: 20px;
  }

  .btn-azul {
    background-color: #3c8dbc;
    border-color: #367fa9;
    color: white;
  }

  .btn-azul:hover {
    background-color: #367fa9;
    border-color: #2c6b96;
  }

  .form-group label {
    font-weight: 600;
  }
</style>

<div class="content-wrapper">
  <section class="content-header">
    <h1>Configuración</h1>
    <ol class="breadcrumb">
      <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      <li class="active">Configuración</li>
    </ol>
  </section>

  <section class="content">
    <div class="row justify-content-center">
      <div class="col-md-6 col-md-offset-3">

        <form method="post">

          <!-- Caja 1: Datos Generales -->
          <div class="contenedor-config">
            <h3>Datos Generales de la Empresa</h3>

            <!-- ID oculto -->
            <input type="hidden" name="id" value="<?= $configuracion["id"] ?? '' ?>">

            <!-- Nombre -->
            <div class="form-group">
              <label>Nombre</label>
              <input type="text" name="nombre" class="form-control" value="<?= $configuracion["nombre"] ?>" required>
            </div>

            <!-- RIF -->
            <div class="form-group">
              <label>RIF</label>
              <div class="row">
                <div class="col-xs-3">
                  <select name="tipo_rif" class="form-control" required>
                    <?php
                    $tipos = ["J", "V", "E", "G", "P"];
                    foreach ($tipos as $tipo) {
                      echo '<option value="'.$tipo.'" '.($configuracion["tipo_rif"] == $tipo ? 'selected' : '').'>'.$tipo.'</option>';
                    }
                    ?>
                  </select>
                </div>
                <div class="col-xs-9">
                  <input type="text" name="numero_rif" class="form-control" value="<?= $configuracion["num_rif"] ?>" required pattern="[0-9]{9}" maxlength="9">
                </div>
              </div>
            </div>

            <!-- Teléfono -->
            <div class="form-group">
              <label>Teléfono</label>
              <div class="row">
                <div class="col-xs-4">
                  <select name="prefijo_telefono" class="form-control" required>
                    <?php
                    $prefijos = ["0272", "0414", "0424", "0412", "0416", "0426"];
                    foreach ($prefijos as $prefijo) {
                      echo '<option value="'.$prefijo.'" '.($configuracion["prefijo_telefono"] == $prefijo ? 'selected' : '').'>'.$prefijo.'</option>';
                    }
                    ?>
                  </select>
                </div>
                <div class="col-xs-8">
                  <input type="text" name="numero_telefono" class="form-control" value="<?= $configuracion["numero_telefono"] ?>" required pattern="[0-9]{7}" maxlength="7">
                </div>
              </div>
            </div>

            <!-- Dirección -->
            <div class="form-group">
              <label>Dirección</label>
              <input type="text" name="direccion" class="form-control" value="<?= $configuracion["direccion"] ?>" required>
            </div>
          </div>

          <!-- Caja 2: Configuración Económica -->
          <div class="contenedor-config">
            <h3>Configuración Económica</h3>

            <!-- Precio Dólar -->
            <div class="form-group">
              <label>Precio del dólar</label>
              <input type="number" name="precio_dolar" step="any" class="form-control" value="<?= $configuracion["precio_dolar"] ?>" required>

            </div>
              <div class="form-group text-center">
              
              <button type="submit" class="btn-lg btn-azul">
               <a href="historial-dolar" class="btn-lg btn-azul">
                <i class="fa fa-line-chart"></i> Historial del Precio del Dólar
            </a>
              </button>
            </div>
            

            <!-- Impuesto -->
            <div class="form-group">
              <label>Impuesto (%)</label>
              <input type="number" name="impuesto" step="any" class="form-control" value="<?= $configuracion["impuesto"] ?>" required>
            </div>

            <!-- Historial y Guardar -->
            <div class="form-group text-center">
              
              <button type="submit" class="btn-lg btn-azul">
                <i class="fa fa-save"></i> Guardar Cambios
              </button>
            </div>

          </div>

        </form>
      </div>
    </div>
  </section>
</div>
