2<?php
require_once '../conexion/conexion.php';

header('Content-Type: application/json');

$response = ['success' => false, 'data' => [], 'message' => ''];

// Registrar parámetros recibidos para depuración
error_log("buscar_clientes.php recibió: tipo_cliente=" . ($_GET['tipo'] ?? 'N/A') . ", query_term=" . ($_GET['q'] ?? 'N/A'));

if (!isset($conn) || $conn->connect_error) {
    $response['message'] = "Error de conexión a la base de datos: " . $conn->connect_error;
    error_log("Error de conexión en buscar_clientes.php: " . $conn->connect_error);
    echo json_encode($response);
    exit();
}

$tipo_cliente = $_GET['tipo'] ?? '';
$query_term = $_GET['q'] ?? '';

if (empty($tipo_cliente) || empty($query_term)) {
    $response['message'] = "Parámetros de búsqueda incompletos. Asegúrese de que 'tipo' y 'q' estén presentes y no vacíos.";
    error_log("Parámetros incompletos en buscar_clientes.php: tipo={$tipo_cliente}, q={$query_term}");
    echo json_encode($response);
    exit();
}

$search_param = '%' . $conn->real_escape_string($query_term) . '%';
$stmt = null;

try {
    if ($tipo_cliente === 'generico') {
        // CORRECCIÓN: Se eliminó la condición 'estado_cliente_generico = 1'
        $sql = "SELECT id_cliente_generico, cedula, nombre, apellido_cliente_generico AS apellido 
                FROM cliente_generico 
                WHERE (
                    nombre LIKE ? OR 
                    apellido_cliente_generico LIKE ? OR 
                    cedula LIKE ?
                ) LIMIT 10"; // Limitar resultados para eficiencia
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            $response['message'] = "Error al preparar la consulta de cliente genérico: " . $conn->error;
            error_log("Error al preparar SQL (generico): " . $conn->error . " | SQL: " . $sql);
            echo json_encode($response);
            exit();
        }
        $stmt->bind_param("sss", $search_param, $search_param, $search_param);
    } elseif ($tipo_cliente === 'mayorista') {
        // CORRECCIÓN: Se eliminó la condición 'estado_cliente_mayor = 1'
        $sql = "SELECT id_cliente_mayor, cedula_identidad, nombre, apellido 
                FROM cliente_mayor 
                WHERE (
                    nombre LIKE ? OR 
                    apellido LIKE ? OR 
                    cedula_identidad LIKE ?
                ) LIMIT 10"; // Limitar resultados para eficiencia
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            $response['message'] = "Error al preparar la consulta de cliente mayorista: " . $conn->error;
            error_log("Error al preparar SQL (mayorista): " . $conn->error . " | SQL: " . $sql);
            echo json_encode($response);
            exit();
        }
        $stmt->bind_param("sss", $search_param, $search_param, $search_param);
    } else {
        $response['message'] = "Tipo de cliente no válido.";
        error_log("Tipo de cliente inválido: " . $tipo_cliente);
        echo json_encode($response);
        exit();
    }

    if (!$stmt->execute()) {
        $response['message'] = "Error al ejecutar la consulta: " . $stmt->error;
        error_log("Error al ejecutar SQL: " . $stmt->error);
        echo json_encode($response);
        exit();
    }

    $result = $stmt->get_result();
    $clientes = [];
    while ($row = $result->fetch_assoc()) {
        $clientes[] = $row;
    }

    $response['success'] = true;
    $response['data'] = $clientes;

} catch (Exception $e) {
    $response['message'] = "Error inesperado en la búsqueda: " . $e->getMessage();
    error_log("Error inesperado en buscar_clientes.php: " . $e->getMessage());
} finally {
    if ($stmt) {
        $stmt->close();
    }
    $conn->close();
}

echo json_encode($response);
?>
