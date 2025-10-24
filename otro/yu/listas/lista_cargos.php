<?php
session_start();
include "../conexion/conexion.php";

$items_per_page = 5;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($current_page < 1) $current_page = 1;
$offset = ($current_page - 1) * $items_per_page;

$search_term = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

$where_conditions = ["estado_cargo = 1"];
$params = [];
$param_types = "";

if (!empty($search_term)) {
    $where_conditions[] = "(id_cargo LIKE ? OR nom_cargo LIKE ?)";
    $search_param = '%' . $search_term . '%';
    $params = array_merge($params, [$search_param, $search_param]);
    $param_types .= "ss";
}
$where_sql = "WHERE " . implode(" AND ", $where_conditions);

$sql_count = "SELECT COUNT(*) as total_registro FROM cargo $where_sql";
$stmt_count = $conn->prepare($sql_count);

if ($stmt_count && !empty($params)) {
    $stmt_count->bind_param($param_types, ...$params);
}
$stmt_count->execute();
$result_registro = $stmt_count->get_result()->fetch_assoc();
$total_registro = $result_registro['total_registro'];
$total_paginas = ceil($total_registro / $items_per_page);
$stmt_count->close();

if ($current_page > $total_paginas && $total_paginas > 0) {
    $current_page = $total_paginas;
    $offset = ($current_page - 1) * $items_per_page;
} elseif ($total_paginas == 0) {
    $current_page = 1;
    $offset = 0;
}

$query_sql = "SELECT * FROM cargo $where_sql ORDER BY id_cargo ASC LIMIT ? OFFSET ?";
$stmt_data = $conn->prepare($query_sql);

$params_data = array_merge($params, [$items_per_page, $offset]);
$param_types_data = $param_types . "ii"; 

if ($stmt_data) {
    $stmt_data->bind_param($param_types_data, ...$params_data);
    $stmt_data->execute();
    $query_result = $stmt_data->get_result();
} else {
    error_log("Error al preparar la consulta de cargos: " . $conn->error);
    $query_result = false;
    $alertas['error'][] = "Error al preparar la consulta de cargos: " . $conn->error;
}

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest' || isset($_GET['ajax'])) {
    $cargos_data = [];
    if ($query_result) {
        while ($data = $query_result->fetch_assoc()) {
            $cargos_data[] = $data;
        }
    }

    echo json_encode([
        'cargos' => $cargos_data,
        'total_pages' => $total_paginas,
        'current_page' => $current_page,
        'total_records' => $total_registro
    ]);
    $stmt_data->close();
    $conn->close();
    exit(); 
}

if (isset($stmt_data) && $query_result) {
    $stmt_data->close();
}
$conn->close();
?>
<html>
<head>
    <?php include "../assets/head_gerente.php"?>
    <link rel="stylesheet" href="../assets/css/lista_empleados.css">
    <link rel="stylesheet" href="../assets/css/lista_cargos.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <title>Lista de Cargos</title>
    <style>
        .filters-container {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 20px;
            align-items: flex-end;
            background-color: #fff; 
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05); 
        }
        .filters-container .form-group {
            margin-bottom: 0;
            flex: 1; 
            min-width: 200px; 
        }
        .filters-container label {
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
        }
        .filters-container input[type="text"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box; 
        }
        .filters-container button {
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }
        
    </style>
