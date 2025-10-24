<?php
session_start();
require_once '../conexion/conexion.php';

$notificaciones_cantidad = [];
$notificaciones_credito_vencido = [];
$notificaciones_credito_pendiente = [];
$notificaciones_proveedor_vencido = [];
$notificaciones_proveedor_pendiente = [];

$sql_cantidad = "SELECT nombre_producto, cantidad FROM producto WHERE estado_producto = '1' AND cantidad < 10 ORDER BY nombre_producto ASC";
$resultado_cantidad = $conn->query($sql_cantidad);
if ($resultado_cantidad) {
    while ($fila = $resultado_cantidad->fetch_assoc()) {
        $notificaciones_cantidad[] = $fila;
    }
}

$sql_creditos = "
    SELECT
        cv.id_credito_venta,
        v.id_ventas,
        cv.monto_total_credito,
        cv.monto_abonado,
        COALESCE(cv.saldo_pendiente, 0.00) AS saldo_pendiente,
        cv.fecha_vencimiento,
        cv.estado_credito,
        COALESCE(cm.nombre, cg.nombre, 'Cliente Desconocido') AS nombre_cliente,
        COALESCE(cm.apellido, cg.apellido_cliente_generico, '') AS apellido_cliente,
        DATEDIFF(cv.fecha_vencimiento, CURDATE()) AS dias_restantes
    FROM
        creditos_venta AS cv
    JOIN
        ventas AS v ON cv.id_ventas = v.id_ventas
    LEFT JOIN
        cliente_mayor AS cm ON v.id_cliente_mayor = cm.id_cliente_mayor
    LEFT JOIN
        cliente_generico AS cg ON v.id_cliente_generico = cg.id_cliente_generico
    WHERE
        cv.estado_credito IN ('Pendiente', 'Pagado Parcialmente')
    ORDER BY
        cv.fecha_vencimiento ASC;
";
$resultado_creditos = $conn->query($sql_creditos);
if ($resultado_creditos) {
    while ($fila = $resultado_creditos->fetch_assoc()) {
        if ($fila['dias_restantes'] < 0) {
            $notificaciones_credito_vencido[] = $fila;
        } else {
            $notificaciones_credito_pendiente[] = $fila;
        }
    }
}

$sql_proveedores = "
    SELECT
        cc.id_compra_credito,
        cc.monto_total_credito,
        cc.monto_abonado,
        (cc.monto_total_credito - cc.monto_abonado) AS saldo_pendiente,
        cc.fecha_vencimiento,
        cc.estado_credito,
        p.nombre_provedor,
        DATEDIFF(cc.fecha_vencimiento, CURDATE()) AS dias_restantes
    FROM
        compras_credito AS cc
    JOIN
        proveedor AS p ON cc.RIF_proveedor = p.RIF
    WHERE
        cc.estado_credito IN ('Pendiente', 'Pagado Parcialmente')
    ORDER BY
        cc.fecha_vencimiento ASC;
