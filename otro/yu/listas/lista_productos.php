<?php
// HEADERS DE CODIFICACIÓN AL INICIO
header('Content-Type: text/html; charset=UTF-8');
ob_start(); // Iniciar buffer desde el principio

require_once '../conexion/conexion.php';

if ($conn->connect_error) {
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest' || isset($_GET['ajax'])) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Error de conexión a la base de datos.', 'productos' => []]);
        exit();
    }
    die("<!DOCTYPE html><html lang='es'><head><title>Error</title></head><body><p class='mensaje-error'>Error de conexión a la base de datos: " . htmlspecialchars($conn->connect_error) . "</p></body></html>");
}

// Establecer charset UTF-8 para la conexión
$conn->set_charset("utf8");

session_start();

$tasaCambio = 1.0;
$mensaje_tasa = "";

$sqlTasa = "SELECT tasa_dolar FROM tasa_dolar ORDER BY fecha_dolar DESC LIMIT 1";
$resultTasa = $conn->query($sqlTasa);

if ($resultTasa && $resultTasa->num_rows > 0) {
    $rowTasa = $resultTasa->fetch_assoc();
    $tasaObtenida = floatval(str_replace(',', '.', $rowTasa['tasa_dolar']));
    if ($tasaObtenida > 0) {
        $tasaCambio = $tasaObtenida;
    } else {
        $mensaje_tasa = "<p class='mensaje-advertencia'>Advertencia: Tasa de cambio inválida (&lt;= 0). Usando tasa predeterminada 1.0.</p>";
    }
} else {
    if ($resultTasa === false) {
        error_log("ERROR: lista_productos.php - Falló la consulta de tasa: " . $conn->error);
        $mensaje_tasa = "<p class='mensaje-error'>Error al obtener tasa: " . htmlspecialchars($conn->error) . ". Usando tasa predeterminada 1.0.</p>";
    } else {
        $mensaje_tasa = "<p class='mensaje-advertencia'>Advertencia: No hay tasa de cambio registrada. Usando tasa predeterminada 1.0.</p>";
    }
}

$items_per_page = 10;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($current_page < 1) $current_page = 1;

$search_term = isset($_GET['search']) ? trim($_GET['search']) : '';

$where_conditions = ["p.estado_producto = 1"];
$params = [];
$param_types = "";

if (!empty($search_term)) {
    $where_conditions[] = "(p.nombre_producto LIKE ? OR c.nombre_categoria LIKE ? OR p.codigo LIKE ?)";
    $search_param = '%' . $search_term . '%';
    $params = array_merge($params, [$search_param, $search_param, $search_param]);
    $param_types .= "sss";
}

$where_sql = !empty($where_conditions) ? "WHERE " . implode(" AND ", $where_conditions) : "";

function refValues($arr){
    $refs = array();
    foreach($arr as $key => $value) {
        $refs[$key] = &$arr[$key];
    }
    return $refs;
}

// --- CONTEO TOTAL DE REGISTROS ---
$sql_count = "SELECT COUNT(p.id_pro) as total_registro
              FROM producto p
              LEFT JOIN categoria c ON p.id_categoria = c.id_categoria
              $where_sql";
$total_registro = 0;
$stmt_count = $conn->prepare($sql_count);

if ($stmt_count === false) {
    error_log("ERROR: lista_productos.php - Falló la preparación de la consulta de conteo: " . $conn->error);
} else {
    if (!empty($params)) {
        $bind_params_count = array_merge([$param_types], $params);
        call_user_func_array([$stmt_count, 'bind_param'], refValues($bind_params_count));
    }
    if ($stmt_count->execute()) {
        $result_registro = $stmt_count->get_result()->fetch_assoc();
        $total_registro = $result_registro['total_registro'];
    } else {
        error_log("ERROR: lista_productos.php - Falló la ejecución de la consulta de conteo: " . $stmt_count->error);
    }
    $stmt_count->close();
}

$total_paginas = ceil($total_registro / $items_per_page);

if ($current_page > $total_paginas && $total_paginas > 0) {
    $current_page = $total_paginas;
} elseif ($total_paginas == 0) {
    $current_page = 1;
}
$offset = ($current_page - 1) * $items_per_page;
if ($offset < 0) $offset = 0;

