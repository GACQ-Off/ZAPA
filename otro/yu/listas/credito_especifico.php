<?php
session_start();
require_once "../conexion/conexion.php"; 

if (!isset($_SESSION['id_usuario'])) {
    header('Location: ../ingreso.php');
    exit;
}

if (!$conn) {
    die("Error de conexión a la base de datos: " . mysqli_connect_error());
}

if (!isset($_GET['id_credito']) || !is_numeric($_GET['id_credito'])) {
    echo "<p>Error: ID de crédito no válido.</p>";
    exit;
}

$id_compra_credito = intval($_GET['id_credito']);
$alert = '';

// Obtener tasa de cambio actual (DEBE ESTAR ANTES del procesamiento POST)
$tasa_dolar_actual = 0;
$sql_tasa = "SELECT tasa_dolar FROM tasa_dolar ORDER BY fecha_dolar DESC LIMIT 1";
$result_tasa = mysqli_query($conn, $sql_tasa);
if ($result_tasa && $tasa_row = mysqli_fetch_assoc($result_tasa)) {
    $tasa_dolar_actual = (float)$tasa_row['tasa_dolar'];
}
mysqli_free_result($result_tasa);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['realizar_abono'])) {
    $id_tipo_pago_abono = filter_input(INPUT_POST, 'id_tipo_pago_abono', FILTER_VALIDATE_INT);
    $fecha_pago_abono = $_POST['fecha_pago_abono'];
    $id_fondo_origen_abono = 1; 
    
    // CAMPOS DE CONVERSIÓN - CORREGIDOS
    $moneda_abono = $_POST['moneda_abono'] ?? 'USD';
    $tasa_cambio_aplicada = $tasa_dolar_actual; // Usar la variable ya definida
    $codigo_moneda_pago = $moneda_abono;
    
    // Determinar montos según la moneda seleccionada
    if ($moneda_abono === 'BS') {
        $monto_abono_bs = filter_input(INPUT_POST, 'monto_abono_bs', FILTER_VALIDATE_FLOAT);
        $monto_abono_usd = $monto_abono_bs / $tasa_cambio_aplicada;
        $monto_moneda_pago = $monto_abono_bs;
    } else {
        $monto_abono_usd = filter_input(INPUT_POST, 'monto_abono_usd', FILTER_VALIDATE_FLOAT);
        $monto_abono_bs = $monto_abono_usd * $tasa_cambio_aplicada;
        $monto_moneda_pago = $monto_abono_usd;
    }

    $referencia_pago_abono = trim($_POST['referencia_pago_abono'] ?? '');
    $notas_pago_abono = trim($_POST['notas_pago_abono'] ?? '');
    $id_usuario_actual = $_SESSION['id_usuario'];

    // Validación corregida
    $monto_a_validar = ($moneda_abono === 'BS') ? $monto_abono_bs : $monto_abono_usd;
    
    if ($monto_a_validar === false || $monto_a_validar <= 0) {
        $alert = '<p style="color:red;">El monto del abono debe ser un número positivo.</p>';
    } elseif ($id_tipo_pago_abono === false || $id_tipo_pago_abono <= 0) {
        $alert = '<p style="color:red;">Seleccione un tipo de pago válido.</p>';
    } elseif (empty($fecha_pago_abono) || !DateTime::createFromFormat('Y-m-d', $fecha_pago_abono)) {
        $alert = '<p style="color:red;">Fecha de pago inválida.</p>';
    } else {
        $sql_check_tipo_pago = "SELECT COUNT(*) FROM tipo_pago WHERE id_tipo_pago = ?";
        $stmt_check_tipo_pago = mysqli_prepare($conn, $sql_check_tipo_pago);
        mysqli_stmt_bind_param($stmt_check_tipo_pago, "i", $id_tipo_pago_abono);
        mysqli_stmt_execute($stmt_check_tipo_pago);
        mysqli_stmt_bind_result($stmt_check_tipo_pago, $tipo_pago_exists);
        mysqli_stmt_fetch($stmt_check_tipo_pago);
        mysqli_stmt_close($stmt_check_tipo_pago);

        if ($tipo_pago_exists == 0) {
            $alert = '<p style="color:red;">El tipo de pago seleccionado no es válido.</p>';
        } else {
            mysqli_begin_transaction($conn);

            try {
                $sql_insert_pago = "INSERT INTO pagos_compras_credito 
                    (id_compra_credito, fecha_pago, monto_pago, id_tipo_pago, id_fondo_origen, referencia_pago, notas_pago, id_usuario_registro_pago,
                     monto_moneda_pago, codigo_moneda_pago, tasa_cambio_aplicada) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt_insert_pago = mysqli_prepare($conn, $sql_insert_pago);
                if (!$stmt_insert_pago) {
                    throw new Exception("Error al preparar la inserción del pago: " . mysqli_error($conn));
                }

                // VALORES CORRECTAMENTE ASIGNADOS
                mysqli_stmt_bind_param($stmt_insert_pago, "isdiisssdsd",
                    $id_compra_credito, $fecha_pago_abono, $monto_abono_usd, $id_tipo_pago_abono,
                    $id_fondo_origen_abono, $referencia_pago_abono, $notas_pago_abono, $id_usuario_actual,
                    $monto_moneda_pago, $codigo_moneda_pago, $tasa_cambio_aplicada
                );
                
                if (!mysqli_stmt_execute($stmt_insert_pago)) {
                    throw new Exception("Error al insertar el pago: " . mysqli_stmt_error($stmt_insert_pago));
                }
                mysqli_stmt_close($stmt_insert_pago);

                // Resto del código de transacción...
                $sql_update_fondo = "UPDATE fondo SET fondo = fondo - ? WHERE id_fondo = ?";
                $stmt_update_fondo = mysqli_prepare($conn, $sql_update_fondo);
                if (!$stmt_update_fondo) {
                    throw new Exception("Error al preparar la actualización del fondo: " . mysqli_error($conn));
                }
                mysqli_stmt_bind_param($stmt_update_fondo, "di", $monto_abono_usd, $id_fondo_origen_abono);
                if (!mysqli_stmt_execute($stmt_update_fondo)) {
                    throw new Exception("Error al actualizar el fondo: " . mysqli_stmt_error($stmt_update_fondo));
                }
               
                $sql_get_saldo = "SELECT saldo_pendiente, monto_total_credito FROM compras_credito WHERE id_compra_credito = ?";
                $stmt_get_saldo = mysqli_prepare($conn, $sql_get_saldo);
                if (!$stmt_get_saldo) {
                    throw new Exception("Error al preparar la consulta de saldo: " . mysqli_error($conn));
                }
                mysqli_stmt_bind_param($stmt_get_saldo, "i", $id_compra_credito);
                mysqli_stmt_execute($stmt_get_saldo);
                $result_saldo_actual = mysqli_stmt_get_result($stmt_get_saldo);
                $credito_actual = mysqli_fetch_assoc($result_saldo_actual);
                mysqli_stmt_close($stmt_get_saldo);

                if (!$credito_actual) {
                    throw new Exception("No se pudo obtener la información del crédito para actualizar.");
                }

                if ($monto_abono_usd > $credito_actual['saldo_pendiente']) {
                    throw new Exception("El monto del abono no puede ser mayor al saldo pendiente ($" . number_format($credito_actual['saldo_pendiente'], 2) . ").");
                }

                $nuevo_saldo_pendiente = $credito_actual['saldo_pendiente'] - $monto_abono_usd;
                if ($nuevo_saldo_pendiente < 0) {
                    $nuevo_saldo_pendiente = 0;
                }
                $nuevo_estado_credito = ($nuevo_saldo_pendiente <= 0.005) ? 'Pagado Totalmente' : 'Pagado Parcialmente';

                $sql_update_credito = "UPDATE compras_credito 
                                       SET monto_abonado = monto_abonado + ?, 
                                           saldo_pendiente = ?, 
                                           estado_credito = ?
                                       WHERE id_compra_credito = ?";
                $stmt_update_credito = mysqli_prepare($conn, $sql_update_credito);
                if (!$stmt_update_credito) {
                    throw new Exception("Error al preparar la actualización del crédito: " . mysqli_error($conn));
                }
                mysqli_stmt_bind_param($stmt_update_credito, "ddsi", $monto_abono_usd, $nuevo_saldo_pendiente, $nuevo_estado_credito, $id_compra_credito);
                if (!mysqli_stmt_execute($stmt_update_credito)) {
                    throw new Exception("Error al actualizar el crédito: " . mysqli_stmt_error($stmt_update_credito));
                }
                mysqli_stmt_close($stmt_update_credito);

                mysqli_commit($conn);
                $alert = '<p style="color:green;">Abono registrado exitosamente.</p>';
                header("Location: credito_especifico.php?id_credito=" . $id_compra_credito);
                exit;

            } catch (Exception $e) {
                mysqli_rollback($conn);
                $alert = '<p style="color:red;">Error al registrar el abono: ' . htmlspecialchars($e->getMessage()) . '</p>';
            } finally {
                if (isset($stmt_update_fondo) && $stmt_update_fondo) {
                    mysqli_stmt_close($stmt_update_fondo);
                }
            }
        }
    }
}
$sql_credito_proveedor = "
    SELECT
        cc.id_compra_credito,
        cc.fecha_compra,
        cc.monto_total_credito,
        cc.monto_abonado,
        cc.saldo_pendiente,
        cc.fecha_vencimiento,
        cc.estado_credito,
        cc.notas_compra,
        p.RIF AS proveedor_rif,
        p.nombre_provedor,
        p.telefono_pro,
        p.correo_pro,
        u_reg.nombre_usuario AS usuario_registro_compra
    FROM compras_credito cc
    JOIN proveedor p ON cc.RIF_proveedor = p.RIF
    JOIN usuario u_reg ON cc.id_usuario_registro = u_reg.id_usuario
    WHERE cc.id_compra_credito = ?;
