<?php
require_once('../conexion/conexion.php');

session_start();
require_once '../conexion/conexion.php';

if (!isset($_SESSION['id_usuario']) || !isset($_SESSION['id_tipo_usuario']) || ($_SESSION['id_tipo_usuario'] != 2 && $_SESSION['id_tipo_usuario'] != 1)) {
    header('Location: ../ingreso.php');
    exit();
}

if (!isset($conn) || $conn->connect_error) {
    die("Error crítico: No se pudo establecer la conexión a la base de datos. Por favor, contacte al administrador.");
}

$alertas = ['success' => [], 'error' => []];

if (!isset($_GET['id_venta']) || empty($_GET['id_venta'])) {
    die("Error: No se especificó un ID de venta. <a href='lista_ventas.php'>Volver al listado</a>");
}
$nombre_usuario_actual = $_SESSION['nombre_usuario'] ?? 'Usuario';
$id_venta = intval($_GET['id_venta']);

$conn->set_charset("utf8");

$sql_venta_general = "SELECT
                            v.id_ventas AS ID_Venta,
                            v.fecha_venta AS Fecha_Venta,
                            CASE
                                WHEN v.id_cliente_generico IS NOT NULL THEN CONCAT(cg.nombre, ' ', COALESCE(cg.apellido_cliente_generico, ''))
                                WHEN v.id_cliente_mayor IS NOT NULL THEN CONCAT(cm.nombre, COALESCE(CONCAT(' ', cm.apellido), ''))
                                ELSE 'Cliente General'
                            END AS Nombre_Cliente,
                            u.nombre_usuario AS Nombre_Cajero,
                            v.subtotal_venta AS Subtotal,
                            v.total_iva_venta AS Total_IVA,
                            v.total_neto_venta AS Total_Neto,
                                v.estado_venta AS Estado_Venta
                        FROM
                            ventas v
                        LEFT JOIN cliente_generico cg ON v.id_cliente_generico = cg.id_cliente_generico
                        LEFT JOIN cliente_mayor cm ON v.id_cliente_mayor = cm.id_cliente_mayor
                        JOIN usuario u ON v.id_usuario_registro = u.id_usuario
                        WHERE v.id_ventas = ?";

$stmt_venta_general = $conn->prepare($sql_venta_general);
$stmt_venta_general->bind_param("i", $id_venta);
$stmt_venta_general->execute();
$result_venta_general = $stmt_venta_general->get_result();
$venta_general = $result_venta_general->fetch_assoc();

if (!$venta_general) {
    die("Error: Venta no encontrada. <a href='lista_ventas.php'>Volver al listado</a>");
}

$sql_detalle_productos = "SELECT
                                p.nombre_producto AS Producto,
                                dv.cantidad_vendida AS Cantidad,
                                dv.precio_unitario_venta_sin_iva AS Precio_Unitario_Sin_IVA,
                                dv.subtotal_linea_sin_iva AS Subtotal_Linea_Sin_IVA,
                                dv.monto_iva_linea AS IVA_Linea,
                                dv.total_linea_con_iva AS Total_Linea_Con_IVA
                            FROM
                                detalle_venta dv
                            JOIN
                                producto p ON dv.id_pro = p.id_pro
                            WHERE dv.id_ventas = ?";
$stmt_detalle_productos = $conn->prepare($sql_detalle_productos);
$stmt_detalle_productos->bind_param("i", $id_venta);
$stmt_detalle_productos->execute();
$result_detalle_productos = $stmt_detalle_productos->get_result();

$sql_pagos_venta = "SELECT
                        tp.tipo_pago AS Tipo_Pago,
                        pv.monto_pagado_moneda_principal AS Monto_Pagado_USD,
                        pv.monto_transaccion AS Monto_Transaccion_Otra_Moneda,
                        pv.codigo_moneda_transaccion AS Codigo_Moneda_Transaccion,
                        pv.referencia_pago AS Referencia
                    FROM
                        pagos_venta pv
                    JOIN
                        tipo_pago tp ON pv.id_tipo_pago = tp.id_tipo_pago
                    WHERE pv.id_ventas = ?";
$stmt_pagos_venta = $conn->prepare($sql_pagos_venta);
$stmt_pagos_venta->bind_param("i", $id_venta);
$stmt_pagos_venta->execute();
$result_pagos_venta = $stmt_pagos_venta->get_result();

