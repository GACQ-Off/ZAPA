<?php
ini_set('display_errors',1);
error_reporting(E_ALL);

if ($_SESSION["perfil"] == "Especial") {

    echo '<script>

        window.location = "inicio";

    </script>';

    return;
}

// Incluir el modelo de conexión si no está ya incluido globalmente
// require_once "rutas/a/tu/conexion.php"; // Asegúrate de que la ruta sea correcta

?>

<div class="content-wrapper">

    <section class="content-header">

        <h1>

            Crear venta

        </h1>

        <ol class="breadcrumb">

            <li><a href="#"><i class="fa fa-dashboard"></i> Inicio</a></li>

            <li class="active">Crear venta</li>

        </ol>

    </section>

    <section class="content">

        <div class="row">

            <div class="col-lg-5 col-xs-12">

                <div class="box box-success">

                    <div class="box-header with-border"></div>

                    <form role="form" method="post" class="formularioVenta">

                        <div class="box-body">

                            <div class="box">

                                <?php
                                    $usuario = ControladorUsuarios::ctrMostrarUsuarios("cedula",$_SESSION["cedula"]);
                                ?>

                                <div class="form-group">

                                    <div class="input-group">

                                        <span class="input-group-addon"><i class="fa fa-user"></i></span>

                                        <input type="text" class="form-control" id="nuevoVendedor" value="<?php echo $usuario["nombre"]; ?>" readonly>

                                        <input type="hidden" name="idVendedor" value="<?php echo $_SESSION["cedula"]; ?>">

                                    </div>

                                </div>

                                <div class="form-group">

                                    <div class="input-group">

                                        <span class="input-group-addon"><i class="fa fa-key"></i></span>

                                        <?php
                                        // Asumo que 'Conexion::conectar()' está disponible si ya lo tienes en tu controlador
                                        // Es importante que este código de conexión esté disponible aquí si lo vas a usar.
                                        $item = null;
                                        $valor = null;
                                        $codigo;

                                        $ventas = ControladorVentas::ctrMostrarVentas($item, $valor); // Esto podría traer todas las ventas, no solo la última

                                        if (!$ventas) {
                                            echo '<input type="text" class="form-control" id="nuevaVenta" name="nuevaVenta" value="10001" readonly>';
                                        } else {
                                            // Conexión a la base de datos para obtener el último código de factura
                                            try {
                                                $pdo = Conexion::conectar();
                                                $stmt = $pdo->prepare("SELECT MAX(factura) AS ultimo_codigo FROM ventas");
                                                $stmt->execute();
                                                $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
                                                $ultimoCodigo = $resultado['ultimo_codigo'];

                                                // Asignar el nuevo código
                                                if (!$ultimoCodigo) {
                                                    $codigo = 10001; // Si no hay registros, se inicia en 10001
                                                } else {
                                                    $codigo = $ultimoCodigo + 1;
                                                }
                                                echo '<input type="text" class="form-control" id="nuevaVenta" name="nuevaVenta" value="' . $codigo . '" readonly>';
                                            } catch (PDOException $e) {
                                                // Manejar el error de conexión o consulta
                                                echo '<input type="text" class="form-control" id="nuevaVenta" name="nuevaVenta" value="ERROR_CODE" readonly title="Error al generar código de venta">';
                                                error_log("Error al obtener el último código de factura: " . $e->getMessage());
                                            }
                                        }

                                        ?>

                                    </div>

                                </div>
                                



                             <div class="form-group">
  <label for="seleccionarCliente">Seleccionar cliente</label>
  <div class="input-group">

    <!-- Select con buscador -->
    <select class="form-control select2" id="seleccionarCliente" name="seleccionarCliente" required style="width: 100%;">
      <option value="">Seleccionar cliente</option>
      <?php
        $clientes = ControladorClientes::ctrMostrarClientes(null, null);
        foreach ($clientes as $cliente) {
          echo '<option value="' . $cliente["tipo_ced"] . '-' . $cliente["num_ced"] . '">' .
                $cliente["tipo_ced"] . '-' . $cliente["num_ced"] . ' - ' .
                ucwords($cliente["nombre"] . ' ' . $cliente["apellido"]) . '</option>';
        }
      ?>
    </select>

    <!-- Botón Agregar cliente -->
    <span class="input-group-btn">
      <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalAgregarCliente" style="height: 34px;">
        <i class="fa fa-plus-circle"></i> Agregar
      </button>
    </span>

  </div>
