<?php
session_start();
require_once '../conexion/conexion.php'; 

$alert = '';
$perdidas = [];
$total_cantidad_perdida = 0;
$total_precio_perdida = 0.00;

$search_term = isset($_GET['search_term']) ? trim($_GET['search_term']) : '';
$date_from = isset($_GET['date_from']) ? $_GET['date_from'] : '';
$date_to = isset($_GET['date_to']) ? $_GET['date_to'] : '';

if (!$conn) {
    die("Error crítico: No se pudo establecer la conexión a la base de datos. Error: " . mysqli_connect_error());
}

try {
    $sql_base = "FROM perdida p JOIN producto pr ON p.id_pro = pr.id_pro";
    $where_clauses = [];
    $params = [];
    $types = "";

    if (!empty($search_term)) {
        $where_clauses[] = "(pr.nombre_producto LIKE ? OR CAST(p.id_perdida AS CHAR) LIKE ?)";
        $search_like = "%" . $search_term . "%";
        array_push($params, $search_like, $search_like);
        $types .= "ss";
    }

    if (!empty($date_from)) {
        if (DateTime::createFromFormat('Y-m-d', $date_from) !== false) {
            $where_clauses[] = "DATE(p.fecha_perdida) >= ?";
            $params[] = $date_from;
            $types .= "s";
        } else {
            $alert .= '<p class="msg_error">Formato de fecha "Desde" inválido. Use AAAA-MM-DD.</p>';
        }
    }

    if (!empty($date_to)) {
        if (DateTime::createFromFormat('Y-m-d', $date_to) !== false) {
            $where_clauses[] = "DATE(p.fecha_perdida) <= ?";
            $params[] = $date_to;
            $types .= "s";
        } else {
            $alert .= '<p class="msg_error">Formato de fecha "Hasta" inválido. Use AAAA-MM-DD.</p>';
        }
    }

    $sql_where = "";
    if (!empty($where_clauses)) {
        $sql_where = " WHERE " . implode(" AND ", $where_clauses);
    }

    $sql_select = "SELECT p.id_perdida, p.cant, p.precio_perdida, p.fecha_perdida, pr.nombre_producto " . $sql_base . $sql_where . " ORDER BY p.fecha_perdida DESC, p.id_perdida DESC";

    $stmt_select = $conn->prepare($sql_select);
    if ($stmt_select) {
        if (!empty($types) && !empty($params)) {
            $stmt_select->bind_param($types, ...$params);
        }
        $stmt_select->execute();
        $result = $stmt_select->get_result();
        while ($row = $result->fetch_assoc()) {
            $perdidas[] = $row;
        }
        $stmt_select->close();
    } else {
        throw new Exception("Error al preparar la consulta de pérdidas: " . $conn->error);
    }

    $sql_totals = "SELECT SUM(p.cant) AS total_cant, SUM(p.precio_perdida) AS total_precio " . $sql_base . $sql_where;
    $stmt_totals = $conn->prepare($sql_totals);
    if ($stmt_totals) {
        if (!empty($types) && !empty($params)) { 
            $stmt_totals->bind_param($types, ...$params);
        }
        $stmt_totals->execute();
        $result_totals = $stmt_totals->get_result();
        if ($row_totals = $result_totals->fetch_assoc()) {
            $total_cantidad_perdida = $row_totals['total_cant'] ?? 0;
            $total_precio_perdida = $row_totals['total_precio'] ?? 0.00;
        }
        $stmt_totals->close();
    } else {
        throw new Exception("Error al preparar la consulta de totales: " . $conn->error);
    }

} catch (Exception $e) {
    $alert = '<p class="msg_error">Error: ' . $e->getMessage() . '</p>';
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Pérdidas</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background-color: #f9f9f9; color: #333; }
        .container { background-color: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 0 15px rgba(0,0,0,0.1); width: 900px; max-width: 100%; }
        h1, h2 { color: #333; text-align: center; margin-bottom: 20px; }
        .filters { margin-bottom: 20px; padding: 15px; background-color: #f1f1f1; border-radius: 5px; display: flex; flex-wrap: wrap; gap: 15px; align-items: flex-end; }
        .filters label { font-weight: bold; margin-bottom: 5px; display: block; }
        .filters input[type="text"], .filters input[type="date"], .filters button {
            padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box;
        }
        .filters input[type="text"] { flex-grow: 1; min-width: 200px; }
        .filters input[type="date"] { min-width: 150px; }
        .filters button { background-color: #3533cd; color: white; cursor: pointer; border: none; }
        .filters button:hover { background-color: #0056b3; }
        .redirect-button {
            background-color: #3533cd;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            font-size: 1em;
        }
        .redirect-button:hover {
            background-color: #0056b3;
        }
        .table-container { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #f2f2f2; }
        .totals-summary { margin-top: 20px; padding: 15px; background-color: #e9f5ff; border: 1px solid #b3d7ff; border-radius: 5px; }
        .totals-summary h3 { margin-top: 0; color: #0056b3; }
        .totals-summary p { margin: 5px 0; }
        .msg_error { background-color: #f2dede; color: #a94442; border: 1px solid #ebccd1; padding: 10px; margin-bottom: 15px; border-radius: 4px; }
        .msg_success { background-color: #dff0d8; color: #3c763d; border: 1px solid #d6e9c6; padding: 10px; margin-bottom: 15px; border-radius: 4px; }
    </style>
    <?php include "../assets/head_gerente.php"?>
   
</head>
<body>
     <?php  include "../assets/lista_gerente.php"; ?>
    <div class="container">
        <h2>Lista de Pérdidas Registradas</h2>

        <?php echo $alert; ?>

        <div style="display: flex; gap: 10px; margin-bottom: 15px; align-items: center;">
            <a href="../registrar/registrar_perdida.php" class="redirect-button">Registrar nueva Pérdida</a>
            <form method="GET" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="filters" style="margin-bottom: 0;">
                <div>
                    <label for="search_term">Buscar:</label>
                    <input type="text" name="search_term" id="search_term" value="<?php echo htmlspecialchars($search_term); ?>" placeholder="Buscar">
                </div>
                <div>
                    <label for="date_from">Desde:</label>
                    <input type="date" name="date_from" id="date_from" value="<?php echo htmlspecialchars($date_from); ?>">
                </div>
                <div>
                    <label for="date_to">Hasta:</label>
                    <input type="date" name="date_to" id="date_to" value="<?php echo htmlspecialchars($date_to); ?>">
                </div>
                <button type="submit">Filtrar</button>
            </form>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID Pérdida</th>
                        <th>Producto</th>
                        <th>Cantidad Perdida</th>
                        <th>Precio Pérdida ($)</th>
                        <th>Fecha de Pérdida</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($perdidas)): ?>
                        <?php foreach ($perdidas as $perdida): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($perdida['id_perdida']); ?></td>
                                <td><?php echo htmlspecialchars($perdida['nombre_producto']); ?></td>
                                <td><?php echo htmlspecialchars($perdida['cant']); ?></td>
                                <td><?php echo number_format($perdida['precio_perdida'], 2); ?></td>
                                <td><?php echo htmlspecialchars(date("d/m/Y", strtotime($perdida['fecha_perdida']))); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" style="text-align:center;">No se encontraron pérdidas con los criterios seleccionados.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="totals-summary">
            <h3><span class="material-symbols-outlined ico-trending_up"></span>Resumen de Pérdidas (Periodo Seleccionado)</h3>
            <p><strong>Total Cantidad de Productos Perdidos:</strong> <?php echo htmlspecialchars($total_cantidad_perdida); ?></p>
            <p><strong>Total Dinero Perdido:</strong> <?php echo number_format($total_precio_perdida, 2); ?> $</p>
        </div>

    </div>
</body>
</html>
<script>
        document.addEventListener("DOMContentLoaded", function() {
        const inputFecha = document.getElementById("date_from");
        const hoy = new Date();
        const anio = hoy.getFullYear();
        let mes = hoy.getMonth() + 1;
        let dia = hoy.getDate();
        mes = mes < 10 ? '0' + mes : mes;
        dia = dia < 10 ? '0' + dia : dia;
        const fechaMaxima = `${anio}-${mes}-${dia}`;
        inputFecha.max = fechaMaxima;
    });
        document.addEventListener("DOMContentLoaded", function() {
        const inputFecha = document.getElementById("date_to");
        const hoy = new Date();
        const anio = hoy.getFullYear();
        let mes = hoy.getMonth() + 1;
        let dia = hoy.getDate();
        mes = mes < 10 ? '0' + mes : mes;
        dia = dia < 10 ? '0' + dia : dia;
        const fechaMaxima = `${anio}-${mes}-${dia}`;
        inputFecha.max = fechaMaxima;
    });
</script>