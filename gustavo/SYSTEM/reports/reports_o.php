<?php
$autores = $bd->obtenerRegistro("SELECT ci_autor, CONCAT(nombres_autor, ' ', apellidos_autor) AS nombres_autor FROM autor WHERE status_autor = 1 ORDER BY nombres_autor", '', []);
$categorias = $bd->obtenerRegistro("SELECT id_categoria, nombre_categoria FROM categoria WHERE status_categoria = 1 ORDER BY nombre_categoria", '', []);
$tecnicas = $bd->obtenerRegistro("SELECT id_tecnica, nombre_tecnica FROM tecnica WHERE status_tecnica = 1 ORDER BY nombre_tecnica", '', []);
$materiales = $bd->obtenerRegistro("SELECT id_material, nombre_material FROM material WHERE status_material = 1 ORDER BY nombre_material", '', []);
$areas = $bd->obtenerRegistro("SELECT id_area, nombre_area FROM area WHERE status_area = 1 ORDER BY nombre_area", '', []);

function generar_options(array $datos, string $id_columna, string $desc_columna, string $entity_name): string {
    $disabled = empty($datos) ? 'disabled' : '';
    $html = "<select name=\"_{$entity_name}\" id=\"_{$entity_name}\" required {$disabled}>";
    $initial_option = empty($datos) ? "No hay {$entity_name}s activas" : "Seleccione {$entity_name}";
    $html .= "<option value=\"\"> {$initial_option}</option>";
    
    if (!empty($datos)) {
        foreach ($datos as $fila) {
            $id = htmlspecialchars($fila[$id_columna]);
            $desc = htmlspecialchars($fila[$desc_columna]);
            $html .= "<option value=\"{$id}\">{$desc}</option>";
        }
    }
    $html .= '</select>';
    return $html;
}

$html_autores_select = generar_options($autores, 'ci_autor', 'nombres_autor', 'Autor');
$html_categorias_select = generar_options($categorias, 'id_categoria', 'nombre_categoria', 'Categoria');
$html_tecnicas_select = generar_options($tecnicas, 'id_tecnica', 'nombre_tecnica', 'Tecnica');
$html_materiales_select = generar_options($materiales, 'id_material', 'nombre_material', 'Material');
$html_areas_select = generar_options($areas, 'id_area', 'nombre_area', 'Area');
?>
<form action="reports/reports_o_a.php" method="post" >
    <fieldset class="reportes_form">
        <legend class="label_artificial">
            Generación en Relación a un Autor
        </legend>
        <div>
            <div>
                <label for="_Autor">
                    Indique Autor:
                </label>
                <?php echo $html_autores_select; ?>
            </div>
            <button type="submit" name="accion_reporte" value="autor" <?php echo empty($autores) ? 'disabled' : ''; ?>>
                Generar
            </button>
        </div>
    </fieldset>
</form>

<form action="reports/reports_o_c.php" method="post" >
    <fieldset class="reportes_form">
        <legend class="label_artificial">
            Generación en Relación a una Categoría
        </legend>
        <div>
            <div>
                <label for="_Categoria">
                    Indique Categoría:
                </label>
                <?php echo $html_categorias_select; ?>
            </div>
            <button type="submit" name="accion_reporte" value="categoria" <?php echo empty($categorias) ? 'disabled' : ''; ?>>
                Generar
            </button>
        </div>
    </fieldset>
</form>

<form action="reports/reports_o_t.php" method="post" >
    <fieldset class="reportes_form">
        <legend class="label_artificial">
            Generación en Relación a una Técnica
        </legend>
        <div>
            <div>
                <label for="_Tecnica">
                Indique Técnica:
            </label>
            <?php echo $html_tecnicas_select; ?>
            </div>
            <button type="submit" name="accion_reporte" value="tecnica" <?php echo empty($tecnicas) ? 'disabled' : ''; ?>>
                Generar
            </button>
        </div>
    </fieldset>
</form>

<form action="reports/reports_o_m.php" method="post" >
    <fieldset class="reportes_form">
        <legend class="label_artificial">
            Generación en Relación a un Material
        </legend>
        <div>
            <div>
                <label for="_Material">
                    Indique Material:
                </label>
                <?php echo $html_materiales_select; ?>
            </div>
            <button type="submit" name="accion_reporte" value="material" <?php echo empty($materiales) ? 'disabled' : ''; ?>>
                Generar
            </button>
        </div>
    </fieldset>
</form>

<form action="reports/reports_o_ar.php" method="post" >
    <fieldset class="reportes_form">
        <legend class="label_artificial">
            Generación en Relación a un Área
        </legend>
        <div>
            <div>
                <label for="_Area">
                    Indique Área:
                </label>
                <?php echo $html_areas_select; ?>
            </div>
            <button type="submit" name="accion_reporte" value="area" <?php echo empty($areas) ? 'disabled' : ''; ?>>
                Generar
            </button>
        </div>
    </fieldset>
</form>