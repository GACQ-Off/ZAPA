<?php
require_once '../conexion/conexion.php';

header('Content-Type: application/json');

if (isset($_GET['id'])) {
    $productId = intval($_GET['id']);
    
    $sql = "SELECT cantidad FROM producto WHERE id_pro = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        echo json_encode(['success' => true, 'stock' => $row['cantidad']]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Producto no encontrado']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'ID no proporcionado']);
}
?>