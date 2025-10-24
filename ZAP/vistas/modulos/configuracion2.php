 <?php
ini_set('display_errors',1);
error_reporting(E_ALL);

$guardarConfiguracion = new ControladorConfiguracion();
$guardarConfiguracion->ctrEditarConfiguracion();


?>
 
 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Configuración
        
      </h1>
      <ol class="breadcrumb">
        <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li class="active">Configuración</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
    <?php
      $configuracion = ControladorConfiguracion::ctrMostrarConfigracion(null,null);

    ?>
      <!-- Default box -->
      <div class="row">
    <div class="col-md-6 mx-auto">
        <div class="card">
            <div class="card-header card-header-primary">
                <h4 class="card-title">Datos de la Empresa</h4>
            </div>
            <div class="card-body">
                <?php echo isset($alert) ? $alert : ''; ?>
                <form action="" method="post" class="p-3">
                    <div class="form-group">
                          <a><b>Nombre</b></a>
                        <input type="hidden" name="key" value="<?=$configuracion["rif"]?>">
                        <input type="text" name="nombre" class="form-control" value="<?=$configuracion["nombre"]?>" id="txtNombre" placeholder="Nombre de la Empresa" required minlength="3" maxlength="80" pattern="[a-zA-ZñÑáéíóúÁÉÍÓÚ0-9 ]+" 
                        oninput="validarNombre()" oninvalid="this.setCustomValidity('El nombre debe tener entre 3 y 80 caracteres y solo puede contener letras, números y espacios .')">
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

                    

                    <div class="form-group">
    <a><b>Rif</b></a>
    <input type="hidden" name="id" value="">
    <div style="display: flex; align-items: center;">
        <select name="rif_type" class="form-control" style="width: auto; margin-right: 5px;" required>
            <option value="" disabled selected>Tipo</option>
            <option value="G" <?= (isset($configuracion["rif"]) && strtoupper(substr($configuracion["rif"], 0, 1)) === 'G') ? 'selected' : '' ?>>G</option>
            <option value="J" <?= (isset($configuracion["rif"]) && strtoupper(substr($configuracion["rif"], 0, 1)) === 'J') ? 'selected' : '' ?>>J</option>
            <option value="V" <?= (isset($configuracion["rif"]) && strtoupper(substr($configuracion["rif"], 0, 1)) === 'V') ? 'selected' : '' ?>>V</option>
            <option value="P" <?= (isset($configuracion["rif"]) && strtoupper(substr($configuracion["rif"], 0, 1)) === 'P') ? 'selected' : '' ?>>P</option>
            <option value="C" <?= (isset($configuracion["rif"]) && strtoupper(substr($configuracion["rif"], 0, 1)) === 'C') ? 'selected' : '' ?>>C</option>
        </select>
        -
        <input type="text" name="rif_number" class="form-control" value="<?= isset($configuracion["rif"]) ? substr($configuracion["rif"], 2) : '' ?>" id="txtRifNumber" placeholder="Número de Rif (9 dígitos)" required class="form-control" minlength="9" maxlength="9" pattern="[0-9]{9}"
               oninput="validarRif()" oninvalid="this.setCustomValidity('El número de Rif solo puede contener numeros del 0 al 9.')">
    </div>
    <script>
        function validarRif() {
            const rifType = document.querySelector('select[name="rif_type"]');
            const rifNumberInput = document.getElementById('txtRifNumber');
            const rifNumber = rifNumberInput.value;

            if (!/^[0-9]{9}$/.test(rifNumber)) {
                rifNumberInput.setCustomValidity('El número de Rif solo puede contener numeros del 0 al 9.');
            } else {
                rifNumberInput.setCustomValidity('');
            }
        }

        // Function to update the hidden input with the combined RIF value on form submission (optional)
        document.querySelector('form').addEventListener('submit', function() {
            const rifType = document.querySelector('select[name="rif_type"]').value;
            const rifNumber = document.querySelector('input[name="rif_number"]').value;
            document.querySelector('input[name="rif"]').value = rifType + '-' + rifNumber;
        });
    </script>
