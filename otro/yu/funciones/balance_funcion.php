<?php
header('Content-Type: application/json');
require_once '../conexion/conexion.php';
session_start();

$fecha_inicio = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$fecha_fin = isset($_GET['end_date']) ? $_GET['end_date'] : '';
$order = isset($_GET['order']) && $_GET['order'] === 'ASC' ? 'ASC' : 'DESC';
$id_categoria = isset($_GET['id_categoria']) ? $_GET['id_categoria'] : '';

if (empty($fecha_inicio) || empty($fecha_fin)) {
    echo json_encode(['error' => 'Fechas no proporcionadas']);
    exit();
}

$db = $conn;

try {
    $sql = "
        SELECT
            p.nombre_producto AS producto,
            SUM(dv.cantidad_vendida) AS cantidad_vendida,
            SUM(dv.cantidad_vendida * dv.precio_unitario_venta_sin_iva) AS cantidad_ganada
        FROM
            detalle_venta dv
        JOIN
            ventas v ON dv.id_ventas = v.id_ventas
        JOIN
            producto p ON dv.id_pro = p.id_pro
    ";

    $params = [];
    $types = "";
    $where_clauses = [];

    $where_clauses[] = "v.fecha_venta BETWEEN ? AND ?";
    $params[] = $fecha_inicio;
    $params[] = $fecha_fin;
    $types .= "ss";

    if (!empty($id_categoria)) {
        $where_clauses[] = "p.id_categoria = ?";
        $params[] = $id_categoria;
        $types .= "i";
    }

    if (!empty($where_clauses)) {
        $sql .= " WHERE " . implode(" AND ", $where_clauses);
    }

    $sql .= "
        GROUP BY
            p.nombre_producto
        ORDER BY
            cantidad_vendida " . $order . "
        LIMIT 10;
    ";

    $stmt = $db->prepare($sql);
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $resultados = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    if (empty($resultados)) {
        echo json_encode(['error' => 'No se encontraron datos para los filtros seleccionados.']);
        exit();
    }

    echo json_encode(['productos' => $resultados]);

} catch (Exception $e) {
    echo json_encode(['error' => 'Error en la consulta: ' . $e->getMessage()]);
}

$db->close();
exit();
?>