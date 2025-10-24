<?php
if (basename(__FILE__) == basename($_SERVER['SCRIPT_FILENAME'])) {
    die("Acceso directo denegado.");
}
require_once __DIR__ . '/conexion_notificaciones.php';
$total_cantidad = 0;
$total_creditos_vencidos = 0;
$total_creditos_pendientes = 0;
$total_proveedores_vencidos = 0;
$total_proveedores_pendientes = 0;
if (isset($conn_notificaciones) && $conn_notificaciones) {
    $sql_cantidad = "SELECT COUNT(*) AS total FROM producto WHERE estado_producto = '1' AND cantidad < 10";
    $resultado_cantidad = $conn_notificaciones->query($sql_cantidad);
    if ($resultado_cantidad) {
        $total_cantidad = $resultado_cantidad->fetch_assoc()['total'];
    }
    $sql_creditos = "SELECT fecha_vencimiento FROM creditos_venta WHERE estado_credito IN ('Pendiente', 'Pagado Parcialmente')";
    $resultado_creditos = $conn_notificaciones->query($sql_creditos);
    if ($resultado_creditos) {
        while ($fila = $resultado_creditos->fetch_assoc()) {
            if (strtotime($fila['fecha_vencimiento']) < strtotime(date('Y-m-d'))) {
                $total_creditos_vencidos++;
            } else {
                $total_creditos_pendientes++;
            }
        }
    }
    $sql_proveedores = "SELECT fecha_vencimiento FROM compras_credito WHERE estado_credito IN ('Pendiente', 'Pagado Parcialmente')";
    $resultado_proveedores = $conn_notificaciones->query($sql_proveedores);
    if ($resultado_proveedores) {
        while ($fila = $resultado_proveedores->fetch_assoc()) {
            if (strtotime($fila['fecha_vencimiento']) < strtotime(date('Y-m-d'))) {
                $total_proveedores_vencidos++;
            } else {
                $total_proveedores_pendientes++;
            }
        }
    }
    $conn_notificaciones->close();
}
$contador_notificaciones = $total_cantidad + $total_creditos_vencidos + $total_creditos_pendientes + $total_proveedores_vencidos + $total_proveedores_pendientes;