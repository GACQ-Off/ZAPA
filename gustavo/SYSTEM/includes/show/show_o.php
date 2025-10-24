<?php
function obtener_info_por_id($data_array, $id, $id_key, $name_key = null) {
    if (empty($id)) {
        return ($name_key) ? 'N/A' : null;
    }
    
    if (!is_array($data_array) || empty($data_array)) {
        return ($name_key) ? htmlspecialchars($id) . ' (Lista de búsqueda vacía)' : null;
    }
    
    foreach ($data_array as $item) {
        if (isset($item[$id_key]) && $item[$id_key] == $id) {
            if ($name_key) {
                return htmlspecialchars($item[$name_key]);
            }
            return $item;
        }
    }
    return ($name_key) ? 'ID no encontrado (' . htmlspecialchars($id) . ')' : null; 
}

$autores_obra = $autores_obra ?? []; 

if ($modo === 'editar') { ?>
    <input type="hidden" name="id_registro_a_modificar_m" 
        value="<?php echo htmlspecialchars($datos['cod_obra'] ?? ''); ?>">
<?php } ?>

<section id="cuerpo_formulario">

    <fieldset id="f_contenedor_uno">
        <legend class="label_artificial">
            Fase 1 de 4 - Información General
        </legend>
        <table class="formulario_tabla">
            <tbody>
                <tr>
                    <td colspan="2">
                        <div>
                            <label for="id_formulario" class="labels">
                                Código de Identificación (*):
                            </label>
                            <?php if ($modo === 'editar'): ?>
                                <input type="text" autocomplete="off" id="id_formulario" name="id_formulario_m" 
                                    placeholder="Código de Identificación" maxlength="25" minlength="1" 
                                    readonly value="<?php echo htmlspecialchars($datos['cod_obra'] ?? ''); ?>">
                            <?php else: ?>
                                <p class="valor_visualizacion">
                                    <?php echo htmlspecialchars($datos['cod_obra'] ?? 'N/A'); ?></p>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td>
                        <div>
                            <label for="ti_formulario" class="labels">
                                Título (*):
                            </label>
                            <?php if ($modo === 'editar'): ?>
                                <input type="text" autocomplete="off" name="ti_formulario_m" id="ti_formulario" placeholder="Título"
                                    maxlength="65" minlength="2" required 
                                    value="<?php echo htmlspecialchars($datos['titulo_obra'] ?? ''); ?>">
                            <?php else: ?>
                                <p class="valor_visualizacion">
                                    <?php echo htmlspecialchars($datos['titulo_obra'] ?? 'N/A'); ?></p>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td>
                        <div>
                            <label for="f_in_formulario">
                                Fecha de Ingreso:
                            </label>
                            <?php 
                            $fecha_ing = $datos['f_ingreso_obra'] ?? '';
                            if ($modo === 'editar'): 
                                $valor_ing = (!empty($fecha_ing) && strtotime($fecha_ing)) ? date('Y-m-d', strtotime($fecha_ing)) : '';
                                ?>
                                <input type="date" name="f_in_formulario_m" id="f_in_formulario" 
                                    value="<?php echo htmlspecialchars($valor_ing); ?>">
                            <?php else: 
                                $fecha_mostrar = 'N/A';
                                if (!empty($fecha_ing) && $fecha_ing !== '0000-00-00' && strtotime($fecha_ing)) {
                                    $fecha_mostrar = date('d-m-Y', strtotime($fecha_ing));
                                }
                                ?>
                                <p class="valor_visualizacion">
                                    <?php echo htmlspecialchars($fecha_mostrar); ?></p>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td>
                        <div>
                            <label for="es_con_formulario" class="labels">
                                Estado de Conservación (*):
                            </label>
                            <?php 
                            $estado_actual = $datos['estado_obra'] ?? '';
                            $estados = ['Bueno', 'Regular', 'Malo'];
                            if ($modo === 'editar'): ?>
                                <select name="es_con_formulario_m" id="es_con_formulario" required>
                                    <option value="">Seleccione...</option>
                                    <?php foreach ($estados as $est): ?>
                                        <option value="<?php echo htmlspecialchars($est); ?>" 
                                            <?php echo (strcasecmp($est, $estado_actual) == 0) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($est); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            <?php else: ?>
                                <p class="valor_visualizacion">
                                    <?php echo htmlspecialchars($estado_actual ?: 'N/A'); ?></p>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td>
                        <div>
                            <label for="f_el_formulario" class="labels">
                                Fecha de Elaboración:
                            </label>
                            <?php 
                            $fecha_elab = $datos['f_elaboracion_obra'] ?? '';
                            if ($modo === 'editar'): 
                                $valor_elab = (!empty($fecha_elab) && strtotime($fecha_elab)) ? date('Y-m-d', strtotime($fecha_elab)) : '';
                                ?>
                                <input type="date" name="f_el_formulario_m" id="f_el_formulario" 
                                    value="<?php echo htmlspecialchars($valor_elab); ?>">
                            <?php else: 
                                $fecha_mostrar = 'N/A';
                                if (!empty($fecha_elab) && $fecha_elab !== '0000-00-00' && strtotime($fecha_elab)) {
                                    $fecha_mostrar = date('d-m-Y', strtotime($fecha_elab));
                                }
                                ?>
                                <p class="valor_visualizacion">
                                    <?php echo htmlspecialchars($fecha_mostrar); ?></p>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td>
                        <div>
                            <label for="va_formulario" class="labels">
                                Valor Estimado ($):
                            </label>
                            <?php if ($modo === 'editar'): ?>
                                <input type="number" autocomplete="off" name="va_formulario_m" id="va_formulario" 
                                    placeholder="Valor Estimado (en Doláres)" step="0.01" max="99999999999" min="0" 
                                    value="<?php echo htmlspecialchars($datos['valor_obra'] ?? ''); ?>">
                            <?php else: ?>
                                <p class="valor_visualizacion">
                                    <?php echo htmlspecialchars($datos['valor_obra'] ?? 'N/A'); ?></p>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td>
                        <div>
                            <label for="f_va_formulario" class="labels">
                                Fecha de Tasación:
                            </label>
                            <?php 
                            $fecha_tas = $datos['f_tasacion_obra'] ?? '';
                            if ($modo === 'editar'): 
                                $valor_tas = (!empty($fecha_tas) && strtotime($fecha_tas)) ? date('Y-m-d', strtotime($fecha_tas)) : '';
                                ?>
                                <input type="date" name="f_va_formulario_m" id="f_va_formulario" 
                                    value="<?php echo htmlspecialchars($valor_tas); ?>">
                            <?php else: 
                                $fecha_mostrar = 'N/A';
                                if (!empty($fecha_tas) && $fecha_tas !== '0000-00-00' && strtotime($fecha_tas)) {
                                    $fecha_mostrar = date('d-m/Y', strtotime($fecha_tas));
                                }
                                ?>
                                <p class="valor_visualizacion">
                                    <?php echo htmlspecialchars($fecha_mostrar); ?></p>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td>
                        <div>
                            <label for="cat_formulario" class="labels">
                                Categoría (*):
                            </label>
                            <?php 
                            $cat_actual = $datos['categoria_id'] ?? '';
                            if ($modo === 'editar'): ?>
                                <select name="cat_formulario_m" id="cat_formulario" required 
                                    <?php echo empty($categorias) ? 'disabled' : ''; ?>>
                                    <option value="">
                                        <?php echo empty($categorias) ? 'No hay Categorías activas' : 'Seleccione Categoría'; ?>
                                    </option>
                                    <?php if (!empty($categorias)) {
                                        foreach ($categorias as $categoria) {
                                            $id = htmlspecialchars($categoria["id_categoria"]);
                                            $nombre = htmlspecialchars($categoria["nombre_categoria"]);
                                            $selected = ($id == $cat_actual) ? 'selected' : '';
                                            echo "<option value='{$id}' {$selected}>{$nombre}</option>";
                                        }
                                    } ?>
                                </select>
                            <?php else:
                                $nombre_cat = obtener_info_por_id($categorias, $cat_actual, 'id_categoria', 'nombre_categoria');
                                ?>
                                <p class="valor_visualizacion"><?php echo $nombre_cat ?? 'N/A'; ?></p>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td>
                        <div>
                            <label for="tec_formulario" class="labels">
                                Técnica (*):
                            </label>
                            <?php 
                            $tec_actual = $datos['tecnica_id'] ?? '';
                            if ($modo === 'editar'): ?>
                                <select name="tec_formulario_m" id="tec_formulario" required 
                                    <?php echo empty($tecnicas) ? 'disabled' : ''; ?>>
                                    <option value="">
                                        <?php echo empty($tecnicas) ? 'No hay Técnicas activas' : 'Seleccione Técnica'; ?>
                                    </option>
                                    <?php if (!empty($tecnicas)) {
                                        foreach ($tecnicas as $tecnica) {
                                            $id = htmlspecialchars($tecnica["id_tecnica"]);
                                            $nombre = htmlspecialchars($tecnica["nombre_tecnica"]);
                                            $selected = ($id == $tec_actual) ? 'selected' : '';
                                            echo "<option value='{$id}' {$selected}>{$nombre}</option>";
                                        }
                                    } ?>
                                </select>
                            <?php else:
                                $nombre_tec = obtener_info_por_id($tecnicas, $tec_actual, 'id_tecnica', 'nombre_tecnica');
                                ?>
                                <p class="valor_visualizacion"><?php echo $nombre_tec ?? 'N/A'; ?></p>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td>
                        <div>
                            <label for="mat_formulario" class="labels">
                                Material (*):
                            </label>
                            <?php 
                            $mat_actual = $datos['material_id'] ?? '';
                            if ($modo === 'editar'): ?>
                                <select name="mat_formulario_m" id="mat_formulario" required 
                                    <?php echo empty($materiales) ? 'disabled' : ''; ?>>
                                    <option value="">
                                        <?php echo empty($materiales) ? 'No hay Materiales activos' : 'Seleccione Material'; ?>
                                    </option>
                                    <?php if (!empty($materiales)) {
                                        foreach ($materiales as $material) {
                                            $id = htmlspecialchars($material["id_material"]);
                                            $nombre = htmlspecialchars($material["nombre_material"]);
                                            $selected = ($id == $mat_actual) ? 'selected' : '';
                                            echo "<option value='{$id}' {$selected}>{$nombre}</option>";
                                        }
                                    } ?>
                                </select>
                            <?php else:
                                $nombre_mat = obtener_info_por_id($materiales, $mat_actual, 'id_material', 'nombre_material');
                                ?>
                                <p class="valor_visualizacion"><?php echo $nombre_mat ?? 'N/A'; ?></p>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td>
                        <div>
                            <label for="col_formulario" class="labels">
                                Colección (*):
                            </label>
                            <?php 
                            $col_actual = $datos['coleccion_cod'] ?? '';
                            if ($modo === 'editar'): ?>
                                <select name="col_formulario_m" id="col_formulario" required 
                                    <?php echo empty($colecciones) ? 'disabled' : ''; ?>>
                                    <option value="">
                                        <?php echo empty($colecciones) ? 'No hay Colecciones activas' : 'Seleccione Colección'; ?>
                                    </option>
                                    <?php if (!empty($colecciones)) {
                                        foreach ($colecciones as $coleccion) {
                                            $id = htmlspecialchars($coleccion["cod_coleccion"]);
                                            $nombre = htmlspecialchars($coleccion["titulo_coleccion"]);
                                            $selected = ($id == $col_actual) ? 'selected' : '';
                                            echo "<option value='{$id}' {$selected}>{$nombre}</option>";
                                        }
                                    } ?>
                                </select>
                            <?php else:
                                $nombre_col = obtener_info_por_id($colecciones, $col_actual, 'cod_coleccion', 'titulo_coleccion');
                                ?>
                                <p class="valor_visualizacion"><?php echo $nombre_col ?? 'N/A'; ?></p>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td>
                        <div>
                            <label for="are_formulario" class="labels">
                                Área de Almacenamiento/Exposición (*):
                            </label>
                            <?php 
                            $area_actual = $datos['area_id'] ?? '';
                            if ($modo === 'editar'): ?>
                                <select name="are_formulario_m" id="are_formulario" required 
                                    <?php echo empty($areas) ? 'disabled' : ''; ?>>
                                    <option value="">
                                        <?php echo empty($areas) ? 'No hay Áreas activas' : 'Seleccione Área'; ?>
                                    </option>
                                    <?php if (!empty($areas)) {
                                        foreach ($areas as $area) {
                                            $id = htmlspecialchars($area["id_area"]);
                                            $nombre = htmlspecialchars($area["nombre_area"]);
                                            $selected = ($id == $area_actual) ? 'selected' : '';
                                            echo "<option value='{$id}' {$selected}>{$nombre}</option>";
                                        }
                                    } ?>
                                </select>
                            <?php else:
                                $nombre_area = obtener_info_por_id($areas, $area_actual, 'id_area', 'nombre_area');
                                ?>
                                <p class="valor_visualizacion"><?php echo $nombre_area ?? 'N/A'; ?></p>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td>
                        <div>
                            <label for="pro_formulario" class="labels">
                                Procedencia (*):
                            </label>
                            <?php 
                            $proc_actual = $datos['procedencia_obra'] ?? '';
                            $procedencias = ["Donación", "Comodato", "Premiación", "Adquisición"];
                            if ($modo === 'editar'): ?>
                                <select name="pro_formulario_m" id="pro_formulario" required>
                                    <option value="">Seleccione Procedencia</option>
                                    <?php foreach ($procedencias as $proc): ?>
                                        <option value="<?php echo htmlspecialchars($proc); ?>" 
                                            <?php echo (strcasecmp($proc, $proc_actual) == 0) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($proc); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            <?php else: ?>
                                <p class="valor_visualizacion">
                                    <?php echo htmlspecialchars($proc_actual ?: 'N/A'); ?></p>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                
            </tbody>
        </table>
    </fieldset>
    
    <fieldset id="f_contenedor_dos">
        <legend>
            <label for="autores_formulario" class="label_artificial">
                Fase 2 de 4 - Autoría (*)
            </label>
        </legend>
        <?php if ($modo === 'editar'): ?>
            <div id="contenedor_de_tabla">
                <table>
                    <thead>
                        <tr>
                            <th>Selección</th> <th>Nombres</th>
                            <th>Apellidos</th>
                            <th>Cédula</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($autores)) {
                            foreach ($autores as $autor) {
                                $id_form = $autor['ci_autor'];
                                $checked = in_array($id_form, $autores_obra) ? 'checked' : '';
                                ?>
                                <tr>
                                    <td>
                                        <input type="checkbox" name="autor_seleccionado_m[]"
                                            class="autor_selector" 
                                            value='<?php echo htmlspecialchars($id_form) ?>' <?php echo $checked; ?>>
                                    </td>
                                    <td><?php echo htmlspecialchars($autor['nombres_autor']); ?>
                                    </td>
                                    <td>
                                        <?php echo htmlspecialchars($autor['apellidos_autor']); ?></td>
                                    <td><?php echo htmlspecialchars($autor['ci_autor']); ?></td>
                                </tr>
                            <?php }
                        } else {
                            echo '<tr><td colspan="4">No se encontraron autores disponibles.</td></tr>';
                        } ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="valor_visualizacion">
                <?php
                $nombres_autores = [];
                if (!empty($autores_obra)) {
                    foreach ($autores_obra as $ci_autor) {
                        $autor_registro = obtener_info_por_id($autores, $ci_autor, 'ci_autor');
                        
                        if ($autor_registro && is_array($autor_registro)) {
                            $nombres_autores[] = htmlspecialchars(trim($autor_registro['nombres_autor'] . ' ' . $autor_registro['apellidos_autor']));
                        } else {
                            $nombres_autores[] = htmlspecialchars($ci_autor) . ' (CI no encontrado)';
                        }
                    }
                    echo implode('<br>', array_unique($nombres_autores));
                } else {
                    echo 'Sin autores asignados.';
                }
                ?>
            </div>
        <?php endif; ?>
    </fieldset>

    <fieldset id="f_contenedor_tres">
        <legend>
            Fase 3 de 4 - Material Fotográfico de Referencia:
        </legend>
        <?php $imagen_actual = htmlspecialchars('uploads/obras/'.$datos['img_obra'] ?? 'img/default.png'); ?>
        <div class="imagen_contenedor_uno">
            <img id="imagen_previa_m" src="<?php echo $imagen_actual; ?>" alt="Previsualización de la imagen">
        </div>
        <?php if ($modo === 'editar'): ?>
            <input type="hidden" name="foto_existente_m" value="<?php echo htmlspecialchars($datos['img_obra'] ?? ''); ?>">
            <div id="foto_input_contenedor">
                <label for="foto_formulario_m" class="label_de_imagen_o_archivo">
                    <img src="img/svg/foto_blanco.svg" class="icono">
                    Cambiar Foto
                </label>
                <input type="file" name="foto_formulario_m" id="foto_formulario_m" accept="image/*" 
                    class="label_de_imagen_o_archivo">
            </div>
        <?php else: ?>
            <p class="nota_visualizacion">
                (El control de subida de archivo no está disponible en modo Visualización)
            </p>
        <?php endif; ?>
    </fieldset>

    <fieldset id="f_contenedor_cuatro">
        <legend class="label_artificial">
            Fase 4 de 4 - Informaciones Adicionales
        </legend>
        <div class="partes_uno">
            <h5>
                Dimensiones
            </h5>
            <div>
                <label for="alt_formulario">
                    Alto (cm.):
                </label>
                <?php if ($modo === 'editar'): ?>
                    <input type="number" autocomplete="off" id="alt_formulario" name="alt_formulario_m"
                        placeholder="Altura (en Centimetros)" step="any" max="99999999999" min="0" 
                        value="<?php echo htmlspecialchars($datos['alto_obra'] ?? ''); ?>">
                <?php else: ?>
                    <p class="valor_visualizacion">
                        <?php echo htmlspecialchars($datos['alto_obra'] ?? 'N/A'); ?> cm.</p>
                <?php endif; ?>
            </div>
            <div>
                <label for="anc_formulario">
                    Ancho (cm.):
                </label>
                <?php if ($modo === 'editar'): ?>
                    <input type="number" autocomplete="off" name="anc_formulario_m" id="anc_formulario"
                        placeholder="Anchura (en Centimetros)" step="any" max="99999999999" min="0" 
                        value="<?php echo htmlspecialchars($datos['ancho_obra'] ?? ''); ?>">
                <?php else: ?>
                    <p class="valor_visualizacion">
                        <?php echo htmlspecialchars($datos['ancho_obra'] ?? 'N/A'); ?> cm.</p>
                <?php endif; ?>
            </div>
            <div>
                <label for="profun_formulario">
                    Profundidad (cm.):
                </label>
                <?php if ($modo === 'editar'): ?>
                    <input type="number" autocomplete="off" name="profun_formulario_m"
                        id="profun_formulario" 
                        placeholder="Profundidad (en Centimetros)" step="any"
                        max="99999999999" min="0" 
                        value="<?php echo htmlspecialchars($datos['profundidad_obra'] ?? ''); ?>">
                <?php else: ?>
                    <p class="valor_visualizacion">
                        <?php echo htmlspecialchars($datos['profundidad_obra'] ?? 'N/A'); ?> cm.</p>
                <?php endif; ?>
            </div>
        </div>
        <div class="partes_uno">
            <label for="resu_formulario" class="label_artificial">
                Texto Descripción:
            </label>
            <?php if ($modo === 'editar'): ?>
                <textarea name="resu_formulario_m" autocomplete="off" id="resu_formulario"
                    placeholder="Esta obra es..." 
                    maxlength="255"
                    minlength="20"><?php echo htmlspecialchars($datos['descripcion_obra'] ?? ''); ?></textarea>
            <?php else: ?>
                <p class="valor_visualizacion descripcion">
                    <?php 
                    $desc = $datos['descripcion_obra'] ?? 'Sin descripción.';
                    echo nl2br(htmlspecialchars($desc)); 
                    ?>
                </p>
            <?php endif; ?>
        </div>
    </fieldset>
</section>