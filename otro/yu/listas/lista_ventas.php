<?php
session_start();
require_once('../conexion/conexion.php');

if (!isset($_SESSION['id_usuario']) || !isset($_SESSION['id_tipo_usuario']) || $_SESSION['id_tipo_usuario'] != 1) {
    header('Location: ../ingreso.php');
    exit();
}

if (!isset($conn) || $conn->connect_error) {
    die("Error crítico: No se pudo establecer la conexión a la base de datos. Por favor, contacte al administrador.");
}

$nombre_usuario_actual = $_SESSION['nombre_usuario'] ?? 'Usuario';
$alertas = ['success' => [], 'error' => []];

$conn->set_charset("utf8");

$items_per_page = 10;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($current_page < 1) $current_page = 1;
$offset = ($current_page - 1) * $items_per_page;

$search_term = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : '';
$fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : '';


$sql_base = "SELECT
            v.id_ventas AS ID_Venta,
            v.fecha_venta AS Fecha_Venta,
            CASE
                WHEN v.id_cliente_generico IS NOT NULL THEN CONCAT(cg.nombre, ' ', COALESCE(cg.apellido_cliente_generico, ''))
                WHEN v.id_cliente_mayor IS NOT NULL THEN CONCAT(cm.nombre, COALESCE(CONCAT(' ', cm.apellido), ''))
                ELSE 'Cliente General'
            END AS Nombre_Cliente,
            u.nombre_usuario AS Usuario,
            v.subtotal_venta AS Subtotal,
            v.total_iva_venta AS Total_IVA,
            v.total_neto_venta AS Total_Neto,
            v.estado_venta AS Estado_Venta
        FROM
            ventas v
        LEFT JOIN cliente_generico cg ON v.id_cliente_generico = cg.id_cliente_generico
        LEFT JOIN cliente_mayor cm ON v.id_cliente_mayor = cm.id_cliente_mayor
        JOIN usuario u ON v.id_usuario_registro = u.id_usuario";


$where_clauses = [];
$params = [];
$param_types = "";

if (!empty($search_term)) {
    $where_clauses[] = "(v.id_ventas LIKE ? OR CONCAT(cg.nombre, ' ', COALESCE(cg.apellido_cliente_generico, '')) LIKE ? OR CONCAT(cm.nombre, COALESCE(CONCAT(' ', cm.apellido), '')) LIKE ? OR u.nombre_usuario LIKE ? OR v.estado_venta LIKE ?)";
    $search_param = '%' . $search_term . '%';
    $params = array_merge($params, [$search_param, $search_param, $search_param, $search_param, $search_param]);
    $param_types .= "sssss";
}

if (!empty($fecha_inicio) && !empty($fecha_fin)) {
    $where_clauses[] = "v.fecha_venta BETWEEN ? AND ?";
    $params = array_merge($params, [$fecha_inicio . ' 00:00:00', $fecha_fin . ' 23:59:59']);
    $param_types .= "ss";
} elseif (!empty($fecha_inicio)) {
    $where_clauses[] = "v.fecha_venta >= ?";
    $params = array_merge($params, [$fecha_inicio . ' 00:00:00']);
    $param_types .= "s";
} elseif (!empty($fecha_fin)) {
    $where_clauses[] = "v.fecha_venta <= ?";
    $params = array_merge($params, [$fecha_fin . ' 23:59:59']);
    $param_types .= "s";
}

$sql_where = '';
if (!empty($where_clauses)) {
    $sql_where = " WHERE " . implode(" AND ", $where_clauses);
}


$sql_count = "SELECT COUNT(v.id_ventas) AS total_records FROM ventas v
              LEFT JOIN cliente_generico cg ON v.id_cliente_generico = cg.id_cliente_generico
              LEFT JOIN cliente_mayor cm ON v.id_cliente_mayor = cm.id_cliente_mayor
              JOIN usuario u ON v.id_usuario_registro = u.id_usuario" . $sql_where;

$stmt_count = $conn->prepare($sql_count);
if ($stmt_count && !empty($params)) {
    $stmt_count->bind_param($param_types, ...$params);
}
$stmt_count->execute();
$result_count = $stmt_count->get_result()->fetch_assoc();
$total_records = $result_count['total_records'];
$total_pages = ceil($total_records / $items_per_page);
$stmt_count->close();

$sql_data = $sql_base . $sql_where . " ORDER BY v.fecha_venta DESC LIMIT ? OFFSET ?";
$stmt_data = $conn->prepare($sql_data);

$params[] = $items_per_page;
$params[] = $offset;
$param_types .= "ii";

if ($stmt_data) {
    $stmt_data->bind_param($param_types, ...$params);
    $stmt_data->execute();
    $result = $stmt_data->get_result();
} else {
    $result = false;
    $alertas['error'][] = "Error al preparar la consulta de ventas: " . $conn->error;
}


