<?php 
if ($modo === 'editar') { ?>
    <input type="hidden" 
    name="id_registro_a_modificar_m" 
    value="<?php echo htmlspecialchars($datos['id_area'] ?? ''); ?>">
    <?php } ?>
    
    <section id="cuerpo_formulario">
        <div>
        <label for="nom_formulario" class="label_artificial">
            Nombre<?php if ($modo === 'editar') { ?> (*): <?php } ?>:
        </label>
        <?php if ($modo === 'editar'): ?>
            <input type="text" 
                name="nom_formulario_m" 
                id="nom_formulario" 
                placeholder="Nombre del Area" 
                maxlength="32" 
                autocomplete="off" 
                minlength="4"
                required
                value="<?php echo htmlspecialchars($datos['nombre_area'] ?? ''); ?>">
        <?php else: ?>
            <p class="valor_visualizacion">
                <?php echo htmlspecialchars($datos['nombre_area'] ?? 'N/A'); ?>
            </p>
        <?php endif; ?>
    </div>
    
    <div>
        <label for="" class="label_artificial">
            Material Fotográfico de Referencia:
        </label>
        
        <?php 
        $ruta = 'uploads/zones/';
        $ruta_db = $datos['img_area'] ?? 'img/default.png'; 
        $imagen_completa = htmlspecialchars($ruta . $ruta_db); 
        ?>
        <div class="imagen_contenedor">
            <img id="imagen_previa_m" src="<?php echo $imagen_completa; ?>" 
            alt="Previsualización de la imagen de referencia">
        </div>
        
        <?php if ($modo === 'editar') { ?>
            <div>
                <input type="hidden" name="foto_existente_m" value="<?php echo htmlspecialchars($ruta_db); ?>">
                
                <label for="foto_formulario_m" class="label_de_imagen_o_archivo">
                    <img src="img/svg/foto_blanco.svg" alt="icono de cámara" class="icono">
                    Cambiar Foto
                </label>
                <input type="file" 
                    name="foto_formulario_m" 
                    id="foto_formulario_m" 
                    accept="image/*" 
                    class="label_de_imagen_o_archivo">
            </div>
        <?php } else { ?>
            <p class="nota_visualizacion">
                (El control de subida de archivo no está disponible en modo Visualización)
            </p>
        <?php }; ?>
    </div>
</section>