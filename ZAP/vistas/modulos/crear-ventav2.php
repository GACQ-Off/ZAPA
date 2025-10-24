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

                                    <div class="input-group">

                                        <span class="input-group-addon"><i class="fa fa-users"></i></span>

                                        <select class="form-control" id="seleccionarCliente" name="seleccionarCliente" required>

                                            <option value="">Seleccionar cliente</option>

                                            <?php

                                            $item = null;
                                            $valor = null;

                                            // Obtener los clientes desde el controlador
                                            $clientes = ControladorClientes::ctrMostrarClientes($item, $valor); // Renombrado a $clientes para mayor claridad

                                            // Iterar sobre los clientes para mostrarlos en el select
                                            foreach ($clientes as $key => $value) {
                                                // Mostrar primero la cédula y luego el nombre
                                                echo '<option value="' . $value["tipo_ced"] . '-' . $value["num_ced"] . '">' .
                                                     $value["tipo_ced"] . '-' . $value["num_ced"] . ' - ' .
                                                     ucwords($value["nombre"] . ' ' . $value["apellido"]) . '</option>';
                                            }

                                            ?>

                                        </select>

                                        <span class="input-group-addon"><button type="button" class="btn btn-default btn-xs" data-toggle="modal" data-target="#modalAgregarCliente" data-dismiss="modal">Agregar cliente</button></span>

                                    </div>

                                </div>

                                <HR>

                                <div class="form-group row nuevoProducto">

                                </div>

                                <input type="hidden" id="listaProductos" name="listaProductos">

                                <button type="button" class="btn btn-default hidden-lg btnAgregarProducto">Agregar producto</button>

                                <div class="row">

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

                               

                            </div>

                        </div>


                        <div class="box-footer">
                            <button type="button" class="btn btn-primary pull-right" id="btnAbrirModalPago">Realizar Pago</button>
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

            <div class="modal fade" id="modalProcesarPago" role="dialog" aria-labelledby="modalProcesarPagoLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title" id="modalProcesarPagoLabel">Procesar Pago de Venta</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="totalVentaModal">Total de la Venta:</label>
                                        <input type="text" class="form-control" id="totalVentaModal" name="totalVentaModal" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Seleccionar Tipo de Pago:</label><br>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="tipoPagoSeleccion" id="pagoSimple" value="simple" checked>
                                            <label class="form-check-label" for="pagoSimple">Pago Simple</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="tipoPagoSeleccion" id="pagoMultiple" value="multiple">
                                            <label class="form-check-label" for="pagoMultiple">Pago Múltiple</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <div id="contenedorPagos">
                                <div class="row pago-item mb-3 border p-3 rounded">
                                    <div class="col-md-4 form-group">
                                        <label>Tipo de Pago:</label>
                                        <select class="form-control tipo-pago" name="tipos_pago[]">
                                            <option value="">Seleccione...</option>
                                            <option value="efectivo">Efectivo</option>
                                            <option value="divisas">Divisas</option>
                                            <option value="tarjeta">Tarjeta</option>
                                            <option value="pago_movil">Pago Móvil</option>
                                            <option value="transferencia">Transferencia</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label>Monto:</label>
                                        <input type="number" step="0.01" min="0" class="form-control monto-pago" name="montos_pago[]" placeholder="0.00">
                                    </div>
                                    <div class="col-md-4 form-group referencia-container" style="display: none;">
                                        <label>Referencia:</label>
                                        <input type="text" class="form-control referencia-pago" name="referencias_pago[]" placeholder="Referencia del pago">
                                    </div>
                                    <div class="col-md-12 text-right">
                                        <button type="button" class="btn btn-danger btn-sm eliminar-pago" style="display: none;">
                                            <i class="fa fa-times"></i> Eliminar Pago
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3" id="btnAgregarPagoContainer" style="display: none;">
                                <div class="col-md-12 text-right">
                                    <button type="button" class="btn btn-info btn-sm" id="btnAgregarPago">
                                    
                                        <i class="fa fa-plus"></i> Agregar Otro Pago
                                    </button>
                                </div>
                            </div>

                            <hr>

                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="totalPagadoModal">Total Pagado:</label>
                                        <input type="text" class="form-control" id="totalPagadoModal" name="totalPagadoModal" readonly value="0.00">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="montoRestanteModal">Monto Restante:</label>
                                        <input type="text" class="form-control" id="montoRestanteModal" name="montoRestanteModal" readonly value="0.00">
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                            <button type="button" class="btn btn-success" id="btnGuardarPagoModal" disabled>Guardar Venta</button>
                        </div>
                    </div>
                </div>
            </div>

            <template id="templatePagoItem">
                <div class="row pago-item mb-3 border p-3 rounded">
                    <div class="col-md-4 form-group">
                        <label>Tipo de Pago:</label>
                        <select class="form-control tipo-pago" name="tipos_pago[]">
                            <option value="">Seleccione...</option>
                            <option value="efectivo">Efectivo</option>
                            <option value="divisas">Divisas</option>
                            <option value="tarjeta">Tarjeta</option>
                            <option value="pago_movil">Pago Móvil</option>
                            <option value="transferencia">Transferencia</option>
                        </select>
                    </div>
                    <div class="col-md-4 form-group">
                        <label>Monto:</label>
                        <input type="number" step="0.01" min="0" class="form-control monto-pago" name="montos_pago[]" placeholder="0.00">
                    </div>
                    <div class="col-md-4 form-group referencia-container" style="display: none;">
                        <label>Referencia:</label>
                        <input type="text" class="form-control referencia-pago" name="referencias_pago[]" placeholder="Referencia del pago">
                    </div>
                    <div class="col-md-12 text-right">
                        <button type="button" class="btn btn-danger btn-sm eliminar-pago">
                            <i class="fa fa-times"></i> Eliminar Pago
                        </button>
                    </div>
                </div>
            </template>
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