if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest' || isset($_GET['ajax'])) {
    $sales_data = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $row['Fecha_Venta_Formatted'] = date("d/m/Y H:i:s", strtotime($row["Fecha_Venta"]));
            $row['Subtotal_Formatted'] = number_format($row["Subtotal"], 2, ',', '.');
            $row['Total_IVA_Formatted'] = number_format($row["Total_IVA"], 2, ',', '.');
            $row['Total_Neto_Formatted'] = number_format($row["Total_Neto"], 2, ',', '.');
            $sales_data[] = $row;
        }
    }

    echo json_encode([
        'sales' => $sales_data,
        'total_pages' => $total_pages,
        'current_page' => $current_page,
        'total_records' => $total_records
    ]);
    $stmt_data->close();
    $conn->close();
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Ventas</title>
    <link rel="stylesheet" href="../assets/css/cajero.css">
    
    <?php include "../assets/head_gerente.php"?>
    <style>
        body {
            font-family: sans-serif;
            margin: 0; 
            padding: 20px; 
            min-height: 100vh;
            background-color: #f4f6f8;
            box-sizing: border-box;
        }
        h1 {
            text-align: center;
            color: #333;
            margin-top: 0; 
            margin-bottom: 25px;
        }
        table {
            width: 100%; 
            border-collapse: collapse;
            margin-top: 20px; 
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1); 
            border-radius: 8px; 
            overflow: hidden; 
        }
        th, td {
            color: #000000;
            border: 1px solid #dee2e6;
            padding: 12px 15px; 
            text-align: left;
        }
        th {
            background-color: #c8d0d8;
            color: rgb(0, 0, 0);
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .actions a {
            color: #007bff;
            text-decoration: none;
            margin-right: 10px;
        }
        .actions a:hover {
            text-decoration: underline;
        }
        .no-sales {
            text-align: center;
            padding: 20px;
            margin-top: 20px;
            color: #555;
        }
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
            min-width: 180px;
        }
        .filters-container label {
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
        }
        .pagination-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 20px;
            gap: 5px;
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
        }
    </style>
</head>
<body>
<?php include "../assets/lista_gerente.php"?>
<div class="sales-container">
    <h1>Historial de Ventas</h1>

    <div class="filters-container">
        <div class="form-group">
            <label for="searchInput">Buscar por ID, Cliente, Cajero o Estado:</label>
            <input type="text" id="searchInput" class="form-control" placeholder="Escribe para buscar..." value="<?php echo htmlspecialchars($search_term); ?>">
        </div>
        <div class="form-group">
            <label for="fechaInicio">Fecha Desde:</label>
            <input type="date" id="fechaInicio" class="form-control" value="<?php echo htmlspecialchars($fecha_inicio); ?>">
        </div>
        <div class="form-group">
            <label for="fechaFin">Fecha Hasta:</label>
            <input type="date" id="fechaFin" class="form-control" value="<?php echo htmlspecialchars($fecha_fin); ?>">
        </div>
        <div class="form-group">
            <button id="clearFilters" class="btn btn-secondary">Limpiar Filtros</button>
        </div>
    </div>

    <div id="salesTableContainer">
        <?php if ($result && $result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID Venta</th>
                        <th>Fecha</th>
                        <th>Cliente</th>
                        <th>Cajero</th>
                        <th>Subtotal</th>
                        <th>IVA</th>
                        <th>Total Neto</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="salesTableBody">
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row["ID_Venta"]); ?></td>
                            <td><?php echo htmlspecialchars(date("d/m/Y H:i:s", strtotime($row["Fecha_Venta"]))); ?></td>
                            <td><?php echo htmlspecialchars($row["Nombre_Cliente"]); ?></td>
                            <td><?php echo htmlspecialchars($row["Usuario"]); ?></td>
                            <td><?php echo htmlspecialchars(number_format($row["Subtotal"], 2, ',', '.')); ?></td>
                            <td><?php echo htmlspecialchars(number_format($row["Total_IVA"], 2, ',', '.')); ?></td>
                            <td><?php echo htmlspecialchars(number_format($row["Total_Neto"], 2, ',', '.')); ?></td>
                            <td><?php echo htmlspecialchars($row["Estado_Venta"]); ?></td>
                            <td class="actions">
                                <a href="detalle_venta.php?id_venta=<?php echo htmlspecialchars($row["ID_Venta"]); ?>" title="Ver detalles de esta venta">Ver Detalles</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php elseif ($result): ?>
            <p class="no-sales" id="noSalesMessage">No se encontraron ventas registradas.</p>
        <?php else: ?>
            <p class="no-sales" id="errorMessage">Error al ejecutar la consulta: <?php echo htmlspecialchars($conn->error); ?></p>
        <?php endif; ?>
    </div>

    <div class="pagination-container" id="paginationContainer">
        <button id="prevPage">Anterior</button>
        <span id="pageInfo">Página 1 de 1</span>
        <button id="nextPage">Siguiente</button>
    </div>
