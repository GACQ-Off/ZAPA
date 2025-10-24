<?php
session_start();
require_once __DIR__ . '/conexion/conexion.php';

if (!isset($_SESSION['id_usuario']) || $_SESSION['id_usuario'] !== '1') {
    header("Location: ingreso.php");
    exit();
}

$mostrar_modal_bienvenida = false;
if (!isset($_SESSION['modal_mostrada'])) {
    $mostrar_modal_bienvenida = true;
    $_SESSION['modal_mostrada'] = true;
}

if (!isset($conn) || $conn->connect_error) {
    die("Error de conexión: " . ($conn->connect_error ?? "Error desconocido."));
}

$notificacion_producto = null;
$notificacion_vencida = null;
$notificacion_pendiente = null;
$notificacion_proveedor_vencida = null;
$notificacion_proveedor_pendiente = null;

// Lógica de conteo de notificaciones
$sql_cantidad = "SELECT COUNT(*) AS total FROM producto WHERE estado_producto = '1' AND cantidad < 10";
$resultado_cantidad = $conn->query($sql_cantidad);
$total_cantidad = $resultado_cantidad ? $resultado_cantidad->fetch_assoc()['total'] : 0;

$sql_creditos = "
    SELECT
        cv.id_credito_venta,
        cv.fecha_vencimiento
    FROM
        creditos_venta AS cv
    WHERE
        cv.estado_credito IN ('Pendiente', 'Pagado Parcialmente')
";
$resultado_creditos = $conn->query($sql_creditos);

$total_creditos_vencidos = 0;
$total_creditos_pendientes = 0;
if ($resultado_creditos) {
    while ($fila = $resultado_creditos->fetch_assoc()) {
        if (strtotime($fila['fecha_vencimiento']) < strtotime(date('Y-m-d'))) {
            $total_creditos_vencidos++;
        } else {
            $total_creditos_pendientes++;
        }
    }
}

$sql_proveedores = "
    SELECT
        cc.id_compra_credito,
        cc.fecha_vencimiento
    FROM
        compras_credito AS cc
    WHERE
        cc.estado_credito IN ('Pendiente', 'Pagado Parcialmente')
";
$resultado_proveedores = $conn->query($sql_proveedores);

$total_proveedores_vencidos = 0;
$total_proveedores_pendientes = 0;
if ($resultado_proveedores) {
    while ($fila = $resultado_proveedores->fetch_assoc()) {
        if (strtotime($fila['fecha_vencimiento']) < strtotime(date('Y-m-d'))) {
            $total_proveedores_vencidos++;
        } else {
            $total_proveedores_pendientes++;
        }
    }
}

$sql_primera_cantidad = "SELECT nombre_producto, cantidad FROM producto WHERE estado_producto = '1' AND cantidad < 10 ORDER BY nombre_producto ASC LIMIT 1";
$res_primera_cantidad = $conn->query($sql_primera_cantidad);
if ($res_primera_cantidad && $res_primera_cantidad->num_rows > 0) {
    $notificacion_producto = $res_primera_cantidad->fetch_assoc();
}

$sql_primera_vencida = "
    SELECT
        COALESCE(cm.nombre, cg.nombre, 'Cliente Desconocido') AS nombre_cliente,
        COALESCE(cm.apellido, cg.apellido_cliente_generico, '') AS apellido_cliente
    FROM
        creditos_venta AS cv
    JOIN
        ventas AS v ON cv.id_ventas = v.id_ventas
    LEFT JOIN
        cliente_mayor AS cm ON v.id_cliente_mayor = cm.id_cliente_mayor
    LEFT JOIN
        cliente_generico AS cg ON v.id_cliente_generico = cg.id_cliente_generico
    WHERE
        cv.estado_credito IN ('Pendiente', 'Pagado Parcialmente') AND DATEDIFF(cv.fecha_vencimiento, CURDATE()) < 0
    ORDER BY
        cv.fecha_vencimiento ASC
    LIMIT 1;
