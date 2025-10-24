<?php
// HEADERS DE CODIFICACIÓN AL INICIO
header('Content-Type: text/html; charset=UTF-8');
ob_start(); // Iniciar buffer desde el principio

session_start();

include "../conexion/conexion.php";

// Verificar conexión primero
if (!isset($conn) || $conn->connect_error) {
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest' || isset($_GET['ajax'])) {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'message' => 'Error de conexión a la base de datos.',
            'gastos' => [], 
            'total_pages' => 0, 
            'current_page' => 1, 
            'total_records' => 0
        ]);
        ob_end_flush();
        exit();
    }
}

// Establecer charset UTF-8 para la conexión
if ($conn) {
    $conn->set_charset("utf8");
}

$items_per_page = 5;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($current_page < 1) $current_page = 1;

$search_term = isset($_GET['search']) ? trim($_GET['search']) : '';
$fecha_desde = isset($_GET['fecha_desde']) ? trim($_GET['fecha_desde']) : '';
$fecha_hasta = isset($_GET['fecha_hasta']) ? trim($_GET['fecha_hasta']) : '';

$where_conditions = ["gastos.estado_gasto = 1"];
$params = [];
$param_types = "";

if (!empty($search_term)) {
    $where_conditions[] = "(gastos.descripcion_gasto LIKE ? OR categoria_gasto.nombre_categoria_gasto LIKE ?)";
    $search_param = '%' . $search_term . '%';
    array_push($params, $search_param, $search_param);
    $param_types .= "ss";
}

if (!empty($fecha_desde)) {
    $where_conditions[] = "DATE(gastos.fecha_gasto) >= ?";
    array_push($params, $fecha_desde);
    $param_types .= "s";
}

if (!empty($fecha_hasta)) {
    $where_conditions[] = "DATE(gastos.fecha_gasto) <= ?";
    array_push($params, $fecha_hasta);
    $param_types .= "s";
}

$where_sql = count($where_conditions) > 0 ? "WHERE " . implode(" AND ", $where_conditions) : "";

// Función refValues mejorada
function refValues($arr) {
    $refs = [];
    foreach ($arr as $key => $value) {
        $refs[$key] = &$arr[$key];
    }
    return $refs;
}

// Contar total de registros
$sql_count = "SELECT COUNT(*) as total_registro
    FROM gastos
    INNER JOIN categoria_gasto ON gastos.id_categoria_gasto = categoria_gasto.id_categoria_gasto
    $where_sql";
    
$stmt_count = $conn->prepare($sql_count);
$total_registro = 0;

if ($stmt_count) {
    if (!empty($params)) {
        $bound = call_user_func_array([$stmt_count, 'bind_param'], refValues(array_merge([$param_types], $params)));
        if (!$bound) {
            error_log("Error en bind_param (count): " . $stmt_count->error);
        }
    }
    
    $executed = $stmt_count->execute();
    if ($executed) {
        $result_registro = $stmt_count->get_result();
        if ($result_registro) {
            $row = $result_registro->fetch_assoc();
            $total_registro = $row['total_registro'];
        }
    } else {
        error_log("Error en execute (count): " . $stmt_count->error);
        $total_registro = 0;
    }
    $stmt_count->close();
} else {
    error_log("Error en prepare (count): " . $conn->error);
    $total_registro = 0;
}

$total_paginas = ceil($total_registro / $items_per_page);

if ($total_paginas == 0) {
    $current_page = 1;
    $offset = 0;
} elseif ($current_page > $total_paginas) {
    $current_page = $total_paginas;
}
$offset = ($current_page - 1) * $items_per_page;

// Consulta principal para datos iniciales
$query_sql = "SELECT gastos.id_gastos,
    gastos.descripcion_gasto,
    gastos.monto_gasto,
    gastos.fecha_gasto,
    categoria_gasto.nombre_categoria_gasto AS categoria_gasto
    FROM gastos
    INNER JOIN categoria_gasto ON gastos.id_categoria_gasto = categoria_gasto.id_categoria_gasto
    $where_sql
    ORDER BY gastos.fecha_gasto DESC 
    LIMIT ? OFFSET ?";

$stmt_data = $conn->prepare($query_sql);
$initial_data = [];