</div>


                                <HR>

                                <div class="form-group row nuevoProducto">

                                </div>

                                <input type="hidden" id="listaProductos" name="listaProductos">

                                <button type="button" class="btn btn-default hidden-lg btnAgregarProducto">Agregar producto</button>

                                <div class="row">
                                <style>
    /* Asegura que el Select2 dentro de un input-group tenga la misma altura que los controles de formulario de Bootstrap */
    .input-group .select2-container .select2-selection--single {
        height: 34px !important; /* Altura estándar de los inputs de Bootstrap 3 */
    }

    /* Ajusta la línea de texto dentro del Select2 para que quede centrada verticalmente */
    .input-group .select2-container .select2-selection--single .select2-selection__rendered {
        line-height: 32px !important; /* Ajusta este valor para centrar el texto */
        padding-top: 0 !important;
        padding-bottom: 0 !important;
    }

    /* Ajusta la flecha desplegable del Select2 */
    .input-group .select2-container .select2-selection--single .select2-selection__arrow {
        height: 34px !important; /* La misma altura que el select */
    }
</style>

                                    <div class="col-xs-12 pull-right">

                                        <table class="table">

                                            <thead>

                                                <tr>
                                                    <th>Neto</th>
                                                    <th>Iva %</th>
                                                    <th>Monto del Iva</th>
                                                    <th>Total</th>
                                                </tr>

                                            </thead>
                                            <?php
                                            $empresa = ControladorConfiguracion::ctrMostrarConfigracion(null,null);

                                            echo '<input type="hidden" id="dolarPrice" value="'.$empresa["precio_dolar"].'">'; // Quité el cierre de la etiqueta </input> que es inválido en HTML5

                                            ?>
                                            <tbody>

                                                <tr>
                                                    <td style="width: 26%">

                                                        <div class="input-group">


                                                            <input type="text" class="form-control input-lg" id="nuevoPrecioNeto" name="nuevoNeto" total="" placeholder="0.00" readonly required>



                                                        </div>

                                                    </td>
                                                    <td style="width: 20%">

                                                        <div class="input-group">

                                                            <input type="number" class="form-control input-lg" min="0" readonly value="<?=$empresa["impuesto"]?>" id="nuevoImpuestoVenta" name="nuevoImpuestoVenta" placeholder="0" required>

                                                            <input type="hidden" name="nuevoPrecioImpuesto" id="precioImpuestoHidden" required> <input type="hidden" name="nuevoPrecioNetoHidden" id="nuevoPrecioNetoHidden" required> </div>

                                                    </td>
                                                    <td style="width: 25%">

                                                        <div class="input-group">


                                                            <input type="text" class="form-control input-lg" id="nuevoPrecioImpuesto" name="nuevoTotalVentaIVA" total="" placeholder="0.00" readonly required> </div>

                                                    </td>

                                                    <td style="width: 60%">

                                                        <div class="input-group">


                                                            <input type="text" class="form-control input-lg" id="nuevoTotalVenta" name="nuevoTotalVenta" total="" placeholder="0.00" readonly required>

                                                            <input type="hidden" name="totalVenta" id="totalVenta">
                                                            <input type="hidden" name="totalPago" id="totalPago">


                                                        </div>

                                                    </td>
                                                </tr>

                                            </tbody>

                                        </table>

                                    </div>

                                </div>

                                <hr>
                                <div class="form-group row">

                                    <div class="col-xs-6" style="padding-right:0px">

                                        <div class="input-group">

                                            <select class="form-control" id="nuevoMetodoPago" name="nuevoMetodoPago" required>
                                                <option value="">Seleccione método de pago</option>
                                                <option value="Efectivo">Efectivo</option>
                                                <option value="Divisas">Divisas</option>
                                                <option value="Tarjeta">Tarjeta</option>
                                                <option value="Pago Movil">Pago movil</option>
                                                <option value="Transferencia">Transferencia</option>
                                            </select>

                                        </div>

                                    </div>

                                    <div class="cajasMetodoPago"></div>


                                    <input type="hidden" id="listaMetodoPago" name="listaMetodoPago">

                                </div>

                                <br>

                            </div>

                        </div>

                       <div class="text-right">
    <button type="submit" class="btn btn-primary" id="guardarVentaButton">Guardar venta</button>
    <button type="button" class="btn btn-primary" id="btnRealizarPago" data-toggle="modal" data-target="#modalPagoVenta">
        <i class="fa fa-credit-card"></i> Realizar Pago
    </button>

    <!-- Script independiente para habilitar/deshabilitar botón Realizar Pago -->