---

## Scripts Adicionales

<script>
// ELIMINADO: Este script original ocultaba el botón "Guardar Venta" en base a un elemento '#divisa'.
// Lo reemplazaremos con la lógica del modal de pago.
/*
$(document).ready(function() {
    $('#divisa').on('change', function() {
        if ($(this).val() === '$') {
            $('#guardarVentaButton').hide();
        } else {
            $('#guardarVentaButton').show();
        }
    });
});
*/
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

<script src="pagos_venta.js"></script>


<script>
$(document).ready(function() {
    // Variable para almacenar el monto total de la venta
    let totalVentaActual = 0;

    // Cuando se hace clic en el botón "Realizar Pago"
    $('#btnAbrirModalPago').on('click', function() {
        // 1. Obtener el total de la venta desde el campo oculto
        // Asegúrate de que el ID #totalVenta contiene el valor numérico correcto.
        totalVentaActual = parseFloat($('#totalVenta').val()) || 0;

        // 2. Mostrar el total de la venta en el modal
        $('#totalVentaModal').val(totalVentaActual.toFixed(2)); // Formato a 2 decimales

        // 3. Reiniciar el modal a la configuración de "Pago Simple" por defecto
        $('#pagoSimple').prop('checked', true);
        $('#btnAgregarPagoContainer').hide(); // Ocultar botón de agregar pago
        $('.eliminar-pago').hide(); // Ocultar botones de eliminar

        // Limpiar y dejar solo un campo de pago si ya hay múltiples
        $('#contenedorPagos .pago-item').not(':first').remove();
        let firstPagoItem = $('#contenedorPagos .pago-item:first');
        firstPagoItem.find('.tipo-pago').val('');
        firstPagoItem.find('.monto-pago').val('');
        firstPagoItem.find('.referencia-pago').val('');
        firstPagoItem.find('.referencia-container').hide();

        // 4. Recalcular y actualizar los totales del modal al abrirlo (deberían ser 0.00 al inicio)
        actualizarTotalesPagoModal();

        // 5. Mostrar el modal
        $('#modalProcesarPago').modal('show');
    });

    // Función para añadir un nuevo campo de pago (para modo múltiple)
    $('#btnAgregarPago').on('click', function() {
        const template = $('#templatePagoItem')[0].content.cloneNode(true);
        const newPagoItem = $(template).find('.pago-item');

        // Mostrar el botón de eliminar en el nuevo item
        newPagoItem.find('.eliminar-pago').show();

        $('#contenedorPagos').append(newPagoItem);
        actualizarEventosPagoItem(newPagoItem); // Adjuntar eventos al nuevo item
        actualizarBotonesEliminar(); // Actualizar visibilidad de botones eliminar
        actualizarTotalesPagoModal(); // Recalcular totales
    });

    // Delegar eventos para elementos dinámicos
    // Manejar cambio en el tipo de pago para mostrar/ocultar referencia
    $('#contenedorPagos').on('change', '.tipo-pago', function() {
        let tipoPago = $(this).val();
        let referenciaContainer = $(this).closest('.pago-item').find('.referencia-container');
        let referenciaInput = $(this).closest('.pago-item').find('.referencia-pago');

        if (tipoPago === 'tarjeta' || tipoPago === 'pago_movil' || tipoPago === 'transferencia') {
            referenciaContainer.slideDown(); // Muestra con animación
            referenciaInput.prop('required', true); // Hace la referencia obligatoria
        } else {
            referenciaContainer.slideUp(); // Oculta con animación
            referenciaInput.val(''); // Limpia el campo de referencia
            referenciaInput.prop('required', false); // Quita la obligatoriedad
        }
    });

    // Manejar el cambio en el monto de pago para recalcular totales
    $('#contenedorPagos').on('input', '.monto-pago', function() {
        actualizarTotalesPagoModal();
    });

    // Manejar clic en el botón de eliminar pago
    $('#contenedorPagos').on('click', '.eliminar-pago', function() {
        $(this).closest('.pago-item').remove();
        actualizarTotalesPagoModal(); // Recalcular después de eliminar
        actualizarBotonesEliminar(); // Revalidar visibilidad de botones eliminar
    });

    // Manejar cambio entre pago simple y múltiple
    $('input[name="tipoPagoSeleccion"]').on('change', function() {
        if ($(this).val() === 'simple') {
            // Eliminar todos los ítems de pago excepto el primero
            $('#contenedorPagos .pago-item').not(':first').remove();
            // Limpiar el primer ítem
            let firstPagoItem = $('#contenedorPagos .pago-item:first');
            firstPagoItem.find('.tipo-pago').val('');
            firstPagoItem.find('.monto-pago').val('');
            firstPagoItem.find('.referencia-pago').val('');
            firstPagoItem.find('.referencia-container').hide();
            firstPagoItem.find('.eliminar-pago').hide(); // Ocultar botón de eliminar en el único item
            $('#btnAgregarPagoContainer').hide(); // Ocultar botón de agregar

        } else { // pagoMultiple
            $('#btnAgregarPagoContainer').show(); // Mostrar botón de agregar
            // Si solo hay un ítem, mostrar su botón de eliminar, si no está ya visible
            if ($('#contenedorPagos .pago-item').length === 1) {
                 $('#contenedorPagos .pago-item:first').find('.eliminar-pago').show();
            }
        }
        actualizarTotalesPagoModal(); // Recalcular totales
        actualizarBotonesEliminar(); // Asegurarse de que los botones eliminar estén bien
    });

    // Función para actualizar todos los eventos en un nuevo elemento de pago
    function actualizarEventosPagoItem(item) {
        item.find('.tipo-pago').off('change').on('change', function() {
            let tipoPago = $(this).val();
            let referenciaContainer = $(this).closest('.pago-item').find('.referencia-container');
            let referenciaInput = $(this).closest('.pago-item').find('.referencia-pago');

            if (tipoPago === 'tarjeta' || tipoPago === 'pago_movil' || tipoPago === 'transferencia') {
                referenciaContainer.slideDown();
                referenciaInput.prop('required', true);
            } else {
                referenciaContainer.slideUp();
                referenciaInput.val('');
                referenciaInput.prop('required', false);
            }
        });
        item.find('.monto-pago').off('input').on('input', function() {
            actualizarTotalesPagoModal();
        });
        item.find('.eliminar-pago').off('click').on('click', function() {
            $(this).closest('.pago-item').remove();
            actualizarTotalesPagoModal();
            actualizarBotonesEliminar();
        });
    }

    // Asegurarse de que el primer elemento de pago tenga los eventos al cargar
    actualizarEventosPagoItem($('#contenedorPagos .pago-item:first'));


    // Función principal para actualizar los montos y el estado del botón Guardar
    function actualizarTotalesPagoModal() {
        let totalPagado = 0;
        $('.monto-pago').each(function() {
            let monto = parseFloat($(this).val()) || 0;
            totalPagado += monto;
        });

        let montoRestante = totalVentaActual - totalPagado;

        // Actualizar los campos en el modal
        $('#totalPagadoModal').val(totalPagado.toFixed(2));
        $('#montoRestanteModal').val(montoRestante.toFixed(2));

        // Cambiar color del Monto Restante
        if (montoRestante > 0) {
            $('#montoRestanteModal').css('color', 'red');
        } else if (montoRestante < 0) {
            $('#montoRestanteModal').css('color', 'orange'); // Si se paga de más
        } else {
            $('#montoRestanteModal').css('color', 'green');
        }

        // Validar y habilitar/deshabilitar el botón "Guardar Venta"
        // No permitir pagos de más del total
        if (montoRestante < 0) {
            $('#btnGuardarPagoModal').prop('disabled', true);
            // Opcional: Mostrar un mensaje al usuario
            // swal.fire({
            //     icon: 'warning',
            //     title: 'Monto Excedido',
            //     text: 'No puedes pagar más del total de la venta.',
            //     toast: true,
            //     position: 'top-end',
            //     showConfirmButton: false,
            //     timer: 3000
            // });
        } else if (montoRestante === 0) {
            $('#btnGuardarPagoModal').prop('disabled', false);
        } else {
            $('#btnGuardarPagoModal').prop('disabled', true);
        }
    }

    // Función para actualizar la visibilidad de los botones de eliminar pago
    function actualizarBotonesEliminar() {
        const numItems = $('#contenedorPagos .pago-item').length;
        if ($('input[name="tipoPagoSeleccion"]:checked').val() === 'simple') {
            $('.eliminar-pago').hide(); // Siempre ocultar en modo simple
        } else {
            if (numItems > 1) {
                $('.eliminar-pago').show(); // Mostrar si hay más de un pago en modo múltiple
            } else {
                $('.eliminar-pago').hide(); // Ocultar si solo hay un pago en modo múltiple
            }
        }
    }


    // Manejar el clic en el botón "Guardar Venta" del modal
    $('#btnGuardarPagoModal').on('click', function() {
        // Validar que el monto restante sea 0 antes de proceder
        if (parseFloat($('#montoRestanteModal').val()) !== 0) {
            swal.fire({
                icon: 'error',
                title: 'Pago Incompleto',
                text: 'El monto total no ha sido cubierto o hay un excedente.',
                confirmButtonText: 'Entendido'
            });
            return; // Detener la ejecución si el pago no es correcto
        }

        // Recolectar todos los datos de los pagos
        let metodosPago = [];
        $('.pago-item').each(function() {
            let tipo = $(this).find('.tipo-pago').val();
            let monto = parseFloat($(this).find('.monto-pago').val()) || 0;
            let referencia = $(this).find('.referencia-pago').val();

            if (tipo && monto > 0) { // Solo si hay tipo y monto
                let pago = {
                    tipo: tipo,
                    monto: monto
                };
                if (referencia) { // Solo añadir referencia si existe
                    pago.referencia = referencia;
                }
                metodosPago.push(pago);
            }
        });

        // Actualizar el campo oculto `listaMetodoPago` en el formulario principal
        // Esto enviará los datos de los pagos al controlador PHP
        $('#listaMetodoPago').val(JSON.stringify(metodosPago));

        // Puedes opcionalmente actualizar el campo `nuevoMetodoPago` si lo usabas para el pago simple,
        // pero con el nuevo esquema, `listaMetodoPago` es más completo.
        // Si el pago es simple, puedes establecer el primer método de pago en `nuevoMetodoPago`.
        if (metodosPago.length === 1) {
            $('#nuevoMetodoPago').val(metodosPago[0].tipo);
        } else {
            $('#nuevoMetodoPago').val('Multiple'); // O un valor que indique pago múltiple
        }


        // Finalmente, enviar el formulario de venta principal
        // NOTA: Asegúrate de que tu controlador PHP esté listo para procesar `listaMetodoPago`
        // como un JSON string y decodificarlo para guardar los detalles de cada pago.
        $('.formularioVenta').submit();

        // Cerrar el modal después de guardar (o después de una respuesta exitosa del servidor)
        $('#modalProcesarPago').modal('hide');
    });

    // Opcional: Cuando el modal se oculta, puedes reiniciar algunos valores
    $('#modalProcesarPago').on('hidden.bs.modal', function (e) {
        // Limpiar o resetear el estado si es necesario
        $('#totalPagadoModal').val('0.00');
        $('#montoRestanteModal').val('0.00');
        $('#montoRestanteModal').css('color', ''); // Resetear color
        $('#btnGuardarPagoModal').prop('disabled', true);
    });

}); // Fin de document ready
</script>