if ($stmt_data) {
    $limit_param = $items_per_page;
    $offset_param = $offset;
    
    // Combinar parámetros para la consulta principal
    $params_data = $params;
    $params_data[] = $limit_param;
    $params_data[] = $offset_param;
    $param_types_data = $param_types . "ii";
    
    if (!empty($params_data)) {
        $bound = call_user_func_array([$stmt_data, 'bind_param'], refValues(array_merge([$param_types_data], $params_data)));
        if (!$bound) {
            error_log("Error en bind_param (data): " . $stmt_data->error);
        }
    }
    
    $executed = $stmt_data->execute();
    if ($executed) {
        $result = $stmt_data->get_result();
        if ($result) {
            while ($data = $result->fetch_assoc()) {
                $initial_data[] = $data;
            }
        }
    } else {
        error_log("Error en execute (data): " . $stmt_data->error);
    }
    $stmt_data->close();
} else {
    error_log("Error en prepare (data): " . $conn->error);
}

// Manejar respuesta AJAX
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest' || isset($_GET['ajax'])) {
    
    // Limpiar buffer completamente
    ob_clean();
    
    $response = [
        'success' => true,
        'message' => '',
        'gastos' => [],
        'total_pages' => $total_paginas,
        'current_page' => $current_page,
        'total_records' => $total_registro
    ];

    // Procesar datos para JSON
    foreach ($initial_data as $data) {
        $response['gastos'][] = [
            'id_gastos' => $data['id_gastos'],
            'descripcion_gasto' => $data['descripcion_gasto'],
            'monto_gasto' => $data['monto_gasto'],
            'monto_gasto_formatted' => number_format($data["monto_gasto"], 2, '.', ','),
            'fecha_gasto' => $data['fecha_gasto'],
            'fecha_gasto_formatted' => $data['fecha_gasto'],
            'categoria_gasto' => $data['categoria_gasto']
        ];
    }

    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    
    // Limpiar y salir
    if ($conn) $conn->close();
    exit();
}

// Cerrar conexión para HTML
if ($conn) {
    $conn->close();
}

// Limpiar buffer para HTML
ob_end_flush();
?>

<html>
<head>
<?php include "../assets/head_gerente.php"?>
<link rel="stylesheet" href="../assets/css/lista_empleados.css">
<title>Lista de Gastos</title>
<style>
.btn-pagination {
    padding: 8px 15px;
    border: 1px solid #007bff;
    background-color: #007bff;
    color: white;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.2s ease;
}

.btn-pagination:disabled {
    background-color: #6c757d;
    cursor: not-allowed;
}

.btn-pagination:hover:not(:disabled) {
    background-color: #0056b3;
}

.pagination-container {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-top: 20px;
    gap: 10px;
}

.confirm-dialog {
    border: none;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    max-width: 500px;
    width: 90%;
}

.dialog-actions {
    display: flex;
    gap: 10px;
    justify-content: flex-end;
    margin-top: 20px;
}