// --- CONSULTA DE DATOS PARA RENDERIZADO INICIAL ---
$sql_productos = "SELECT p.id_pro, p.nombre_producto, p.cantidad, p.precio, p.codigo, p.descrip_prod, c.nombre_categoria, p.id_tipo_cuenta
                  FROM producto p
                  LEFT JOIN categoria c ON p.id_categoria = c.id_categoria
                  $where_sql
                  ORDER BY p.id_pro ASC
                  LIMIT ?, ?";
$stmt_data = $conn->prepare($sql_productos);

$initial_data = [];
$query_result_success = false;

if ($stmt_data === false) {
    error_log("ERROR: lista_productos.php - Falló la preparación de la consulta de datos: " . $conn->error);
} else {
    $params_data = $params;
    $param_types_data = $param_types . "ii";
    $params_data[] = $offset;
    $params_data[] = $items_per_page;
    
    if (!empty($param_types_data)) {
        $bind_params_data = array_merge([$param_types_data], $params_data);
        if (call_user_func_array([$stmt_data, 'bind_param'], refValues($bind_params_data)) && $stmt_data->execute()) {
            $result = $stmt_data->get_result();
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $initial_data[] = $row;
                }
                $query_result_success = true;
            }
        } else {
            error_log("ERROR: lista_productos.php - Falló la ejecución/bind_param de la consulta de datos: " . $stmt_data->error);
        }
    }
    $stmt_data->close();
}

// --- BLOQUE AJAX DE RESPUESTA ---
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest' || isset($_GET['ajax'])) {
    
    ob_clean(); // Limpiar buffer antes de JSON
    header('Content-Type: application/json; charset=UTF-8');
    
    $productos_data = [];
    $error_message = '';
    $success = true;

    if (!$query_result_success) {
        $success = false;
        $error_message = "Error en la consulta de productos: " . ($conn->error ?? 'Error desconocido');
        error_log("ERROR: lista_productos.php (AJAX/consulta): " . $error_message);
    } else {
        foreach ($initial_data as $data) {
            $precio_usd = floatval(str_replace(',', '.', $data['precio']));
            $precio_ves = $precio_usd * $tasaCambio;

            $data['precio_usd_formatted'] = number_format($precio_usd, 4, '.', ',');
            $data['precio_ves_formatted'] = number_format($precio_ves, 2, '.', ',');
            $data['cantidad_display'] = (intval($data['id_tipo_cuenta']) === 1) ? 'Siempre en Existencia' : htmlspecialchars($data['cantidad']);
            $data['nombre_categoria_display'] = htmlspecialchars($data['nombre_categoria'] ?? 'N/A');
            $data['descrip_prod_short'] = htmlspecialchars($data['descrip_prod']);
            
            $productos_data[] = $data;
        }
    }

    $response = [
        'success' => $success,
        'message' => $error_message,
        'productos' => $productos_data,
        'total_pages' => $total_paginas,
        'current_page' => $current_page,
        'total_records' => $total_registro,
        'tasa_cambio' => $tasaCambio
    ];
    
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    
    if ($conn) {
        $conn->close();
    }
    exit();
}

// Cierre de conexión para la carga inicial HTML
if ($conn) {
    $conn->close();
}

