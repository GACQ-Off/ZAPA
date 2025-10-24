<section id="primer_modal">
    <div id="primer_modal__contenedor">
        <section id="cabezal_modal_f">
            <h1 id="h_de_formulario">
                <?php
                echo htmlspecialchars($titulo);
                ?>
            </h1>
            <div id="boton_cerrar_modal">
                <img src="img/svg/equis_negro.svg" alt="icono de cerrar" id="boton_cerrar_modal_r">
            </div>
        </section>
        <p id="Obligatorios"><strong> (*): Campo(s) Obligatorio(s)</strong></p>
        <div id="modal_area">
            <form method="post" enctype="multipart/form-data" id="_registrar">
                <?php $ci_usuario_sesion = $_SESSION['ci_usuario'] ?? null; $status_comun = 1;    
                if (!empty($form_rut_e) && file_exists($form_rut_e)) {include $form_rut_e; } ?>
                <section id="bottom_modal">
                <?php include 'b_form.php';?>
                </section>
                <input type="hidden" name="accion" value="_registrar">
            </form>
        </div>
    </div>
</section>
<div id="modal_segundo" class="modal_confirmacion">
    <div class="modal_contenido">
        <p>¿Está seguro de que quiere limpiar todos los campos del formulario? Se perderán todos los datos ingresados.</p>
        <?php if ($_tabs_atr == 0 || $_tabs_atr == 1) { ?> <p><strong>Esta acción lo devolverá a la fase 1 del formulario.</strong></p> <?php } ?>
        <div class="modal_botones">
            <button id="confirmar_limpiar">Sí, Limpiar</button>
            <button id="cancelar_limpiar">Cancelar</button>
        </div>
    </div>
</div>
<div id="modal_tercero" class="modal_confirmacion">
    <div class="modal_contenido">
        <p>¿Está seguro de que los datos ingresados son los correctos?</p>
        <div class="modal_botones">
            <button id="confirmar_guardar">Sí, <?php echo htmlspecialchars($_tex_b_f) ?></button>
            <button id="cancelar_guardar">Cancelar</button>
        </div>
    </div>
</div>