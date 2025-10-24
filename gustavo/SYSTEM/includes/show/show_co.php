<?php 
if ($modo === 'editar') { ?>
    <input type="hidden" 
        name="id_registro_a_modificar_m" 
        value="<?php echo htmlspecialchars($datos['cod_coleccion'] ?? ''); ?>">
<?php } ?>

<section id="cuerpo_formulario">
    <table class="formulario_tabla">
        <tr>
            <td>
                <div>
                    <label for="id_formulario" class="labels">
                        Código de Identificación (*):
                    </label>
                    <?php if ($modo === 'editar'): ?>
                        <input type="text" 
                            id="id_formulario" 
                            name="cod_formulario_m" 
                            placeholder="Código de Identificación"
                            maxlength="25" minlength="1" autocomplete="off"
                            readonly 
                            value="<?php echo htmlspecialchars($datos['cod_coleccion'] ?? ''); ?>">
                    <?php else: ?>
                        <p class="valor_visualizacion">
                            <?php echo htmlspecialchars($datos['cod_coleccion'] ?? 'N/A'); ?>
                        </p>
                    <?php endif; ?>
                </div>
            </td>
            <td colspan="2">
                <div>
                    <label for="ti_formulario" class="labels">
                        Título (*):
                    </label>
                    <?php if ($modo === 'editar'): ?>
                        <input type="text" 
                            name="ti_formulario_m" 
                            id="ti_formulario" 
                            placeholder="Título" 
                            maxlength="65" minlength="4"
                            autocomplete="off" 
                            required
                            value="<?php echo htmlspecialchars($datos['titulo_coleccion'] ?? ''); ?>">
                    <?php else: ?>
                        <p class="valor_visualizacion">
                            <?php echo htmlspecialchars($datos['titulo_coleccion'] ?? 'N/A'); ?>
                        </p>
                    <?php endif; ?>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div>
                    <label for="f_cr_formulario" class="labels">
                        Fecha de Creación:
                    </label>
                    <?php 
                    $fecha_creacion = $datos['f_creacion_coleccion'] ?? date('Y-m-d');
                    if ($modo === 'editar'): ?>
                        <input type="date" 
                            name="f_cr_formulario_m" 
                            id="f_cr_formulario"
                            value="<?php echo htmlspecialchars(date('Y-m-d', strtotime($fecha_creacion))); ?>">
                    <?php else: ?>
                        <p class="valor_visualizacion">
                            <?php echo htmlspecialchars(date( 'd/m/Y', strtotime($fecha_creacion)) ?? 'N/A'); ?>
                        </p>
                    <?php endif; ?>
                </div>
            </td>
            <td>
                <div>
                    <label for="tip_formulario" class="labels">Tipo/Naturaleza (*):</label>
                    <?php 
                    $naturaleza_actual = $datos['naturaleza_coleccion'] ?? '';
                    $naturalezas = ['Permanente', 'Temporal'];
                    if ($modo === 'editar'): ?>
                        <select name="tip_formulario_m" id="tip_formulario" required>
                            <option value="">Seleccione...</option>
                            <?php foreach ($naturalezas as $naturaleza): ?>
                                <option value="<?php echo htmlspecialchars($naturaleza); ?>"
                                    <?php echo (strcasecmp($naturaleza, $naturaleza_actual) == 0) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($naturaleza); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    <?php else: ?>
                        <p class="valor_visualizacion">
                            <?php echo htmlspecialchars($naturaleza_actual ?: 'N/A'); ?>
                        </p>
                    <?php endif; ?>
                </div>
            </td>
            <td>
                <div>
                    <label for="es_formulario" class="labels">
                        Estado (*):
                    </label>
                    <?php 
                    $estado_actual = $datos['estado_coleccion'] ?? '';
                    $estados = ['Disponible', 'En prestamo'];
                    if ($modo === 'editar'): ?>
                        <select name="es_formulario_m" id="es_formulario" required>
                            <option value="">Seleccione...</option>
                            <?php foreach ($estados as $estado): ?>
                                <option value="<?php echo htmlspecialchars($estado); ?>"
                                    <?php echo (strcasecmp($estado, $estado_actual) == 0) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($estado); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    <?php else: ?>
                        <p class="valor_visualizacion">
                            <?php echo htmlspecialchars($estado_actual ?: 'N/A'); ?>
                        </p>
                    <?php endif; ?>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <div>
                    <label for="resu_formulario" class="labels">
                        Texto Descripción:
                    </label>
                    <?php if ($modo === 'editar'): ?>
                        <textarea 
                            name="resu_formulario_m" 
                            id="resu_formulario" 
                            placeholder="Esta colección es..." 
                            maxlength="255"
                            minlength="20" 
                            autocomplete="off"
                            ><?php echo htmlspecialchars($datos['descripcion_coleccion'] ?? ''); ?></textarea>
                    <?php else: ?>
                        <p class="valor_visualizacion descripcion">
                            <?php echo nl2br(htmlspecialchars($datos['descripcion_coleccion'] ?? 'Sin descripción.')); ?>
                        </p>
                    <?php endif; ?>
                </div>
            </td>
        </tr>
    </table>
</section>