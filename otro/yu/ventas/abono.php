<?php
session_start();
require_once '../conexion/conexion.php';

if (!isset($_SESSION['id_usuario']) || !isset($_SESSION['id_tipo_usuario']) || $_SESSION['id_tipo_usuario'] != 2) {
    header('Location: ../ingreso.php');
    exit();
}

if (!isset($conn) || $conn->connect_error) {
    die("Error crítico: No se pudo establecer la conexión a la base de datos.");
}

date_default_timezone_set('America/Caracas');
$id_usuario_actual = $_SESSION['id_usuario'];
$id_cajero_actual = null;
$nombre_cajero_actual = $_SESSION['nombre_usuario'] ?? 'Cajero'; 

$alertas = ['success' => [], 'error' => []];
$id_usuario_actual = $_SESSION['id_usuario'];
$nombre_usuario_actual = $_SESSION['nombre_usuario'] ?? 'Cajero';

$id_venta_seleccionada = isset($_GET['id_venta']) ? (int)$_GET['id_venta'] : null;
$detalles_credito = null;
$tasa_dolar_actual = null;
$id_tasa_dolar_actual = null;
$tipos_pago = [];

function obtener_tasa_dolar_actual($conn) {
    $sql = "SELECT id_tasa_dolar, tasa_dolar FROM tasa_dolar ORDER BY fecha_dolar DESC LIMIT 1";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    return null;
}

function obtener_tipos_pago($conn) {
    $tipos = [];
    $sql = "SELECT id_tipo_pago, tipo_pago FROM tipo_pago ORDER BY tipo_pago ASC";
    $result = $conn->query($sql);
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $tipos[] = $row;
        }
    }
    return $tipos;
}

function obtener_detalles_credito_abono($conn, $id_venta) {
    $detalles = null;
    $sql_credito_venta = "SELECT 
                            cv.id_credito_venta,
                            cv.monto_total_credito,
                            cv.monto_abonado,
                            cv.fecha_credito,
                            cv.fecha_vencimiento,
                            cv.estado_credito,
                            v.id_ventas,
                            v.fecha_venta,
                            v.subtotal_venta,
                            v.total_iva_venta,
                            v.total_neto_venta,
                            v.id_fondo AS id_fondo_venta,
                            cg.nombre AS nombre_cliente_generico,
                            cg.apellido_cliente_generico AS apellido_cliente_generico,
                            cg.cedula AS cedula_cliente_generico,
                            cm.nombre AS nombre_cliente_mayor,
                            cm.apellido AS apellido_cliente_mayor,
                            cm.cedula_identidad AS identificacion_cliente_mayor,
                            cm.telefono AS telefono_cliente_mayor,
                            cm.correo AS correo_cliente_mayor,
                            cm.direccion AS direccion_cliente_mayor
                          FROM 
                            creditos_venta cv
                          JOIN 
                            ventas v ON cv.id_ventas = v.id_ventas
                          LEFT JOIN 
                            cliente_generico cg ON v.id_cliente_generico = cg.id_cliente_generico
                          LEFT JOIN 
                            cliente_mayor cm ON v.id_cliente_mayor = cm.id_cliente_mayor
                          WHERE 
                            v.id_ventas = ?";
    $stmt_credito_venta = $conn->prepare($sql_credito_venta);
    if (!$stmt_credito_venta) {
        error_log("Error preparando la consulta de detalles de crédito: " . $conn->error);
        return null;
    }
    $stmt_credito_venta->bind_param("i", $id_venta);
    $stmt_credito_venta->execute();
    $result_credito_venta = $stmt_credito_venta->get_result();
    if ($info_credito_venta = $result_credito_venta->fetch_assoc()) {
        $detalles = $info_credito_venta;
        $detalles['saldo_pendiente'] = $info_credito_venta['monto_total_credito'] - $info_credito_venta['monto_abonado'];
    } else {
        $stmt_credito_venta->close();
        return null; 
    }
    $stmt_credito_venta->close();

    $sql_productos = "SELECT dv.cantidad_vendida, dv.precio_unitario_venta_sin_iva, dv.porcentaje_iva_aplicado, dv.subtotal_linea_sin_iva, dv.monto_iva_linea, dv.total_linea_con_iva, p.nombre_producto, p.codigo AS codigo_producto
                      FROM detalle_venta dv JOIN producto p ON dv.id_pro = p.id_pro WHERE dv.id_ventas = ?";
    $stmt_productos = $conn->prepare($sql_productos);
    $stmt_productos->bind_param("i", $id_venta);
    $stmt_productos->execute();
    $result_productos = $stmt_productos->get_result();
    $productos_venta = [];
    while ($row = $result_productos->fetch_assoc()) {
        $productos_venta[] = $row;
    }
    $detalles['productos'] = $productos_venta;
    $stmt_productos->close();

    $sql_pagos = "SELECT pv.monto_transaccion, pv.codigo_moneda_transaccion, pv.referencia_pago, tp.tipo_pago, pv.fecha_pago, pv.monto_pagado_moneda_principal
                  FROM pagos_venta pv JOIN tipo_pago tp ON pv.id_tipo_pago = tp.id_tipo_pago WHERE pv.id_ventas = ? ORDER BY pv.fecha_pago ASC";
    $stmt_pagos = $conn->prepare($sql_pagos);
    $stmt_pagos->bind_param("i", $id_venta);
    $stmt_pagos->execute();
    $result_pagos = $stmt_pagos->get_result();
    $pagos_venta = [];
    while ($row = $result_pagos->fetch_assoc()) {
        $pagos_venta[] = $row;
    }
    $detalles['pagos'] = $pagos_venta;
    $stmt_pagos->close();

    return $detalles;
}


