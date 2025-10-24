<?php
session_start();
require_once '../conexion/conexion.php';

if (!isset($conn) || $conn->connect_error) {
    die("Error crítico: No se pudo establecer la conexión a la base de datos.");
}

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../ingreso.php"); 
    exit();
}
$id_usuario_sesion = $_SESSION['id_usuario'];

$alert = '';
$creditos_pendientes = [];
$tipos_pago = [];
$tasa_dolar_actual = null;
$saldo_fondo_unico = null;
$post_data = []; 
$moneda_principal_sistema = 'USD'; 
$id_fondo_fijo = 1;

$sql_creditos = "SELECT
                    cc.id_compra_credito,
                    cc.fecha_compra,
                    cc.monto_total_credito,
                    cc.monto_abonado,
                    (cc.monto_total_credito - cc.monto_abonado) AS saldo_pendiente,
                    p.nombre_provedor,
                    cc.RIF_proveedor
                FROM
                    compras_credito cc
                JOIN
                    proveedor p ON cc.RIF_proveedor = p.RIF
                WHERE
                    cc.estado_credito IN ('Pendiente', 'Pagado Parcialmente') AND (cc.monto_total_credito - cc.monto_abonado) > 0
                ORDER BY
                    p.nombre_provedor ASC, cc.fecha_compra ASC";

$result_creditos = $conn->query($sql_creditos);
if ($result_creditos) {
    while ($row = $result_creditos->fetch_assoc()) {
        $creditos_pendientes[] = $row;
    }
} else {
    $alert .= '<p class="msg_error">Error al cargar los créditos pendientes: ' . $conn->error . '</p>';
}

$sql_tipos_pago = "SELECT id_tipo_pago, tipo_pago FROM tipo_pago ORDER BY tipo_pago";
$result_tipos_pago = $conn->query($sql_tipos_pago);
if ($result_tipos_pago) {
    while ($row = $result_tipos_pago->fetch_assoc()) {
        if (stripos($row['tipo_pago'], '(BS)') !== false) {
            $row['moneda_pago_default'] = 'BS';
        } elseif (stripos($row['tipo_pago'], '(USD)') !== false || stripos($row['tipo_pago'], 'Zelle') !== false || stripos($row['tipo_pago'], 'PayPal') !== false) {
            $row['moneda_pago_default'] = 'USD';
        } else {
            $row['moneda_pago_default'] = null; 
        }
        $tipos_pago[] = $row;
    }
} else {
    $alert .= '<p class="msg_error">Error al cargar los tipos de pago: ' . $conn->error . '</p>';
}

$sql_fondo_unico = "SELECT fondo FROM fondo WHERE id_fondo = ?";
$stmt_fondo_unico = $conn->prepare($sql_fondo_unico);
if ($stmt_fondo_unico) {
    $stmt_fondo_unico->bind_param("i", $id_fondo_fijo);
    $stmt_fondo_unico->execute();
    $result_fondo_unico = $stmt_fondo_unico->get_result();
    if ($result_fondo_unico && $row_fondo = $result_fondo_unico->fetch_assoc()) {
        $saldo_fondo_unico = floatval($row_fondo['fondo']);
    } else {
        $alert .= '<p class="msg_error">Error: No se pudo cargar el saldo del fondo principal (ID: '.$id_fondo_fijo.'). Verifique que exista.</p>';
    }
    $stmt_fondo_unico->close();

} else {
    $alert .= '<p class="msg_error">Error al preparar la consulta del fondo: ' . $conn->error . '</p>';
}

