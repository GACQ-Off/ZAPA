<?php
header('Content-Type: application/json');
require_once '../conexion/conexion.php';
session_start();

$fecha_inicio = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$fecha_fin = isset($_GET['end_date']) ? $_GET['end_date'] : '';

// Si las fechas están vacías, usa el mes actual por defecto
if (empty($fecha_inicio) || empty($fecha_fin)) {
    $fecha_inicio = date('Y-m-01');
    $fecha_fin = date('Y-m-d');
}

// Prepara las variables de fecha para las consultas
$fecha_inicio_ventas = $fecha_inicio . ' 00:00:00';
$fecha_fin_ventas = $fecha_fin . ' 23:59:59';

$db = $conn;

try {
    // CONSULTA CORREGIDA: Ventas Totales (solo ventas completadas)
    $sqlVentas = "
        SELECT COALESCE(SUM(total_neto_venta), 0) AS total_ventas
        FROM ventas 
        WHERE fecha_venta BETWEEN ? AND ?
        AND estado_venta = 'completada'
    ";
    $stmtVentas = $db->prepare($sqlVentas);
    $stmtVentas->bind_param("ss", $fecha_inicio_ventas, $fecha_fin_ventas);
    $stmtVentas->execute();
    $resultVentas = $stmtVentas->get_result()->fetch_assoc();
    $totalVentas = $resultVentas['total_ventas'] ?? 0;

    // CONSULTA CORREGIDA: Costo de Productos (solo compras al contado)
    $sqlCostoProductos = "
        SELECT COALESCE(SUM(cantidad_compra * costo_compra), 0) AS total_costo_productos 
        FROM producto_proveedor 
        WHERE fecha BETWEEN ? AND ?
        AND id_compra_credito_fk IS NULL
    ";
    $stmtCostoProductos = $db->prepare($sqlCostoProductos);
    $stmtCostoProductos->bind_param("ss", $fecha_inicio, $fecha_fin);
    $stmtCostoProductos->execute();
    $resultCostoProductos = $stmtCostoProductos->get_result()->fetch_assoc();
    $totalCostoProductos = $resultCostoProductos['total_costo_productos'] ?? 0;

    // Consulta para Gastos Totales (tabla 'gastos')
    $sqlGastos = "SELECT COALESCE(SUM(monto_gasto), 0) AS total_gastos FROM gastos WHERE fecha_gasto BETWEEN ? AND ?";
    $stmtGastos = $db->prepare($sqlGastos);
    $stmtGastos->bind_param("ss", $fecha_inicio, $fecha_fin);
    $stmtGastos->execute();
    $resultGastos = $stmtGastos->get_result()->fetch_assoc();
    $totalGastos = $resultGastos['total_gastos'] ?? 0;

    // Consulta para Pérdidas Totales (tabla 'perdida')
    $sqlPerdidas = "SELECT COALESCE(SUM(precio_perdida), 0) AS total_perdidas FROM perdida WHERE fecha_perdida BETWEEN ? AND ?";
    $stmtPerdidas = $db->prepare($sqlPerdidas);
    $stmtPerdidas->bind_param("ss", $fecha_inicio, $fecha_fin);
    $stmtPerdidas->execute();
    $resultPerdidas = $stmtPerdidas->get_result()->fetch_assoc();
    $totalPerdidas = $resultPerdidas['total_perdidas'] ?? 0;

    // Calcula el balance neto del negocio
    $gananciaBruta = $totalVentas - $totalCostoProductos;
    $totalEgresos = $totalGastos + $totalPerdidas;
    $balanceNeto = $gananciaBruta - $totalEgresos;
    
    $total_ganancia_neta = 0;
    $total_perdida_neta = 0;

    if ($balanceNeto > 0) {
        $total_ganancia_neta = $balanceNeto;
    } else {
        $total_perdida_neta = abs($balanceNeto);
    }
    
    echo json_encode([
        'total_ventas' => (float)$totalVentas,
        'total_ganancia_neta' => (float)$total_ganancia_neta,
        'total_perdida_neta' => (float)$total_perdida_neta,
        'total_gastos' => (float)$totalGastos,
        'total_perdidas' => (float)$totalPerdidas,
        'total_costos_productos' => (float)$totalCostoProductos
    ]);

} catch (Exception $e) {
    echo json_encode(['error' => 'Error en la consulta: ' . $e->getMessage()]);
}

$db->close();
exit();
?>