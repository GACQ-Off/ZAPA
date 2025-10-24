<?php 
if ($modo === 'editar') { ?>
    <input type="hidden" 
        name="id_registro_a_modificar_m" 
        value="<?php echo htmlspecialchars($datos['id_material'] ?? ''); ?>">
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
                placeholder="Nombre del Material" 
                minlength="3" 
                maxlength="32"
                required
                value="<?php echo htmlspecialchars($datos['nombre_material'] ?? ''); ?>">
        <?php else: ?>
            <p class="valor_visualizacion">
                <?php echo htmlspecialchars($datos['nombre_material'] ?? 'N/A'); ?>
            </p>
        <?php endif; ?>
    </div>
</section>