<link rel="stylesheet" href="../assets/css/menu.css"> 
 <link rel="stylesheet" href="../assets/fonts/google-icons/index.css"> 
<div id="content-wrapper"> 
        <div class="top-bar">
            <div class="top-bar-left">
                <img src="../assets/images/vertex.png" alt="Logo Empresa" id="logo">
                <button id="openBtn" onclick="openNav()">&#9776;</button>
            </div>
            <div class="manager-menu-container">
                <button id="managerBtn"><span class="material-symbols-outlined ico-account_circle md-18"></span>
                    <?php echo htmlspecialchars($nombre_usuario_actual); ?> &#9662;
                </button>
                <div id="managerDropdown" class="manager-dropdown-content">
                    <a href="../logout.php">Cerrar Sesión</a>
                </div>
            </div>
        </div>

        <div id="sideMenu" class="sidenav">
            <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
            <ul>
                <li>
                    <a href="cajero.php?action=nueva_venta">
                        <span class="material-symbols-outlined ico-home"></span> Inicio
                    </a>
                </li>
                <li>
                    <a href="historial_credito.php">
                        <span class="material-symbols-outlined ico-person_add"></span>Creditos
                    </a>
                </li> <li>
                    <a href="caja.php">
                        <span class="material-symbols-outlined ico-person_add"></span>Caja
                    </a>
                </li>
                 </li> <li>
                    <a href="cierre_caja.php">
                        <span class="material-symbols-outlined ico-person_add"></span>Cierre Caja
                    </a>
                </li>
                <li>
                    <a href="venta.php">
                        <span class="material-symbols-outlined ico-receipt_long"></span> Historial
                    </a>
                </li>
                <li>
                    <a href="javascript:void(0);" class="dropdown-toggle">
                        <span class="material-symbols-outlined ico-settings"></span>Configuración <span class="arrow">&#9662;</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="../registrar/registro_tasa_dolar.php">Tasa de Dolar</a></li>
                    </ul>
                </li>
                <li>
                    <a href="../logout.php"><span class="material-symbols-outlined ico-logout"></span>Cerrar Sesión</a>
                </li>
            </ul>
        </div>
        <script src="../assets/js/menu.js"></script> 