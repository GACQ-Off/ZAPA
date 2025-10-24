<?php
include "../conexion/conexion.php";

if (isset($_GET['id']) && isset($_GET['tabla'])) {
    $id = $_GET['id'];
    $tabla = $_GET['tabla'];
    $campo_id = '';
    $campo_estado = '';

    switch ($tabla) {
        case 'producto':
            $campo_id = 'id_pro';
            $campo_estado = 'estado_producto';
            break;
        case 'empleado':
            $campo_id = 'cedula_emple';
            $campo_estado = 'estado_empleado';
            break;
        case 'proveedor':
            $campo_id = 'RIF';
            $campo_estado = 'estado_proveedor';
            break;
        case 'categoria':
            $campo_id = 'id_categoria';
            $campo_estado = 'estado_categoria';
            break;
        case 'cargo':
            $campo_id = 'id_cargo';
            $campo_estado = 'estado_cargo';
            break;
        case 'gastos':
            $campo_id = 'id_gastos';
            $campo_estado = 'estado_gasto';
            break;
        case 'categoria_gasto':
            $campo_id = 'id_categoria_gasto';
            $campo_estado = 'estado_categoria_gasto';
            break;
        case 'usuario':
            $campo_id = '   id_usuario';
            $campo_estado = 'estado_usuario';
            break;     
        default:
            echo "Tabla no válida.";
            exit();
    }

    $sql = "UPDATE $tabla SET $campo_estado = 1 WHERE $campo_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $id);

    if ($stmt->execute()) {
        header("Location: ../listas/papelera.php?tabla=$tabla");
        exit();
    } else {
        echo "Error al actualizar el estado: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();

} else {
    echo "Parámetros ID y Tabla no proporcionados.";
}
?>