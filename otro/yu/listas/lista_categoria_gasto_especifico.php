<?php
session_start();
require_once '../conexion/conexion.php';
include('../assets/head_gerente.php');

if (!isset($conn) || !($conn instanceof mysqli) || $conn->connect_error) {
    die("Error crítico de conexión a la base de datos: " . ($conn->connect_error ?? "Error desconocido"));
}

$id_categoria_gasto_seleccionado = isset($_GET['Categoria']) ? (int)$_GET['Categoria'] : null;
$nombre_categoria_gasto = "Todos los Gastos";
$gastos_categoria = [];

if ($id_categoria_gasto_seleccionado) {
    $sql_nombre_categoria_gasto = "SELECT nombre_categoria_gasto FROM categoria_gasto WHERE id_categoria_gasto = ?";
    $stmt_nombre_categoria_gasto = $conn->prepare($sql_nombre_categoria_gasto);
    if ($stmt_nombre_categoria_gasto) {
        $stmt_nombre_categoria_gasto->bind_param("i", $id_categoria_gasto_seleccionado);
        $stmt_nombre_categoria_gasto->execute();
        $result_nombre_categoria_gasto = $stmt_nombre_categoria_gasto->get_result();
        if ($result_nombre_categoria_gasto && $result_nombre_categoria_gasto->num_rows > 0) {
            $row_nombre_categoria_gasto = $result_nombre_categoria_gasto->fetch_assoc();
            $nombre_categoria_gasto = "Gastos con la Categoría: " . $row_nombre_categoria_gasto['nombre_categoria_gasto'];
        }
        $stmt_nombre_categoria_gasto->close();
    }

    $sql_gastos = "SELECT
                            g.id_gastos,
                            g.descripcion_gasto,
                            g.monto_gasto,
                            g.fecha_gasto
                        FROM
                            gastos g
                        WHERE
                            g.id_categoria_gasto = ?";
    $stmt_gastos = $conn->prepare($sql_gastos);
    if ($stmt_gastos) {
        $stmt_gastos->bind_param("i", $id_categoria_gasto_seleccionado);
        $stmt_gastos->execute();
        $result_gastos = $stmt_gastos->get_result();
        if ($result_gastos && $result_gastos->num_rows > 0) {
            while ($row_gasto = $result_gastos->fetch_assoc()) {
                $gastos_categoria[] = $row_gasto;
            }
        }
        $stmt_gastos->close();
    } else {
        echo "<p class='mensaje-error'>Error al preparar la consulta de gastos por categoría: " . htmlspecialchars($conn->error) . "</p>";
    }
} else {
    $sql_todos_gastos = "SELECT
                            g.id_gastos,
                            g.descripcion_gasto,
                            g.monto_gasto,
                            g.fecha_gasto
                        FROM
                            gastos g";
    $result_todos_gastos = $conn->query($sql_todos_gastos);
    if ($result_todos_gastos && $result_todos_gastos->num_rows > 0) {
        while ($row_gasto = $result_todos_gastos->fetch_assoc()) {
            $gastos_categoria[] = $row_gasto;
        }
    } else {
        echo "<p class='mensaje-info'>No hay gastos registrados.</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gastos por Categoría</title>
    <link rel="stylesheet" href="../assets/css/lista_productos.css">
</head>
<body>
    <?php include('../assets/lista_gerente.php'); ?>
    <div class="container mt-4 lista-container">
        <h2><?php echo htmlspecialchars($nombre_categoria_gasto, ENT_QUOTES, 'UTF-8'); ?></h2>

        <?php if (!empty($gastos_categoria)): ?>
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>ID</th>
                            <th>Descripción</th>
                            <th>Monto $</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($gastos_categoria as $gasto): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($gasto['id_gastos'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($gasto['descripcion_gasto'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars(number_format($gasto['monto_gasto'], 2, '.', ','), ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($gasto['fecha_gasto'], ENT_QUOTES, 'UTF-8'); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class='mensaje-info'><?php echo ($id_categoria_gasto_seleccionado) ? 'No se encontraron gastos para esta categoría.' : 'No hay gastos registrados.'; ?></p>
        <?php endif; ?>

        <div style="margin-top: 20px;">
            <a href="lista_categoria_gasto.php" class="btn-regresar">Regresar a Lista de Categorías de Gasto</a>
        </div>
    </div>

    <?php
    if (isset($conn)) {
        $conn->close();
    }
    ?>
</body>
</html>