";
$res_primera_vencida = $conn->query($sql_primera_vencida);
if ($res_primera_vencida && $res_primera_vencida->num_rows > 0) {
    $notificacion_vencida = $res_primera_vencida->fetch_assoc();
}

$sql_primera_pendiente = "
    SELECT
        COALESCE(cm.nombre, cg.nombre, 'Cliente Desconocido') AS nombre_cliente,
        COALESCE(cm.apellido, cg.apellido_cliente_generico, '') AS apellido_cliente
    FROM
        creditos_venta AS cv
    JOIN
        ventas AS v ON cv.id_ventas = v.id_ventas
    LEFT JOIN
        cliente_mayor AS cm ON v.id_cliente_mayor = cm.id_cliente_mayor
    LEFT JOIN
        cliente_generico AS cg ON v.id_cliente_generico = cg.id_cliente_generico
    WHERE
        cv.estado_credito IN ('Pendiente', 'Pagado Parcialmente') AND DATEDIFF(cv.fecha_vencimiento, CURDATE()) >= 0
    ORDER BY
        cv.fecha_vencimiento ASC
    LIMIT 1;
";
$res_primera_pendiente = $conn->query($sql_primera_pendiente);
if ($res_primera_pendiente && $res_primera_pendiente->num_rows > 0) {
    $notificacion_pendiente = $res_primera_pendiente->fetch_assoc();
}

$sql_primera_proveedor_vencido = "
    SELECT
        p.nombre_provedor
    FROM
        compras_credito AS cc
    JOIN
        proveedor AS p ON cc.RIF_proveedor = p.RIF
    WHERE
        cc.estado_credito IN ('Pendiente', 'Pagado Parcialmente') AND DATEDIFF(cc.fecha_vencimiento, CURDATE()) < 0
    ORDER BY
        cc.fecha_vencimiento ASC
    LIMIT 1;
";
$res_primera_proveedor_vencido = $conn->query($sql_primera_proveedor_vencido);
if ($res_primera_proveedor_vencido && $res_primera_proveedor_vencido->num_rows > 0) {
    $notificacion_proveedor_vencida = $res_primera_proveedor_vencido->fetch_assoc();
}

$sql_primera_proveedor_pendiente = "
    SELECT
        p.nombre_provedor
    FROM
        compras_credito AS cc
    JOIN
        proveedor AS p ON cc.RIF_proveedor = p.RIF
    WHERE
        cc.estado_credito IN ('Pendiente', 'Pagado Parcialmente') AND DATEDIFF(cc.fecha_vencimiento, CURDATE()) >= 0
    ORDER BY
        cc.fecha_vencimiento ASC
    LIMIT 1;
";
$res_primera_proveedor_pendiente = $conn->query($sql_primera_proveedor_pendiente);
if ($res_primera_proveedor_pendiente && $res_primera_proveedor_pendiente->num_rows > 0) {
    $notificacion_proveedor_pendiente = $res_primera_proveedor_pendiente->fetch_assoc();
}

$contador_notificaciones = $total_cantidad + $total_creditos_vencidos + $total_creditos_pendientes + $total_proveedores_vencidos + $total_proveedores_pendientes;

$capitalGlobal = 0.00;
$sqlCapital = "SELECT fondo FROM fondo WHERE id_fondo = 1";
$resultCapital = $conn->query($sqlCapital);
if ($resultCapital && $resultCapital->num_rows > 0) {
    $rowCapital = $resultCapital->fetch_assoc();
    $capitalGlobal = floatval($rowCapital['fondo']);
}

$tasaId = 'N/A';
$tasaValor = null;
$tasaFecha = null;
$tasaFechaFormateada = 'N/A';

$sqlTasa = "SELECT id_tasa_dolar, tasa_dolar, fecha_dolar FROM tasa_dolar ORDER BY id_tasa_dolar DESC LIMIT 1";
$resultTasa = $conn->query($sqlTasa);

