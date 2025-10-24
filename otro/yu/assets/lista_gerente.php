<?php include '../funciones/contador_notificaciones.php'; ?>
<div class="top-bar">
    <div class="top-bar-left">
        <img src="../assets/images/vertex.png" alt="Logo Empresa" id="logo">
        <button id="openBtn" onclick="openNav()">&#9776;</button>
    </div>
    <div class="manager-menu-container">
        <button id="managerBtn"><span class="material-symbols-outlined ico-account_circle"></span>Gerente &#9662;</button>
        <div id="managerDropdown" class="manager-dropdown-content">
            <a href="../funciones/mi_empresa.php">Mi Empresa</a>
            <a href="../logout.php">Cerrar Sesión</a>
        </div>
        <a href="../funciones/notificaciones.php" class="notifications-link">
            <button id="managerBtn" class="notifications-btn">
                <span class="material-symbols-outlined ico-notifications"></span>
                <?php if ($contador_notificaciones > 0): ?>
                    <span class="notification-badge"><?php echo $contador_notificaciones; ?></span>
                <?php endif; ?>
            </button>
        </a>
    </div>
</div>

<div id="sideMenu" class="sidenav">
    <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
    <a href="../menu.php"><span class="material-symbols-outlined ico-home"></span>
Inicio </a>
    <li>
        <li>
            <a href="javascript:void(0);" class="dropdown-toggle"><span class="material-symbols-outlined ico-store"></span>Ventas<span class="arrow">&#9662;</span></a>
            <ul class="dropdown-menu">
                <li><a href="../listas/lista_ventas.php">Ventas</a></li>
                
                <li><a href="../funciones/venta_gerente.php">Generar ventas</a></li>
                <li><a href="../funciones/cierre_caja.php">Cierre de caja</a></li>
                <li><a href="../funciones/credito_ventas.php">Creditos</a></li>
            </ul>
        </li>
    </li>
    <li>
        <a href="javascript:void(0);" class="dropdown-toggle"><span class="material-symbols-outlined ico-receipt_long"></span>Nómina <span class="arrow">&#9662;</span></a>
        <ul class="dropdown-menu">
            <li><a href="../listas/lista_cargos.php">Cargos</a></li>
            <li><a href="../listas/lista_empleado.php">Empleado</a></li>
        </ul>
    </li>
        <li>
            <a href="javascript:void(0);" class="dropdown-toggle"><span class="material-symbols-outlined ico-shopping_basket"></span>Compras<span class="arrow">&#9662;</span></a>
            <ul class="dropdown-menu">
                <li><a href="../listas/lista_provedor.php">Proveedor</a></li>
                <li><a href="../listas/lista_compras.php">Historial de Compras</a></li>
                <li><a href="../registrar/abono_credito.php">Abonar a Credito</a></li>
            </ul>
        </li>
        <li>
            <a href="javascript:void(0);" class="dropdown-toggle"><span class="material-symbols-outlined ico-inventory"></span>Inventario <span class="arrow">&#9662;</span></a>
            <ul class="dropdown-menu">
                <li><a href="../listas/lista_categoria.php">Categoría</a></li>
                <li><a href="../listas/lista_productos.php">Productos</a></li>
                <li><a href="../listas/lista_perdida.php">Pérdidas</a></li>
            </ul>
        </li>
    <li>
        <a href="javascript:void(0);" class="dropdown-toggle"><span class="material-symbols-outlined ico-checkbook"></span>Gastos <span class="arrow">&#9662;</span></a>
        <ul class="dropdown-menu">
                <li><a href="../listas/lista_categoria_gasto.php">Categoría</a></li>
                <li><a href="../listas/lista_gastos.php">Lista de Gastos</a></li>
        </ul>
    </li>
    <li>
        <a href="javascript:void(0);" class="dropdown-toggle"><span class="material-symbols-outlined ico-balance"></span>Balance <span class="arrow">&#9662;</span></a>
        <ul class="dropdown-menu">
            <li><a href="../funciones/balance_negocio.php">Negocio</a></li>
            <li><a href="../funciones/balance.php">Productos</a></li>
        </ul>
    </li>
        <li>
            <a href="javascript:void(0);" class="dropdown-toggle"><span class="material-symbols-outlined ico-plagiarism"></span>Reportes <span class="arrow">&#9662;</span></a>
            <ul class="dropdown-menu">
                <li><a href="../funciones/reporte_inventario.php">Inventario</a></li>
                <li><a href="../funciones/reporte_compras.php">Compras</a></li>
                <li><a href="../funciones/reporte_perdida.php">Perdida</a></li>
                <li><a href="../funciones/reporte_gasto.php">Gasto</a></li>
            </ul>
        </li>  
    <li>
        <a href="javascript:void(0);" class="dropdown-toggle"><span class="material-symbols-outlined ico-settings"></span>Configuración <span class="arrow">&#9662;</span></a>
        <ul class="dropdown-menu">
            <li><a href="../registrar/registro_tasa_dolar.php">Tasa de Dolar</a></li>
            <li>
                <a href="javascript:void(0);" class="dropdown-toggle">Mantenimiento <span class="arrow arrow-right">&#9656;</span></a>
                <ul class="dropdown-menu sub-submenu">
                    <li><a href="../listas/Respaldar.php">Respaldar</a></li>
                    <li><a href="../listas/Restaurar.php">Restaurar</a></li>
                </ul>
            </li>
            <li>
                <a href="javascript:void(0);" class="dropdown-toggle">Gestión de Usuario <span class="arrow arrow-right">&#9656;</span></a>
                <ul class="dropdown-menu sub-submenu">
                    <li><a href="../listas/gestion_usuario.php">Gerente</a></li>
                    <li><a href="../listas/gestion_usuario_2.php">Cajero</a></li>
                </ul>
            </li>
            <li><a href="../listas/papelera.php">Papelera</a></li>
        </ul>
    </li>
</div>
<div id="content-wrapper">
<script src="../assets/js/menu.js"></script>