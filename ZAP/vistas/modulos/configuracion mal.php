<?php
ini_set('display_errors',1);
error_reporting(E_ALL);

$guardarConfiguracion = new ControladorConfiguracion();
$guardarConfiguracion->ctrEditarConfiguracion();
?>

<div class="content-wrapper">
  <section class="content-header">
    <h1>Configuración</h1>
    <ol class="breadcrumb">
      <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      <li class="active">Configuración</li>
    </ol>
  </section>

  <section class="content">
    <?php $configuracion = ControladorConfiguracion::ctrMostrarConfigracion(null,null); ?>

    <div class="custom-center-container">
      <div class="box box-primary custom-blue-border mb-4">
        <div class="box-header with-border">
          <h4 class="box-title">Datos Generales de la Empresa</h4>
        </div>
        <div class="box-body">
          <?php echo isset($alert) ? $alert : ''; ?>
          <form action="" method="post" class="p-3">
            <!-- Nombre -->
            <div class="form-group">
              <a><b>Nombre</b></a>
              <input type="hidden" name="key" value="<?=$configuracion["rif"]?>">
              <input type="text" name="nombre" class="form-control" value="<?=$configuracion["nombre"]?>" id="txtNombre" placeholder="Nombre de la Empresa" required minlength="3" maxlength="80" pattern="[a-zA-ZñÑáéíóúÁÉÍÓÚ0-9 ]+" oninput="validarNombre()" oninvalid="this.setCustomValidity('El nombre debe tener entre 3 y 80 caracteres y solo puede contener letras, números y espacios .')">
              <script>
                function validarNombre() {
                  const nombreInput = document.getElementById('txtNombre');
                  const nombreRegex = /^[a-zA-ZñÑáéíóúÁÉÍÓÚ0-9 ]+$/;
                  if (!nombreRegex.test(nombreInput.value)) {
                    nombreInput.setCustomValidity('El nombre debe contener solo letras, números y espacios.');
                  } else {
                    nombreInput.setCustomValidity('');
                  }
                }
              </script>
            </div>

            <!-- RIF -->
            <div class="form-group">
              <a><b>Rif</b></a>
              <input type="hidden" name="id" value="<?= $configuracion["id"] ?? '' ?>">
              <div class="row">
                <div class="col-md-3">
                  <select name="tipo_rif" class="form-control" required>
                    <option value="">Tipo</option>
                    <?php
                      $tipos = ['V', 'E', 'J', 'G', 'P'];
                      foreach ($tipos as $tipo) {
                        $selected = ($configuracion["tipo_rif"] ?? '') === $tipo ? 'selected' : '';
                        echo "<option value='$tipo' $selected>$tipo</option>";
                      }
                    ?>
                  </select>
                </div>
                <div class="col-md-9">
                  <input type="text" name="numero_rif" class="form-control" value="<?= $configuracion["num_rif"] ?? '' ?>" id="txtNumeroRif" placeholder="Número del Rif (9 dígitos)" required minlength="9" maxlength="9" pattern="[0-9]{9}" oninvalid="this.setCustomValidity('El número del RIF debe contener exactamente 9 dígitos.')" oninput="this.setCustomValidity('')">
                </div>
              </div>
            </div>

            <!-- Teléfono -->
            <div class="form-group">
              <a><b>Teléfono</b></a>
              <div style="display: flex; align-items: center;">
                <select name="prefijo_telefono" class="form-control" style="width: auto; margin-right: 5px;">
                  <option value="">Prefijo</option>
                  <?php
                    $prefijos = ['0272', '0414', '0424', '0412', '0416', '0426'];
                    foreach ($prefijos as $prefijo) {
                      $selected = ($configuracion['prefijo_telefono'] ?? '') === $prefijo ? 'selected' : '';
                      echo "<option value='$prefijo' $selected>$prefijo</option>";
                    }
                  ?>
                </select>
                <span style="margin-right: 5px;">-</span>
                <input type="text" name="numero_telefono" class="form-control" value="<?= $configuracion["numero_telefono"] ?? '' ?>" id="txtTelEmpresaResto" placeholder="numero del teléfono" required minlength="7" maxlength="7" pattern="[0-9]{7}" oninput="validarTelEmpresa()" oninvalid="this.setCustomValidity('El resto del teléfono solo puede contener 7 números sin caracteres especiales ni espacios.')">
              </div>
              <script>
                const prefijoTelInput = document.querySelector('select[name="prefijo_telefono"]');
                const restoTelInput = document.getElementById('txtTelEmpresaResto');
                function validarTelEmpresa() {
                  const resto = restoTelInput.value;
                  const regex = /^[0-9]{7}$/;
                  restoTelInput.setCustomValidity(regex.test(resto) ? '' : 'El resto del teléfono solo puede contener 7 números.');
                }
                prefijoTelInput.addEventListener('change', validarTelEmpresa);
              </script>
            </div>

            <!-- Dirección -->
            <div class="form-group">
              <a><b>Dirección</b></a>
              <input type="text" name="direccion" class="form-control" value="<?=$configuracion["direccion"]?>" id="txtDirEmpresa" placeholder="Dirección de la Empresa" required minlength="3" maxlength="80" pattern="[a-zA-ZñÑáéíóúÁÉÍÓÚ0-9 º#-]+" oninput="validarDirEmpresa()" oninvalid="this.setCustomValidity('La Dirección debe tener entre 3 y 80 caracteres y solo puede contener letras, números, espacios, y estos caracteres especiales(-º #) .')">
              <script>
                function validarDirEmpresa() {
                  const input = document.getElementById('txtDirEmpresa');
                  const regex = /^[a-zA-ZñÑáéíóúÁÉÍÓÚ0-9 º#-]+$/;
                  input.setCustomValidity(regex.test(input.value) ? '' : 'La Dirección contiene caracteres inválidos.');
                }
              </script>
            </div>
          </form>
        </div>
      </div>

      <div class="box box-primary custom-blue-border">
        <div class="box-header with-border">
          <h4 class="box-title">Configuración Económica</h4>
        </div>
        <div class="box-body">
          <form method="post">
            <div class="form-group">
              <a><b>Precio del dólar</b></a>
              <input type="number" step="any" name="precio_dolar" class="form-control" value="<?=$configuracion["precio_dolar"]?>" id="txtPrecioDolar" placeholder="37" required oninput="validatePrice(this)">
              <span id="error-message"></span>
              <script>
                function validatePrice(input) {
                  const price = input.value;
                  const errorMessage = document.getElementById('error-message');
                  errorMessage.textContent = (isNaN(price) || parseFloat(price) <= 0) ? 'Debe ser un número positivo.' : '';
                }
              </script>
            </div>
            <div class="form-group">
              <a href="historial-dolar" class="btn-lg btn-primary">
                <i class="fa fa-line-chart"></i> Historial del Precio del Dólar
              </a>
            </div>

            <div class="form-group">
              <a><b>Impuesto</b></a>
              <input type="number" step="any" name="impuesto" class="form-control" value="<?=$configuracion["impuesto"]?>" id="txtImpuesto" placeholder="Impuesto" required oninput="validateImpuesto(this)">
              <span id="mensaje-de-error"></span>
              <script>
                function validateImpuesto(input) {
                  const val = input.value;
                  const msg = document.getElementById('mensaje-de-error');
                  msg.textContent = (isNaN(val) || parseFloat(val) <= 0) ? 'Debe ser un número positivo.' : '';
                }
              </script>
            </div>

           

            <div class="form-group">
                  <button type="submit" class="btn btn-primary"><i ></i> Modificar Datos</button>
              
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>
</div>

<style>
html, body {
  height: 100%;
  margin: 0;
  background: #fff;
}

.wrapper {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
}

.content-wrapper {
  flex: 1;
  background-color: #fff;
  padding-bottom: 30px;
}

.custom-blue-border {
  border: 2px solid #3c8dbc;
}

.box.box-primary > .box-header {
  background-color: #3c8dbc;
  color: #fff;
  border-top-color: #3c8dbc;
}

.box.box-primary {
  border-top: none;
}

.custom-center-container {
  max-width: 800px;
  margin: 0 auto;
}

.custom-center-container .box {
  margin-bottom: 30px;
}
</style>
