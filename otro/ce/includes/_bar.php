        <nav id="contenedor_bar">
            <section id="cabeza_bar">
                <img src="img/blue.jpeg" alt="Logo" class="icono" id="Logo">
            </section>
            <section id="cuerpo_bar">
                <a <?php if ($iu == 0) { ?> id="acento_bar" <?php } ?> href="#">Inicio</a>
                <a <?php if ($iu == 1) { ?> id="acento_bar" <?php } ?> href="index.php">Profesores</a>
                <a <?php if ($iu == 2) { ?> id="acento_bar" <?php } ?> href="list_r.php">Profesiones</a>
            </section>
            <a id="cola_bar" href="#">
                <p>Perfil</p>
                <img src="img/svg/profile_black.svg" alt="Logo" class="icono">
            </a>
        </nav>