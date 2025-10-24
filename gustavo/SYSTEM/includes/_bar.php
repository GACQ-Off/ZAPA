    <aside id="barra">
        <nav>
            <div id="primer_contenedor">
                <div id="imagen">
                    <img alt="logo" src="img/logo-black.jpg">
                </div>
                <a href="index.php" class="botones_de_barra" <?php if ($_tabs_atr == 0) { ?> id="acento_barra" <?php } ?>>
                    <img src="img/svg/casa_blaco.svg" alt="Icono de Barra">
                    <p>Menú Principal</p>
                </a>
                <a href="list_o.php" class="botones_de_barra"<?php if ($_tabs_atr >= 1 && $_tabs_atr <= 8) { ?>id="acento_barra"<?php } ?>>
                    <img src="img/svg/listas_blanco.svg" alt="Icono de Barra">
                    <p>Listados</p>
                </a>
            </div>
            <div id="segundo_contenedor">
                <a href="sett_p.php" class="botones_de_barra"<?php if ($_tabs_atr >= 9 && $_tabs_atr <= 14) { ?>id="acento_barra"<?php } ?>>
                    <img src="img/svg/engranaje_blanco.svg" alt="Icono de Barra">
                    <p>Configuración</p>
                </a>
                <a href="about_.php" class="botones_de_barra"<?php if ($_tabs_atr >= 15) { ?>id="acento_barra"<?php } ?>>
                    <img src="img/svg/exclamacion_blaco.svg" alt="Icono de Barra">
                    <p>Ayuda</p>
                </a>
            </div>
        </nav>
    </aside>