";
$resultado_proveedores = $conn->query($sql_proveedores);
if ($resultado_proveedores) {
    while ($fila = $resultado_proveedores->fetch_assoc()) {
        if ($fila['dias_restantes'] < 0) {
            $notificaciones_proveedor_vencido[] = $fila;
        } else {
            $notificaciones_proveedor_pendiente[] = $fila;
        }
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notificaciones del Sistema</title>
    <?php include "../assets/head_gerente.php"?>
    <link rel="stylesheet" href="../assets/css/lista_productos.css">
    <link rel="stylesheet" href="../assets/css/lista_empleados.css">
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f7f6; color: #333; }
        .container-notificaciones { max-width: 800px; margin: 20px auto; padding: 20px; background-color: #fff; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #eee; padding-bottom: 10px; }
        .seccion-notificaciones { margin-bottom: 30px; }
        .seccion-notificaciones h3 { font-size: 1.2em; border-left: 4px solid; padding-left: 10px; margin-bottom: 15px; display: flex; justify-content: space-between; align-items: center; }
        .seccion-notificaciones h3.cantidad { border-color: #ff9800; color: #e65100; }
        .seccion-notificaciones h3.vencidos { border-color: #f44336; color: #d32f2f; }
        .seccion-notificaciones h3.pendientes { border-color: #2196f3; color: #1565c0; }
        .seccion-notificaciones h3.proveedores { border-color: #4CAF50; color: #388E3C; }
        .lista-notificaciones { list-style: none; padding: 0; }
        .notificacion-item { padding: 15px; margin-bottom: 10px; border-radius: 6px; display: flex; align-items: center; justify-content: space-between; }
        .notificacion-cantidad { background-color: #fff3e0; border: 1px solid #ffb74d; }
        .notificacion-vencido { background-color: #ffebee; border: 1px solid #ef9a9a; }
        .notificacion-pendiente { background-color: #e3f2fd; border: 1px solid #90caf9; }
        .notificacion-proveedor { background-color: #e8f5e9; border: 1px solid #A5D6A7; }
        .notificacion-texto strong { font-size: 1.1em; }
        .notificacion-fecha { font-size: 0.9em; color: #777; }
        .badge { padding: 5px 10px; border-radius: 20px; color: #fff; font-weight: bold; font-size: 0.8em; }
        .badge.cantidad { background-color: #ff9800; }
        .badge.vencido { background-color: #f44336; }
        .badge.pendiente { background-color: #2196f3; }
        .badge.proveedor-vencido { background-color: #D32F2F; }
        .badge.proveedor-pendiente { background-color: #388E3C; }
        .sin-notificaciones { text-align: center; color: #777; padding: 20px; font-style: italic; }
        .btn-ver-mas { background-color: #007bff; color: white; border: none; padding: 8px 12px; border-radius: 5px; cursor: pointer; font-size: 0.9em; transition: background-color 0.3s; }
        .btn-ver-mas:hover { background-color: #0056b3; }
        
        .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.4); }
        .modal-content { background-color: #fefefe; margin: 10% auto; padding: 20px; border: 1px solid #888; width: 80%; border-radius: 8px; box-shadow: 0 5px 15px rgba(0,0,0,0.3); }
        .modal-header { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #ddd; padding-bottom: 10px; margin-bottom: 15px; }
        .close-btn { color: #aaa; font-size: 28px; font-weight: bold; }
        .close-btn:hover, .close-btn:focus { color: #000; text-decoration: none; cursor: pointer; }
        .modal-body { padding-top: 10px; }
        .modal-body ul { list-style-type: none; padding: 0; }
        .modal-body li { padding: 10px; border-bottom: 1px solid #eee; }
        .modal-body li:last-child { border-bottom: none; }
        .modal-footer { display: flex; justify-content: center; padding-top: 15px; border-top: 1px solid #ddd; }
        .pagination-btn { background-color: #f0f0f0; border: 1px solid #ccc; color: #333; padding: 8px 12px; cursor: pointer; margin: 0 5px; border-radius: 4px; }
        .pagination-btn:hover:not(.disabled) { background-color: #e0e0e0; }
        .pagination-btn.disabled { opacity: 0.5; cursor: not-allowed; }
        .search-container { margin-bottom: 15px; }
        .search-container input { width: 100%; padding: 8px; box-sizing: border-box; border-radius: 5px; border: 1px solid #ccc; }
    </style>
</head>
<body>
<?php include "../assets/lista_gerente.php"?>
<div class="container-notificaciones">
    <div class="header">
        <h2>Bandeja de Notificaciones</h2>
    </div>

    <div class="seccion-notificaciones">
        <h3 class="cantidad">
            <span>Productos con baja cantidad (<?php echo count($notificaciones_cantidad); ?>)</span>
            <button class="btn-ver-mas" data-type="cantidad">Ver más</button>
        </h3>
        <ul class="lista-notificaciones">
            <?php if (count($notificaciones_cantidad) > 0): ?>
                <?php foreach (array_slice($notificaciones_cantidad, 0, 3) as $notificacion): ?>
                    <li class="notificacion-item notificacion-cantidad">
                        <div class="notificacion-texto">
                            <strong><?php echo htmlspecialchars($notificacion['nombre_producto']); ?></strong>
                            <p>Cantidad actual: <?php echo htmlspecialchars($notificacion['cantidad']); ?></p>
                        </div>
                        <span class="badge cantidad">Alerta</span>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li class="sin-notificaciones">No hay notificaciones de este apartado.</li>
            <?php endif; ?>
        </ul>
    </div>

    <div class="seccion-notificaciones">
        <h3 class="proveedores">
            <span>Créditos de Proveedores Vencidos (<?php echo count($notificaciones_proveedor_vencido); ?>)</span>
            <button class="btn-ver-mas" data-type="proveedor_vencido">Ver más</button>
        </h3>
        <ul class="lista-notificaciones">
            <?php if (count($notificaciones_proveedor_vencido) > 0): ?>
                <?php foreach (array_slice($notificaciones_proveedor_vencido, 0, 3) as $notificacion): ?>
                    <li class="notificacion-item notificacion-proveedor">
                        <div class="notificacion-texto">
                            <strong>
                                <?php echo htmlspecialchars($notificacion['nombre_provedor']); ?>
                            </strong>
                            <p>Monto pendiente: $<?php echo htmlspecialchars(number_format($notificacion['saldo_pendiente'], 2)); ?> | Vencido hace: <?php echo abs($notificacion['dias_restantes']); ?> días</p>
                        </div>
                        <span class="badge proveedor-vencido">¡Vencido!</span>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li class="sin-notificaciones">No hay notificaciones de este apartado.</li>
            <?php endif; ?>
        </ul>
    </div>

    <div class="seccion-notificaciones">
        <h3 class="proveedores">
            <span>Créditos de Proveedores Pendientes (<?php echo count($notificaciones_proveedor_pendiente); ?>)</span>
            <button class="btn-ver-mas" data-type="proveedor_pendiente">Ver más</button>
        </h3>
        <ul class="lista-notificaciones">
            <?php if (count($notificaciones_proveedor_pendiente) > 0): ?>
                <?php foreach (array_slice($notificaciones_proveedor_pendiente, 0, 3) as $notificacion): ?>
                    <li class="notificacion-item notificacion-proveedor">
                        <div class="notificacion-texto">
                            <strong>
                                <?php echo htmlspecialchars($notificacion['nombre_provedor']); ?>
                            </strong>
                            <p>Monto pendiente: $<?php echo htmlspecialchars(number_format($notificacion['saldo_pendiente'], 2)); ?> | Fecha de vencimiento: <?php echo htmlspecialchars($notificacion['fecha_vencimiento']); ?></p>
                        </div>
                        <span class="badge proveedor-pendiente">Pendiente</span>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li class="sin-notificaciones">No hay notificaciones de este apartado.</li>
            <?php endif; ?>
        </ul>
    </div>

    <div class="seccion-notificaciones">
        <h3 class="vencidos">
            <span>Créditos de Clientes Vencidos (<?php echo count($notificaciones_credito_vencido); ?>)</span>
            <button class="btn-ver-mas" data-type="vencidos">Ver más</button>
        </h3>
        <ul class="lista-notificaciones">
            <?php if (count($notificaciones_credito_vencido) > 0): ?>
                <?php foreach (array_slice($notificaciones_credito_vencido, 0, 3) as $notificacion): ?>
                    <li class="notificacion-item notificacion-vencido">
                        <div class="notificacion-texto">
                            <strong>
                                <a title="Abono" href="abono.php?id_venta=<?php echo htmlspecialchars($notificacion['id_ventas']); ?>">
                                    <?php echo htmlspecialchars($notificacion['nombre_cliente'] . ' ' . $notificacion['apellido_cliente']); ?>
                                </a>
                            </strong>
                            <p>Monto total del crédito: $<?php echo htmlspecialchars(number_format($notificacion['monto_total_credito'], 2)); ?> | Vencido hace: <?php echo abs($notificacion['dias_restantes']); ?> días</p>
                        </div>
                        <span class="badge vencido">¡Vencido!</span>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li class="sin-notificaciones">No hay notificaciones de este apartado.</li>
            <?php endif; ?>
        </ul>
    </div>

    <div class="seccion-notificaciones">
        <h3 class="pendientes">
            <span>Créditos de Clientes Pendientes (<?php echo count($notificaciones_credito_pendiente); ?>)</span>
            <button class="btn-ver-mas" data-type="pendientes">Ver más</button>
        </h3>
        <ul class="lista-notificaciones">
            <?php if (count($notificaciones_credito_pendiente) > 0): ?>
                <?php foreach (array_slice($notificaciones_credito_pendiente, 0, 3) as $notificacion): ?>
                    <li class="notificacion-item notificacion-pendiente">
                        <div class="notificacion-texto">
                            <strong>
                                <a href="abono.php?id_venta=<?php echo htmlspecialchars($notificacion['id_ventas']); ?>">
                                    <?php echo htmlspecialchars($notificacion['nombre_cliente'] . ' ' . $notificacion['apellido_cliente']); ?>
                                </a>
                            </strong>
                            <p>Monto total del crédito: $<?php echo htmlspecialchars(number_format($notificacion['monto_total_credito'], 2)); ?> | Fecha de vencimiento: <?php echo htmlspecialchars($notificacion['fecha_vencimiento']); ?></p>
                        </div>
                        <span class="badge pendiente">Pendiente</span>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li class="sin-notificaciones">No hay notificaciones de este apartado.</li>
            <?php endif; ?>
        </ul>
    </div>
</div>

<div id="myModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="modal-title"></h2>
            <span class="close-btn">&times;</span>
        </div>
        <div class="modal-body">
            <div class="search-container">
                <input type="text" id="searchInput" placeholder="Buscar...">
            </div>
            <ul id="modal-list"></ul>
        </div>
        <div class="modal-footer">
            <button id="prev-btn" class="pagination-btn disabled">Anterior</button>
            <span id="page-info">Página 1 de 1</span>
            <button id="next-btn" class="pagination-btn disabled">Siguiente</button>
        </div>
    </div>
</div>

<script>
    const productosBajaCantidad = <?php echo json_encode($notificaciones_cantidad); ?>;
    const creditosVencidos = <?php echo json_encode($notificaciones_credito_vencido); ?>;
    const creditosPendientes = <?php echo json_encode($notificaciones_credito_pendiente); ?>;
    const proveedoresVencidos = <?php echo json_encode($notificaciones_proveedor_vencido); ?>;
    const proveedoresPendientes = <?php echo json_encode($notificaciones_proveedor_pendiente); ?>;

    const itemsPerPage = 5;
    let currentPage = 1;
    let currentData = [];
    let currentDataType = "";
    let filteredData = [];

    const modal = document.getElementById("myModal");
    const modalTitle = document.getElementById("modal-title");
    const modalList = document.getElementById("modal-list");
    const prevBtn = document.getElementById("prev-btn");
    const nextBtn = document.getElementById("next-btn");
    const pageInfo = document.getElementById("page-info");
    const closeBtn = document.querySelector(".close-btn");
    const searchInput = document.getElementById("searchInput");

    document.querySelectorAll(".btn-ver-mas").forEach(button => {
        button.addEventListener("click", () => {
            const dataType = button.getAttribute("data-type");
            modal.style.display = "block";
            currentPage = 1;
            searchInput.value = "";

            if (dataType === "cantidad") {
                modalTitle.textContent = "Productos con baja cantidad";
                currentData = productosBajaCantidad;
                currentDataType = "cantidad";
            } else if (dataType === "vencidos") {
                modalTitle.textContent = "Créditos de Clientes Vencidos";
                currentData = creditosVencidos;
                currentDataType = "creditos_clientes";
            } else if (dataType === "pendientes") {
                modalTitle.textContent = "Créditos de Clientes Pendientes";
                currentData = creditosPendientes;
                currentDataType = "creditos_clientes";
            } else if (dataType === "proveedor_vencido") {
                modalTitle.textContent = "Créditos de Proveedores Vencidos";
                currentData = proveedoresVencidos;
                currentDataType = "creditos_proveedores";
            } else if (dataType === "proveedor_pendiente") {
                modalTitle.textContent = "Créditos de Proveedores Pendientes";
                currentData = proveedoresPendientes;
                currentDataType = "creditos_proveedores";
            }
            filterData();
        });
    });

    searchInput.addEventListener("input", filterData);

    function filterData() {
        const searchTerm = searchInput.value.toLowerCase();
        
        if (currentDataType === "cantidad") {
            filteredData = currentData.filter(item => 
                item.nombre_producto.toLowerCase().includes(searchTerm) ||
                item.cantidad.toString().includes(searchTerm)
            );
        } else if (currentDataType === "creditos_clientes") {
            filteredData = currentData.filter(item => {
                const nombreCompleto = `${(item.nombre_cliente || '')} ${(item.apellido_cliente || '')}`;
                return nombreCompleto.toLowerCase().includes(searchTerm) ||
                       item.monto_total_credito.toString().includes(searchTerm);
            });
        } else if (currentDataType === "creditos_proveedores") {
            filteredData = currentData.filter(item =>
                item.nombre_provedor.toLowerCase().includes(searchTerm) ||
                item.monto_total_credito.toString().includes(searchTerm)
            );
        }

        currentPage = 1;
        displayData();
    }

    function displayData() {
        modalList.innerHTML = "";
        const startIndex = (currentPage - 1) * itemsPerPage;
        const endIndex = startIndex + itemsPerPage;
        const paginatedItems = filteredData.slice(startIndex, endIndex);

        if (paginatedItems.length > 0) {
            paginatedItems.forEach(item => {
                const li = document.createElement("li");
                let content = "";
                if (currentDataType === "cantidad") {
                    content = `<strong>${item.nombre_producto}</strong><br>Cantidad actual: ${item.cantidad}`;
                } else if (currentDataType === "creditos_clientes") {
                    const nombreCompleto = (item.nombre_cliente || 'Cliente Desconocido') + ' ' + (item.apellido_cliente || '');
                    const monto = parseFloat(item.monto_total_credito).toFixed(2);
                    const estado = item.dias_restantes < 0 ? `Vencido hace: ${Math.abs(item.dias_restantes)} días` : `Fecha de vencimiento: ${item.fecha_vencimiento}`;
                    content = `<strong>
                                <a href="abono.php?id_venta=${item.id_ventas}">
                                    ${nombreCompleto.trim()}
                                </a>
                                </strong><br>Monto total del crédito: $${monto} | ${estado}`;
                } else if (currentDataType === "creditos_proveedores") {
                    const nombreProveedor = item.nombre_provedor || 'Proveedor Desconocido';
                    const monto = parseFloat(item.monto_total_credito).toFixed(2);
                    const estado = item.dias_restantes < 0 ? `Vencido hace: ${Math.abs(item.dias_restantes)} días` : `Fecha de vencimiento: ${item.fecha_vencimiento}`;
                    content = `<strong>${nombreProveedor}</strong><br>Monto pendiente: $${monto} | ${estado}`;
                }
                li.innerHTML = content;
                modalList.appendChild(li);
            });
        } else {
            const li = document.createElement("li");
            li.className = "sin-notificaciones";
            li.textContent = "No hay resultados.";
            modalList.appendChild(li);
        }
        updatePaginationButtons();
    }

    function updatePaginationButtons() {
        const totalPages = Math.ceil(filteredData.length / itemsPerPage);
        pageInfo.textContent = `Página ${currentPage} de ${totalPages || 1}`;

        prevBtn.classList.toggle("disabled", currentPage === 1);
        nextBtn.classList.toggle("disabled", currentPage === totalPages || totalPages === 0);
    }

    prevBtn.addEventListener("click", () => {
        if (currentPage > 1) {
            currentPage--;
            displayData();
        }
    });

    nextBtn.addEventListener("click", () => {
        const totalPages = Math.ceil(filteredData.length / itemsPerPage);
        if (currentPage < totalPages) {
            currentPage++;
            displayData();
        }
    });

    closeBtn.addEventListener("click", () => {
        modal.style.display = "none";
    });
    window.addEventListener("click", (event) => {
        if (event.target === modal) {
            modal.style.display = "none";
        }
    });
</script>

</body>
</html>