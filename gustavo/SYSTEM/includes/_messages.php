<section> 
    <?php if ($_tabs_atr === null) {$sys='SYSTEM/';} else {$sys='';};
    if (isset($_GET['s']) && array_key_exists($_GET['s'], $mensajes_s)) {
        $success_i = $_GET['s'];
        $mensaje = $mensajes_s[$success_i]; 
        echo "<div id='mensaje_exito'><img src='". htmlspecialchars($sys) ."img/svg/check_blanco.svg' alt='icono de error' class='icono'><p>" . htmlspecialchars($mensaje) . "</p></div>";
    } elseif (isset($_GET['e']) && array_key_exists($_GET['e'], $mensajes_e)) {
        $error_i = $_GET['e'];
        $mensaje = $mensajes_e[$error_i];
        echo "<div id='mensaje_error'><img src='". htmlspecialchars($sys) ."img/svg/error_blanco.svg' alt='icono de error' class='icono'><p>" . htmlspecialchars($mensaje) . "</p></div>";} ?>
</section>