<section id="pestanas_">
    <ul>
        <li>
            <a class="pestanas_accion" href="sett_p.php" <?php if ($_tabs_atr == 9) { ?> id="acento_pestanas" <?php } ?>                >Perfil</a>
        </li>
        <?php if ($_SESSION['rol_id'] != 1) { ?>
            <li>
                <a class="pestanas_accion" href="sett_bd.php" <?php if ($_tabs_atr == 10) { ?> id="acento_pestanas" <?php } ?>                >Base de Datos</a>
            </li>
            <li>
                <a class="pestanas_accion" href="sett_u.php" <?php if ($_tabs_atr == 11) { ?> id="acento_pestanas" <?php } ?>                >Usuarios</a>
            </li>
            <li>
                <a class="pestanas_accion" href="sett_t.php" <?php if ($_tabs_atr == 12) { ?> id="acento_pestanas" <?php } ?>                >Datos de Trapiche</a>
            </li> <?php } ?>
    </ul>
</section>