ob_end_flush(); // Limpiar buffer final
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Productos</title>
    <?php include "../assets/head_gerente.php"?>
    <link rel="stylesheet" href="../assets/css/lista_productos.css">
    <link rel="stylesheet" href="../assets/css/lista_empleados.css">
    <style>
        .filters-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 15px;
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
        .btn--secondary {
            background-color: #6c757d;
            color: white;
        }
        .btn--secondary:hover {
            background-color: #5a6268;
        }

        .pagination-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 20px;
            gap: 10px;
        }
        .pagination-container button {
            padding: 8px 15px;
            border: 1px solid #007bff;
            background-color: #007bff;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }
        .pagination-container button:hover:not(:disabled) {
            background-color: #0056b3;
        }
        .pagination-container button:disabled {
            background-color: #cccccc;
            border-color: #cccccc;
            cursor: not-allowed;
        }
        .pagination-container span {
            padding: 8px 15px;
            font-weight: bold;
            color: #333;
        }
    </style>
</head>
<body>
<?php include "../assets/lista_gerente.php"?>

    <div class="main-content-lista">
        <h1>Lista de Productos</h1>

        <?php echo $mensaje_tasa; ?>

        <div class="filters-container">
            <div class="form-group">
                <label for="searchInput">Buscar Producto:</label>
                <input type="text" id="searchInput" class="form-control" placeholder="Nombre, categoría o código..." value="<?php echo htmlspecialchars($search_term); ?>">
            </div>
            <div class="form-group">
                <button id="clearFilters" class="btn btn--secondary">Limpiar Búsqueda</button>
            </div>
        </div>

        <a href='../registrar/productos.php' class="btn btn--secondary">Registrar Nuevo Producto</a>
        
        <div class="info-tasa">
            <input type="hidden" id="tasaCambio" value="<?php echo htmlspecialchars($tasaCambio); ?>">
            <span>Tasa Actual (1 USD = <span id="tasa_actual_display"><?php echo htmlspecialchars(number_format($tasaCambio, 2, '.', ',')); ?></span> VES)</span>
        </div>

        <div id="productosTableContainer">
            <table class="attendance__table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Cantidad</th>
                        <th>Precio (USD)</th>
                        <th>Precio (VES)</th>
                        <th>Categoría</th>
                        <th>Código</th>
                        <th>Descripción</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="productosTableBody">
                    <?php if (!empty($initial_data)): ?>
                        <?php foreach ($initial_data as $row): ?>
                            <?php
                            $id_pro = intval($row['id_pro']);
                            $cantidad_db = intval($row['cantidad']);
                            $id_tipo_cuenta_producto = isset($row['id_tipo_cuenta']) ? intval($row['id_tipo_cuenta']) : null;
                            $precio_usd = floatval(str_replace(',', '.', $row['precio']));
                            $precio_ves = $precio_usd * $tasaCambio;
                            ?>
                            <tr>
                                <td><?php echo $id_pro; ?></td>
                                <td><?php echo htmlspecialchars($row["nombre_producto"]); ?></td>
                                <td><?php echo ($id_tipo_cuenta_producto === 1) ? 'Siempre en Existencia' : htmlspecialchars($cantidad_db); ?></td>
                                <td class='precio-usd' data-original-usd='<?php echo $precio_usd; ?>'><?php echo number_format($precio_usd, 4, '.', ','); ?></td>
                                <td class='precio-ves'><?php echo number_format($precio_ves, 2, '.', ','); ?></td>
                                <td><?php echo htmlspecialchars($row['nombre_categoria'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($row["codigo"]); ?></td>
                                <td><div class='descripcion-corta' title='<?php echo htmlspecialchars($row["descrip_prod"]); ?>'><?php echo htmlspecialchars($row["descrip_prod"]); ?></div></td>
                                <td class='acciones'>
                                    <a href='../editar/modificar_productos.php?id=<?php echo $id_pro; ?>' title='Editar'>
                                        <span class='material-symbols-rounded ico-edit md-24 fill-1 wght-400'></span>
                                    </a>
                                    <button type='button' title='Eliminar' class='btn-icon' onclick="showDeleteModal('<?php echo htmlspecialchars($row['id_pro']); ?>', '<?php echo htmlspecialchars(addslashes($row['nombre_producto'])); ?>');">
                                        <span class='material-symbols-rounded ico-delete md-24 fill-1 wght-400'></span>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr id="noRecordsRow"><td colspan="9" class="attendance__table-cell">No hay productos registrados o activos que coincidan con la búsqueda.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="pagination-container" id="paginationContainer" style="display: <?php echo ($total_paginas > 1) ? 'flex' : 'none'; ?>;">
            <button id="prevPage" <?php echo ($current_page <= 1) ? 'disabled' : ''; ?>>Anterior</button>
            <span id="pageInfo">Página <?php echo $current_page; ?> de <?php echo $total_paginas; ?></span>
            <button id="nextPage" <?php echo ($current_page >= $total_paginas || $total_paginas == 0) ? 'disabled' : ''; ?>>Siguiente</button>
        </div>

    </div>

<script>
    // Inicialización con valores PHP.
    let currentPage = <?php echo $current_page; ?>;
    let totalPages = <?php echo $total_paginas; ?>;
    let currentTasaCambio = parseFloat(<?php echo json_encode($tasaCambio); ?>);
    // Datos iniciales precargados
    const initialData = <?php echo json_encode($initial_data, JSON_UNESCAPED_UNICODE); ?>;

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
    
    function htmlspecialchars(text) {
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

    function renderProductosTable(productos) {
        const productosTableBody = document.getElementById('productosTableBody');
        productosTableBody.innerHTML = '';

        if (productos.length > 0) {
            productos.forEach(producto => {
                const precio_usd = parseFloat(producto.precio);
                const precio_ves = precio_usd * currentTasaCambio;
                
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${producto.id_pro}</td>
                    <td>${htmlspecialchars(producto.nombre_producto)}</td>
                    <td>${(parseInt(producto.id_tipo_cuenta) === 1) ? 'Siempre en Existencia' : htmlspecialchars(producto.cantidad)}</td>
                    <td class='precio-usd' data-original-usd='${precio_usd.toFixed(4)}'>${precio_usd.toFixed(4)}</td>
                    <td class='precio-ves'>${precio_ves.toFixed(2)}</td>
                    <td>${htmlspecialchars(producto.nombre_categoria || 'N/A')}</td>
                    <td>${htmlspecialchars(producto.codigo)}</td>
                    <td><div class='descripcion-corta' title='${htmlspecialchars(producto.descrip_prod)}'>${htmlspecialchars(producto.descrip_prod)}</div></td>
                    <td class='acciones'>
                        <a href='../editar/modificar_productos.php?id=${producto.id_pro}' title='Editar'>
                            <span class='material-symbols-rounded ico-edit md-24 fill-1 wght-400'>edit</span>
                        </a>
                        <button type='button' title='Eliminar' class='btn-icon' onclick="showDeleteModal('${producto.id_pro}', '${escapeHtml(producto.nombre_producto)}');">
                            <span class='material-symbols-rounded ico-delete md-24 fill-1 wght-400'>delete</span>
                        </button>
                    </td>
                `;
                productosTableBody.appendChild(row);
            });
        } else {
            productosTableBody.innerHTML = '<tr id="noRecordsRow"><td colspan="9" class="attendance__table-cell">No hay productos registrados o activos que coincidan con la búsqueda.</td></tr>';
        }
    }

    function updatePaginationControls() {
        const pageInfoSpan = document.getElementById('pageInfo');
        const prevPageBtn = document.getElementById('prevPage');
        const nextPageBtn = document.getElementById('nextPage');
        const paginationContainer = document.getElementById('paginationContainer');
        
        pageInfoSpan.textContent = `Página ${currentPage} de ${totalPages}`;
        prevPageBtn.disabled = (currentPage === 1);
        nextPageBtn.disabled = (currentPage === totalPages || totalPages === 0);
        paginationContainer.style.display = (totalPages > 1) ? 'flex' : 'none';
    }

    async function fetchProductos(page = 1) {
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
            currentTasaCambio = data.tasa_cambio;
            
            const tasaActualDisplay = document.getElementById('tasa_actual_display');
            if (tasaActualDisplay) {
                tasaActualDisplay.textContent = currentTasaCambio.toFixed(2);
            }

            renderProductosTable(data.productos);
            updatePaginationControls();

        } catch (error) {
            console.error('Error al cargar los productos:', error);
            const productosTableBody = document.getElementById('productosTableBody');
            productosTableBody.innerHTML = `<tr><td colspan="9" class="attendance__table-cell" style="color: red;">Error al cargar datos: ${error.message}</td></tr>`;
            updatePaginationControls();
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const clearFiltersBtn = document.getElementById('clearFilters');
        const prevPageBtn = document.getElementById('prevPage');
        const nextPageBtn = document.getElementById('nextPage');

        // CORRECCIÓN CLAVE: Renderizar datos iniciales al cargar la página
        renderProductosTable(initialData);
        updatePaginationControls();

        window.showDeleteModal = function(id_pro, nombre_producto) {
            if (confirm(`¿Está seguro de eliminar el producto: ${nombre_producto}?`)) {
                window.location.href = `../eliminar/eliminar_producto.php?id=${id_pro}`;
            }
        }

        // Event listeners
        searchInput.addEventListener('input', () => fetchProductos(1));
        clearFiltersBtn.addEventListener('click', () => {
            searchInput.value = '';
            fetchProductos(1);
        });
        prevPageBtn.addEventListener('click', () => {
            if (currentPage > 1) fetchProductos(currentPage - 1);
        });
        nextPageBtn.addEventListener('click', () => {
            if (currentPage < totalPages) fetchProductos(currentPage + 1);
        });
    });
</script>
</body>
</html>