";

$stmt_credito = mysqli_prepare($conn, $sql_credito_proveedor);
if (!$stmt_credito) {
    die("Error al preparar la consulta de crédito: " . mysqli_error($conn));
}
mysqli_stmt_bind_param($stmt_credito, "i", $id_compra_credito);
mysqli_stmt_execute($stmt_credito);
$result_credito = mysqli_stmt_get_result($stmt_credito);
$credito_info = mysqli_fetch_assoc($result_credito);
mysqli_stmt_close($stmt_credito); 

// VERIFICAR SI SE OBTUVO LA INFORMACIÓN
if (!$credito_info) {
    echo "<p>Error: Crédito no encontrado.</p>";
    mysqli_close($conn);
    exit;
}



$sql_productos_compra = "
    SELECT
        pp.id_pro,
        pr.codigo AS codigo_producto,
        pr.nombre_producto,
        pp.cantidad_compra,
        pp.costo_compra,
        (pp.cantidad_compra * pp.costo_compra) AS subtotal_producto
    FROM producto_proveedor pp
    JOIN producto pr ON pp.id_pro = pr.id_pro
    WHERE pp.id_compra_credito_fk = ?;
";
$stmt_productos = mysqli_prepare($conn, $sql_productos_compra);
if (!$stmt_productos) {
    die("Error al preparar la consulta de productos: " . mysqli_error($conn));
}
mysqli_stmt_bind_param($stmt_productos, "i", $id_compra_credito);
mysqli_stmt_execute($stmt_productos);
$result_productos = mysqli_stmt_get_result($stmt_productos);

