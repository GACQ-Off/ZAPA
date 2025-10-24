<?php
session_start();
include "../conexion/conexion.php";
$cedula_empleado_url = '';
$empleado = null;
$pagos = [];
$fecha_pago_desde = $_GET['fecha_pago_desde'] ?? '';
$fecha_pago_hasta = $_GET['fecha_pago_hasta'] ?? '';
$monto_pago_exacto = $_GET['monto_pago_exacto'] ?? '';
$error_message = '';
$pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
if ($pagina_actual < 1) $pagina_actual = 1;
$registros_por_pagina = 5;
$offset = ($pagina_actual - 1) * $registros_por_pagina;
$total_registros_pagos = 0;
$total_paginas = 0;

if (empty($_GET["Empleado"])) {
    header('Location: ../listas/lista_empleado.php');
    exit();
}
$cedula_empleado_url = $_GET['Empleado'];
$stmt_empleado = $conn->prepare(
    "SELECT e.cedula_emple, e.nombre_emp, e.apellido_emp, e.fecha_nacimiento, e.telefono_emple, c.nom_cargo
    FROM empleado e
    INNER JOIN cargo c ON e.id_cargo = c.id_cargo
    WHERE e.cedula_emple = ?"
);

if ($stmt_empleado) {
    $stmt_empleado->bind_param("s", $cedula_empleado_url);
    $stmt_empleado->execute();
    $result_empleado = $stmt_empleado->get_result();

    if ($result_empleado->num_rows > 0) {
        $empleado = $result_empleado->fetch_assoc();
    } else {
        $error_message = "No se encontró ningún empleado con la cédula proporcionada.";
    }
    $stmt_empleado->close();
} else {
    $error_message = "Error al preparar la consulta del empleado: " . $conn->error;
}
if ($empleado) {
    $sql_pagos_base = "SELECT
        n.id_nomina,
        n.pago_total,
        n.fecha_nom,
        n.id_fondo,
        td.tasa_dolar AS valor_tasa,
        td.fecha_dolar AS fecha_tasa
    FROM nomina n
    INNER JOIN tasa_dolar td ON n.id_tasa_dolar = td.id_tasa_dolar";

    $where_clauses_pagos = ["n.cedula_emple = ?"];
    $params_pagos = [$cedula_empleado_url];
    $types_pagos = "s";

    if (!empty($fecha_pago_desde)) {
        $where_clauses_pagos[] = "DATE(n.fecha_nom) >= ?";
        $params_pagos[] = $fecha_pago_desde;
        $types_pagos .= "s";
    }
    if (!empty($fecha_pago_hasta)) {
        $where_clauses_pagos[] = "DATE(n.fecha_nom) <= ?";
        $params_pagos[] = $fecha_pago_hasta;
        $types_pagos .= "s";
    }
    if (!empty($monto_pago_exacto) && is_numeric($monto_pago_exacto)) {
        $where_clauses_pagos[] = "n.pago_total = ?";
        $params_pagos[] = (float)$monto_pago_exacto;
        $types_pagos .= "d";
    }

    $sql_count_pagos = "SELECT COUNT(*) as total FROM nomina n INNER JOIN tasa_dolar td ON n.id_tasa_dolar = td.id_tasa_dolar";
    if (!empty($where_clauses_pagos)) {
        $sql_count_pagos .= " WHERE " . implode(" AND ", $where_clauses_pagos);
    }

    $stmt_count_pagos = $conn->prepare($sql_count_pagos);
    if ($stmt_count_pagos) {
        if (!empty($params_pagos)) {
            $stmt_count_pagos->bind_param($types_pagos, ...$params_pagos);
        }
        $stmt_count_pagos->execute();
        $result_count_pagos = $stmt_count_pagos->get_result();
        $total_registros_pagos = $result_count_pagos->fetch_assoc()['total'] ?? 0;
        $stmt_count_pagos->close();

        if ($total_registros_pagos > 0) {
            $total_paginas = ceil($total_registros_pagos / $registros_por_pagina);
            if ($pagina_actual > $total_paginas) {
                $pagina_actual = $total_paginas;
                $offset = ($pagina_actual - 1) * $registros_por_pagina;
            }
        }
    } else {
        $error_message .= "<br>Error al preparar la consulta de conteo de pagos: " . $conn->error;
    }

    $sql_pagos_final = $sql_pagos_base . " WHERE " . implode(" AND ", $where_clauses_pagos) . " ORDER BY n.fecha_nom DESC LIMIT ? OFFSET ?";

    $params_pagos_con_limit = $params_pagos;
    $params_pagos_con_limit[] = $registros_por_pagina;
    $params_pagos_con_limit[] = $offset;
    $types_pagos_con_limit = $types_pagos . "ii";

    $stmt_pagos = $conn->prepare($sql_pagos_final);

    if ($stmt_pagos) {
        $stmt_pagos->bind_param($types_pagos_con_limit, ...$params_pagos_con_limit);
        $stmt_pagos->execute();
        $result_pagos = $stmt_pagos->get_result();
        while ($fila = $result_pagos->fetch_assoc()) {
            $pagos[] = $fila;
        }
        $stmt_pagos->close();
    } else {
        $error_message .= "<br>Error al preparar la consulta de pagos filtrada: " . $conn->error;
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="stylesheet" href="../assets/css/historial_pagos.css">
<?php include "../assets/head_gerente.php"; ?>
    <title>Historial de Pagos - <?php echo htmlspecialchars($empleado ? $empleado['nombre_emp'] . ' ' . $empleado['apellido_emp'] : 'Empleado'); ?></title>
</head>
<body>
<?php include "../assets/lista_gerente.php";?>

<div class="container">
    <?php if (!empty($error_message)): ?>
        <p class="error-msg"><?php echo $error_message; ?></p>
    <?php endif; ?>

    <h2 class="section-title" style="margin-top: 20px;">Busqueda de Pagos</h2>
    <form method="GET" action="historial_pagos.php" class="filtros-form">
        <input type="hidden" name="Empleado" value="<?php echo htmlspecialchars($cedula_empleado_url); ?>">

        <div>
            <label for="fecha_pago_desde">Fecha Pago Desde:</label>
            <input type="date" name="fecha_pago_desde" id="fecha_pago_desde" value="<?php echo htmlspecialchars($fecha_pago_desde); ?>">
        </div>
        <div>
            <label for="fecha_pago_hasta">Fecha Pago Hasta:</label>
            <input type="date" name="fecha_pago_hasta" id="fecha_pago_hasta" value="<?php echo htmlspecialchars($fecha_pago_hasta); ?>">
        </div>
        <div>
            <label for="monto_pago_exacto">Monto Exacto ($):</label>
            <input type="number" step="0.01" name="monto_pago_exacto" id="monto_pago_exacto" value="<?php echo htmlspecialchars($monto_pago_exacto); ?>" placeholder="Buscar">
        </div>
        <button type="submit">Buscar</button>
        <a href="historial_pagos.php?Empleado=<?php echo htmlspecialchars($cedula_empleado_url); ?>" class="btn-limpiar-filtros">Limpiar Filtros</a>
    </form>

    <?php if ($empleado): ?>
        <h2 class="section-title">Datos del Empleado</h2>
        <div class="empleado-info">
            <p><strong>Cédula:</strong> <?php echo htmlspecialchars($empleado['cedula_emple']); ?></p>
            <p><strong>Nombre Completo:</strong> <?php echo htmlspecialchars($empleado['nombre_emp'] . ' ' . $empleado['apellido_emp']); ?></p>
            <p><strong>Fecha de Nacimiento:</strong> <?php echo htmlspecialchars(date("d/m/Y", strtotime($empleado['fecha_nacimiento']))); ?></p>
            <p><strong>Teléfono:</strong> <?php echo htmlspecialchars($empleado['telefono_emple']); ?></p>
            <p><strong>Cargo:</strong> <?php echo htmlspecialchars($empleado['nom_cargo']); ?></p>
        </div>

        <h2 class="section-title" style="margin-top: 30px;">Historial de Pagos</h2>
        <?php if (!empty($pagos)): ?>
            <table class="pagos-table">
            <thead>
                <tr>
                    <th>Fecha del Pago</th>
                    <th>Monto Pagado Dólares</th>
                    <th>Tasa del dolar</th>
                    <th>Monto Equivalente Bolivares</th>
                    <th>Acciones</th>
                </tr>
            </thead>
                <tbody>
                    <?php foreach ($pagos as $pago): ?>
                        <?php
                            $monto_pagado_usd = floatval($pago['pago_total']);
                            $tasa_cambio_aplicada = floatval($pago['valor_tasa']);
                            $monto_equivalente_bs = 0;
                            if ($tasa_cambio_aplicada > 0) {
                                $monto_equivalente_bs = $monto_pagado_usd * $tasa_cambio_aplicada;
                            }
                            $fecha_tasa_formateada = date("d/m/Y", strtotime($pago['fecha_tasa']));?>
                        <tr>
                            <td><?php echo htmlspecialchars(date("d/m/Y", strtotime($pago['fecha_nom']))); ?></td>
                            <td><?php echo htmlspecialchars(number_format($monto_pagado_usd, 2, ',', '.')); ?></td>
                            <td><?php echo htmlspecialchars(number_format($tasa_cambio_aplicada, 2, ',', '.')) . ' (del ' . htmlspecialchars($fecha_tasa_formateada) . ')'; ?></td>
                            <td><?php echo htmlspecialchars(number_format($monto_equivalente_bs, 2, ',', '.')); ?></td>
                            <td><a title="Realizar Recibo" href="recibo_pago_especifico.php?Empleado=<?php echo htmlspecialchars($cedula_empleado_url); ?>&id_pago=<?php echo htmlspecialchars($pago['id_nomina']); ?>" target="_blank">
                                    <span class="material-symbols-rounded ico-description md-24 fill-1 wght-18 leading-none error">
                                    </span>
                                </a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="no-pagos">
                <?php if ($total_registros_pagos > 0 && $pagina_actual > $total_paginas): ?>
                    No hay registros en esta página. Intente una página anterior.
                <?php else: ?>
                    No hay registros de pagos para este empleado con la busqueda seleccionada.
                <?php endif; ?>
            </p>
        <?php endif; ?>

    <?php elseif (empty($error_message)):?>
        <p class="error-msg">No se pudo cargar la información del empleado.</p>
    <?php endif; ?>

    <?php if ($total_paginas > 1): ?>
        <ul class="pagination">
            <?php
            $query_params_pagination = [
                'Empleado' => $cedula_empleado_url,
                'fecha_pago_desde' => $fecha_pago_desde,
                'fecha_pago_hasta' => $fecha_pago_hasta,
                'monto_pago_exacto' => $monto_pago_exacto,
            ];
            $query_params_pagination = array_filter($query_params_pagination, function($value) { return $value !== '' && $value !== null; });
            $base_url_pagination = "historial_pagos.php?" . http_build_query($query_params_pagination);
            $separator = !empty($query_params_pagination) ? '&' : '';
            ?>

            <?php if ($pagina_actual > 1): ?>
                <li><a href="<?php echo $base_url_pagination . $separator; ?>pagina=1">|&lt;&lt;</a></li>
                <li><a href="<?php echo $base_url_pagination . $separator; ?>pagina=<?php echo $pagina_actual - 1; ?>">&lt;&lt;</a></li>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                <li class="<?php echo ($i == $pagina_actual) ? 'active' : ''; ?>">
                    <a href="<?php echo $base_url_pagination . $separator; ?>pagina=<?php echo $i; ?>"><?php echo $i; ?></a>
                </li>
            <?php endfor; ?>

            <?php if ($pagina_actual < $total_paginas): ?>
                <li><a href="<?php echo $base_url_pagination . $separator; ?>pagina=<?php echo $pagina_actual + 1; ?>">&gt;&gt;</a></li>
                <li><a href="<?php echo $base_url_pagination . $separator; ?>pagina=<?php echo $total_paginas; ?>">&gt;&gt;|</a></li>
            <?php endif; ?>
        </ul>
    <?php endif; ?>

    <a href="../listas/lista_empleado.php" class="btn-regresar">Volver a la Lista de Empleados</a>
</div>
</body>
</html>
<script>
        document.addEventListener("DOMContentLoaded", function() {
        const inputFecha = document.getElementById("fecha_pago_desde");
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
        const inputFecha = document.getElementById("fecha_pago_hasta");
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