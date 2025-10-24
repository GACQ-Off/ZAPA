
<section id="modal_ocho">
    <div id="modal_contenedor_r">
        <section id="cabezal_modal_r">
            <h1 id="h_de_reportes">
                Generación de Reportes
            </h1>
            <div id="boton_cerrar_modal">
                <img src="img/svg/equis_negro.svg" alt="icono de cerrar" id="boton_cerrar_modal_re">
            </div>
        </section>
        <div id="modal_area_r">
            <?php if (!empty($reports_rut_e) && file_exists($reports_rut_e)) {
                include $reports_rut_e;
            } ?>
            <button type="button">
                Cerrar Ventana
            </button>
        </div>
    </div>
</section>