</head>
<body>
    <?php include "../assets/lista_gerente.php"?>
    <div class="main-content-lista"> 
        <h3>Lista de Cargos</h3>

        <div class="filters-container">
            <div class="form-group">
                <label for="searchInput">Buscar Cargo:</label>
                <input type="text" id="searchInput" class="form-control" placeholder="Buscar por código o nombre..." value="<?php echo htmlspecialchars($search_term); ?>">
            </div>
            <div class="form-group">
                <button id="clearFilters" class="btn btn--secondary">Limpiar Búsqueda</button>
            </div>
        </div>

        <a href="../registrar/registrar_cargo.php" class="btn btn--secondary">Crear Cargo</a>
        
        <div id="cargosTableContainer">
            <table class="attendance__table">
                <tr class="attendance__table-header">
                    <th class="attendance__table-header-cell">Código cargo</th>
                    <th class="attendance__table-header-cell">Nombre del cargo</th>
                    <th>Acciones</th>
                </tr>
                <tbody id="cargosTableBody">
                    <?php
                    if ($query_result && mysqli_num_rows($query_result) > 0) {
                        while ($data = mysqli_fetch_array($query_result)) {
                    ?>
                            <tr>
                                <td class="attendance__table-cell "><?php echo htmlspecialchars($data["id_cargo"]); ?></td>
                                <td class="attendance__table-cell ">
                                    <a href="lista_empleado_cargo.php?Cargo=<?php echo htmlspecialchars($data['id_cargo']); ?>" title="Ver empleados con este cargo"><?php echo htmlspecialchars($data["nom_cargo"]); ?>      
                                    </a>
                                </td>
                                <td class="attendance__table-cell ">
                                    <a title="editar" href="../editar/editar_cargo.php?Cargo=<?php echo htmlspecialchars($data["id_cargo"]); ?>">
                                        <span class="material-symbols-rounded ico-edit md-24 fill-1 wght-18 leading-none alert"></span>
                                    </a> 
                                    <button type="button" title="Eliminar" class="btn-icon" onclick="showDeleteModal('<?php echo htmlspecialchars($data['id_cargo']); ?>', '<?php echo htmlspecialchars(addslashes($data['nom_cargo'])); ?>');">
                                        <span class="material-symbols-rounded ico-delete md-24 fill-1 wght-18 leading-none error"></span>
                                    </button>
                                    <dialog id="confirmDeleteModal_<?php echo htmlspecialchars($data['id_cargo']); ?>" class="confirm-dialog">
                                        <h3 class="dialog-title">Confirmar Eliminación</h3>
                                        <p class="dialog-message">
                                            ¿Estás seguro de que deseas eliminar el cargo
                                            <strong><?php echo htmlspecialchars($data["nom_cargo"]); ?></strong>?
                                        </p>
                                        <p class="dialog-warning">Al eliminarlo, solo se marcará como inactivo.</p>
                                        <div class="dialog-actions">
                                            <a href="../eliminar/eliminar_cargos.php?Cargo=<?php echo htmlspecialchars($data['id_cargo']); ?>" class="btn btn--danger">Eliminar</a>
                                            <button type="button" class="btn btn--secondary" onclick="document.getElementById('confirmDeleteModal_<?php echo htmlspecialchars($data['id_cargo']); ?>').close();">Cancelar</button>
                                        </div>
                                    </dialog>
                                </td>
                            </tr>
                    <?php
                        }
                    } else {
                        echo '<tr id="noRecordsRow"><td colspan="3" class="attendance__table-cell ">No hay registros</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <div class="pagination-container" id="paginationContainer">
            <button id="prevPage">Anterior</button>
            <span id="pageInfo">Página <?php echo $current_page; ?> de <?php echo $total_paginas; ?></span>
            <button id="nextPage">Siguiente</button>
        </div>

        <a href="../menu.php" class="btn btn--cancel">Regresar</a>
    </div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const clearFiltersBtn = document.getElementById('clearFilters');
        const cargosTableBody = document.getElementById('cargosTableBody');
        const paginationContainer = document.getElementById('paginationContainer');
        const prevPageBtn = document.getElementById('prevPage');
        const nextPageBtn = document.getElementById('nextPage');
        const pageInfoSpan = document.getElementById('pageInfo');

        let currentPage = <?php echo $current_page; ?>;
        let totalPages = <?php echo $total_paginas; ?>;

        function renderCargosTable(cargos) {
            cargosTableBody.innerHTML = ''; 

            if (cargos.length > 0) {
                cargos.forEach(cargo => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="attendance__table-cell">${cargo.id_cargo}</td>
                        <td class="attendance__table-cell">
                            <a href="lista_empleado_cargo.php?Cargo=${cargo.id_cargo}" title="Ver empleados con este cargo">${cargo.nom_cargo}</a>
                        </td>
                        <td class="attendance__table-cell">
                            <a title="editar" href="../editar/editar_cargo.php?Cargo=${cargo.id_cargo}">
                                <span class="material-symbols-rounded ico-edit md-24 fill-1 wght-18 leading-none alert"></span>
                            </a> 
                            <button type="button" title="Eliminar" class="btn-icon" onclick="showDeleteModal('${cargo.id_cargo}', '${escapeHtml(cargo.nom_cargo)}');">
                                <span class="material-symbols-rounded ico-delete md-24 fill-1 wght-18 leading-none error"></span>
                            </button>
                        </td>
                    `;
                    cargosTableBody.appendChild(row);
                });
                if (cargosTableBody.closest('table')) cargosTableBody.closest('table').style.display = 'table';
                const noRecordsRow = document.getElementById('noRecordsRow');
                if (noRecordsRow) noRecordsRow.style.display = 'none';

            } else {
                cargosTableBody.innerHTML = '<tr id="noRecordsRow"><td colspan="3" class="attendance__table-cell">No hay registros</td></tr>';
                if (cargosTableBody.closest('table')) cargosTableBody.closest('table').style.display = 'table';
            }
            attachModalListeners();
        }

        function updatePaginationControls() {
            pageInfoSpan.textContent = `Página ${currentPage} de ${totalPages}`;
            prevPageBtn.disabled = (currentPage === 1);
            nextPageBtn.disabled = (currentPage === totalPages || totalPages === 0);
            paginationContainer.style.display = (totalPages > 1) ? 'flex' : 'none'; 
        }

        async function fetchCargos(page = 1) {
            const searchTerm = searchInput.value;

            const params = new URLSearchParams();
            params.append('ajax', '1'); 
            params.append('page', page);
            if (searchTerm) params.append('search', searchTerm);

            try {
                const response = await fetch(`lista_cargos.php?${params.toString()}`);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                const data = await response.json();
                
                currentPage = data.current_page;
                totalPages = data.total_pages;

                renderCargosTable(data.cargos);
                updatePaginationControls();

            } catch (error) {
                console.error('Error al cargar los cargos:', error);
                cargosTableBody.innerHTML = `<tr><td colspan="3" class="attendance__table-cell" style="color: red;">Error al cargar los cargos: ${error.message}</td></tr>`;
                updatePaginationControls(); 
            }
        }

        window.showDeleteModal = function(id_cargo, nom_cargo) {
            let dialog = document.getElementById(`confirmDeleteModal_${id_cargo}`);
            if (!dialog) {
                dialog = document.createElement('dialog');
                dialog.id = `confirmDeleteModal_${id_cargo}`;
                dialog.className = 'confirm-dialog';
                dialog.innerHTML = `
                    <h3 class="dialog-title">Confirmar Eliminación</h3>
                    <p class="dialog-message">
                        ¿Estás seguro de que deseas eliminar el cargo
                        <strong>${nom_cargo}</strong>?
                    </p>
                    <p class="dialog-warning">Al eliminarlo, solo se marcará como inactivo.</p>
                    <div class="dialog-actions">
                        <a href="../eliminar/eliminar_cargos.php?Cargo=${id_cargo}" class="btn btn--danger">Eliminar</a>
                        <button type="button" class="btn btn--secondary" onclick="document.getElementById('confirmDeleteModal_${id_cargo}').close();">Cancelar</button>
                    </div>
                `;
                document.body.appendChild(dialog);
            }
            dialog.showModal();
        }

        function escapeHtml(text) {
            const map = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            };
            return text.replace(/[&<>"']/g, function(m) { return map[m]; });
        }

        function attachModalListeners() {
            
        }

        searchInput.addEventListener('input', () => fetchCargos(1)); 

        clearFiltersBtn.addEventListener('click', () => {
            searchInput.value = '';
            fetchCargos(1); 
        });

        prevPageBtn.addEventListener('click', () => {
            if (currentPage > 1) {
                fetchCargos(currentPage - 1);
            }
        });

        nextPageBtn.addEventListener('click', () => {
            if (currentPage < totalPages) {
                fetchCargos(currentPage + 1);
            }
        });

        fetchCargos(currentPage);
    });
</script>
</body>
</html>