</div>
                   

                    <div class="form-group">
                          <a><b>Teléfono</b></a>
                        <input type="text" name="telefono" class="form-control" value="<?=$configuracion["telefono"]?>" id="txtTelEmpresa" placeholder="teléfono de la Empresa" required minlength="11" maxlength="11" pattern="[0-9]{11}" 
                        oninput="validarTelEmpresa()" oninvalid="this.setCustomValidity('El telefono solo puede contener numeros sin caracteres especiales ni espacios ejemplo:(04141074586),(02726521412).')">
                        <script>
                            function validarTelEmpresa() {
                                const telEmpresaInput = document.getElementById('txtTelEmpresa');
                                const telEmpresaRegex = /^[0-9]{11}$/;

                                if (!telEmpresaRegex.test(telEmpresaInput.value)) {
                                    telEmpresaInput.setCustomValidity('El telefono solo puede contener numeros sin caracteres especiales ni espacios ejemplo:(04141074586),(02726521412)');
                                } else {
                                    telEmpresaInput.setCustomValidity('');
                                }
                            }
                        </script>
                                         
                    </div>
                    
                    <div class="form-group">
                         <a><b>Dirección</b></a>
                        <input type="text" name="direccion" class="form-control" value="<?=$configuracion["direccion"]?>" id="txtDirEmpresa" placeholder="Dirreción de la Empresa" required minlength="3" maxlength="80" pattern="[a-zA-ZñÑáéíóúÁÉÍÓÚ0-9 º#-]+" 
                        oninput="validarDirEmpresa()" oninvalid="this.setCustomValidity('La Direccion debe tener entre 3 y 80 caracteres y solo puede contener letras, números, espacios, y estos caracteres especiales(-º #) .')">
                        <script>
                          function validarDirEmpresa() {
                              const dirEmpresaInput = document.getElementById('txtDirEmpresa');
                              const dirEmpresaRegex = /^[a-zA-ZñÑáéíóúÁÉÍÓÚ0-9 º#-]+$/;

                              if (!dirEmpresaRegex.test(dirEmpresaInput.value)) {
                                  dirEmpresaInput.setCustomValidity('La Direccion debe tener entre 3 y 80 caracteres y solo puede contener letras, números, espacios, y estos caracteres especiales(-º #) .');
                              } else {
                                  dirEmpresaInput.setCustomValidity('');
                              }
                          }
                        </script>
                    </div>
                    <div class="form-group">
                         <a><b>Precio del dolar</b></a>
                        <input type="number" step="any" name="precio_dolar" class="form-control" value="<?=$configuracion["precio_dolar"]?>" id="txtDirEmpresa" placeholder="37" required oninput="validatePrice(this)">
                        <span id="error-message"></span>
                        <script>
                          function validatePrice(input) {
                          const price = input.value;
                          const errorMessage = document.getElementById('error-message');

                          if (isNaN(price)) {
                            errorMessage.textContent = "Por favor, ingresa solo números para el precio en dólares.";
                          } else if (price <= 0) {
                            errorMessage.textContent = "El precio no debe contener letras solo numeros.";
                          } else {
                            errorMessage.textContent = "";
                          }
                        }

                        </script>

                    </div>
                    <div class="form-group">
                         <a><b>Impuesto</b></a>
                        <input type="number" step="any" name="impuesto" class="form-control" value="<?=$configuracion["impuesto"]?>" id="txtDirEmpresa" placeholder="Impuesto" required oninput="validateImpuesto(this)">
                        <span id="mensaje-de-error"></span>
                        <script>
                          function validateImpuesto(input) {
                        const impuesto = input.value;
                        const errorMessage = document.getElementById('mensaje-de-error');

                        if (isNaN(impuesto)) {
                          errorMessage.textContent = "Por favor, ingrese un número válido para el impuesto.";
                        } else if (impuesto <= 0) {
                          errorMessage.textContent = "El impuesto no debe contener letras solo numeros.";
                        } else {
                          errorMessage.textContent = "";
                        }
                      }
                        </script>

                    </div>
                    <div>
                        <button type="submit" class="btn btn-primary"><i ></i> Modificar Datos</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
      <!-- /.box -->

    </section>
    <!-- /.content -->
  </div>
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
  <!-- /.content-wrapper -->