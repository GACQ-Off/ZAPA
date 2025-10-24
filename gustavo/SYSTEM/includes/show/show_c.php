
<?php 
if ($modo === 'editar') { ?>
    <input type="hidden" 
        name="id_registro_a_modificar_m" 
        value="<?php echo htmlspecialchars($datos['id_categoria'] ?? ''); ?>">
<?php } ?>

<section id="cuerpo_formulario">
    <div>
        <label for="nom_formulario" class="labels">
            Nombre (*):
        </label>
        <?php if ($modo === 'editar'): ?>
            <input type="text" 
                name="nom_formulario_m" 
                id="nom_formulario" 
                placeholder="Nombre de la Categoría" 
                maxlength="32" 
                minlength="4" 
                autocomplete="off"
                required
                value="<?php echo htmlspecialchars($datos['nombre_categoria'] ?? ''); ?>">
        <?php else: ?>
            <p class="valor_visualizacion">
                <?php echo htmlspecialchars($datos['nombre_categoria'] ?? 'N/A'); ?>
            </p>
        <?php endif; ?>
    </div>
    
    <div>
        <label for="resu_formulario" class="labels">
            Texto Descripción:
        </label>
        <?php if ($modo === 'editar'): ?>
            <textarea 
                name="resu_formulario_m" 
                id="resu_formulario" 
                placeholder="Esta categoría se trata de..." 
                maxlength="255" 
                autocomplete="off"
                ><?php echo htmlspecialchars($datos['descripcion_categoria'] ?? ''); ?></textarea>
        <?php else: ?>
            <p class="valor_visualizacion descripcion">
                <?php echo nl2br(htmlspecialchars($datos['descripcion_categoria'] ?? 'Sin descripción.')); ?>
            </p>
        <?php endif; ?>
    </div>
</section>