$tasa_info = obtener_tasa_dolar_actual($conn);
if ($tasa_info) {
    $tasa_dolar_actual = (float)$tasa_info['tasa_dolar'];
    $id_tasa_dolar_actual = (int)$tasa_info['id_tasa_dolar'];
} else {
    $alertas['error'][] = "No se pudo obtener la tasa de dólar actual. No se pueden procesar pagos en BS.";
}

$tipos_pago = obtener_tipos_pago($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['procesar_abono'])) {
    if (!$id_venta_seleccionada) {
        $alertas['error'][] = "ID de venta no especificado.";
    } elseif ($tasa_dolar_actual === null && $_POST['moneda_pago'] === 'BS') {
        $alertas['error'][] = "No hay tasa de dólar configurada para procesar pagos en BS.";
    } else {
        $monto_a_pagar_input = (float)($_POST['monto_a_pagar'] ?? 0);
        $moneda_pago = $_POST['moneda_pago'] ?? 'USD';
        $id_tipo_pago = (int)($_POST['id_tipo_pago'] ?? 0);
        $referencia_pago = trim($_POST['referencia_pago'] ?? '');

        if ($monto_a_pagar_input <= 0) {
            $alertas['error'][] = "El monto a pagar debe ser mayor que cero.";
        } elseif (empty($id_tipo_pago)) {
            $alertas['error'][] = "Debe seleccionar un método de pago.";
        } else {
            $detalles_credito_actual = obtener_detalles_credito_abono($conn, $id_venta_seleccionada);
            if ($detalles_credito_actual) {
                $saldo_pendiente_usd_actual = (float)$detalles_credito_actual['saldo_pendiente'];
                $monto_total_credito_usd = (float)$detalles_credito_actual['monto_total_credito'];
                $monto_abonado_actual_usd = (float)$detalles_credito_actual['monto_abonado'];
                $id_fondo_venta = (int)$detalles_credito_actual['id_fondo_venta'];

                $monto_pagado_en_usd = 0;
                $monto_transaccion_original = $monto_a_pagar_input;
                $codigo_moneda_transaccion_original = $moneda_pago;
                $id_tasa_aplicada_pago = null;

                if ($moneda_pago === 'BS') {
                    $monto_pagado_en_usd = $monto_a_pagar_input / $tasa_dolar_actual;
                    $id_tasa_aplicada_pago = $id_tasa_dolar_actual;
                } else {
                    $monto_pagado_en_usd = $monto_a_pagar_input;
                }
                
                $monto_pagado_en_usd = round($monto_pagado_en_usd, 2);

                if ($monto_pagado_en_usd > $saldo_pendiente_usd_actual) {
                    $monto_pagado_en_usd = $saldo_pendiente_usd_actual;
                    if ($moneda_pago === 'BS') {
                        $monto_transaccion_original = round($monto_pagado_en_usd * $tasa_dolar_actual, 2);
                        $alertas['success'][] = "El monto ingresado excede el saldo. Se ajustó el pago al saldo pendiente: " . number_format($monto_transaccion_original, 2) . " BS ($" . number_format($monto_pagado_en_usd, 2) . ").";
                    } else {
                           $monto_transaccion_original = $monto_pagado_en_usd;
                           $alertas['success'][] = "El monto ingresado excede el saldo. Se ajustó el pago al saldo pendiente: $" . number_format($monto_pagado_en_usd, 2) . ".";
                    }
                }
                
                if ($monto_pagado_en_usd > 0) {
                    $conn->begin_transaction();
                    try {
                        $nuevo_monto_abonado_usd = $monto_abonado_actual_usd + $monto_pagado_en_usd;
                        $nuevo_saldo_pendiente_usd = $monto_total_credito_usd - $nuevo_monto_abonado_usd;

                        $estado_credito_nuevo = 'Pagado Parcialmente';
                        if ($nuevo_saldo_pendiente_usd <= 0.005) {
                            $estado_credito_nuevo = 'Pagado Totalmente';
                            $nuevo_monto_abonado_usd = $monto_total_credito_usd;
                            $nuevo_saldo_pendiente_usd = 0;
                        }

                        $sql_update_credito = "UPDATE creditos_venta SET monto_abonado = ?, estado_credito = ? WHERE id_ventas = ?";
                        $stmt_update_credito = $conn->prepare($sql_update_credito);
                        $stmt_update_credito->bind_param("dsi", $nuevo_monto_abonado_usd, $estado_credito_nuevo, $id_venta_seleccionada);
                        $stmt_update_credito->execute();
                        $stmt_update_credito->close();

                        $sql_insert_pago = "INSERT INTO pagos_venta (id_ventas, id_tipo_pago, monto_pagado_moneda_principal, monto_transaccion, codigo_moneda_transaccion, id_tasa_dolar_aplicada, referencia_pago, id_usuario_registro) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                        $stmt_insert_pago = $conn->prepare($sql_insert_pago);
                        $stmt_insert_pago->bind_param("iidssdss", $id_venta_seleccionada, $id_tipo_pago, $monto_pagado_en_usd, $monto_transaccion_original, $codigo_moneda_transaccion_original, $id_tasa_aplicada_pago, $referencia_pago, $id_usuario_actual);
                        $stmt_insert_pago->execute();
                        $stmt_insert_pago->close();

                        $sql_update_fondo = "UPDATE fondo SET fondo = fondo + ? WHERE id_fondo = ?";
                        $stmt_update_fondo = $conn->prepare($sql_update_fondo);
                        $id_fondo_a_actualizar = 1;
                        $stmt_update_fondo->bind_param("di", $monto_pagado_en_usd, $id_fondo_a_actualizar);
                        $stmt_update_fondo->execute();
                        $stmt_update_fondo->close();
                        
                        $conn->commit();
                        $alertas['success'][] = "Abono procesado exitosamente.";
                        header("Location: abono.php?id_venta=" . $id_venta_seleccionada . "&abono_exitoso=1");
                        exit();

                    } catch (mysqli_sql_exception $e) {
                        $conn->rollback();
                        $alertas['error'][] = "Error al procesar el abono: " . $e->getMessage();
                        error_log("Error en transacción de abono: " . $e->getMessage());
                    }
                } else {
                    $alertas['error'][] = "El monto a pagar (convertido a USD) es cero o negativo después del ajuste.";
                }
            } else {
                $alertas['error'][] = "No se pudieron obtener los detalles del crédito para procesar el pago.";
            }
        }
    }
}