</div>


                    </form>

                    <?php

                    $guardarVenta = new ControladorVentas();
                    $guardarVenta->ctrCrearVenta();

                    ?>

                </div>

            </div>

            <div class="col-lg-7 hidden-md hidden-sm hidden-xs">

                <div class="box box-warning">

                    <div class="box-header with-border"></div>

                    <div class="box-body">

                        <table class="table table-bordered table-striped dt-responsive tablaVentas">

                            <thead>

                                <tr>
                                    <th style="width: 10px">#</th>
                                    <th>Imagen</th>

                                    <th>Código</th>
                                    <th>Descripción</th>
                                    <th>Stock</th>
                                    <th>Acciones</th>
                                </tr>

                            </thead>

                        </table>

                    </div>

                </div>


            </div>

        </div>

    </section>

</div>

---

## Modal Agregar Cliente

<div id="modalAgregarCliente" class="modal fade" role="dialog">

    <div class="modal-dialog">

        <div class="modal-content">

            <form role="form" method="post">

                <div class="modal-header" style="background:#3c8dbc; color:white">

                    <button type="button" class="close" data-dismiss="modal">&times;</button>

                    <h4 class="modal-title">Agregar cliente</h4>

                </div>

                <div class="modal-body">

                    <div class="box-body">

                        <a><b>Cédula</b></a>
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-id-card-o"></i></span>

                                <select class="form-control input-lg" id="selectTipoCedula" style="width: 80px; margin-right: 5px;" required>
                                    <option value="V">V</option>
                                    <option value="E">E</option>
                                </select>

                                <span class="input-group-addon" >-</span>

                                <input type="text" class="form-control input-lg" id="inputNumeroCedula" placeholder="Ingresar cédula" required minlength="7" maxlength="8" pattern="[0-9]{7,8}"
                                    oninput="validarNumeroCedula()"
                                    oninvalid="this.setCustomValidity('La cédula solo puede contener entre 7 y 8 números sin caracteres especiales ni espacios. Ejemplo: (1234567) o (87654321).')"
                                    style="flex-grow: 1; border-left: none;">

                                <input type="hidden" name="nuevaCedula" id="hiddenNuevaCedula">

                                <script>
                                    function validarNumeroCedula() {
                                        const inputNumeroCedula = document.getElementById('inputNumeroCedula');
                                        const numeroCedulaRegex = /^[0-9]{7,8}$/;

                                        if (!numeroCedulaRegex.test(inputNumeroCedula.value)) {
                                            inputNumeroCedula.setCustomValidity('La cédula solo puede contener entre 7 y 8 números sin caracteres especiales ni espacios. Ejemplo: (1234567) o (87654321).');
                                        } else {
                                            inputNumeroCedula.setCustomValidity('');
                                        }
                                        combinarCedula(); // Llama a la función de combinación cada vez que cambia el input de número
                                    }

                                    // Función para combinar el tipo de cédula y el número
                                    function combinarCedula() {
                                        const tipoCedula = document.getElementById('selectTipoCedula').value;
                                        const numeroCedula = document.getElementById('inputNumeroCedula').value;
                                        const hiddenNuevaCedula = document.getElementById('hiddenNuevaCedula');

                                        // Solo combinar si la parte numérica es válida
                                        if (numeroCedula.match(/^[0-9]{7,8}$/)) {
                                            hiddenNuevaCedula.value = tipoCedula + '-' + numeroCedula;
                                        } else {
                                            hiddenNuevaCedula.value = ''; // Vaciar si la parte numérica es inválida
                                        }
                                    }

                                    // Adjuntar escuchadores de eventos para actualizar el input oculto
                                    document.getElementById('selectTipoCedula').addEventListener('change', combinarCedula);
                                    document.getElementById('inputNumeroCedula').addEventListener('input', combinarCedula);

                                    // Asegurar que el valor se establezca al enviar el formulario, especialmente si los campos están pre-rellenados o no se interactúa con ellos
                                    document.querySelector('#modalAgregarCliente form[role="form"]').addEventListener('submit', function() { // Específico para este formulario
                                        combinarCedula();
                                    });
                                </script>
                            </div>
                        </div>

                        <a><b>Nombre del Cliente</b></a>
                        <div class="form-group">

                            <div class="input-group">

                                <span class="input-group-addon"><i class="fa fa-user"></i></span>

                                <input type="text" class="form-control input-lg" name="nuevoCliente" id="nuevoCliente" placeholder="Ingresar nombre" required minlength="3" maxlength="30" pattern="[a-zA-ZñÑáéíóúÁÉÍÓÚ ]+"

                                oninput="validarNuevoCliente()" oninvalid="this.setCustomValidity('El nombre debe tener entre 3 y 30 caracteres y solo puede contener letras y espacios.')">
                                <script>
                                    function validarNuevoCliente() {
                                        const nuevoClienteInput = document.getElementById('nuevoCliente');
                                        const nuevoClienteRegex = /^[a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/;

                                        if (!nuevoClienteRegex.test(nuevoClienteInput.value)) {
                                            nuevoClienteInput.setCustomValidity('El nombre debe contener solo letras y espacios.');
                                        } else {
                                            nuevoClienteInput.setCustomValidity('');
                                        }
                                    }
                                </script>

                            </div>

                        </div>

                        <a><b> Apellido del Cliente</b></a>
                        <div class="form-group">

                            <div class="input-group">

                                <span class="input-group-addon"><i class="fa fa-key"></i></span>

                                <input type="text" class="form-control input-lg" name="nuevoApellido" id="nuevoApellido" placeholder="Ingresar Apellido" required
                                minlength="3" maxlength="30" pattern="[a-zA-ZñÑáéíóúÁÉÍÓÚ ]+"
                                oninput="validarNuevoApellido()" oninvalid="this.setCustomValidity('El apellido debe tener entre 3 y 30 caracteres y solo puede contener letras y espacios.')">
                                <script>
                                function validarNuevoApellido() {
                                    const nuevoApellidoInput = document.getElementById('nuevoApellido');
                                    const nuevoApellidoRegex = /^[a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/;

                                    if (!nuevoApellidoRegex.test(nuevoApellidoInput.value)) {
                                        nuevoApellidoInput.setCustomValidity('El apellido debe contener solo letras y espacios.');
                                    } else {
                                        nuevoApellidoInput.setCustomValidity('');
                                    }
                                }
                                </script>

                            </div>

                        </div>

                        <a><b> Correo del Cliente</b></a>
                        <div class="form-group">

                            <div class="input-group">

                                <span class="input-group-addon"><i class="fa fa-envelope"></i></span>

                                <input type="email" class="form-control input-lg" name="nuevoEmail" id="nuevoEmail" placeholder="Ingresar Email" required oninput="validarNuevoEmail()" oninvalid="this.setCustomValidity('Por favor, ingresa una dirección de correo electrónico válida por ejemplo (noposee@gmail.com),(bocono12@gmail.com).')">
                                <script>
                                    function validarNuevoEmail() {
                                        const nuevoEmailInput = document.getElementById('nuevoEmail');
                                        // Esta regex es un poco más permisiva para correos. Ajusta según tus necesidades.
                                        const nuevoEmailRegex = /^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/;

                                        if (!nuevoEmailRegex.test(nuevoEmailInput.value)) {
                                            nuevoEmailInput.setCustomValidity('Por favor, ingresa una dirección de correo electrónico válida.');
                                        } else {
                                            nuevoEmailInput.setCustomValidity('');
                                        }
                                    }
                                </script>

                            </div>

                        </div>

                        <a><b>Número de teléfono</b></a>
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-phone"></i></span>
                                <select class="form-control input-lg" id="selectPrefijoTelefono" style="width: 100px; margin-right: 5px;" required>
                                    <option value="">Prefijo</option>
                                    <option value="0272">0272</option>
                                    <option value="0414">0414</option>
                                    <option value="0424">0424</option>
                                    <option value="0412">0412</option>
                                    <option value="0416">0416</option>
                                    <option value="0426">0426</option>
                                </select>

                                <span class="input-group-addon" >-</span>
                                <input type="text" class="form-control input-lg" id="inputRestoTelefono" placeholder="Ej: 1234567" required minlength="7" maxlength="7" pattern="[0-9]{7}"
                                    oninput="validarRestoTelefono()"
                                    oninvalid="this.setCustomValidity('Los 7 dígitos del teléfono solo pueden contener números.')"
                                    style="flex-grow: 1; border-left: none;">

                                <input type="hidden" name="nuevoTelefono" id="hiddenNuevoTelefono">

                                <script>
                                    function validarRestoTelefono() {
                                        const inputRestoTelefono = document.getElementById('inputRestoTelefono');
                                        const restoTelefonoRegex = /^[0-9]{7}$/;

                                        if (!restoTelefonoRegex.test(inputRestoTelefono.value)) {
                                            inputRestoTelefono.setCustomValidity('Los 7 dígitos del teléfono solo pueden contener números.');
                                        } else {
                                            inputRestoTelefono.setCustomValidity('');
                                        }
                                        combinarTelefono(); // Llama a la función de combinación cada vez que cambia el input de número
                                    }

                                    // Función para combinar prefijo, guion y resto del número
                                    function combinarTelefono() {
                                        const prefijo = document.getElementById('selectPrefijoTelefono').value;
                                        const resto = document.getElementById('inputRestoTelefono').value;
                                        const hiddenNuevoTelefono = document.getElementById('hiddenNuevoTelefono');

                                        // Solo combinar si ambas partes están presentes y son válidas
                                        if (prefijo !== "" && resto.match(/^[0-9]{7}$/)) {
                                            hiddenNuevoTelefono.value = prefijo + '-' + resto;
                                        } else {
                                            hiddenNuevoTelefono.value = ''; // Vaciar si las partes están incompletas/inválidas
                                        }
                                    }

                                    // Adjuntar escuchadores de eventos para actualizar el input oculto
                                    document.getElementById('selectPrefijoTelefono').addEventListener('change', combinarTelefono);
                                    document.getElementById('inputRestoTelefono').addEventListener('input', combinarTelefono);

                                    // Asegurar que el valor se establezca al enviar el formulario
                                    // Asumiendo que tu formulario está dentro de #modalAgregarCliente
                                    document.querySelector('#modalAgregarCliente form[role="form"]').addEventListener('submit', function() {
                                        combinarTelefono();
                                    });
                                </script>
                            </div>
                        </div>

                        <a><b>Dirección del Cliente</b></a>
                        <div class="form-group">

                            <div class="input-group">

                                <span class="input-group-addon"><i class="fa fa-map-marker"></i></span>

                                <input type="text" class="form-control input-lg" name="nuevaDireccion" id="nuevaDireccion" placeholder="Ingresar dirección" required minlength="3" maxlength="80" pattern="[a-zA-ZñÑáéíóúÁÉÍÓÚ0-9 º#-]+"
                                oninput="validarNuevaDireccion()" oninvalid="this.setCustomValidity('La dirección debe tener entre 3 y 80 caracteres y solo puede contener letras, números, espacios, y estos caracteres especiales (-º #).')">
                                <script>
                                    function validarNuevaDireccion() {
                                        const nuevaDireccionInput = document.getElementById('nuevaDireccion');
                                        const nuevaDireccionRegex = /^[a-zA-ZñÑáéíóúÁÉÍÓÚ0-9 º#-]+$/;

                                        if (!nuevaDireccionRegex.test(nuevaDireccionInput.value)) {
                                            nuevaDireccionInput.setCustomValidity('La dirección debe contener solo letras, números, espacios, y estos caracteres especiales (-º #).');
                                        } else {
                                            nuevaDireccionInput.setCustomValidity('');
                                        }
                                    }
                                </script>

                            </div>

                        </div>

                        <div class="form-group">


                        </div>

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>

                    <button type="submit" class="btn btn-primary">Guardar cliente</button>

                </div>

            </form>

            <?php

            $crearCliente = new ControladorClientes();
            $crearCliente->ctrCrearCliente();

            ?>

        </div>

    </div>

</div>
<!-- Modal de Pago -->
<div class="modal fade" id="modalPagoVenta" tabindex="-1" role="dialog" aria-labelledby="modalPagoVentaLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="modalPagoVentaLabel"><i class="fa fa-credit-card"></i> Registrar Pago</h5>
        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
      </div>

      <div class="modal-body">
        <!-- Totales al inicio del modal -->
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label>Total de la venta (Bs)</label>
              <input type="text" id="totalVentaBsModal" class="form-control" readonly value="0.00">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label>Total de la venta (USD)</label>
              <input type="text" id="totalVentaModal" class="form-control" readonly value="0.00">
            </div>
          </div>
        </div>

        <div class="form-group">
          <label>Tipo de pago</label>
          <select class="form-control" id="tipoPago">
            <option value="">Seleccionar</option>
            <option value="simple">Simple</option>
            <option value="compuesto">Compuesto</option>
          </select>
        </div>

        <!-- Contenedor de pago simple -->
        <div id="pagoSimpleContainer" style="display:none; margin-top:15px;">
          <div class="pagoItem border p-2 mb-2 rounded bg-light">
            <div class="row align-items-end">
              <div class="col-md-3">
                <label>Método</label>
                <select class="form-control metodoIndividual">
                  <option value="">Seleccionar</option>
                  <option value="efectivo_bs">Efectivo Bs</option>
                  <option value="efectivo_usd">Efectivo USD</option>
                  <option value="transferencia">Transferencia</option>
                  <option value="pago_movil">Pago Movil</option>
                  <option value="tarjeta_debito">Tarjeta Débito</option>
                </select>
              </div>
              <div class="col-md-3">
                <label>Monto Bs</label>
                <input type="number" step="0.01" min="0" class="form-control montoBs" placeholder="0.00">
              </div>
              <div class="col-md-3">
                <label>Monto USD</label>
                <input type="number" step="0.01" min="0" class="form-control montoUsd" placeholder="0.00">
              </div>
              <div class="col-md-3">
                <label>Referencia</label>
                <input type="text" class="form-control referencia" placeholder="N° Referencia" readonly>
              </div>
            </div>
          </div>
        </div>

        <!-- Contenedor de pago múltiple -->
        <div id="pagoCompuestoContainer" style="display:none; margin-top:15px;">
          <div id="pagosMultiples"></div>
          <button type="button" id="btnAgregarPago" class="btn btn-sm btn-primary mt-2"><i class="fa fa-plus"></i> Agregar pago</button>
        </div>

        <hr>
        <!-- Totales abonados y restante -->
        <div class="row">
          <div class="col-md-3">
            <label>Total abonado (Bs)</label>
            <input type="text" id="totalAbonadoBs" class="form-control" readonly value="0.00">
          </div>
          <div class="col-md-3">
            <label>Total abonado (USD)</label>
            <input type="text" id="totalAbonado" class="form-control" readonly value="0.00">
          </div>
          <div class="col-md-3">
            <label>Restante (Bs)</label>
            <input type="text" id="restanteBs" class="form-control" readonly value="0.00">
          </div>
          <div class="col-md-3">
            <label>Restante (USD)</label>
            <input type="text" id="restante" class="form-control" readonly value="0.00">
          </div>
        </div>

      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        <button type="button" id="btnGuardarPago" class="btn btn-success" disabled><i class="fa fa-check"></i> Guardar Pago</button>


      </div>

    </div>
  </div>
</div>



<script>
$(document).ready(function(){

  const tasaCambio = parseFloat($('#dolarPrice').val()) || 0;
  let totalVenta = 0;

  // Abrir modal: tomar total de la venta desde la página
  $('#modalPagoVenta').on('shown.bs.modal', function () {
    totalVenta = parseFloat($('#totalVenta').val()) || 0;
    const totalBs = totalVenta * tasaCambio;

    $('#totalVentaModal').val(totalVenta.toFixed(2));
    $('#totalVentaBsModal').val(totalBs.toFixed(2));

    $('#totalAbonado').val("0.00");
    $('#totalAbonadoBs').val("0.00");
    $('#restante').val(totalVenta.toFixed(2));
    $('#restanteBs').val(totalBs.toFixed(2));

    $('#tipoPago').val('');
    $('#pagoSimpleContainer').hide();
    $('#pagoCompuestoContainer').hide();
    $('#pagosMultiples').empty();
    $('#btnGuardarPago').prop('disabled', true);
  });

  // Cambiar tipo de pago
  $('#tipoPago').change(function(){
    const tipo = $(this).val();
    if(tipo=='simple'){
      $('#pagoSimpleContainer').show();
      $('#pagoCompuestoContainer').hide();
      $('#pagoSimpleContainer .pagoItem').find('input').val('');
      $('#pagoSimpleContainer .pagoItem .referencia').prop('readonly', true);
    } else if(tipo=='compuesto'){
      $('#pagoSimpleContainer').hide();
      $('#pagoCompuestoContainer').show();
      agregarPagoCompuesto();
    } else {
      $('#pagoSimpleContainer').hide();
      $('#pagoCompuestoContainer').hide();
    }
    actualizarTotales();
  });

  // Cambio método simple
  $(document).on('change', '.metodoIndividual', function(){
    const metodo = $(this).val();
    const referencia = $(this).closest('.pagoItem').find('.referencia');
    if(['transferencia','pago_movil','tarjeta_debito'].includes(metodo)){
      referencia.prop('readonly', false);
    } else {
      referencia.prop('readonly', true).val('');
    }
  });

  // Actualizar Bs/USD en simple
  $(document).on('input', '.montoBs', function(){
    const bs = parseFloat($(this).val()) || 0;
    $(this).closest('.pagoItem').find('.montoUsd').val((bs/tasaCambio).toFixed(2));
    actualizarTotales();
  });
  $(document).on('input', '.montoUsd', function(){
    const usd = parseFloat($(this).val()) || 0;
    $(this).closest('.pagoItem').find('.montoBs').val((usd*tasaCambio).toFixed(2));
    actualizarTotales();
  });

  // Agregar pago múltiple
  $('#btnAgregarPago').click(function(){ agregarPagoCompuesto(); });

  function agregarPagoCompuesto(){
    const pagoHTML = `
    <div class="pagoItem border rounded p-2 mb-2 bg-light">
      <div class="row align-items-end">
        <div class="col-md-3">
          <label>Método</label>
          <select class="form-control metodoIndividual">
            <option value="">Seleccionar</option>
            <option value="efectivo_bs">Efectivo Bs</option>
            <option value="efectivo_usd">Efectivo USD</option>
            <option value="transferencia">Transferencia</option>
            <option value="pago_movil">Pago Movil</option>
            <option value="tarjeta_debito">Tarjeta Débito</option>
          </select>
        </div>
        <div class="col-md-2">
          <label>Bs</label>
          <input type="number" class="form-control montoBs" step="0.01" min="0">
        </div>
        <div class="col-md-2">
          <label>USD</label>
          <input type="number" class="form-control montoUsd" step="0.01" min="0">
        </div>
        <div class="col-md-3">
          <label>Referencia</label>
          <input type="text" class="form-control referencia" readonly>
        </div>
        <div class="col-md-2">
          <button type="button" class="btn btn-danger btn-sm btnEliminarPago" style="margin-top:30px;"><i class="fa fa-times"></i></button>
        </div>
      </div>
    </div>`;
    $('#pagosMultiples').append(pagoHTML);
  }

  // Eliminar pago compuesto
  $(document).on('click','.btnEliminarPago', function(){
    $(this).closest('.pagoItem').remove();
    actualizarTotales();
  });

  // Actualizar totales
  function actualizarTotales(){
    let totalAbonado = 0;
    let totalAbonadoBs = 0;

    $('#pagoSimpleContainer .pagoItem, #pagosMultiples .pagoItem').each(function(){
      const usd = parseFloat($(this).find('.montoUsd').val()) || 0;
      const bs = parseFloat($(this).find('.montoBs').val()) || 0;
      totalAbonado += usd;
      totalAbonadoBs += bs;
    });

    totalAbonado = parseFloat(totalAbonado.toFixed(2));
    totalAbonadoBs = parseFloat(totalAbonadoBs.toFixed(2));
    let restante = parseFloat((totalVenta-totalAbonado).toFixed(2));
    let restanteBs = parseFloat((totalVenta*tasaCambio-totalAbonadoBs).toFixed(2));

    if(restante<0) restante=0;
    if(restanteBs<0) restanteBs=0;

    $('#totalAbonado').val(totalAbonado.toFixed(2));
    $('#totalAbonadoBs').val(totalAbonadoBs.toFixed(2));
    $('#restante').val(restante.toFixed(2));
    $('#restanteBs').val(restanteBs.toFixed(2));

    // Activar o desactivar botón guardar
    if(totalAbonado===totalVenta){
      $('#btnGuardarPago').prop('disabled', false);
    } else {
      $('#btnGuardarPago').prop('disabled', true);
    }
  }

  // Guardar pago
  $('#btnGuardarPago').click(function(){
    Swal.fire({
      icon:'success',
      title:'Pago registrado',
      text:'El pago ha sido registrado correctamente',
      timer:1500,
      showConfirmButton:false
    });
    $('#modalPagoVenta').modal('hide');
    $('#guardarVentaButton').prop('disabled', false); // activar guardar venta
  });

});
</script>



---

## Scripts Adicionales

<script>
  $(document).ready(function () {
    // Inicializa Select2 en AdminLTE 2
    $('#seleccionarCliente').select2({
      placeholder: 'Seleccionar cliente',
      allowClear: true
    });
  });
</script>

<script>
$('#btnRealizarPago').click(function(e){
    e.preventDefault(); // evitar submit

    let error = false;

    // Validar cliente
    const cliente = $('#seleccionarCliente').val();
    $('#seleccionarCliente').removeClass('is-invalid'); // limpiar estado previo
    if(!cliente){
        $('#seleccionarCliente').addClass('is-invalid');
        error = true;
    }

    // Validar productos
    let hayProductoValido = false;
    $('.nuevoProducto .rowProducto').each(function(){
        const cantidad = parseFloat($(this).find('.inputCantidad').val()) || 0;
        const precio = parseFloat($(this).find('.inputPrecio').val()) || 0;
        if(cantidad > 0 && precio > 0){
            hayProductoValido = true;
            return false; // salir del each
        }
    });

    // Limpiar estilos de productos
    $('.nuevoProducto .rowProducto .inputCantidad, .nuevoProducto .rowProducto .inputPrecio')
        .removeClass('is-invalid');

    if(!hayProductoValido){
        $('.nuevoProducto .rowProducto .inputCantidad, .nuevoProducto .rowProducto .inputPrecio')
            .addClass('is-invalid');
        error = true;
    }

    if(error){
        Swal.fire({
            icon: 'warning',
            title: 'Faltan datos',
            text: 'Debes seleccionar un cliente y agregar al menos un producto antes de continuar.'
        });
        return; // no abrir modal
    }

    // Si todo está correcto, abrir modal
    $('#modalPagoVenta').modal('show');
});


</script>
<script>
$(document).ready(function() {
    // Si tienes un elemento con ID 'divisa' que controla la visibilidad del botón 'guardarVentaButton'
    // este script ocultará el botón si el valor de 'divisa' es '$'.
    // Sino, puedes eliminar esta parte si no es necesaria.
    $('#divisa').on('change', function() {
        if ($(this).val() === '$') {
            $('#guardarVentaButton').hide();
        } else {
            $('#guardarVentaButton').show();
        }
    });
});
</script>






<script>
    $("#logout").click(function(e) {
        e.preventDefault();

        swal({
            type: "warning",
            title: "¿Estás seguro de salir?",
            text: "Esta acción cerrará tu sesión.",
            showCancelButton: true,
            confirmButtonColor: "#3498db",
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