.filters-container {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    align-items: flex-end;
    margin-bottom: 20px;
    background-color: #fff;
    padding: 15px;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.filters-container > div {
    flex: 1;
    min-width: 200px;
}

.filters-container label {
    display: block;
    font-weight: bold;
    margin-bottom: 5px;
}

.filters-container input {
    width: 100%;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
}
</style>
</head>

<body>
<?php include "../assets/lista_gerente.php"?>
<div class="main-content-lista">
    <h3>Lista de Gastos</h3>
    <div class="filters-container">
        <div>
            <label for="searchInput">Buscar Gasto:</label>
            <input type="text" id="searchInput" class="form-control" placeholder="Descripción o categoría..." value="<?php echo htmlspecialchars($search_term); ?>">
        </div>
        <div>
            <label for="fechaDesdeInput">Fecha Desde:</label>
            <input type="date" id="fechaDesdeInput" class="form-control" value="<?php echo htmlspecialchars($fecha_desde); ?>">
        </div>
        <div>
            <label for="fechaHastaInput">Fecha Hasta:</label>
            <input type="date" id="fechaHastaInput" class="form-control" value="<?php echo htmlspecialchars($fecha_hasta); ?>">
        </div>
        <div>
            <button id="clearFilters" class="btn btn--secondary">Limpiar Filtros</button>
        </div>
    </div>

    <a href="../registrar/registrar_gastos.php" class="btn btn--secondary">Registrar Gastos</a>
    
    <div id="gastosTableContainer">
        <table class="attendance__table">
            <thead>
                <tr class="attendance__table-header">
                    <th class="attendance__table-header-cell">Codigo Gasto</th>
                    <th class="attendance__table-header-cell">Descripción del Gasto</th>
                    <th class="attendance__table-header-cell">Monto $</th>
                    <th class="attendance__table-header-cell">Fecha</th>
                    <th class="attendance__table-header-cell">Categoria</th>
                    <th class="attendance__table-header-cell">Acciones</th>
                </tr>
            </thead>
            <tbody id="gastosTableBody">
                <?php if (!empty($initial_data)): ?>
                    <?php foreach ($initial_data as $data): ?>
                        <tr>
                            <td class="attendance__table-cell"><?php echo htmlspecialchars($data["id_gastos"]); ?></td>
                            <td class="attendance__table-cell"><?php echo htmlspecialchars($data["descripcion_gasto"]); ?></td>
                            <td class="attendance__table-cell"><?php echo number_format($data["monto_gasto"], 2, '.', ','); ?></td>
                            <td class="attendance__table-cell"><?php echo htmlspecialchars($data["fecha_gasto"]); ?></td>
                            <td class="attendance__table-cell"><?php echo htmlspecialchars($data["categoria_gasto"]); ?></td>
                            <td class="attendance__table-cell">
                                <a title="editar" href="../editar/editar_gastos.php?id_gasto=<?php echo htmlspecialchars($data["id_gastos"]); ?>">
                                    <span class="material-symbols-rounded ico-edit md-24 fill-1 wght-18 leading-none error">edit</span>
                                </a>
                                <button type="button" title="Eliminar" class="btn-icon" onclick="showDeleteModal('<?php echo htmlspecialchars($data['id_gastos']); ?>', '<?php echo htmlspecialchars(addslashes($data['descripcion_gasto'])); ?>');">
                                    <span class="material-symbols-rounded ico-delete md-24 fill-1 wght-18 leading-none error">delete</span>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr id="noRecordsRow">
                        <td colspan="6" class="attendance__table-cell">No hay Gastos disponibles.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="pagination-container" id="paginationContainer" style="display: <?php echo ($total_paginas > 1) ? 'flex' : 'none'; ?>;">
        <button id="prevPage" class="btn-pagination" <?php echo ($current_page <= 1) ? 'disabled' : ''; ?>>Anterior</button>
        <span id="pageInfo">Página <?php echo $current_page; ?> de <?php echo $total_paginas; ?></span>
        <button id="nextPage" class="btn-pagination" <?php echo ($current_page >= $total_paginas) ? 'disabled' : ''; ?>>Siguiente</button>
    </div>

    <a href="../menu.php" class="btn btn--cancel">Regresar</a>
</div>

<!-- Modal para eliminar -->
<dialog id="confirmDeleteModal" class="confirm-dialog">
    <h3 class="dialog-title">Confirmar Eliminación</h3>
    <p class="dialog-message">
        ¿Estás seguro de que deseas eliminar el gasto:
        <strong id="deleteGastoName"></strong>
    </p>
    <p class="dialog-warning">Al eliminarlo, solo se marcará como inactivo.</p>
    <div class="dialog-actions">
        <a id="deleteGastoLink" class="btn btn--danger">Eliminar</a>
        <button type="button" class="btn btn--secondary" onclick="document.getElementById('confirmDeleteModal').close();">Cancelar</button>
    </div>
</dialog>

<script>
// Datos iniciales precargados
const initialData = <?php echo json_encode($initial_data, JSON_UNESCAPED_UNICODE); ?>;

document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const fechaDesdeInput = document.getElementById('fechaDesdeInput');
    const fechaHastaInput = document.getElementById('fechaHastaInput');
    const clearFiltersBtn = document.getElementById('clearFilters');
    const gastosTableBody = document.getElementById('gastosTableBody');
    const prevPageBtn = document.getElementById('prevPage');
    const nextPageBtn = document.getElementById('nextPage');
    const pageInfoSpan = document.getElementById('pageInfo');
    const paginationContainer = document.getElementById('paginationContainer');

    let currentPage = <?php echo $current_page; ?>;
    let totalPages = <?php echo $total_paginas; ?>;

    // Configurar fechas máximas
    const hoy = new Date();
    const fechaMaxima = hoy.toISOString().split('T')[0];
    if (fechaDesdeInput) fechaDesdeInput.max = fechaMaxima;
    if (fechaHastaInput) fechaHastaInput.max = fechaMaxima;

    function renderGastosTable(gastos) {
        if (!gastosTableBody) return;
        
        gastosTableBody.innerHTML = '';

        if (gastos && gastos.length > 0) {
            gastos.forEach(gasto => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="attendance__table-cell">${escapeHtml(gasto.id_gastos)}</td>
                    <td class="attendance__table-cell">${escapeHtml(gasto.descripcion_gasto)}</td>
                    <td class="attendance__table-cell">${formatMoney(gasto.monto_gasto)}</td>
                    <td class="attendance__table-cell">${escapeHtml(gasto.fecha_gasto)}</td>
                    <td class="attendance__table-cell">${escapeHtml(gasto.categoria_gasto)}</td>
                    <td class="attendance__table-cell">
                        <a title="editar" href="../editar/editar_gastos.php?id_gasto=${escapeHtml(gasto.id_gastos)}">
                            <span class="material-symbols-rounded ico-edit md-24 fill-1 wght-18 leading-none error">edit</span>
                        </a>
                        <button type="button" title="Eliminar" class="btn-icon" onclick="showDeleteModal('${escapeHtml(gasto.id_gastos)}', '${escapeHtml(gasto.descripcion_gasto)}');">
                            <span class="material-symbols-rounded ico-delete md-24 fill-1 wght-18 leading-none error">delete</span>
                        </button>
                    </td>
                `;
                gastosTableBody.appendChild(row);
            });
        } else {
            gastosTableBody.innerHTML = '<tr id="noRecordsRow"><td colspan="6" class="attendance__table-cell">No hay Gastos disponibles.</td></tr>';
        }
    }

    function updatePaginationControls() {
        if (!pageInfoSpan || !prevPageBtn || !nextPageBtn) return;
        
        pageInfoSpan.textContent = `Página ${currentPage} de ${totalPages}`;
        prevPageBtn.disabled = (currentPage <= 1);
        nextPageBtn.disabled = (currentPage >= totalPages || totalPages === 0);
        
        if (paginationContainer) {
            paginationContainer.style.display = (totalPages > 1) ? 'flex' : 'none';
        }
    }

    function escapeHtml(text) {
        if (text === null || text === undefined) return '';
        if (typeof text !== 'string') text = String(text);
        const map = {
            '&': '&amp;',
            '<': '&lt;', 
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, function(m) { return map[m]; });
    }

    function formatMoney(amount) {
        return parseFloat(amount).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
    }

    async function fetchGastos(page = 1) {
        const searchTerm = searchInput ? searchInput.value.trim() : '';
        const fechaDesde = fechaDesdeInput ? fechaDesdeInput.value : '';
        const fechaHasta = fechaHastaInput ? fechaHastaInput.value : '';

        const params = new URLSearchParams();
        params.append('ajax', '1');
        params.append('page', page.toString());
        
        if (searchTerm) params.append('search', searchTerm);
        if (fechaDesde) params.append('fecha_desde', fechaDesde);
        if (fechaHasta) params.append('fecha_hasta', fechaHasta);

        try {
            const response = await fetch(`lista_gastos.php?${params.toString()}`);
            
            if (!response.ok) {
                throw new Error(`Error del servidor: ${response.status}`);
            }
            
            const data = await response.json();
            
            if (!data.success) {
                throw new Error(data.message || 'Error al cargar los datos');
            }

            currentPage = data.current_page;
            totalPages = data.total_pages;

            renderGastosTable(data.gastos);
            updatePaginationControls();

        } catch (error) {
            console.error('Error:', error);
            // Mostrar error en la tabla en lugar de alert
            if (gastosTableBody) {
                gastosTableBody.innerHTML = `<tr><td colspan="6" class="attendance__table-cell" style="color: red;">Error al cargar datos: ${error.message}</td></tr>`;
            }
        }
    }

    // Función global para mostrar modal de eliminación
    window.showDeleteModal = function(id, descripcion) {
        const modal = document.getElementById('confirmDeleteModal');
        const nameSpan = document.getElementById('deleteGastoName');
        const deleteLink = document.getElementById('deleteGastoLink');
        
        if (nameSpan) nameSpan.textContent = descripcion;
        if (deleteLink) deleteLink.href = `../eliminar/eliminar_gasto.php?id_gasto=${id}`;
        
        if (modal) modal.showModal();
    }

    // Event listeners
    let searchTimeout;
    if (searchInput) {
        searchInput.addEventListener('input', () => {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => fetchGastos(1), 500);
        });
    }

    if (fechaDesdeInput) {
        fechaDesdeInput.addEventListener('change', () => fetchGastos(1));
    }

    if (fechaHastaInput) {
        fechaHastaInput.addEventListener('change', () => fetchGastos(1));
    }

    if (clearFiltersBtn) {
        clearFiltersBtn.addEventListener('click', () => {
            if (searchInput) searchInput.value = '';
            if (fechaDesdeInput) fechaDesdeInput.value = '';
            if (fechaHastaInput) fechaHastaInput.value = '';
            fetchGastos(1);
        });
    }

    if (prevPageBtn) {
        prevPageBtn.addEventListener('click', () => {
            if (currentPage > 1) {
                fetchGastos(currentPage - 1);
            }
        });
    }

    if (nextPageBtn) {
        nextPageBtn.addEventListener('click', () => {
            if (currentPage < totalPages) {
                fetchGastos(currentPage + 1);
            }
        });
    }

    // INICIALIZACIÓN CLAVE: Renderizar datos iniciales al cargar la página
    renderGastosTable(initialData);
    updatePaginationControls();
});
</script>
</body>
</html>