$sql_tasa_dolar = "SELECT tasa_dolar FROM tasa_dolar ORDER BY fecha_dolar DESC LIMIT 1";
$result_tasa_dolar = $conn->query($sql_tasa_dolar);
if ($result_tasa_dolar && $row_tasa = $result_tasa_dolar->fetch_assoc()) {
    $tasa_dolar_actual = floatval($row_tasa['tasa_dolar']);
} else {
    $tasa_dolar_actual = null;
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $post_data = $_POST; 

    $id_compra_credito = filter_input(INPUT_POST, 'id_compra_credito', FILTER_VALIDATE_INT);
    $id_tipo_pago = filter_input(INPUT_POST, 'id_tipo_pago', FILTER_VALIDATE_INT);
    $monto_ingresado_input = filter_input(INPUT_POST, 'monto_ingresado', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $tasa_cambio_input = filter_input(INPUT_POST, 'tasa_cambio', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    
    $referencia_pago = htmlspecialchars(trim($_POST['referencia_pago'] ?? ''));
    $notas_pago = htmlspecialchars(trim($_POST['notas_pago'] ?? ''));
    $fecha_pago = date('Y-m-d');

    $monto_ingresado = 0;
    $monto_abono_en_moneda_principal = 0; 
    $codigo_moneda_del_pago = '';
    $tasa_aplicada_guardar = 1.0;

    if (empty($id_compra_credito)) {
        $alert .= '<p class="msg_error">Debe seleccionar una compra a crédito.</p>';
    }
    if (empty($id_tipo_pago)) {
        $alert .= '<p class="msg_error">Debe seleccionar un método de pago.</p>';
    }
    if (empty($monto_ingresado_input) || !is_numeric($monto_ingresado_input) || floatval($monto_ingresado_input) <= 0) {
        $alert .= '<p class="msg_error">El monto ingresado debe ser un número positivo.</p>';
    } else {
        $monto_ingresado = floatval($monto_ingresado_input);
    }

    $tipo_pago_seleccionado = null;
    foreach ($tipos_pago as $tp) {
        if ($tp['id_tipo_pago'] == $id_tipo_pago) {
            $tipo_pago_seleccionado = $tp;
            break;
        }
    }

    if (empty($alert)) {
        
        $credito_seleccionado = null;
        foreach ($creditos_pendientes as $credito) {
            if ($credito['id_compra_credito'] == $id_compra_credito) {
                $credito_seleccionado = $credito;
                break;
            }
        }

        if (!$credito_seleccionado) {
            $alert .= '<p class="msg_error">La compra a crédito seleccionada no es válida o no está pendiente.</p>';
        }

        if (!$tipo_pago_seleccionado && empty($alert)) {
             $alert .= '<p class="msg_error">El método de pago seleccionado no es válido.</p>';
        } else if ($tipo_pago_seleccionado) {
            $codigo_moneda_del_pago = $tipo_pago_seleccionado['moneda_pago_default'];

            if ($codigo_moneda_del_pago === 'BS') {
                if (empty($tasa_cambio_input) || !is_numeric($tasa_cambio_input) || floatval($tasa_cambio_input) <= 0) {
                    $alert .= '<p class="msg_error">Debe ingresar una tasa de cambio válida (Bs. por USD) para pagos en BS.</p>';
                } elseif ($tasa_dolar_actual === null && empty($alert)) {
                     $alert .= '<p class="msg_error">No hay una tasa de dólar de referencia registrada en el sistema para pagos en BS.</p>';
                } else {
                    $tasa_aplicada_guardar = floatval($tasa_cambio_input);
                    $monto_abono_en_moneda_principal = $monto_ingresado / $tasa_aplicada_guardar;
                }
            } elseif ($codigo_moneda_del_pago === 'USD') {
                $monto_abono_en_moneda_principal = $monto_ingresado;
                $tasa_aplicada_guardar = 1.0;
            } else {
                 $alert .= '<p class="msg_error">La moneda para el método de pago seleccionado no está clara. Contacte al administrador.</p>';
            }
        }

        if (empty($alert) && $monto_abono_en_moneda_principal > ($credito_seleccionado['saldo_pendiente'] + 0.009)) { 
            $alert .= '<p class="msg_error">El monto a abonar en USD ('.number_format($monto_abono_en_moneda_principal,2).') no puede ser mayor al saldo pendiente ('.number_format($credito_seleccionado['saldo_pendiente'],2).').</p>';
        }

        if ($saldo_fondo_unico === null && empty($alert)) { 
            $alert .= '<p class="msg_error">No se pudo verificar el saldo del fondo principal.</p>';
        } elseif (empty($alert) && $monto_abono_en_moneda_principal > $saldo_fondo_unico) {
            $alert .= '<p class="msg_error">El fondo principal (ID: '.$id_fondo_fijo.') no tiene saldo suficiente (Disponible: '.number_format($saldo_fondo_unico,2).' USD, Requerido: '.number_format($monto_abono_en_moneda_principal,2).' USD).</p>';
        }
    }

    if (empty($alert)) {
        $conn->begin_transaction();
        try {
            $sql_insert_pago = "INSERT INTO pagos_compras_credito 
                                (id_compra_credito, fecha_pago, monto_pago, id_fondo_origen, id_tipo_pago, 
                                 monto_moneda_pago, codigo_moneda_pago, tasa_cambio_aplicada, 
                                 referencia_pago, id_usuario_registro_pago, notas_pago)
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt_pago = $conn->prepare($sql_insert_pago);
            if (!$stmt_pago) throw new Exception("Error preparando inserción de pago: " . $conn->error);
            
            $stmt_pago->bind_param("isdiidssdss", 
                $id_compra_credito, $fecha_pago, $monto_abono_en_moneda_principal, $id_fondo_fijo, $id_tipo_pago,
                $monto_ingresado, $codigo_moneda_del_pago, $tasa_aplicada_guardar,
                $referencia_pago, $id_usuario_sesion, $notas_pago
            );
            if (!$stmt_pago->execute()) throw new Exception("Error al registrar el pago: " . $stmt_pago->error);
            $stmt_pago->close();

            $nuevo_monto_abonado = $credito_seleccionado['monto_abonado'] + $monto_abono_en_moneda_principal;
            $nuevo_saldo_pendiente = $credito_seleccionado['monto_total_credito'] - $nuevo_monto_abonado;
            
            $nuevo_estado_credito = $credito_seleccionado['estado_credito']; 
            if ($nuevo_saldo_pendiente <= 0.009) { 
                $nuevo_estado_credito = 'Pagado Totalmente';
                $nuevo_saldo_pendiente = 0; 
                $nuevo_monto_abonado = $credito_seleccionado['monto_total_credito'];
            } elseif ($nuevo_monto_abonado > 0) {
                $nuevo_estado_credito = 'Pagado Parcialmente';
            }

            $sql_update_credito = "UPDATE compras_credito SET monto_abonado = ?, estado_credito = ? WHERE id_compra_credito = ?";
            $stmt_credito = $conn->prepare($sql_update_credito);
            if (!$stmt_credito) throw new Exception("Error preparando actualización de crédito: " . $conn->error);

            $stmt_credito->bind_param("dsi", $nuevo_monto_abonado, $nuevo_estado_credito, $id_compra_credito);
            if (!$stmt_credito->execute()) throw new Exception("Error al actualizar el crédito: " . $stmt_credito->error);
            $stmt_credito->close();

            $nuevo_saldo_fondo_calculado = $saldo_fondo_unico - $monto_abono_en_moneda_principal;
            $sql_update_fondo = "UPDATE fondo SET fondo = ? WHERE id_fondo = ?";
            $stmt_fondo = $conn->prepare($sql_update_fondo);
            if (!$stmt_fondo) throw new Exception("Error preparando actualización de fondo: " . $conn->error);

            $stmt_fondo->bind_param("di", $nuevo_saldo_fondo_calculado, $id_fondo_fijo);
            if (!$stmt_fondo->execute()) throw new Exception("Error al actualizar el fondo: " . $stmt_fondo->error);
            $stmt_fondo->close();

            $conn->commit();
            $alert = '<p class="msg_success">Abono de '.number_format($monto_abono_en_moneda_principal,2).' '.$moneda_principal_sistema.' registrado exitosamente (Pago original: '.number_format($monto_ingresado,2).' '.$codigo_moneda_del_pago.').</p>';
            $post_data = [];
            $creditos_pendientes = [];
            $result_creditos_reload = $conn->query($sql_creditos);
            if ($result_creditos_reload) while ($row = $result_creditos_reload->fetch_assoc()) $creditos_pendientes[] = $row;
            
            $saldo_fondo_unico = null;
            $stmt_fondo_unico_reload = $conn->prepare($sql_fondo_unico);
            $stmt_fondo_unico_reload->bind_param("i", $id_fondo_fijo);
            $stmt_fondo_unico_reload->execute();
            $result_fondo_unico_reload = $stmt_fondo_unico_reload->get_result();
            if ($result_fondo_unico_reload && $row_fondo_reload = $result_fondo_unico_reload->fetch_assoc()) $saldo_fondo_unico = floatval($row_fondo_reload['fondo']);
            $stmt_fondo_unico_reload->close();

        } catch (Exception $e) {
            $conn->rollback();
            $alert .= '<p class="msg_error">Error en la transacción: ' . $e->getMessage() . '</p>';
        }
    }
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Abono a Crédito de Compra</title>
    <?php include "../assets/head_gerente.php"; ?>
    <link rel="stylesheet" href="../assets/css/compras.css"> <!-- Puedes reusar o crear un CSS específico -->
    <style>
        .container { margin: 0 0 0 150px; width: 700px; max-width: 100%; box-shadow: 0 2px 10px #0056b3; padding: 20px; }
        .msg_error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; padding: 10px; margin-bottom: 15px; border-radius: 4px; }
        .msg_success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; padding: 10px; margin-bottom: 15px; border-radius: 4px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group input[type="number"],
        .form-group input[type="text"],
        .form-group select,
        .form-group textarea { width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .btn-primary { padding: 10px 15px; background-color: #3533cd; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; }
        .btn-primary:hover { background-color: #0056b3; }
        .btn--cancel{
    background-color: #6c757d; 
    color: white;
     padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 1em;
    text-decoration: none; 
    display: inline-block; 
}
 .btn--cancel:hover {
    background-color: #5a6268;
}

        .hidden { display: none; }
        .currency-indicator { font-weight: normal; color: #777; }
        .saldo-info { font-size: 0.9em; color: #555; margin-top: 5px; }
    </style>
</head>
<body>
    <?php include "../assets/lista_gerente.php"; ?>

    <div class="container">
        <h1>Registrar Abono a Crédito de Compra</h1>

        <?php if (!empty($alert)) echo $alert; ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <div class="form-group">
                <label for="id_compra_credito">Compra a Crédito Pendiente:</label>
                <select name="id_compra_credito" id="id_compra_credito" required onchange="actualizarInfoCredito(this)">
                    <option value="">-- Seleccione un crédito --</option>
                    <?php foreach ($creditos_pendientes as $credito): ?>
                        <option value="<?php echo $credito['id_compra_credito']; ?>" 
                                data-saldo="<?php echo $credito['saldo_pendiente']; ?>"
                                <?php echo (isset($post_data['id_compra_credito']) && $post_data['id_compra_credito'] == $credito['id_compra_credito']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($credito['nombre_provedor']) . " (RIF: " . htmlspecialchars($credito['RIF_proveedor']) . ") - Compra: " . date("d/m/Y", strtotime($credito['fecha_compra'])) . " - Saldo: " . number_format($credito['saldo_pendiente'], 2) . " " . $moneda_principal_sistema; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <div id="saldo_seleccionado_info" class="saldo-info"></div>
            </div>
            
            <div class="form-group">
                <label for="id_tipo_pago">Método de Pago:</label>
                <select name="id_tipo_pago" id="id_tipo_pago" required onchange="gestionarCamposPago(this)">
                    <option value="">-- Seleccione un método de pago --</option>
                    <?php foreach ($tipos_pago as $tp): ?>
                        <option value="<?php echo $tp['id_tipo_pago']; ?>"
                                data-moneda="<?php echo $tp['moneda_pago_default']; ?>"
                                <?php echo (isset($post_data['id_tipo_pago']) && $post_data['id_tipo_pago'] == $tp['id_tipo_pago']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($tp['tipo_pago']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="monto_ingresado">Monto Pagado <span id="monto_currency_indicator" class="currency-indicator"></span>:</label>
                <input type="number" name="monto_ingresado" id="monto_ingresado" step="0.01" min="0.01" required
                       value="<?php echo htmlspecialchars($post_data['monto_ingresado'] ?? ''); ?>">
                <div id="conversion_info" class="saldo-info" style="margin-top: 5px;"></div>
            </div>

            <div class="form-group hidden" id="tasa_cambio_group">
                <label for="tasa_cambio">Tasa de Cambio (Bs. por <?php echo $moneda_principal_sistema; ?>):</label>
                <input type="number" name="tasa_cambio" id="tasa_cambio" step="0.0001" min="0.0001"
                       value="<?php echo htmlspecialchars($post_data['tasa_cambio'] ?? ($tasa_dolar_actual ?? '')); ?>" placeholder="Ej: <?php echo $tasa_dolar_actual ?? '36.50'; ?>" readonly>
                <?php if ($tasa_dolar_actual): ?>
                    <small>Tasa de referencia actual: <?php echo number_format($tasa_dolar_actual, 4); ?></small>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="referencia_pago">Referencia del Pago (Opcional):</label>
                <input type="text" name="referencia_pago" id="referencia_pago"
                       value="<?php echo htmlspecialchars($post_data['referencia_pago'] ?? ''); ?>" placeholder="Ej: Nro. Transacción, Cheque">
            </div>

            <div class="form-group">
                <label for="notas_pago">Notas Adicionales (Opcional):</label>
                <textarea name="notas_pago" id="notas_pago" rows="3"><?php echo htmlspecialchars($post_data['notas_pago'] ?? ''); ?></textarea>
            </div>

            <button type="submit" class="btn-primary">Registrar Abono</button>
             <a href="../registrar/compras.php" class="btn btn--cancel">Regresar</a>
        </form>
    </div>

    <script>
        const monedaPrincipalSistema = '<?php echo $moneda_principal_sistema; ?>';
        const tasaDolarActual = <?php echo json_encode($tasa_dolar_actual); ?>;

        function actualizarInfoCredito(selectElement) {
            const saldoInfoDiv = document.getElementById('saldo_seleccionado_info');
            const selectedOption = selectElement.options[selectElement.selectedIndex];
            if (selectedOption && selectedOption.value !== "") {
                const saldo = parseFloat(selectedOption.dataset.saldo);
                saldoInfoDiv.textContent = "Saldo pendiente del crédito: " + saldo.toFixed(2) + " " + monedaPrincipalSistema;
            } else {
                saldoInfoDiv.textContent = "";
            }
        }

        function gestionarCamposPago(selectElement) {
            const tasaCambioGroup = document.getElementById('tasa_cambio_group');
            const tasaCambioInput = document.getElementById('tasa_cambio');
            const montoCurrencyIndicator = document.getElementById('monto_currency_indicator');
            const montoIngresadoInput = document.getElementById('monto_ingresado');
            const selectedOption = selectElement.options[selectElement.selectedIndex];

            if (selectedOption && selectedOption.value !== "") {
                const monedaPago = selectedOption.dataset.moneda;
                montoCurrencyIndicator.textContent = '(' + monedaPago + ')';
                montoIngresadoInput.placeholder = '0.00 ' + monedaPago;

                if (monedaPago === 'BS') {
                    tasaCambioGroup.classList.remove('hidden');
                    tasaCambioInput.required = true;
                    if (!tasaCambioInput.value && tasaDolarActual) {
                        tasaCambioInput.value = tasaDolarActual.toFixed(4);
                    }
                } else {
                    tasaCambioGroup.classList.add('hidden');
                    tasaCambioInput.required = false;
                    tasaCambioInput.value = '';
                }
            } else {
                montoCurrencyIndicator.textContent = '';
                montoIngresadoInput.placeholder = '0.00';
                tasaCambioGroup.classList.add('hidden');
                tasaCambioInput.required = false;
            }
            actualizarConversionDisplay(); 
        }

        function actualizarConversionDisplay() {
            const montoIngresadoInput = document.getElementById('monto_ingresado');
            const tipoPagoSelect = document.getElementById('id_tipo_pago');
            const tasaCambioInput = document.getElementById('tasa_cambio');
            const conversionInfoDiv = document.getElementById('conversion_info');

            const montoIngresado = parseFloat(montoIngresadoInput.value);
            const selectedTipoPagoOption = tipoPagoSelect.options[tipoPagoSelect.selectedIndex];
            
            conversionInfoDiv.textContent = ''; 

            if (!selectedTipoPagoOption || selectedTipoPagoOption.value === "" || isNaN(montoIngresado) || montoIngresado <= 0) {
                return; 
            }

            const monedaPago = selectedTipoPagoOption.dataset.moneda;

            if (monedaPago === 'BS') {
                const tasa = parseFloat(tasaCambioInput.value);
                if (!isNaN(tasa) && tasa > 0) {
                    const equivalenteUsd = montoIngresado / tasa;
                    conversionInfoDiv.textContent = `Equivalente aprox.: ${equivalenteUsd.toFixed(2)} ${monedaPrincipalSistema}`;
                } else {
                    conversionInfoDiv.textContent = 'Ingrese una tasa de cambio válida para ver el equivalente.';
                }
            } else if (monedaPago === 'USD') {
                if (tasaDolarActual !== null && tasaDolarActual > 0) {
                    const equivalenteBS = montoIngresado * tasaDolarActual;
                    conversionInfoDiv.textContent = `Equivalente aprox.: ${equivalenteBS.toLocaleString('es-VE', { minimumFractionDigits: 2, maximumFractionDigits: 2 })} BS`;
                } else {
                    conversionInfoDiv.textContent = 'Tasa de referencia BS no disponible para mostrar equivalencia.';
                }
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const creditoSelect = document.getElementById('id_compra_credito');
            const tipoPagoSelect = document.getElementById('id_tipo_pago');
            const montoIngresadoInput = document.getElementById('monto_ingresado');
            const tasaCambioInput = document.getElementById('tasa_cambio');

            if (creditoSelect) actualizarInfoCredito(creditoSelect);
            if (tipoPagoSelect) gestionarCamposPago(tipoPagoSelect);

            montoIngresadoInput?.addEventListener('input', actualizarConversionDisplay);
            tasaCambioInput?.addEventListener('input', actualizarConversionDisplay);
        });
    </script>

</body>
</html>