<?php
// 1. LIMPIEZA DE BUFFER Y ENCABEZADOS DE CODIFICACIÓN
header('Content-Type: text/html; charset=UTF-8');
if (ob_get_level() > 0) {
    ob_end_clean();
}
ob_start(); // Iniciar buffer nuevo

session_start();

// 2. CONEXIÓN
include "../conexion/conexion.php";

// Verificar conexión
if (!$conn) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Establecer charset UTF-8 para la conexión
$conn->set_charset("utf8");

// --- INICIALIZACIÓN DE VARIABLES ---
$items_per_page = 5;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($current_page < 1) $current_page = 1;

$search_term = isset($_GET['search']) ? trim($_GET['search']) : '';

$where_conditions = ["empleado.estado_empleado = 1"];
$params = [];
$param_types = "";

if (!empty($search_term)) {
    $where_conditions[] = "(empleado.cedula_emple LIKE ? OR
                            empleado.nombre_emp LIKE ? OR
                            empleado.apellido_emp LIKE ? OR
                            cargo.nom_cargo LIKE ?)";
    $search_param = '%' . $search_term . '%';
    $params = array_merge($params, [$search_param, $search_param, $search_param, $search_param]);
    $param_types .= "ssss";
}

$where_sql = !empty($where_conditions) ? "WHERE " . implode(" AND ", $where_conditions) : "";

// Función auxiliar para bind_param (compatibilidad PHP)
function refValues($arr){
    $refs = array();
    foreach($arr as $key => $value) {
        $refs[$key] = &$arr[$key];
    }
    return $refs;
}

// --- CONTEO TOTAL DE REGISTROS ---
$sql_count = "SELECT COUNT(*) as total_registro
    FROM empleado
    INNER JOIN cargo ON empleado.id_cargo = cargo.id_cargo
    $where_sql";
    
$stmt_count = $conn->prepare($sql_count);
$total_registro = 0;

if ($stmt_count) {
    if (!empty($params)) {
        call_user_func_array([$stmt_count, 'bind_param'], refValues(array_merge([$param_types], $params)));
    }
    $stmt_count->execute();
    $result_registro = $stmt_count->get_result();
    if ($result_registro) {
        $row = $result_registro->fetch_assoc();
        $total_registro = $row ? $row['total_registro'] : 0;
    }
    $stmt_count->close();
} else {
    error_log("Error en contar registros: " . $conn->error);
    $total_registro = 0;
}

$items_per_page = 5;
$total_paginas = $total_registro > 0 ? ceil($total_registro / $items_per_page) : 1;

// Ajuste de página si está fuera de rango
if ($total_paginas > 0 && $current_page > $total_paginas) {
    $current_page = $total_paginas;
} elseif ($total_paginas == 0) {
    $current_page = 1;
}

$offset = ($current_page - 1) * $items_per_page;
if ($offset < 0) $offset = 0;

// --- CONSULTA DE DATOS DE EMPLEADOS ---
$query_sql = "SELECT empleado.cedula_emple, empleado.nombre_emp, empleado.apellido_emp,
    cargo.nom_cargo AS cargo
    FROM empleado
    INNER JOIN cargo ON empleado.id_cargo = cargo.id_cargo
    $where_sql
    ORDER BY empleado.cedula_emple ASC LIMIT ? OFFSET ?";

$initial_data = [];
$query_result_success = false;

$stmt_data = $conn->prepare($query_sql);

if ($stmt_data) {
    // Preparar parámetros para LIMIT y OFFSET
    $params_data = $params;
    $param_types_data = $param_types . "ii";
    $params_data[] = $items_per_page;
    $params_data[] = $offset;
    
    if (!empty($param_types_data)) {
        call_user_func_array([$stmt_data, 'bind_param'], refValues(array_merge([$param_types_data], $params_data)));
    }
    
    $stmt_data->execute();
    $result = $stmt_data->get_result();
    
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $initial_data[] = $row;
        }
        $query_result_success = true;
    }
    $stmt_data->close();
} else {
    error_log("Error al preparar consulta: " . $conn->error);
}

