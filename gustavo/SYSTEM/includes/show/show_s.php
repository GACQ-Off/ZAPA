<?php
function get_restaurador_name($bd, $ci) {
    if (empty($ci)) return 'N/A';
    $res = $bd->obtenerRegistro("SELECT CONCAT(nombres_restaurador, ' ', apellidos_restaurador) AS nombre_completo FROM restaurador WHERE ci_restaurador = ?", 's', [$ci]);
    return !empty($res) ? $res[0]['nombre_completo'] : 'N/A';
}


$restauradores = $bd->obtenerRegistro("SELECT ci_restaurador, CONCAT(nombres_restaurador, ' ', apellidos_restaurador) AS nombre_completo FROM restaurador WHERE status_restaurador = 1", '', []);


$resumen_mantenimiento = htmlspecialchars($datos['resumen_mantenimiento'] ?? '');
$descripcion_mantenimiento = htmlspecialchars($datos['descripcion_mantenimiento'] ?? '');
$restaurador_ci_actual = $datos['restaurador_ci'] ?? '';
$imagen_actual = htmlspecialchars('uploads/requests/'.$datos['img_mantenimiento'] ?? 'img/default.png');
?>

<section id="cuerpo_formulario">
    <fieldset id="f_contenedor_uno">
        <legend>
            <label for="obras_formulario" class="label_artificial">
                1ra Parte - Obras Asociadas (*):
            </label>
        </legend>
        <div class="buscador">
            <input type="text" placeholder="Buscar por Codigo, Título, Categoría, ...">
            <img src="img/svg/lupa_negro.svg" alt="icono" class="icono">
        </div>
        <div id="contenedor_de_tabla">
            <table>
                <thead>
                    <tr>
                        <th class="columna_corta">
                            ...
                        </th>
                        <th class="columna_larga">
                            Título
                        </th>
                        <th class="columna_larga">
                            Autor
                        </th>
                        <th class="columna_corta">
                            Código
                        </th>
                        <th class="columna_corta">
                            Estado
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td colspan="5">Funcionalidad de visualización de obras asociadas no implementada en este snippet.</td></tr>
                </tbody>
            </table>
        </div>
    </fieldset>
    <fieldset id="f_contenedor_dos">
        <legend>
            <label class="label_artificial">
                2da Parte - Sustentación
            </label>
        </legend>
        <div>
            <label for="resu_formulario">
                Título Resumen (*):
            </label>
            <?php if ($modo === 'editar'): ?>
                <input type="text" name="resu_formulario_m" id="resu_formulario" minlength="14" maxlength="85" 
                    placeholder="Título que resuma los motivos de la Solicitud" autocomplete="off" 
                    value="<?php echo $resumen_mantenimiento; ?>">
            <?php else: ?>
                <p class="valor_visualizacion"><?php echo $resumen_mantenimiento; ?></p>
            <?php endif; ?>
        </div>
        <div>
            <label for="te_formulario" class="label_artificial">
                Descripción de los Motivos (*):
            </label>
            <?php if ($modo === 'editar'): ?>
                <textarea name="te_formulario_m" id="te_formulario" placeholder="Descripción de los Motivos" 
                    minlength="28" maxlength="2550" autocomplete="off"><?php echo $descripcion_mantenimiento; ?></textarea>
            <?php else: ?>
                <p class="valor_visualizacion"><?php echo nl2br($descripcion_mantenimiento); ?></p>
            <?php endif; ?>
        </div>
        <div>
            <label for="re_formulario">
                Restaurador Asociado (*):
            </label>
            <?php if ($modo === 'editar'): ?>
                <select name="re_formulario_m" id="re_formulario" required>
                    <option value="">Seleccione un Restaurador</option>
                    <?php foreach ($restauradores as $restaurador): ?>
                        <option value="<?php echo htmlspecialchars($restaurador['ci_restaurador']); ?>" 
                            <?php echo ($restaurador['ci_restaurador'] == $restaurador_ci_actual) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($restaurador['nombre_completo']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            <?php else: ?>
                <p class="valor_visualizacion">
                    <?php echo get_restaurador_name($bd, $restaurador_ci_actual); ?>
                </p>
            <?php endif; ?>
        </div>
    </fieldset>
    <fieldset id="f_contenedor_tres">
        <legend>
            <label class="label_artificial">
                3ra Parte - Sustento Fotográfico
            </label>
            <div class="imagen_contenedor_uno">
                <img id="imagen_previa_m" src="<?php echo $imagen_actual; ?>" alt="Previsualización de la imagen">
            </div>
        <?php if ($modo === 'editar'): ?>
            <input type="hidden" name="foto_existente_m" value="<?php echo htmlspecialchars($datos['img_mantenimiento'] ?? ''); ?>">
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
</section>