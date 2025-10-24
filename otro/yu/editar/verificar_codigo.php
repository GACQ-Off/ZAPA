<?php
require_once '../conexion/conexion.php';

header('Content-Type: application/json');

if (!isset($_GET['codigo'])) {
    echo json_encode(['existe' => false]);
    exit();
}

$codigo = trim($_GET['codigo']);
$exclude_id = isset($_GET['exclude']) ? intval($_GET['exclude']) : 0;

if (empty($codigo)) {
    echo json_encode(['existe' => false]);
    exit();
}

$sql = "SELECT id_pro, nombre_producto FROM producto WHERE codigo_barras = ?";
$params = [$codigo];

if ($exclude_id > 0) {
    $sql .= " AND id_pro != ?";
    $params[] = $exclude_id;
}

$stmt = $conn->prepare($sql);
if ($stmt) {
    $types = str_repeat('s', count($params));
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode(['existe' => true, 'producto' => $row['nombre_producto']]);
    } else {
        echo json_encode(['existe' => false]);
    }
    $stmt->close();
} else {
    echo json_encode(['existe' => false, 'error' => $conn->error]);
}
?>