$credito_venta = null;
if (strpos(strtolower($venta_general['Estado_Venta']), 'credito') !== false) {
    $sql_credito_venta = "SELECT
                                cv.monto_total_credito AS Monto_Credito,
                                cv.monto_abonado AS Monto_Abonado,
                                cv.saldo_pendiente AS Saldo_Pendiente,
                                cv.fecha_vencimiento AS Fecha_Vencimiento_Credito,
                                cv.estado_credito AS Estado_del_Credito
                            FROM
                                creditos_venta cv
                            WHERE cv.id_ventas = ?";
    $stmt_credito_venta = $conn->prepare($sql_credito_venta);
    $stmt_credito_venta->bind_param("i", $id_venta);
    $stmt_credito_venta->execute();
    $result_credito_venta = $stmt_credito_venta->get_result();
    $credito_venta = $result_credito_venta->fetch_assoc();
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de Venta #<?php echo htmlspecialchars($venta_general['ID_Venta']); ?> - Vertex</title>
    <link rel="stylesheet" href="../assets/css/cajero.css">
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background-color: #f4f4f4; color: #333; }
        .container { background-color: #fff; padding: 10px; border-radius: 8px; box-shadow: 0 2px 15px rgba(0,0,0,0.1); }
        h1, h2 { color: #333; border-bottom: 2px solid #007bff; padding-bottom: 10px; }
        h1 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; margin-bottom: 30px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #007bff; color: white; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .info-general p, .credito-info p { margin: 5px 0; }
        .info-general strong, .credito-info strong { display: inline-block; width: 150px; }
        .total-final { font-size: 1.2em; font-weight: bold; text-align: right; margin-top: 10px; }
        .back-link { display: block; text-align: center; margin-top: 30px; color: #007bff; text-decoration: none; font-size: 1.1em; }
        .back-link:hover { text-decoration: underline; }
    </style>
</head>
<body>
<?php include "menu_cajero.php"; ?>

    <div class="container">
        <h1>Detalle de Venta #<?php echo htmlspecialchars($venta_general['ID_Venta']); ?></h1>

        <h2>Información General</h2>
        <div class="info-general">
            <p><strong>Fecha:</strong> <?php echo htmlspecialchars(date("d/m/Y H:i:s", strtotime($venta_general['Fecha_Venta']))); ?></p>
            <p><strong>Cliente:</strong> <?php echo htmlspecialchars($venta_general['Nombre_Cliente']); ?></p>
            <p><strong>Cajero:</strong> <?php echo htmlspecialchars($venta_general['Nombre_Cajero']); ?></p>
            <p><strong>Estado:</strong> <?php echo htmlspecialchars($venta_general['Estado_Venta']); ?></p>
            <p><strong>Subtotal:</strong> <?php echo htmlspecialchars(number_format($venta_general['Subtotal'], 2, ',', '.')); ?></p>
            <p><strong>IVA:</strong> <?php echo htmlspecialchars(number_format($venta_general['Total_IVA'], 2, ',', '.')); ?></p>
            <p class="total-final"><strong>Total Neto:</strong> <?php echo htmlspecialchars(number_format($venta_general['Total_Neto'], 2, ',', '.')); ?></p>
        </div>

        <h2>Productos Vendidos</h2>
        <?php if ($result_detalle_productos->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio Unit. (Sin IVA)</th>
                        <th>Subtotal (Sin IVA)</th>
                        <th>IVA</th>
                        <th>Total Neto (Con IVA)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($producto = $result_detalle_productos->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($producto['Producto']); ?></td>
                            <td><?php echo htmlspecialchars($producto['Cantidad']); ?></td>
                            <td><?php echo htmlspecialchars(number_format($producto['Precio_Unitario_Sin_IVA'], 2, ',', '.')); ?></td>
                            <td><?php echo htmlspecialchars(number_format($producto['Subtotal_Linea_Sin_IVA'], 2, ',', '.')); ?></td>
                            <td><?php echo htmlspecialchars(number_format($producto['IVA_Linea'], 2, ',', '.')); ?></td>
                            <td><?php echo htmlspecialchars(number_format($producto['Total_Linea_Con_IVA'], 2, ',', '.')); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No se encontraron productos para esta venta.</p>
        <?php endif; ?>

        <h2>Detalles de Pago</h2>
        <?php if ($result_pagos_venta->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Tipo de Pago</th>
                        <th>Monto Pagado (USD)</th>
                        <th>Monto Transacción </th>
                        <th>Moneda Transacción</th>
                        <th>Referencia</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($pago = $result_pagos_venta->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($pago['Tipo_Pago']); ?></td>
                            <td><?php echo htmlspecialchars(number_format($pago['Monto_Pagado_USD'], 2, ',', '.')); ?></td>
                            <td><?php echo htmlspecialchars($pago['Monto_Transaccion_Otra_Moneda'] ? number_format($pago['Monto_Transaccion_Otra_Moneda'], 2, ',', '.') : '-'); ?></td>
                            <td><?php echo htmlspecialchars($pago['Codigo_Moneda_Transaccion'] ?: '-'); ?></td>
                            <td><?php echo htmlspecialchars($pago['Referencia'] ?: '-'); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No se encontraron detalles de pago para esta venta.</p>
        <?php endif; ?>

        <?php if ($credito_venta): ?>
            <h2>Información del Crédito</h2>
            <div class="credito-info">
                <p><strong>Monto del Crédito:</strong> <?php echo htmlspecialchars(number_format($credito_venta['Monto_Credito'], 2, ',', '.')); ?></p>
                <p><strong>Monto Abonado:</strong> <?php echo htmlspecialchars(number_format($credito_venta['Monto_Abonado'], 2, ',', '.')); ?></p>
                <p><strong>Saldo Pendiente:</strong> <?php echo htmlspecialchars(number_format($credito_venta['Saldo_Pendiente'], 2, ',', '.')); ?></p>
                <p><strong>Fecha de Vencimiento:</strong> <?php echo htmlspecialchars(date("d/m/Y", strtotime($credito_venta['Fecha_Vencimiento_Credito']))); ?></p>
                <p><strong>Estado del Crédito:</strong> <?php echo htmlspecialchars($credito_venta['Estado_del_Credito']); ?></p>
            </div>
        <?php endif; ?>

        <a href="venta.php" class="back-link">Volver al Listado de Ventas</a>
    </div>

    <?php
    $stmt_venta_general->close();
    $stmt_detalle_productos->close();
    $stmt_pagos_venta->close();
    if (isset($stmt_credito_venta)) {
        $stmt_credito_venta->close();
    }
    $conn->close();
    ?>
</body>
</html>