<?php
session_start();
require_once '../conexion/conexion.php';

if (!isset($conn) || !($conn instanceof mysqli) || $conn->connect_error) {
    die("Error crítico de conexión a la base de datos: " . ($conn->connect_error ?? "Error desconocido"));
}

$busqueda_form_value = htmlspecialchars($_GET['busqueda'] ?? '', ENT_QUOTES, 'UTF-8');
$active_search_term_for_sql = '';
$active_search_term_for_pagination = '';
$is_search_active = false;

if (isset($_GET['accion_buscar']) || (isset($_GET['pagina']) && isset($_GET['busqueda']))) {
    if (!empty($_GET['busqueda'])) {
        $search_term = trim($_GET['busqueda']);
        if (strlen($search_term) > 0) {
            $active_search_term_for_sql = $conn->real_escape_string($search_term);
            $active_search_term_for_pagination = $search_term;
            $is_search_active = true;
        }
    }
}

$where_conditions = ["estado_proveedor = 1"];
$params = [];
$types = '';

if ($is_search_active && !empty($active_search_term_for_sql)) {
    $search_pattern = "%{$active_search_term_for_sql}%";
    $where_conditions[] = "(RIF LIKE ? OR nombre_provedor LIKE ? OR telefono_pro LIKE ? OR correo_pro LIKE ?)";
    $types .= 'ssss';
    array_push($params, $search_pattern, $search_pattern, $search_pattern, $search_pattern);
}

$where_sql = !empty($where_conditions) ? "WHERE " . implode(" AND ", $where_conditions) : "";

$sql_total_registros = "SELECT COUNT(*) as total_registro FROM proveedor $where_sql";
$stmt_total = $conn->prepare($sql_total_registros);

if (!$stmt_total) {
    die("Error al preparar la consulta de conteo: " . $conn->error);
}

if ($params) {
    $stmt_total->bind_param($types, ...$params);
}

if (!$stmt_total->execute()) {
    die("Error al contar registros: " . $conn->error);
}

$result_total = $stmt_total->get_result();
$fila_total_registros = $result_total->fetch_assoc();
$total_registro = $fila_total_registros['total_registro'] ?? 0;
$stmt_total->close();

$por_pagina = 10;
$pagina = isset($_GET['pagina']) ? max(1, (int)$_GET['pagina']) : 1;
$desde = ($pagina - 1) * $por_pagina;
$total_paginas = max(1, ceil($total_registro / $por_pagina));

$sql_proveedores = "SELECT RIF, nombre_provedor, telefono_pro, correo_pro
                    FROM proveedor
                    $where_sql
                    ORDER BY nombre_provedor ASC
                    LIMIT ?, ?";

$stmt_proveedores = $conn->prepare($sql_proveedores);
if (!$stmt_proveedores) {
    die("Error al preparar la consulta: " . $conn->error);
}

if ($params) {
    $types .= 'ii';
    array_push($params, $desde, $por_pagina);
    $stmt_proveedores->bind_param($types, ...$params);
} else {
    $stmt_proveedores->bind_param('ii', $desde, $por_pagina);
}

if (!$stmt_proveedores->execute()) {
    die("Error al ejecutar la consulta de proveedores: " . $conn->error);
}

$result_proveedores = $stmt_proveedores->get_result();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Proveedores</title>
    <?php include '../assets/head_gerente.php'; ?>
    <link rel="stylesheet" href="../assets/css/lista_empleados.css">
    <link rel="stylesheet" href="../assets/css/lista_provedor.css">
