<?php

    $trapiche_records = $bd->obtenerRegistro("SELECT rif_trapiche FROM trapiche LIMIT 1", '', []);
    $trapiche_exists = !empty($trapiche_records);

if ($trapiche_exists):
?>
<div class="acciones_boton_r" id="_reportes">
    <img src="<?php echo htmlspecialchars($_img_b_r . 'pdf_archivo_negro.svg'); ?>" alt="icono" class="icono">
    <p><?php if ($_tabs_atr == 0): ?> Exportar <?php else: ?> Generar Reporte <?php endif; ?> </p>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        function modalReportes() {
            const modal = document.querySelector("#modal_ocho"); 
            const gatillo = document.querySelector("#_reportes"); 
            const botonCerrarCabezal = document.getElementById('boton_cerrar_modal_re');
            const botonCerrarCuerpo = modal.querySelector('#modal_area_r button'); 
            function cerrar() {
                modal.classList.remove("mostrar");
            }

            if (gatillo) {
                gatillo.addEventListener("click", () => {
                    modal.classList.add("mostrar");
                });
            }

            modal.addEventListener("click", (e) => {
                if (e.target.id === "modal_ocho") { 
                    cerrar();
                }
            });

            if (botonCerrarCabezal) {
                botonCerrarCabezal.addEventListener('click', cerrar);
            }
            if (botonCerrarCuerpo) {
                botonCerrarCuerpo.addEventListener('click', cerrar);
            }
            
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape' && modal.classList.contains('mostrar')) {
                    cerrar();
                }
            });
        }

        modalReportes();
    });
</script>
<?php else: ?>
    <a <?php if ($_SESSION['rol_id'] == 2): ?> href="sett_t.php" <?php endif; ?> class="acciones_boton_r" style="background-color: #ffc107; cursor: default;" title="Debe configurar el Trapiche antes de generar reportes. Vaya a Configuración > Institución.">
        <img src="<?php echo htmlspecialchars($_img_b_r . 'pdf_archivo_negro.svg'); ?>" alt="icono" class="icono">
        <p> Configurar Trapiche (Reportes Deshabilitados) </p>
    </a>
<?php endif; ?>