<?php
$nombre_actual = htmlspecialchars($datos['nombre_tecnica'] ?? ''); 

if ($modo === 'editar') { ?>
    <input type="hidden"
        name="id_registro_a_modificar_m"
        value="<?php echo htmlspecialchars($datos['id_tecnica'] ?? ''); ?>"> 
<?php } ?>

<section id="cuerpo_formulario">
    <div>
        <label for="nom_formulario" class="labels">
            Nombre (*):
        </label>
        <?php if ($modo === 'editar'): ?>
            <input type="text" name="nom_formulario_m" id="nom_formulario" 
                placeholder="Nombre de la Técnica" 
                maxlength="32" minlength="3" autocomplete="off" 
                required
                value="<?php echo $nombre_actual; ?>">
        <?php else: ?>
            <p class="valor_visualizacion">
                <?php echo !empty($nombre_actual) ? $nombre_actual : 'N/A'; ?>
            </p>
        <?php endif; ?>
    </div>
</section>