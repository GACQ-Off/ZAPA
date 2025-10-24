<?php 
require_once '../conexion/conexion.php';
function obtenerDatosSeguros(mysqli $conn, string $sql, array $params = []) {
    $stmt = $conn->prepare($sql);
    if (!$stmt) return [];
    
    if ($params) {
        $types = str_repeat('s', count($params));
        $stmt->bind_param($types, ...$params);
    }
    
    if (!$stmt->execute()) return [];
    
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}
?>