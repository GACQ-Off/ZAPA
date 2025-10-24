<?php
header('Content-Type: application/json');
require_once '../conexion/conexion.php';

$db = $conn;

try {
    $sql = "SELECT id_categoria, nombre_categoria FROM categoria WHERE estado_categoria = '1' ORDER BY nombre_categoria";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $categorias = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    echo json_encode(['categorias' => $categorias]);
} catch (Exception $e) {
    echo json_encode(['error' => 'Error al obtener las categorías: ' . $e->getMessage()]);
}

$db->close();
exit();
?>