// --- BLOQUE AJAX DE RESPUESTA ---
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest' || isset($_GET['ajax'])) {
    
    ob_clean(); // Limpiar buffer antes de JSON
    
    header('Content-Type: application/json; charset=UTF-8');
    
    if (!$query_result_success) {
        $response = [
            'success' => false,
            'message' => 'Error al ejecutar la consulta de empleados.',
            'empleados' => [],
            'total_pages' => 0,
            'current_page' => 1,
            'total_records' => 0
        ];
        http_response_code(500); 
    } else {
        $response = [
            'success' => true,
            'empleados' => $initial_data,
            'total_pages' => $total_paginas,
            'current_page' => $current_page,
            'total_records' => $total_registro,
            'search_term' => $search_term
        ];
    }
    
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    
    if ($conn) { $conn->close(); }
    exit();
}

// Cierre de conexión para la carga inicial HTML
if ($conn) { $conn->close(); }

// --- LÓGICA DE ALERTAS Y VARIABLES DE SESIÓN ---
$alert_message = '';
$alert_type = '';
if (isset($_SESSION['alert_message'])) {
    $alert_message = $_SESSION['alert_message'];
    $alert_type = $_SESSION['alert_type'];
    unset($_SESSION['alert_message']);
    unset($_SESSION['alert_type']);
}

