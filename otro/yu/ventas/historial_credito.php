<?php
session_start();
require_once '../conexion/conexion.php';

if (!isset($_SESSION['id_usuario']) || !isset($_SESSION['id_tipo_usuario']) || $_SESSION['id_tipo_usuario'] != 2) {
    header('Location: ../ingreso.php');
    exit();
}

if (!isset($conn) || $conn->connect_error) {
    die("Error crítico: No se pudo establecer la conexión a la base de datos. Por favor, contacte al administrador.");
}

date_default_timezone_set('America/Caracas');
$nombre_usuario_actual = $_SESSION['nombre_usuario'] ?? 'Usuario';
$alertas = ['success' => [], 'error' => []];
$id_usuario_actual = $_SESSION['id_usuario'];
$id_cajero_actual = null;
$nombre_cajero_actual = $_SESSION['nombre_usuario'] ?? 'Cajero';
$action = $_GET['action'] ?? 'lista_creditos';
$id_credito_seleccionado = $_GET['id_credito'] ?? null;
$id_venta_seleccionada = $_GET['id_venta'] ?? null;

function obtener_creditos_pendientes($conn) {
    $creditos = [];
    $sql = "SELECT
                cv.id_credito_venta,
                cv.id_ventas,
                cv.monto_total_credito,
                cv.monto_abonado,
                cv.fecha_credito,
                cv.fecha_vencimiento,
                cv.estado_credito,
                v.id_cliente_generico,
                v.id_cliente_mayor,
                cg.nombre AS nombre_cliente_generico,
                cg.apellido_cliente_generico AS apellido_cliente_generico,
                cg.cedula AS cedula_cliente_generico,
                cm.nombre AS nombre_cliente_mayor,
                cm.apellido AS apellido_cliente_mayor,
                cm.cedula_identidad AS identificacion_cliente_mayor
            FROM
                creditos_venta cv
            JOIN
                ventas v ON cv.id_ventas = v.id_ventas
            LEFT JOIN
                cliente_generico cg ON v.id_cliente_generico = cg.id_cliente_generico
            LEFT JOIN
                cliente_mayor cm ON v.id_cliente_mayor = cm.id_cliente_mayor
            WHERE
                cv.estado_credito IN ('Pendiente', 'Pagado Parcialmente')
            ORDER BY
                cv.fecha_credito DESC";

    $result = $conn->query($sql);

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $row['saldo_pendiente'] = $row['monto_total_credito'] - $row['monto_abonado'];
            $creditos[] = $row;
        }
        $result->free();
    } else {
        error_log("Error fetching pending credits: " . $conn->error);
    }
    return $creditos;
}