if ($resultTasa && $resultTasa->num_rows > 0) {
    $rowTasa = $resultTasa->fetch_assoc();
    $tasaId = $rowTasa['id_tasa_dolar'];
    $tasaValor = floatval($rowTasa['tasa_dolar']);
    $tasaFecha = $rowTasa['fecha_dolar'];
    if ($tasaFecha) {
        $dateObj = date_create($tasaFecha);
        if ($dateObj) {
            $tasaFechaFormateada = date_format($dateObj, 'd/m/Y');
        } else {
            $tasaFechaFormateada = 'Fecha inválida';
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
    <title>Opciones de gerente</title>
    <link rel="stylesheet" href="assets/css/menu.css">
    <link rel="stylesheet" href="assets/fonts/google-icons/index.css">
    <link rel="icon" href="assets/images/logo_icon.png">
    <?php
    if ($mostrar_modal_bienvenida) {
        require_once __DIR__ . '/modales/bienvenida.php';
    }
    ?>
    <style>
        .notifications-widget {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            max-height: 400px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            font-family: Arial, sans-serif;
        }

        .notifications-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 10px;
            border-bottom: 1px solid #ddd;
            margin-bottom: 10px;
        }

        .notifications-header h4 {
            margin: 0;
            color: #333;
        }

        .notifications-list {
            list-style: none;
            padding: 0;
            margin: 0;
            flex-grow: 1;
        }

        .notifications-list li {
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }

        .notifications-list li:last-child {
            border-bottom: none;
        }

        .notification-item {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #555;
            text-decoration: none;
        }

        .notification-icon {
            font-size: 20px;
        }
        
        .notificacion-producto .notification-icon { color: #ff9800; }
        .notificacion-vencida .notification-icon { color: #f44336; }
        .notificacion-pendiente .notification-icon { color: #2196f3; }
        .notificacion-proveedor-vencida .notification-icon { color: #d32f2f; }
        .notificacion-proveedor-pendiente .notification-icon { color: #388E3C; }

        .notification-message {
            flex-grow: 1;
        }

        .notification-message strong {
            display: block;
            font-size: 14px;
        }

        .notification-message span {
            font-size: 12px;
            color: #888;
        }

        .notifications-footer {
            padding-top: 10px;
            border-top: 1px solid #ddd;
            margin-top: 10px;
            text-align: right;
        }

        .notifications-footer a {
            color: #007BFF;
            text-decoration: none;
            font-size: 12px;
            margin-left: 10px;
        }

        .notifications-link {
            position: relative;
            display: inline-block;
        }

        .notification-badge {
            position: absolute;
            top: 5px;
            right: 5px;
            background-color: #d32f2f;
            color: white;
            font-size: 10px;
            font-weight: bold;
            border-radius: 50%;
            padding: 2px 5px;
            min-width: 12px;
            text-align: center;
            line-height: 1;
            z-index: 10;
        }
    </style>
</head>
<body>
    <div class="top-bar">
        <div class="top-bar-left">
            <img src="assets/images/vertex.png" alt="Logo Empresa" id="logo">
            <button id="openBtn" onclick="openNav()">&#9776;</button>
        </div>
        <div class="manager-menu-container">
            <button id="managerBtn"><span class="material-symbols-outlined ico-account_circle"></span>Gerente &#9662;</button>
            <div id="managerDropdown" class="manager-dropdown-content">
                <a href="funciones/mi_empresa.php">Mi Empresa</a>
                <a href="logout.php">Cerrar Sesión</a>
            </div>
            <a href="funciones/notificaciones.php" class="notifications-link">
                <button id="managerBtn" class="notifications-btn">
                    <span class="material-symbols-outlined ico-notifications"></span>
                    <?php if ($contador_notificaciones > 0): ?>
                        <span class="notification-badge">
                            <?php echo $contador_notificaciones; ?>
                        </span>
                    <?php endif; ?>
                </button>
            </a>
        </div>
    </div>

    <div id="sideMenu" class="sidenav">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
        <a href="#"><span class="material-symbols-outlined ico-home"></span>Inicio</a>
        <li>
            <li>
                <a href="javascript:void(0);" class="dropdown-toggle"><span class="material-symbols-outlined ico-store"></span>Ventas<span class="arrow">&#9662;</span></a>
                <ul class="dropdown-menu">
                <li><a href="listas/lista_ventas.php">Ventas</a></li>
                <li><a href="funciones/venta_gerente.php">Generar ventas</a></li>
                <li><a href="funciones/cierre_caja.php">Cierre de caja</a></li>
                <li><a href="funciones/credito_ventas.php">Creditos</a></li>
                </ul>
            </li>
        </li>
        <li>
            <a href="javascript:void(0);" class="dropdown-toggle"><span class="material-symbols-outlined ico-receipt_long"></span>Nómina <span class="arrow">&#9662;</span></a>
            <ul class="dropdown-menu">
                <li><a href="listas/lista_cargos.php">Cargos</a></li>
                <li><a href="listas/lista_empleado.php">Empleado</a></li>
            </ul>
        </li>
        <li>
            <a href="javascript:void(0);" class="dropdown-toggle"><span class="material-symbols-outlined ico-shopping_basket"></span>Compras<span class="arrow">&#9662;</span></a>
            <ul class="dropdown-menu">
                <li><a href="listas/lista_provedor.php">Proveedor</a></li>
                <li><a href="listas/lista_compras.php">Historial de Compras</a></li>
                <li><a href="registrar/abono_credito.php">Abonar a Credito</a></li>
            </ul>
        </li>
        <li>
            <a href="javascript:void(0);" class="dropdown-toggle"><span class="material-symbols-outlined ico-inventory"></span>Inventario <span class="arrow">&#9662;</span></a>
            <ul class="dropdown-menu">
                <li><a href="registrar/categoria_pro.php">Categoría</a></li>
                <li><a href="listas/lista_productos.php">Productos</a></li>
                <li><a href="listas/lista_perdida.php">Pérdidas</a></li>
            </ul>
        </li>
        <li>
            <a href="javascript:void(0);" class="dropdown-toggle"><span class="material-symbols-outlined ico-checkbook"></span>Gastos <span class="arrow">&#9662;</span></a>
            <ul class="dropdown-menu">
                <li><a href="listas/lista_categoria_gasto.php">Categoría</a></li>
                <li><a href="listas/lista_gastos.php">Lista de Gastos</a></li>
            </ul>
        </li>
        <li>
            <a href="javascript:void(0);" class="dropdown-toggle"><span class="material-symbols-outlined ico-balance"></span>Balance <span class="arrow">&#9662;</span></a>
            <ul class="dropdown-menu">
                <li><a href="funciones/balance_negocio.php">Negocio</a></li>
                <li><a href="funciones/balance.php">Productos</a></li>
            </ul>
        </li>
        <li>
            <a href="javascript:void(0);" class="dropdown-toggle"><span class="material-symbols-outlined ico-plagiarism"></span>Reportes <span class="arrow">&#9662;</span></a>
            <ul class="dropdown-menu">
                <li><a href="funciones/reporte_inventario.php">Inventario</a></li>
                <li><a href="funciones/reporte_compras.php">Compras</a></li>
                <li><a href="funciones/reporte_gasto.php">Gasto</a></li>
                <li><a href="funciones/reporte_perdida.php">Perdida</a></li>
            </ul>
        </li>
        <li>
            <a href="javascript:void(0);" class="dropdown-toggle"><span class="material-symbols-outlined ico-settings"></span>Configuración <span class="arrow">&#9662;</span></a>
            <ul class="dropdown-menu">
                <li><a href="registrar/registro_tasa_dolar.php">Tasa de Dolar</a></li>
                <li>
                    <a href="javascript:void(0);" class="dropdown-toggle">Mantenimiento <span class="arrow arrow-right">&#9656;</span></a>
                    <ul class="dropdown-menu sub-submenu">
                        <li><a href="listas/Respaldar.php">Respaldar</a></li>
                        <li><a href="listas/Restaurar.php">Restaurar</a></li>
                    </ul>
                </li>
                <li>
                    <a href="javascript:void(0);" class="dropdown-toggle">Gestión de Usuario <span class="arrow arrow-right">&#9656;</span></a>
                    <ul class="dropdown-menu sub-submenu">
                        <li><a href="listas/gestion_usuario.php">Gerente</a></li>
                        <li><a href="listas/gestion_usuario_2.php">Cajero</a></li>
                    </ul>
                </li>
                <li><a href="listas/papelera.php">Papelera</a></li>
            </ul>
        </li>
    </div>
    
    <div id="content-wrapper">
        <div class="grid-area area-top-left">
            <div class="info-box">
                <h2>Bienvenido</h2>
                <p>Tu rendimiento financiero ha sido de:</p>
                <p class="performance-percentage" id="financial-performance">--%</p>
                <div class="mini-chart" id="mini-chart">
                    <svg width="100" height="50" viewbox="0 0 100 50" preserveAspectRatio="none">
                        <polyline points="0,45 10,30 25,40 40,20 55,25 70,10 85,15 100,30"
                                    style="fill:none; stroke:rgba(76, 175, 80, 0.5); stroke-width:2" />
                        <polyline points="0,45 10,30 25,40 40,20 55,25 70,10 85,15 100,30"
                                    style="fill:rgba(76, 175, 80, 0.1);" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="grid-area area-bottom-left">
            <div class="info-box tasa-box">
                <h3>Tasa Dólar BCV (Última actualización)</h3>
                <p class="tasa-valor">Valor: Bs. <?php echo isset($tasaValor) ? number_format($tasaValor, 2, ',', '.') : 'N/A'; ?></p>
                <p class="tasa-fecha">
                    <?php if ($tasaFechaFormateada !== 'N/A' && $tasaFechaFormateada !== 'Fecha inválida'): ?>
                        Tasa correspondiente al BCV el <?php echo htmlspecialchars($tasaFechaFormateada); ?>
                    <?php else: ?>
                        Fecha no disponible
                    <?php endif; ?>
                </p>
            </div>
        </div>

        <div class="grid-area area-center">
            <div class="center-image-container">
                <img src="assets/images/empresa.png" alt="Imagen Central" id="main-content-image">
            </div>
        </div>

        <div class="grid-area area-top-right">
            <div class="info-box capital-box">
                <h3>Capital Global</h3>
                <p class="capital-amount" id="global-capital-value">
                    $ <?php echo number_format($capitalGlobal, 2, '.', ','); ?>
                </p>
            </div>
        </div>

        <div class="grid-area area-bottom-right">
            <div class="notifications-widget">
                <div class="notifications-header">
                    <h4>Notificaciones del sistema</h4>
                </div>
                <ul class="notifications-list">
                    <?php if ($notificacion_producto): ?>
                        <li class="notificacion-producto">
                            <span class="material-symbols-outlined ico-inventory_2"></span>
                            <div class="notification-message">
                                <strong>Alerta: Inventario bajo</strong>
                                <span>El producto '<?php echo htmlspecialchars($notificacion_producto['nombre_producto']); ?>' se está agotando.</span>
                            </div>
                        </li>
                    <?php endif; ?>
                    <?php if ($notificacion_vencida): ?>
                        <li class="notificacion-vencida">
                            <span class="material-symbols-outlined ico-event_busy"></span>
                            <div class="notification-message">
                                <strong>Crédito Vencido</strong>
                                <span>Cliente: <?php echo htmlspecialchars($notificacion_vencida['nombre_cliente'] . ' ' . $notificacion_vencida['apellido_cliente']); ?></span>
                            </div>
                        </li>
                    <?php endif; ?>
                    <?php if ($notificacion_pendiente): ?>
                        <li class="notificacion-pendiente">
                            <span class="material-symbols-outlined ico-credit_card"></span>
                            <div class="notification-message">
                                <strong>Crédito Pendiente</strong>
                                <span>Cliente: <?php echo htmlspecialchars($notificacion_pendiente['nombre_cliente'] . ' ' . $notificacion_pendiente['apellido_cliente']); ?></span>
                            </div>
                        </li>
                    <?php endif; ?>
                    <?php if ($notificacion_proveedor_vencida): ?>
                        <li class="notificacion-proveedor-vencida">
                            <span class="material-symbols-outlined ico-event_busy"></span>
                            <div class="notification-message">
                                <strong>Crédito de Proveedor Vencido</strong>
                                <span>Proveedor: <?php echo htmlspecialchars($notificacion_proveedor_vencida['nombre_provedor']); ?></span>
                            </div>
                        </li>
                    <?php endif; ?>
                    <?php if ($notificacion_proveedor_pendiente): ?>
                        <li class="notificacion-proveedor-pendiente">
                            <span class="material-symbols-outlined ico-credit_card"></span>
                            <div class="notification-message">
                                <strong>Crédito de Proveedor Pendiente</strong>
                                <span>Proveedor: <?php echo htmlspecialchars($notificacion_proveedor_pendiente['nombre_provedor']); ?></span>
                            </div>
                        </li>
                    <?php endif; ?>
                    <?php if (!$notificacion_producto && !$notificacion_vencida && !$notificacion_pendiente && !$notificacion_proveedor_vencida && !$notificacion_proveedor_pendiente): ?>
                        <li>
                            <div class="notification-message">
                                <span>No hay nuevas notificaciones.</span>
                            </div>
                        </li>
                    <?php endif; ?>
                </ul>
                <div class="notifications-footer">
                    <a href="funciones/notificaciones.php">Ver todas las notificaciones</a>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/js/menu.js"></script>
    <script>
        function showModal() {
            const modal = document.getElementById('welcomeModal');
            modal.style.display = 'flex';
        }
        function closeModal() {
            const modal = document.getElementById('welcomeModal');
            modal.style.display = 'none';
        }
        window.onclick = function(event) {
            const modal = document.getElementById('welcomeModal');
            if (event.target === modal) {
                closeModal();
            }
        }
        document.addEventListener('DOMContentLoaded', function() {
            const shouldShowModal = <?php echo json_encode($mostrar_modal_bienvenida); ?>;
            if (shouldShowModal) {
                showModal();
            }

            const randomPerformance = (Math.random() * 15 - 5).toFixed(1);
            const performanceElement = document.getElementById('financial-performance');
            if (performanceElement) {
                performanceElement.textContent = `${randomPerformance}%`;
                if (randomPerformance >= 0) {
                    performanceElement.style.color = '#4CAF50';
                    const chartLine = document.querySelector('#mini-chart svg polyline');
                    const chartFill = document.querySelectorAll('#mini-chart svg polyline')[1];
                    if (chartLine) chartLine.style.stroke = 'rgba(76, 175, 80, 0.8)';
                    if (chartFill) chartFill.style.fill = 'rgba(76, 175, 80, 0.2)';
                } else {
                    performanceElement.style.color = '#f44336';
                    const chartLine = document.querySelector('#mini-chart svg polyline');
                    const chartFill = document.querySelectorAll('#mini-chart svg polyline')[1];
                    if (chartLine) chartLine.style.stroke = 'rgba(244, 67, 54, 0.8)';
                    if (chartFill) chartFill.style.fill = 'rgba(244, 67, 54, 0.2)';
                }
            }
        });
    </script>
</body>
</html>