<section id="cabeza_principal">
    <div id="uno_cabeza">
        <h4>
            <?php echo htmlspecialchars($header) ?>
        </h4>
        <h6>
            <?php echo htmlspecialchars($desc) ?>
        </h6>
    </div>
    <form action="" method="get" id="dos_cabeza">
        <div id="busqueda_input">
            <img src="img/svg/search_black.svg" alt="Logo" class="icono">
            <input type="text" name="b" placeholder="<?php echo htmlspecialchars($place) ?>" 
                   value="<?php echo htmlspecialchars($_GET['b'] ?? '') ?>"> 
        </div>
        <button type="submit" id="busqueda_boton">
            Buscar
        </button>
    </form>
</section>