$sql_pagos_credito = "
    SELECT
        pc.fecha_pago,
        pc.monto_pago,
        tp.tipo_pago,
        pc.monto_moneda_pago,
        pc.codigo_moneda_pago,
        pc.tasa_cambio_aplicada,
        pc.referencia_pago,
        pc.notas_pago,
        u_pago.nombre_usuario AS usuario_registro_pago
    FROM pagos_compras_credito pc
    LEFT JOIN tipo_pago tp ON pc.id_tipo_pago = tp.id_tipo_pago
    JOIN usuario u_pago ON pc.id_usuario_registro_pago = u_pago.id_usuario
    WHERE pc.id_compra_credito = ?
    ORDER BY pc.fecha_pago ASC;
";
$stmt_pagos = mysqli_prepare($conn, $sql_pagos_credito);
if (!$stmt_pagos) {
    die("Error al preparar la consulta de pagos: " . mysqli_error($conn));
}
mysqli_stmt_bind_param($stmt_pagos, "i", $id_compra_credito);
mysqli_stmt_execute($stmt_pagos);
$result_pagos = mysqli_stmt_get_result($stmt_pagos);
// No cerrar aquí, se usará en el HTML

// 4. Obtener tipos de pago para el formulario de abono
$sql_tipos_pago = "SELECT id_tipo_pago, tipo_pago FROM tipo_pago ORDER BY tipo_pago";
$result_tipos_pago = mysqli_query($conn, $sql_tipos_pago);
$tipos_pago_options = [];
if ($result_tipos_pago) {
    while ($row = mysqli_fetch_assoc($result_tipos_pago)) {
        $tipos_pago_options[] = $row;
    }
    mysqli_free_result($result_tipos_pago);
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de Crédito de Compra #<?php echo htmlspecialchars($credito_info['id_compra_credito']); ?></title>
    <?php include "../assets/head_gerente.php"; // O el head correspondiente ?>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; padding: 0; background-color: #f4f4f4; color: #333; }
        .container { max-width: 900px; margin: 20px auto; padding: 25px; background-color: #fff; border-radius: 8px; box-shadow: 0 0 15px rgba(0,0,0,0.1); }
        h1, h2, h3 { color: #333; }
        h1 { text-align: center; color: #3533cd; margin-bottom: 25px;}
        h2 { border-bottom: 2px solid #3533cd; padding-bottom: 10px; margin-top: 30px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #3533cd; color: white; }
        .info-section p { margin: 5px 0; }
        .info-section strong { display: inline-block; width: 180px; }
        .text-right { text-align: right; }
        .form-abono { margin-top: 20px; padding: 20px; background-color: #f9f9f9; border-radius: 5px; border: 1px solid #eee;}
        .form-abono label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-abono input[type="number"], .form-abono input[type="date"], .form-abono select, .form-abono textarea, .form-abono input[type="text"] { width: calc(100% - 22px); padding: 10px; margin-bottom: 10px; border: 1px solid #ccc; border-radius: 4px; }
        .form-abono button { background-color: #28a745; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; font-size: 1em; }
        .form-abono button:hover { background-color: #218838; }
        .alert-message { padding: 10px; margin-bottom: 15px; border-radius: 4px; }
        .alert-message p { margin: 0; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Detalle de Crédito #<?php echo htmlspecialchars($credito_info['id_compra_credito']); ?></h1>
        <?php if (!empty($alert)) echo "<div class='alert-message'>" . $alert . "</div>"; ?>

        <h2>Información del Proveedor</h2>
        <div class="info-section">
            <p><strong>Nombre:</strong> <?php echo htmlspecialchars($credito_info['nombre_provedor']); ?></p>
            <p><strong>RIF:</strong> <?php echo htmlspecialchars($credito_info['proveedor_rif']); ?></p>
            <p><strong>Teléfono:</strong> <?php echo htmlspecialchars($credito_info['telefono_pro']); ?></p>
            <p><strong>Correo:</strong> <?php echo htmlspecialchars($credito_info['correo_pro']); ?></p>
        </div>

        <h2>Información del Crédito</h2>
        <div class="info-section">
            <p><strong>Fecha de Compra:</strong> <?php echo htmlspecialchars(date("d/m/Y", strtotime($credito_info['fecha_compra']))); ?></p>
            <p><strong>Monto Total:</strong> $<?php echo htmlspecialchars(number_format($credito_info['monto_total_credito'], 2)); ?></p>
            <p><strong>Monto Abonado:</strong> $<?php echo htmlspecialchars(number_format($credito_info['monto_abonado'] ?? 0, 2)); ?></p>
            <p><strong>Saldo Pendiente:</strong> $<?php echo htmlspecialchars(number_format($credito_info['saldo_pendiente'], 2)); ?></p>
            <p><strong>Fecha de Vencimiento:</strong> <?php echo $credito_info['fecha_vencimiento'] ? htmlspecialchars(date("d/m/Y", strtotime($credito_info['fecha_vencimiento']))) : 'N/A'; ?></p>
            <p><strong>Estado:</strong> <?php echo htmlspecialchars($credito_info['estado_credito']); ?></p>
            <p><strong>Notas de la Compra:</strong> <?php echo nl2br(htmlspecialchars($credito_info['notas_compra'])); ?></p>
            <p><strong>Registrado por (Compra):</strong> <?php echo htmlspecialchars($credito_info['usuario_registro_compra']); ?></p>
        </div>

        <h2>Productos de la Compra</h2>
        <?php if (mysqli_num_rows($result_productos) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Costo Unitario ($)</th>
                        <th class="text-right">Subtotal ($)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($producto = mysqli_fetch_assoc($result_productos)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($producto['codigo_producto']); ?></td>
                            <td><?php echo htmlspecialchars($producto['nombre_producto']); ?></td>
                            <td><?php echo htmlspecialchars(number_format($producto['cantidad_compra'], 2)); ?></td>
                            <td><?php echo htmlspecialchars(number_format($producto['costo_compra'], 2)); ?></td>
                            <td class="text-right">$<?php echo htmlspecialchars(number_format($producto['subtotal_producto'], 2)); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No hay productos asociados a esta compra a crédito.</p>
        <?php endif; ?>

        <h2>Historial de Pagos</h2>
        <?php if (mysqli_num_rows($result_pagos) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Fecha Pago</th>
                        <th>Monto Pagado ($)</th>
                        <th>Tipo de Pago</th>
                        <th>Monto Trans.</th>
                        <th>Moneda Trans.</th>
                        <th>Tasa</th>
                        <th>Referencia</th>
                        <th>Notas</th>
                        <th>Registrado por</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($pago = mysqli_fetch_assoc($result_pagos)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars(date("d/m/Y H:i", strtotime($pago['fecha_pago']))); ?></td>
                            <td><?php echo htmlspecialchars(number_format($pago['monto_pago'], 2)); ?></td>
                            <td><?php echo htmlspecialchars($pago['tipo_pago']); ?></td>
                            <td><?php echo $pago['monto_moneda_pago'] ? htmlspecialchars(number_format($pago['monto_moneda_pago'], 2)) : '-'; ?></td>
                            <td><?php echo htmlspecialchars($pago['codigo_moneda_pago'] ?? '-'); ?></td>
                            <td><?php echo $pago['tasa_cambio_aplicada'] ? htmlspecialchars(number_format($pago['tasa_cambio_aplicada'], 2)) : '-'; ?></td>
                            <td><?php echo htmlspecialchars($pago['referencia_pago'] ?? '-'); ?></td>
                            <td><?php echo nl2br(htmlspecialchars($pago['notas_pago'] ?? '-')); ?></td>
                            <td><?php echo htmlspecialchars($pago['usuario_registro_pago']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No se han registrado pagos para este crédito.</p>
        <?php endif; ?>

        <?php 
// Obtener tasa de cambio actual
$tasa_dolar_actual = 0;
$sql_tasa = "SELECT tasa_dolar FROM tasa_dolar ORDER BY fecha_dolar DESC LIMIT 1";
$result_tasa = mysqli_query($conn, $sql_tasa);
if ($result_tasa && $tasa_row = mysqli_fetch_assoc($result_tasa)) {
    $tasa_dolar_actual = (float)$tasa_row['tasa_dolar'];
}
mysqli_free_result($result_tasa);
?>

<?php if ($credito_info['saldo_pendiente'] > 0 && $credito_info['estado_credito'] !== 'Pagado Totalmente'): ?>
<h2>Realizar Abono</h2>
<div class="form-abono">
    <form action="credito_especifico.php?id_credito=<?php echo $id_compra_credito; ?>" method="POST" id="formAbono">
        <input type="hidden" name="id_compra_credito" value="<?php echo $id_compra_credito; ?>">
        
        <!-- Información de tasa actual -->
        <div style="background: #e7f3ff; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
            <strong>Tasa de cambio actual:</strong> 1 USD = <?php echo number_format($tasa_dolar_actual, 2); ?> BS
        </div>
        
        <!-- Selección de moneda -->
        <div>
            <label for="moneda_abono">Moneda del Abono:</label>
            <select id="moneda_abono" name="moneda_abono" required onchange="cambiarMoneda()">
                <option value="USD">Dólares (USD)</option>
                <option value="BS">Bolívares (BS)</option>
            </select>
        </div>
        
        <!-- SOLO UN CAMPO VISIBLE A LA VEZ -->
        <div id="div_monto_bs" style="display: none;">
            <label for="monto_abono_bs">Monto a Abonar (BS):</label>
            <input type="number" id="monto_abono_bs" name="monto_abono_bs" step="0.01" min="0.01" 
                   oninput="calcularEquivalente('BS')" placeholder="0.00">
        </div>
        
        <div id="div_monto_usd">
            <label for="monto_abono_usd">Monto a Abonar (USD):</label>
            <input type="number" id="monto_abono_usd" name="monto_abono_usd" step="0.01" min="0.01" 
                   max="<?php echo htmlspecialchars(max(0.01, $credito_info['saldo_pendiente'])); ?>" 
                   oninput="calcularEquivalente('USD')" required>
        </div>
        
        <!-- Mostrar equivalencia -->
        <div id="equivalencia" style="background: #f0f8ff; padding: 8px; border-radius: 4px; margin: 10px 0; display: none;">
            <strong>Equivalencia:</strong> <span id="texto_equivalencia"></span>
        </div>
        
        <!-- Campos ocultos para enviar datos de conversión -->
        <input type="hidden" name="tasa_cambio_aplicada" id="tasa_cambio_aplicada" value="<?php echo $tasa_dolar_actual; ?>">
        <input type="hidden" name="monto_moneda_pago" id="monto_moneda_pago" value="0">
        <input type="hidden" name="codigo_moneda_pago" id="codigo_moneda_pago" value="USD">
        
        <div>
            <label for="id_tipo_pago_abono">Tipo de Pago:</label>
            <select id="id_tipo_pago_abono" name="id_tipo_pago_abono" required>
                <option value="">Seleccione un tipo de pago</option>
                <?php foreach ($tipos_pago_options as $tipo_pago): ?>
                    <option value="<?php echo htmlspecialchars($tipo_pago['id_tipo_pago']); ?>">
                        <?php echo htmlspecialchars($tipo_pago['tipo_pago']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
      
                
                <div>
                    <label for="fecha_pago_abono">Fecha del Abono:</label>
                    <input type="date" id="fecha_pago_abono" name="fecha_pago_abono" value="<?php echo date('Y-m-d'); ?>" required>
                </div>
                
                <div>
                    <label for="referencia_pago_abono">Referencia (Opcional):</label>
                    <input type="text" id="referencia_pago_abono" name="referencia_pago_abono">
                </div>
                <div>
                    <label for="notas_pago_abono">Notas (Opcional):</label>
                    <textarea id="notas_pago_abono" name="notas_pago_abono" rows="3"></textarea>
                </div>
                <button type="submit" name="realizar_abono">Registrar Abono</button>
            </form>
        </div>
        <?php endif; ?>
        <p style="text-align:center; margin-top:30px;"><a href="lista_compras.php" class="btn btn-secondary">Volver al Historial de Compras</a></p>

    </div>
</body>
<script>
function cambiarMoneda() {
    const monedaSelect = document.getElementById('moneda_abono');
    const divMontoBS = document.getElementById('div_monto_bs');
    const divMontoUSD = document.getElementById('div_monto_usd');
    const montoUSD = document.getElementById('monto_abono_usd');
    const montoBS = document.getElementById('monto_abono_bs');
    const codigoMonedaPago = document.getElementById('codigo_moneda_pago');
    const equivalenciaDiv = document.getElementById('equivalencia');
    
    if (monedaSelect.value === 'BS') {
        divMontoBS.style.display = 'block';
        divMontoUSD.style.display = 'none';
        montoBS.required = true;
        montoUSD.required = false;
        montoUSD.value = '';
        codigoMonedaPago.value = 'BS';
    } else {
        divMontoBS.style.display = 'none';
        divMontoUSD.style.display = 'block';
        montoBS.required = false;
        montoUSD.required = true;
        montoBS.value = '';
        codigoMonedaPago.value = 'USD';
        if (equivalenciaDiv) equivalenciaDiv.style.display = 'none';
    }
}

function calcularEquivalente(monedaOrigen) {
    const tasa = parseFloat(<?php echo json_encode($tasa_dolar_actual); ?>);
    const montoUSD = document.getElementById('monto_abono_usd');
    const montoBS = document.getElementById('monto_abono_bs');
    const equivalenciaDiv = document.getElementById('equivalencia');
    const textoEquivalencia = document.getElementById('texto_equivalencia');
    const monedaSelect = document.getElementById('moneda_abono');
    const montoMonedaPago = document.getElementById('monto_moneda_pago');
    
    if (!montoUSD || !montoBS || !monedaSelect) return;
    
    if (monedaSelect.value === 'BS' && monedaOrigen === 'BS') {
        const montoBsValue = parseFloat(montoBS.value) || 0;
        const equivalenteUSD = montoBsValue / tasa;
        
        montoUSD.value = equivalenteUSD.toFixed(2);
        if (textoEquivalencia) {
            textoEquivalencia.textContent = montoBsValue.toFixed(2) + ' BS = ' + equivalenteUSD.toFixed(2) + ' USD';
        }
        if (equivalenciaDiv) equivalenciaDiv.style.display = 'block';
        if (montoMonedaPago) montoMonedaPago.value = montoBsValue;
        
    } else if (monedaSelect.value === 'USD' && monedaOrigen === 'USD') {
        const montoUsdValue = parseFloat(montoUSD.value) || 0;
        const equivalenteBS = montoUsdValue * tasa;
        
        if (textoEquivalencia) {
            textoEquivalencia.textContent = montoUsdValue.toFixed(2) + ' USD = ' + equivalenteBS.toFixed(2) + ' BS';
        }
        if (equivalenciaDiv) equivalenciaDiv.style.display = 'block';
        if (montoMonedaPago) montoMonedaPago.value = montoUsdValue;
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const formAbono = document.getElementById('formAbono');
    if (formAbono) {
        formAbono.addEventListener('submit', function(e) {
            const montoUSD = parseFloat(document.getElementById('monto_abono_usd').value);
            const saldoPendiente = parseFloat(<?php echo json_encode($credito_info['saldo_pendiente']); ?>);
            
            if (montoUSD > saldoPendiente) {
                e.preventDefault();
                alert('El monto del abono no puede exceder el saldo pendiente ($' + saldoPendiente.toFixed(2) + ')');
            }
        });
    }
    
    cambiarMoneda(); // Inicializar al cargar
});
</script>
</html>
<?php
mysqli_stmt_close($stmt_productos); 
mysqli_stmt_close($stmt_pagos); 
mysqli_close($conn);
?>