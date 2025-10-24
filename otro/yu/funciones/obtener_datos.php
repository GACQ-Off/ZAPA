<?php
header('Content-Type: application/json');

include "../conexion/conexion.php";

if (isset($_GET['tabla'])) {
    $tabla = $_GET['tabla'];
    $sql = "SELECT * FROM $tabla";

    switch ($tabla) {
        case 'categoria':
            $sql = "SELECT id_categoria AS id, nombre_categoria AS nombre FROM $tabla WHERE estado_categoria = 2";
            break;
        case 'producto':
            $sql = "SELECT id_pro AS id, nombre_producto AS nombre FROM $tabla WHERE estado_producto = 2";
            break;
        case 'empleado':
            $sql = "SELECT cedula_emple AS id, nombre_emp AS nombre FROM $tabla WHERE estado_empleado = 2";
            break;
        case 'proveedor':
            $sql = "SELECT RIF AS id, nombre_provedor AS nombre FROM $tabla WHERE estado_proveedor = 2";
            break;
        case 'cargo':
            $sql = "SELECT id_cargo AS id, nom_cargo AS nombre FROM $tabla WHERE estado_cargo = 2";
            break;
        case 'gastos':
            $sql = "SELECT id_gastos AS id, descripcion_gasto AS nombre FROM $tabla WHERE estado_gasto = 2";
            break;
        case 'categoria_gasto':
            $sql = "SELECT id_categoria_gasto AS id, nombre_categoria_gasto AS nombre FROM $tabla WHERE estado_categoria_gasto = 2";
            break;
        case 'usuario':
            $sql = "SELECT id_usuario AS id, nombre_usuario AS nombre FROM $tabla WHERE estado_usuario = 2";
            break;        
        default:
            echo json_encode(['error' => 'Tabla no válida']);
            exit();
    }

    $result = $conn->query($sql);
    $datos = [];

    if ($result) {
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $datos[] = $row;
            }
        }
        echo json_encode($datos);
    } else {
        echo json_encode(['error' => 'Error en la consulta: ' . $conn->error]);
    }

    $conn->close();
} else {
    echo json_encode(['error' => 'No se especificó la tabla']);
}
?>