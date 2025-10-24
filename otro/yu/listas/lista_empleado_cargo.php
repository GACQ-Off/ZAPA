<?php
session_start();
require_once '../conexion/conexion.php';
include('../assets/head_gerente.php');

if (!isset($conn) || !($conn instanceof mysqli) || $conn->connect_error) {
    die("Error crítico de conexión a la base de datos: " . ($conn->connect_error ?? "Error desconocido"));
}

$id_cargo_seleccionado = isset($_GET['Cargo']) ? (int)$_GET['Cargo'] : null;
$nombre_cargo = "Todos los Empleados"; 
$empleados_cargo = [];

if ($id_cargo_seleccionado) {
    $sql_nombre_cargo = "SELECT nom_cargo FROM cargo WHERE id_cargo = ?";
    $stmt_nombre_cargo = $conn->prepare($sql_nombre_cargo);
    if ($stmt_nombre_cargo) {
        $stmt_nombre_cargo->bind_param("i", $id_cargo_seleccionado);
        $stmt_nombre_cargo->execute();
        $result_nombre_cargo = $stmt_nombre_cargo->get_result();
        if ($result_nombre_cargo && $result_nombre_cargo->num_rows > 0) {
            $row_nombre_cargo = $result_nombre_cargo->fetch_assoc();
            $nombre_cargo = $row_nombre_cargo['nom_cargo'];
        }
        $stmt_nombre_cargo->close();
    }

    $sql_empleados = "SELECT
                            e.cedula_emple,
                            e.nombre_emp,
                            e.apellido_emp
                        FROM
                            empleado e
                        WHERE
                            e.id_cargo = ?";
    $stmt_empleados = $conn->prepare($sql_empleados);
    if ($stmt_empleados) {
        $stmt_empleados->bind_param("i", $id_cargo_seleccionado);
        $stmt_empleados->execute();
        $result_empleados = $stmt_empleados->get_result();
        if ($result_empleados && $result_empleados->num_rows > 0) {
            while ($row_empleado = $result_empleados->fetch_assoc()) {
                $empleados_cargo[] = $row_empleado;
            }
        }
        $stmt_empleados->close();
    } else {
        echo "<p class='mensaje-error'>Error al preparar la consulta de empleados por cargo: " . htmlspecialchars($conn->error) . "</p>";
    }
} else {
    $sql_todos_empleados = "SELECT
                                e.cedula_emple,
                                e.nombre_emp,
                                e.apellido_emp
                            FROM
                                empleado e";
    $result_todos_empleados = $conn->query($sql_todos_empleados);
    if ($result_todos_empleados && $result_todos_empleados->num_rows > 0) {
        while ($row_empleado = $result_todos_empleados->fetch_assoc()) {
            $empleados_cargo[] = $row_empleado;
        }
    } else {
        echo "<p class='mensaje-info'>No hay empleados registrados.</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Empleados por Cargo</title>
    <link rel="stylesheet" href="../assets/css/lista_productos.css">
</head>
<body>
    <?php include('../assets/lista_gerente.php'); ?>
    <div class="container mt-4 lista-container">
        <h2>Empleados con el Cargo: <?php echo htmlspecialchars($nombre_cargo, ENT_QUOTES, 'UTF-8'); ?></h2>

        <?php if (!empty($empleados_cargo)): ?>
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>Cédula</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($empleados_cargo as $empleado): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($empleado['cedula_emple'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($empleado['nombre_emp'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($empleado['apellido_emp'], ENT_QUOTES, 'UTF-8'); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class='mensaje-info'><?php echo ($id_cargo_seleccionado) ? 'No se encontraron empleados con el cargo seleccionado.' : 'No hay empleados registrados.'; ?></p>
        <?php endif; ?>

        <div style="margin-top: 20px;">
            <a href="lista_cargos.php" class="btn-regresar">Regresar a Lista de Cargos</a>
        </div>
    </div>

    <?php
    if (isset($conn)) {
        $conn->close();
    }
    ?>
</body>
</html>