</head>
<body>
    <?php include '../assets/lista_gerente.php'; ?>

    <div class="container">
        <h1>Lista de Proveedores</h1>

        <form action="lista_provedor.php" method="GET" class="form-busqueda controles">
            <input type="text" name="busqueda" id="busqueda" placeholder="Buscar proveedor..."
                   value="<?php echo $busqueda_form_value; ?>">
            <input type="submit" name="accion_buscar" value="Buscar" class="btn-buscar">
            <?php if ($is_search_active): ?>
                <a href="lista_provedor.php" class="btn-limpiar">Limpiar</a>
            <?php endif; ?>
            <a href="../registrar/proveedor.php" class="add-button">Registrar Nuevo Proveedor</a>
        </form>

        <?php if ($total_registro > 0): ?>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>RIF</th>
                        <th>Nombre</th>
                        <th>Teléfono</th>
                        <th>Correo Electrónico</th>
                        
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result_proveedores->num_rows > 0): ?>
                        <?php while($row = $result_proveedores->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['RIF'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><a href="lista_productos_proveedor.php?rif=<?php echo urlencode($row['RIF']); ?>" class="action-link"><?php echo htmlspecialchars($row['nombre_provedor'], ENT_QUOTES, 'UTF-8'); ?>
                                    </a>
                                </td>
                                <td><?php echo !empty($row['telefono_pro']) ? htmlspecialchars($row['telefono_pro'], ENT_QUOTES, 'UTF-8') : '-'; ?></td>
                                <td><?php echo !empty($row['correo_pro']) ? htmlspecialchars($row['correo_pro'], ENT_QUOTES, 'UTF-8') : '-'; ?></td>
                                
                                <td class="action-links">
                                    <a title="editar" href="../editar/editar_provedor.php?rif=<?php echo urlencode($row['RIF']); ?>">
                                        <span class="material-symbols-rounded ico-edit md-24 fill-1 wght-18 leading-none error"></span>
                                    </a>
                                    <button type="button" title="Eliminar" class="btn-icon" onclick="document.getElementById('confirmDeleteModal_<?php echo htmlspecialchars($row['RIF']); ?>').showModal();">
                                        
                                        <span class="material-symbols-rounded ico-delete md-24 fill-1 wght-18 leading-none error"></span>
                                    </button>
                                    <dialog id="confirmDeleteModal_<?php echo htmlspecialchars($row['RIF']); ?>" class="confirm-dialog">
                                        <h3 class="dialog-title">Confirmar Eliminación</h3>
                                        <p class="dialog-message">
                                            ¿Estás seguro de que deseas eliminar al proveedor
                                            <strong><?php echo htmlspecialchars($row["nombre_provedor"]); ?></strong>
                                            (RIF: <?php echo htmlspecialchars($row["RIF"]); ?>)?
                                        </p>
                                        <p class="dialog-warning">Al eliminarlo, solo se marcará como inactivo.</p>
                                        <div class="dialog-actions">
                                            <a href="../eliminar/eliminar_proveedor.php?rif=<?php echo htmlspecialchars($row["RIF"]); ?>" class="btn btn--danger">Eliminar</a>
                                            <button type="button" class="btn btn--secondary" onclick="document.getElementById('confirmDeleteModal_<?php echo htmlspecialchars($row['RIF']); ?>').close();">Cancelar</button>
                                        </div>
                                    </dialog>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6">No se encontraron proveedores con los criterios actuales.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
            <div class="no-results">
                No hay proveedores activos registrados o que coincidan con la búsqueda.
            </div>
        <?php endif; ?>

        <?php if ($total_paginas > 1): ?>
        <div class="paginacion-contenedor">
            <ul class="pagination">
                <?php if ($pagina > 1): ?>
                    <li class="pagination__item">
                        <a href="?pagina=1&busqueda=<?php echo urlencode($active_search_term_for_pagination); ?>"
                           class="pagination__link" title="Primera página">|&lt;&lt;</a>
                    </li>
                    <li class="pagination__item">
                        <a href="?pagina=<?php echo ($pagina - 1); ?>&busqueda=<?php echo urlencode($active_search_term_for_pagination); ?>"
                           class="pagination__link" title="Página anterior">&lt;&lt;</a>
                    </li>
                <?php endif; ?>

                <?php
                $inicio = max(1, $pagina - 2);
                $fin = min($total_paginas, $pagina + 2);
                
                if ($inicio > 1) {
                    echo '<li class="pagination__item"><span class="pagination__ellipsis">...</span></li>';
                }
                
                for ($i = $inicio; $i <= $fin; $i++): ?>
                    <li class="pagination__item <?php echo $i == $pagina ? 'pagination__item--active' : ''; ?>">
                        <?php if ($i == $pagina): ?>
                            <span><?php echo $i; ?></span>
                        <?php else: ?>
                            <a href="?pagina=<?php echo $i; ?>&busqueda=<?php echo urlencode($active_search_term_for_pagination); ?>"
                               class="pagination__link"><?php echo $i; ?></a>
                        <?php endif; ?>
                    </li>
                <?php endfor;
                
                if ($fin < $total_paginas) {
                    echo '<li class="pagination__item"><span class="pagination__ellipsis">...</span></li>';
                }
                ?>

                <?php if ($pagina < $total_paginas): ?>
                    <li class="pagination__item">
                        <a href="?pagina=<?php echo ($pagina + 1); ?>&busqueda=<?php echo urlencode($active_search_term_for_pagination); ?>"
                           class="pagination__link" title="Página siguiente">&gt;&gt;</a>
                    </li>
                    <li class="pagination__item">
                        <a href="?pagina=<?php echo $total_paginas; ?>&busqueda=<?php echo urlencode($active_search_term_for_pagination); ?>"
                           class="pagination__link" title="Última página">&gt;&gt;|</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
        <?php endif; ?>
    </div>

    <a href="../menu.php" class="btn btn--cancel">Regresar</a>

    <?php
    if (isset($stmt_proveedores)) {
        $stmt_proveedores->close();
    }
    if (isset($conn)) {
        $conn->close();
    }
    ?>
</body>
</html>