function obtener_detalles_credito($conn, $id_venta) {
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

    $sql_productos = "SELECT
                        dv.cantidad_vendida,
                        dv.precio_unitario_venta_sin_iva,
                        dv.porcentaje_iva_aplicado,
                        dv.subtotal_linea_sin_iva,
                        dv.monto_iva_linea,
                        dv.total_linea_con_iva,
                        p.nombre_producto,
                        p.codigo AS codigo_producto
                      FROM
                        detalle_venta dv
                      JOIN
                        producto p ON dv.id_pro = p.id_pro
                      WHERE
                        dv.id_ventas = ?";
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

    $sql_pagos = "SELECT
                    pv.monto_transaccion,
                    pv.codigo_moneda_transaccion,
                    pv.referencia_pago,
                    tp.tipo_pago
                  FROM
                    pagos_venta pv
                  JOIN
                    tipo_pago tp ON pv.id_tipo_pago = tp.id_tipo_pago
                  WHERE
                    pv.id_ventas = ?";
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

$lista_creditos = [];
$detalles_credito = null;

if ($action === 'lista_creditos') {
    $lista_creditos = obtener_creditos_pendientes($conn);
} elseif ($action === 'detalle_credito' && $id_venta_seleccionada) {
    $detalles_credito = obtener_detalles_credito($conn, $id_venta_seleccionada);
    if (!$detalles_credito) {
        $alertas['error'][] = "No se encontraron detalles para el crédito/venta seleccionado.";
        $action = 'lista_creditos';
        $lista_creditos = obtener_creditos_pendientes($conn);
    }
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Créditos - Sistema Yu</title>
    <link rel="stylesheet" href="../assets/css/cajero.css">
    <link rel="stylesheet" href="assets/fonts/google-icons/index.css">
    <style>
        .container {
            padding: 10px;
        }
        h1, h2 {
            color: #333;
            margin-bottom: 20px;
        }
        .table-container {
            overflow-x: auto;
            margin-top: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        .btn {
            display: inline-block;
            padding: 8px 15px;
            border-radius: 5px;
            text-decoration: none;
            color: white;
            background-color: #007bff;
            border: none;
            cursor: pointer;
            margin-right: 5px;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        .btn-info {
            background-color: #17a2b8;
        }
        .btn-info:hover {
            background-color: #138496;
        }
        .detalle-section {
            background-color: #fff;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 25px;
            margin-top: 30px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
        }
        .detalle-section h3 {
            color: #555;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .detalle-section p {
            margin-bottom: 10px;
        }
        .detalle-section strong {
            color: #000;
        }
    </style>
</head>
<body>
    <?php include "menu_cajero.php"; ?>

    <div class="container">
        <?php foreach ($alertas['success'] as $msg): ?>
            <div class="alert-success"><?php echo htmlspecialchars($msg); ?></div>
        <?php endforeach; ?>
        <?php foreach ($alertas['error'] as $msg): ?>
            <div class="alert-error"><?php echo htmlspecialchars($msg); ?></div>
        <?php endforeach; ?>

        <?php if ($action === 'lista_creditos'): ?>
            <h1><span class="material-symbols-outlined ico-credit_score"></span> Historial de Créditos Pendientes</h1>
            <div class="table-container">
                <?php if (!empty($lista_creditos)): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Cliente</th>
                                <th>C.I/RIF</th>
                                <th>Fecha Crédito</th>
                                <th>Fecha Vencimiento</th>
                                <th>Monto Total (USD)</th>
                                <th>Monto Abonado (USD)</th>
                                <th>Saldo Pendiente (USD)</th>
                                <th>Estado</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($lista_creditos as $credito): ?>
                                <tr>
                                    <td>
                                        <?php
                                            if ($credito['id_cliente_generico']) {
                                                echo htmlspecialchars($credito['nombre_cliente_generico'] . ' ' . $credito['apellido_cliente_generico']);
                                            } elseif ($credito['id_cliente_mayor']) {
                                                echo htmlspecialchars($credito['nombre_cliente_mayor'] . ($credito['apellido_cliente_mayor'] ? ' ' . $credito['apellido_cliente_mayor'] : ''));
                                            } else {
                                                echo 'N/A';
                                            }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                            if ($credito['id_cliente_generico']) {
                                                echo htmlspecialchars($credito['cedula_cliente_generico']);
                                            } elseif ($credito['id_cliente_mayor']) {
                                                echo htmlspecialchars($credito['identificacion_cliente_mayor']);
                                            } else {
                                                echo 'N/A';
                                            }
                                        ?>
                                    </td>
                                    <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($credito['fecha_credito']))); ?></td>
                                    <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($credito['fecha_vencimiento']))); ?></td>
                                    <td><?php echo number_format($credito['monto_total_credito'], 2); ?></td>
                                    <td><?php echo number_format($credito['monto_abonado'], 2); ?></td>
                                    <td><?php echo number_format($credito['saldo_pendiente'], 2); ?></td>
                                    <td><?php echo htmlspecialchars($credito['estado_credito']); ?></td>
                                    <td>
                                        <a href="abono.php?action=detalle_credito&id_venta=<?php echo htmlspecialchars($credito['id_ventas']); ?>" class="btn btn-info">Ver Detalles</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No hay créditos pendientes registrados en el sistema.</p>
                <?php endif; ?>
            </div>

        <?php elseif ($action === 'detalle_credito' && $detalles_credito): ?>
            <h1><span class="material-symbols-outlined">receipt_long</span> Detalle del Crédito / Venta #<?php echo htmlspecialchars($detalles_credito['id_ventas']); ?></h1>
            <a href="historial_creditos.php" class="btn" style="margin-bottom: 20px;">Volver al Historial de Créditos</a>

            <div class="detalle-section">
                <h3>Información del Crédito</h3>
                <p><strong>ID Crédito:</strong> <?php echo htmlspecialchars($detalles_credito['id_credito_venta']); ?></p>
                <p><strong>Fecha del Crédito:</strong> <?php echo htmlspecialchars(date('d/m/Y', strtotime($detalles_credito['fecha_credito']))); ?></p>
                <p><strong>Fecha de Vencimiento:</strong> <?php echo htmlspecialchars(date('d/m/Y', strtotime($detalles_credito['fecha_vencimiento']))); ?></p>
                <p><strong>Monto Total del Crédito:</strong> $<?php echo number_format($detalles_credito['monto_total_credito'], 2); ?></p>
                <p><strong>Monto Abonado:</strong> $<?php echo number_format($detalles_credito['monto_abonado'], 2); ?></p>
                <p><strong>Saldo Pendiente:</strong> $<?php echo number_format($detalles_credito['saldo_pendiente'], 2); ?></p>
                <p><strong>Estado del Crédito:</strong> <?php echo htmlspecialchars($detalles_credito['estado_credito']); ?></p>
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
                    <p><strong>Teléfono:</strong> <?php echo htmlspecialchars($detalles_credito['telefono_cliente_mayor']); ?></p>
                    <p><strong>Correo:</strong> <?php echo htmlspecialchars($detalles_credito['correo_cliente_mayor']); ?></p>
                    <p><strong>Dirección:</strong> <?php echo htmlspecialchars($detalles_credito['direccion_cliente_mayor']); ?></p>
                <?php else: ?>
                    <p>Información del cliente no disponible.</p>
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
                                <th>Subtotal (USD)</th>
                                <th>Monto IVA (USD)</th>
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
                                    <td><?php echo number_format($producto['subtotal_linea_sin_iva'], 2); ?></td>
                                    <td><?php echo number_format($producto['monto_iva_linea'], 2); ?></td>
                                    <td><?php echo number_format($producto['total_linea_con_iva'], 2); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No hay productos registrados para esta venta.</p>
                <?php endif; ?>
            </div>

            <div class="detalle-section">
                <h3>Pagos Realizados</h3>
                <?php if (!empty($detalles_credito['pagos'])): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Tipo de Pago</th>
                                <th>Monto Transacción</th>
                                <th>Moneda</th>
                                <th>Referencia</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($detalles_credito['pagos'] as $pago): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($pago['tipo_pago']); ?></td>
                                    <td><?php echo number_format($pago['monto_transaccion'], 2); ?></td>
                                    <td><?php echo htmlspecialchars($pago['codigo_moneda_transaccion']); ?></td>
                                    <td><?php echo htmlspecialchars($pago['referencia_pago'] ?: 'N/A'); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No se han registrado pagos para esta venta.</p>
                <?php endif; ?>
            </div>

        <?php endif; ?>
    </div>
</body>
</html>