</div>

<?php
if (isset($stmt_data) && !$result) {
    $stmt_data->close();
} elseif (isset($stmt_data) && $result) {
    $stmt_data->close();
}
$conn->close();
?>

<script>
        document.addEventListener("DOMContentLoaded", function() {
        const inputFecha = document.getElementById("fechaInicio");
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
        const inputFecha = document.getElementById("fechaFin");
        const hoy = new Date();
        const anio = hoy.getFullYear();
        let mes = hoy.getMonth() + 1;
        let dia = hoy.getDate();
        mes = mes < 10 ? '0' + mes : mes;
        dia = dia < 10 ? '0' + dia : dia;
        const fechaMaxima = `${anio}-${mes}-${dia}`;
        inputFecha.max = fechaMaxima;
    }); 
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const fechaInicioInput = document.getElementById('fechaInicio');
        const fechaFinInput = document.getElementById('fechaFin');
        const clearFiltersBtn = document.getElementById('clearFilters');
        const salesTableBody = document.getElementById('salesTableBody');
        const noSalesMessage = document.getElementById('noSalesMessage');
        const errorMessage = document.getElementById('errorMessage');
        const salesTableContainer = document.getElementById('salesTableContainer');
        const prevPageBtn = document.getElementById('prevPage');
        const nextPageBtn = document.getElementById('nextPage');
        const pageInfoSpan = document.getElementById('pageInfo');

        let currentPage = <?php echo $current_page; ?>;
        let totalPages = <?php echo $total_pages; ?>;

        function toggleMessages(showNoSales, showErrors, showTable) {
            if (noSalesMessage) noSalesMessage.style.display = showNoSales ? 'block' : 'none';
            if (errorMessage) errorMessage.style.display = showErrors ? 'block' : 'none';
            if (salesTableBody && salesTableBody.closest('table')) {
                salesTableBody.closest('table').style.display = showTable ? 'table' : 'none';
            }
        }

        function renderSalesTable(sales) {
            salesTableBody.innerHTML = '';
            if (sales.length > 0) {
                sales.forEach(sale => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${sale.ID_Venta}</td>
                        <td>${sale.Fecha_Venta_Formatted}</td>
                        <td>${sale.Nombre_Cliente}</td>
                        <td>${sale.Usuario}</td>
                        <td>${sale.Subtotal_Formatted}</td>
                        <td>${sale.Total_IVA_Formatted}</td>
                        <td>${sale.Total_Neto_Formatted}</td>
                        <td>${sale.Estado_Venta}</td>
                        <td class="actions">
                            <a href="detalle_venta.php?id_venta=${sale.ID_Venta}" title="Ver detalles de esta venta">Ver Detalles</a>
                        </td>
                    `;
                    salesTableBody.appendChild(row);
                });
                toggleMessages(false, false, true);
            } else {
                toggleMessages(true, false, false);
            }
        }

        function updatePaginationControls() {
            pageInfoSpan.textContent = `Página ${currentPage} de ${totalPages}`;
            prevPageBtn.disabled = (currentPage === 1);
            nextPageBtn.disabled = (currentPage === totalPages || totalPages === 0);
        }

        async function fetchSales(page = 1) {
            const searchTerm = searchInput.value;
            const fechaInicio = fechaInicioInput.value;
            const fechaFin = fechaFinInput.value;

            const params = new URLSearchParams();
            params.append('ajax', '1');
            params.append('page', page);
            if (searchTerm) params.append('search', searchTerm);
            if (fechaInicio) params.append('fecha_inicio', fechaInicio);
            if (fechaFin) params.append('fecha_fin', fechaFin);

            try {
                const response = await fetch(`lista_ventas.php?${params.toString()}`);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                const data = await response.json();
                
                currentPage = data.current_page;
                totalPages = data.total_pages;

                renderSalesTable(data.sales);
                updatePaginationControls();

            } catch (error) {
                console.error('Error al cargar las ventas:', error);
                toggleMessages(false, true, false);
                if (errorMessage) errorMessage.textContent = `Error al cargar las ventas: ${error.message}`;
            }
        }

        searchInput.addEventListener('input', () => fetchSales(1));
        fechaInicioInput.addEventListener('change', () => fetchSales(1));
        fechaFinInput.addEventListener('change', () => fetchSales(1));

        clearFiltersBtn.addEventListener('click', () => {
            searchInput.value = '';
            fechaInicioInput.value = '';
            fechaFinInput.value = '';
            fetchSales(1);
        });

        prevPageBtn.addEventListener('click', () => {
            if (currentPage > 1) {
                fetchSales(currentPage - 1);
            }
        });

        nextPageBtn.addEventListener('click', () => {
            if (currentPage < totalPages) {
                fetchSales(currentPage + 1);
            }
        });

        fetchSales(currentPage);
    });
</script>
</body>
</html>
