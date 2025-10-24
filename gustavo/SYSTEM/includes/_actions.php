<section id="acciones_">
        <div class="acciones_boton">
            <img src="<?php echo htmlspecialchars($_img_b_r . $_img_b); ?>" alt="icono" class="icono">
            <p> <?php if ($_tabs_atr == 0) { echo htmlspecialchars($_tit_b);} else { echo htmlspecialchars($_tit_b_e.' '.$_tit_b); }; ?> </p>
        </div>
    <?php if ($_tabs_atr == 0 || $_tabs_atr == 1 || $_tabs_atr == 2 || $_tabs_atr == 6 || $_tabs_atr == 8 || $_tabs_atr == 11) { 
        include 'reports/reports_base.php';
    } ?>
    <form action="" method="get" id="acciones_barra">
        <?php $valor_b_d = isset($_GET['b']) ? htmlspecialchars($_GET['b']) : ''; ?>
        <input type="text" name="b" id="busqueda_input" placeholder="<?php echo htmlspecialchars($_c_seis) ?>"  value="<?php echo $valor_b_d; ?>" autocomplete="off">
        <button type="submit" class="btn_search_icon">
        <img src="img/svg/lupa_negro.svg" alt="icono de búsqueda" class="icono">
        Buscar
        </button>
    </form>
</section>