ob_end_flush(); // Limpiar buffer final
?>
<html>
<head>
<?php include "../assets/head_gerente.php"?>
<link rel="stylesheet" href="../assets/css/lista_empleados.css">
<title>Lista de Empleados</title>
<link rel="stylesheet" href="assets/fonts/google-icons/index.css">
<style>
    .filters-container {
        display: flex; flex-wrap: wrap; gap: 15px; margin-bottom: 20px; align-items: flex-end;
        background-color: #fff; padding: 15px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }
    .filters-container .form-group { margin-bottom: 0; flex: 1; min-width: 200px; }
    .filters-container label { font-weight: bold; margin-bottom: 5px; display: block; }
    .filters-container input[type="text"] { width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
    .filters-container button { padding: 8px 15px; border: none; border-radius: 5px; cursor: pointer; transition: background-color 0.2s ease; }
    .btn--secondary { background-color: #6c757d; color: white; }
    .btn--secondary:hover { background-color: #5a6268; }

    .pagination-container { display: flex; justify-content: center; align-items: center; margin-top: 20px; gap: 10px; }
    .pagination-container button { padding: 8px 15px; border: 1px solid #007bff; background-color: #007bff; color: white; border-radius: 5px; cursor: pointer; transition: background-color 0.2s ease; }
    .pagination-container button:hover:not(:disabled) { background-color: #0056b3; }
    .pagination-container button:disabled { background-color: #cccccc; border-color: #cccccc; cursor: not-allowed; }
    .pagination-container span { padding: 8px 15px; font-weight: bold; color: #333; }
    .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.4); justify-content: center; align-items: center; }
    .modal-content { background-color: #fefefe; margin: auto; padding: 20px; border: 1px solid #888; width: 80%; max-width: 400px; text-align: center; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.2); position: relative; color: #333; }
    .success-icon { color: #4CAF50; font-size: 60px; margin-bottom: 10px; }
    .error-icon { color: #F44336; font-size: 60px; margin-bottom: 10px; }
    .dialog-actions { display: flex; justify-content: center; gap: 10px; flex-direction: row-reverse; }
</style>
</head>

<body>
<?php include "../assets/lista_gerente.php"?>
<div class="main-content-lista">
    <h3>Lista de Empleados</h3>
    
    <div class="filters-container">
        <div class="form-group">
            <label for="searchInput">Buscar Empleado:</label>
            <input type="text" id="searchInput" class="form-control" placeholder="Cédula, nombre, apellido o cargo..." value="<?php echo htmlspecialchars($search_term); ?>">
        </div>
        <div class="form-group">
            <button id="clearFilters" class="btn btn--secondary">Limpiar Búsqueda</button>
        </div>
    </div>

    <a href="../registrar/registrar_empleado.php" class="btn btn--secondary">Registrar Empleado</a>
    
    <div id="empleadosTableContainer">
        <table class="attendance__table">
            <tr class="attendance__table-header">
                <th class="attendance__table-header-cell ">Cédula del Empleado</th>
                <th class="attendance__table-header-cell ">Nombre</th>
                <th class="attendance__table-header-cell ">Apellido</th>
                <th class="attendance__table-header-cell ">Cargo</th>
                <th class="attendance__table-header-cell ">Acciones</th>
            </tr>
            <tbody id="empleadosTableBody">
                <?php if (!empty($initial_data)): ?>
                    <?php foreach ($initial_data as $empleado): ?>
                    <tr>
                        <td class="attendance__table-cell"><?php echo htmlspecialchars($empleado['cedula_emple']); ?></td>
                        <td class="attendance__table-cell">
                            <a href="../funciones/historial_pagos.php?Empleado=<?php echo urlencode($empleado['cedula_emple']); ?>">
                                <?php echo htmlspecialchars($empleado['nombre_emp']); ?>
                            </a>
                        </td>
                        <td class="attendance__table-cell"><?php echo htmlspecialchars($empleado['apellido_emp']); ?></td>
                        <td class="attendance__table-cell"><?php echo htmlspecialchars($empleado['cargo']); ?></td>
                        <td class="attendance__table-cell">
                            <a title="editar" href="../editar/editar_empleado.php?Empleado=<?php echo urlencode($empleado['cedula_emple']); ?>">
                                <span class="material-symbols-rounded ico-edit md-24 fill-1 wght-18 leading-none error"></span>
                            </a>
                            <button type="button" title="Eliminar" class="btn-icon" onclick="showDeleteModal('<?php echo addslashes($empleado['cedula_emple']); ?>', '<?php echo addslashes($empleado['nombre_emp'] . " " . $empleado['apellido_emp']); ?>');">
                                <span class="material-symbols-rounded ico-delete md-24 fill-1 wght-18 leading-none error"></span>
                            </button>
                            <a title="pago" href="../funciones/pago_empleados.php?Empleado=<?php echo urlencode($empleado['cedula_emple']); ?>">
                                <span class="material-symbols-rounded ico-local_atm md-24 fill-1 wght-18 leading-none blue-light"></span>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr id="noRecordsRow">
                        <td colspan="5" class="attendance__table-cell">No hay empleados disponibles.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="pagination-container" id="paginationContainer">
        <button id="prevPage" <?php echo ($current_page == 1) ? 'disabled' : ''; ?>>Anterior</button>
        <span id="pageInfo">Página <?php echo $current_page; ?> de <?php echo $total_paginas; ?></span>
        <button id="nextPage" <?php echo ($current_page >= $total_paginas) ? 'disabled' : ''; ?>>Siguiente</button>
    </div>

    <a href="../menu.php" class="btn btn--cancel">Regresar</a>
</div>

<?php if (!empty($alert_message)): ?>
<div id="miModal" class="modal">
    <div class="modal-content">
        <?php if ($alert_type === 'success'): ?>
            <span class="success-icon">&#10004;</span>
            <h2>Éxito</h2>
        <?php else: ?>
            <span class="error-icon">&#10006;</span>
            <h2>Error</h2>
        <?php endif; ?>
        <p><?php echo $alert_message; ?></p>
    </div>
</div>
<?php endif; ?>

<script>
    // Inicialización con valores PHP.
    let currentPage = <?php echo $current_page; ?>;
    let totalPages = <?php echo $total_paginas; ?>;
    
    function htmlspecialchars(text) {
        if (text === null || text === undefined) return '';
        if (typeof text !== 'string') text = String(text);
        const map = {
            '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, function(m) { return map[m]; });
    }

    function updatePaginationControls() {
        const prevPageBtn = document.getElementById('prevPage');
        const nextPageBtn = document.getElementById('nextPage');
        const pageInfoSpan = document.getElementById('pageInfo');

        pageInfoSpan.textContent = `Página ${currentPage} de ${totalPages}`;
        prevPageBtn.disabled = (currentPage === 1);
        nextPageBtn.disabled = (currentPage === totalPages || totalPages === 0);
    }

    function renderEmpleadosTable(empleados) {
        const empleadosTableBody = document.getElementById('empleadosTableBody');
        empleadosTableBody.innerHTML = '';

        if (empleados.length > 0) {
            empleados.forEach(empleado => {
                const nombre_completo = htmlspecialchars(empleado.nombre_emp + " " + empleado.apellido_emp);
                const cedula = htmlspecialchars(empleado.cedula_emple);
                
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="attendance__table-cell">${cedula}</td>
                    <td class="attendance__table-cell"><a href="../funciones/historial_pagos.php?Empleado=${encodeURIComponent(cedula)}">${htmlspecialchars(empleado.nombre_emp)}</a></td>
                    <td class="attendance__table-cell">${htmlspecialchars(empleado.apellido_emp)}</td>
                    <td class="attendance__table-cell">${htmlspecialchars(empleado.cargo)}</td>
                    <td class="attendance__table-cell">
                        <a title="editar" href="../editar/editar_empleado.php?Empleado=${encodeURIComponent(cedula)}">
                            <span class="material-symbols-rounded ico-edit md-24 fill-1 wght-18 leading-none error">edit</span>
                        </a>
                        <button type="button" title="Eliminar" class="btn-icon" onclick="showDeleteModal('${cedula.replace(/'/g, "\\'")}', '${nombre_completo.replace(/'/g, "\\'")}');">
                            <span class="material-symbols-rounded ico-delete md-24 fill-1 wght-18 leading-none error">delete</span>
                        </button>
                        <a title="pago" href="../funciones/pago_empleados.php?Empleado=${encodeURIComponent(cedula)}">
                            <span class="material-symbols-rounded ico-local_atm md-24 fill-1 wght-18 leading-none blue-light">payments</span>
                        </a>
                    </td>
                `;
                empleadosTableBody.appendChild(row);
            });
        } else {
            empleadosTableBody.innerHTML = '<tr id="noRecordsRow"><td colspan="5" class="attendance__table-cell">No hay empleados disponibles.</td></tr>';
        }

        updatePaginationControls();
    }

    async function fetchEmpleados(page = 1) {
        const searchInput = document.getElementById('searchInput');
        const searchTerm = searchInput.value;

        const params = new URLSearchParams();
        params.append('ajax', '1');
        params.append('page', page);
        if (searchTerm) params.append('search', searchTerm);
        
        try {
            const response = await fetch(window.location.pathname + '?' + params.toString());
            
            if (!response.ok) {
                throw new Error(`Error HTTP! Estado: ${response.status}`);
            }
            
            const data = await response.json();
            
            if (!data.success) {
                throw new Error(data.message || 'Error del servidor');
            }

            currentPage = data.current_page;
            totalPages = data.total_pages;

            renderEmpleadosTable(data.empleados);
            
        } catch (error) {
            console.error('Error al cargar los empleados:', error);
            const empleadosTableBody = document.getElementById('empleadosTableBody');
            empleadosTableBody.innerHTML = `<tr><td colspan="5" class="attendance__table-cell" style="color: red;">Error al cargar datos</td></tr>`;
            updatePaginationControls();
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const clearFiltersBtn = document.getElementById('clearFilters');
        const prevPageBtn = document.getElementById('prevPage');
        const nextPageBtn = document.getElementById('nextPage');
        var modal = document.getElementById('miModal');

        if (modal) {
            modal.style.display = 'flex';
            setTimeout(function() {
                modal.style.display = 'none';
            }, 3000);
        }

        window.showDeleteModal = function(cedula, nombre_completo) {
            if (confirm(`¿Está seguro de eliminar al empleado: ${nombre_completo}?`)) {
                window.location.href = `../funciones/eliminar_empleado.php?Empleado=${encodeURIComponent(cedula)}`;
            }
        }
        
        // Eventos
        searchInput.addEventListener('input', () => fetchEmpleados(1));
        clearFiltersBtn.addEventListener('click', () => {
            searchInput.value = '';
            fetchEmpleados(1);
        });
        prevPageBtn.addEventListener('click', () => {
            if (currentPage > 1) fetchEmpleados(currentPage - 1);
        });
        nextPageBtn.addEventListener('click', () => {
            if (currentPage < totalPages) fetchEmpleados(currentPage + 1);
        });
    });
</script>
</body>
</html>