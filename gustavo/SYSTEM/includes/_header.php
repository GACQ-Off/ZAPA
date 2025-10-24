<?php $nombreUsuario = $_SESSION['nombres_usuario'];
$foto = $_SESSION['img_usuario'];
if ($foto == '' || $foto == NULL) {
    $fotoPerfil = 'img/default_usuario_negro.png';} 
else {
    $fotoPerfil = "uploads/profiles/{$foto}";} ?>
    <section id="cabezal_">
        <h1><?php echo htmlspecialchars($_c_uno); ?> <text> <?php if ($_tabs_atr != 15): echo htmlspecialchars($_c_dos); endif; ?></text></h1>
        <form action="../_clo.php" method="post">
            <div id="boton_usuario">
                <p><?php echo htmlspecialchars($nombreUsuario); ?></p>
                <img src="<?php echo htmlspecialchars($fotoPerfil); ?>" alt="foto de perfil">
                <ul class="submenu-usuario">
                    <li><a href="sett_p.php">Perfil</a></li>
                    <li><button type="submit" id="salir_sesion">Cerrar Sesión</button></li>
                </ul>
            </div>
        </form>
    </section>