if (isset($_GET['abono_exitoso'])) {
    $alertas['success'][] = "Abono procesado exitosamente.";
}

if ($id_venta_seleccionada) {
    $detalles_credito = obtener_detalles_credito_abono($conn, $id_venta_seleccionada);
    if (!$detalles_credito) {
        $alertas['error'][] = "No se encontraron detalles para el crédito/venta seleccionado o el ID es incorrecto.";
    }
} else {
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Abono a Crédito - Sistema Yu</title>
    <link rel="stylesheet" href="../assets/css/cajero.css">
 <link rel="stylesheet" href="assets/fonts/google-icons/index.css">
     <style>
        body { font-family: Arial, sans-serif; margin: 0; background-color: #f4f6f9; }
        .container { max-width: 900px; margin: 20px auto; padding: 20px; background-color: #fff; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h1, h2, h3 { color: #333; }
        .detalle-section, .abono-section { background-color: #fff; border: 1px solid #e0e0e0; border-radius: 8px; padding: 20px; margin-top: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
        .detalle-section h3, .abono-section h3 { color: #555; border-bottom: 1px solid #eee; padding-bottom: 10px; margin-bottom: 15px; }
        .detalle-section p, .abono-section p { margin-bottom: 8px; line-height: 1.6; }
        .detalle-section strong { color: #000; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group input[type="text"], .form-group input[type="number"], .form-group select {
            width: calc(100% - 22px); padding: 10px; border: 1px solid #ccc; border-radius: 4px;
        }
        .btn { display: inline-block; padding: 10px 18px; border-radius: 5px; text-decoration: none; color: white; background-color: #007bff; border: none; cursor: pointer; font-size: 16px; }
        .btn:hover { background-color: #0056b3; }
        .btn-success { background-color: #28a745; }
        .btn-success:hover { background-color: #218838; }
        .alert-success { background-color: #d4edda; color: #155724; padding: 12px; border-radius: 5px; margin-bottom: 15px; border: 1px solid #c3e6cb;}
        .alert-error { background-color: #f8d7da; color: #721c24; padding: 12px; border-radius: 5px; margin-bottom: 15px; border: 1px solid #f5c6cb;}
        .currency-info { font-size: 0.9em; color: #555; margin-top: 5px; }
        .saldo-pendiente { font-size: 1.2em; font-weight: bold; }
        .pagado { color: green; }
        .pendiente { color: orange; }
        .parcialmente-pagado { color: #FF8C00; }
    </style>
</head>
<body>
    <?php include "menu_cajero.php"; ?>

    <div class="container">
        <h1><span class="material-symbols-outlined">payment</span> Abono a Crédito</h1>

        <?php foreach ($alertas['success'] as $msg): ?>
            <div class="alert-success"><?php echo htmlspecialchars($msg); ?></div>
        <?php endforeach; ?>
        <?php foreach ($alertas['error'] as $msg): ?>
            <div class="alert-error"><?php echo htmlspecialchars($msg); ?></div>
        <?php endforeach; ?>

        <?php if ($id_venta_seleccionada && $detalles_credito): ?>
            <div class="detalle-section">
                <h3>Detalle del Crédito / Venta #<?php echo htmlspecialchars($detalles_credito['id_ventas']); ?></h3>
                <p><strong>ID Crédito:</strong> <?php echo htmlspecialchars($detalles_credito['id_credito_venta']); ?></p>
                <p><strong>Fecha del Crédito:</strong> <?php echo htmlspecialchars(date('d/m/Y', strtotime($detalles_credito['fecha_credito']))); ?></p>
                <p><strong>Fecha de Vencimiento:</strong> <?php echo htmlspecialchars(date('d/m/Y', strtotime($detalles_credito['fecha_vencimiento']))); ?></p>
                <p><strong>Monto Total del Crédito:</strong> $<?php echo number_format($detalles_credito['monto_total_credito'], 2); ?>
                    <?php if ($tasa_dolar_actual): ?>
                        (Bs. <?php echo number_format($detalles_credito['monto_total_credito'] * $tasa_dolar_actual, 2); ?>)
                    <?php endif; ?>
                </p>
                <p><strong>Monto Abonado:</strong> $<?php echo number_format($detalles_credito['monto_abonado'], 2); ?>
                    <?php if ($tasa_dolar_actual): ?>
                        (Bs. <?php echo number_format($detalles_credito['monto_abonado'] * $tasa_dolar_actual, 2); ?>)
                    <?php endif; ?>
                </p>
                <p class="saldo-pendiente"><strong>Saldo Pendiente:</strong> $<?php echo number_format($detalles_credito['saldo_pendiente'], 2); ?>
                    <?php if ($tasa_dolar_actual): ?>
                        (Bs. <?php echo number_format($detalles_credito['saldo_pendiente'] * $tasa_dolar_actual, 2); ?>)
                    <?php endif; ?>
                </p>
                <p><strong>Estado del Crédito:</strong> 
                    <span class="<?php echo strtolower(str_replace(' ', '-', $detalles_credito['estado_credito'])); ?>">
                        <?php echo htmlspecialchars($detalles_credito['estado_credito']); ?>
                    </span>
                </p>
            </div>

            <div class="detalle-section">
                <h3>Información del Cliente</h3>
                <?php if ($detalles_credito['nombre_cliente_generico']): ?>
                    <p><strong>Tipo de Cliente:</strong> Genérico</p>
                    <p><strong>Nombre:</strong> <?php echo htmlspecialchars($detalles_credito['nombre_cliente_generico'] . ' ' . $detalles_credito['apellido_cliente_generico']); ?></p>
                    <p><strong>Cédula:</strong> <?php echo htmlspecialchars($detalles_credito['cedula_cliente_generico']); ?></p>
                <?php elseif ($detalles_credito['nombre_cliente_mayor']): ?>
                    <p><strong>Tipo de Cliente:</strong> Mayorista</p>
                    <p><strong>Nombre/Razón Social:</strong> <?php echo htmlspecialchars($detalles_credito['nombre_cliente_mayor'] . ($detalles_credito['apellido_cliente_mayor'] ? ' ' . $detalles_credito['apellido_cliente_mayor'] : '')); ?></p>
                    <p><strong>Identificación:</strong> <?php echo htmlspecialchars($detalles_credito['identificacion_cliente_mayor']); ?></p>
                <?php else: ?>
                    <p>Información del cliente no disponible.</p>
                <?php endif; ?>
            </div>

            <?php if ($detalles_credito['saldo_pendiente'] > 0.005): ?>
            <div class="abono-section">
                <h3>Realizar Abono</h3>
                <?php if ($tasa_dolar_actual): ?>
                    <p class="currency-info">Tasa de cambio actual: 1 USD = <?php echo number_format($tasa_dolar_actual, 2); ?> BS</p>
                <?php else: ?>
                    <p class="alert-error">No se puede procesar pagos en BS porque no hay tasa de cambio configurada.</p>
                <?php endif; ?>

                <form action="abono.php?id_venta=<?php echo $id_venta_seleccionada; ?>" method="POST">
                    <input type="hidden" name="procesar_abono" value="1">
                    <div class="form-group">
                        <label for="monto_a_pagar">Monto a Pagar:</label>
                        <input type="number" id="monto_a_pagar" name="monto_a_pagar" step="0.01" min="0.01" required>
                    </div>
                    <div class="form-group">
                        <label for="moneda_pago">Moneda de Pago:</label>
                        <select id="moneda_pago" name="moneda_pago" required>
                            <option value="USD">USD</option>
                            <?php if ($tasa_dolar_actual): ?>
                            <option value="BS">BS</option>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="id_tipo_pago">Método de Pago:</label>
                        <select id="id_tipo_pago" name="id_tipo_pago" required>
                            <option value="">Seleccione...</option>
                            <?php foreach ($tipos_pago as $tipo): ?>
                                <option value="<?php echo $tipo['id_tipo_pago']; ?>"><?php echo htmlspecialchars($tipo['tipo_pago']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="referencia_pago">Referencia (si aplica):</label>
                        <input type="text" id="referencia_pago" name="referencia_pago">
                    </div>
                    <button type="submit" class="btn btn-success">Procesar Abono</button>
                </form>
            </div>
            <?php else: ?>
            <div class="detalle-section">
                   <p class="alert-success">Este crédito ya ha sido pagado en su totalidad.</p>
            </div>
            <?php endif; ?>


            <div class="detalle-section">
                <h3>Historial de Pagos Realizados para esta Venta</h3>
                <?php if (!empty($detalles_credito['pagos'])): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Fecha Pago</th>
                                <th>Tipo de Pago</th>
                                <th>Monto Transacción</th>
                                <th>Moneda</th>
                                <th>Monto (USD)</th>
                                <th>Referencia</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($detalles_credito['pagos'] as $pago): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($pago['fecha_pago']))); ?></td>
                                    <td><?php echo htmlspecialchars($pago['tipo_pago']); ?></td>
                                    <td><?php echo number_format($pago['monto_transaccion'], 2); ?></td>
                                    <td><?php echo htmlspecialchars($pago['codigo_moneda_transaccion']); ?></td>
                                    <td>$<?php echo number_format($pago['monto_pagado_moneda_principal'], 2); ?></td>
                                    <td><?php echo htmlspecialchars($pago['referencia_pago'] ?: 'N/A'); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No se han registrado pagos para esta venta/crédito.</p>
                <?php endif; ?>
            </div>
            
            <div class="detalle-section">
                <h3>Productos Vendidos</h3>
                   <?php if (!empty($detalles_credito['productos'])): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Código</th>
                                <th>Cantidad</th>
                                <th>Precio Unit. (USD)</th>
                                <th>IVA (%)</th>
                                <th>Total Línea (USD)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($detalles_credito['productos'] as $producto): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($producto['nombre_producto']); ?></td>
                                    <td><?php echo htmlspecialchars($producto['codigo_producto']); ?></td>
                                    <td><?php echo number_format($producto['cantidad_vendida'], 2); ?></td>
                                    <td><?php echo number_format($producto['precio_unitario_venta_sin_iva'], 2); ?></td>
                                    <td><?php echo number_format($producto['porcentaje_iva_aplicado'], 2); ?></td>
                                    <td><?php echo number_format($producto['total_linea_con_iva'], 2); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No hay productos registrados para esta venta.</p>
                <?php endif; ?>
            </div>


        <?php elseif ($id_venta_seleccionada && !$detalles_credito): ?>
            <p class="alert-error">No se pudo cargar la información del crédito. Verifique el ID o contacte al administrador.</p>
        <?php else: ?>
            <p>Por favor, seleccione un crédito desde el <a href="historial_credito.php">historial de créditos</a> para ver sus detalles y realizar abonos.</p>
        <?php endif; ?>
        <br>
        <a href="historial_credito.php" class="btn" style="margin-top: 20px;">Volver al Historial de Créditos</a>
    </div>
</body>
</html>