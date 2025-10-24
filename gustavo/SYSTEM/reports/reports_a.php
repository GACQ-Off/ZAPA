<?php
$usuarios_reg = $bd->obtenerRegistro("SELECT ci_usuario, CONCAT(nombres_usuario, ' ', apellidos_usuario) AS nombre_completo FROM usuario WHERE status_usuario = 1 ORDER BY nombre_completo", '', []);

function generar_user_options(array $datos, string $selected_name): string {
    $html = "<select name=\"{$selected_name}\" id=\"{$selected_name}\" required>";
    $html .= '<option value="">Seleccione Usuario Registrante</option>';
    if (!empty($datos)) {
        foreach ($datos as $fila) {
            $ci = htmlspecialchars($fila['ci_usuario']);
            $nombre = htmlspecialchars($fila['nombre_completo']);
            $html .= "<option value=\"{$ci}\">{$nombre} (CI: {$ci})</option>";
        }
    } else {
        $html .= '<option value="" disabled>No hay usuarios activos</option>';
    }
    $html .= '</select>';
    return $html;
}

$disabled_attr = empty($usuarios_reg) ? 'disabled' : '';
?>

<form action="reports/reports_a_u.php" method="post" >
    <fieldset class="reportes_form">
        <legend class="label_artificial">
            Generación de Autores por Registrante
        </legend>
        <div>
            <div>
                <label for="_UsuarioRegAutor">
                    Indique Usuario:
                </label>
                <?php echo generar_user_options($usuarios_reg, '_UsuarioRegAutor'); ?>
            </div>
            <button type="submit" name="accion_reporte" value="autor_reg" <?php echo $disabled_attr; ?>>
                Generar
            </button>
        </div>
    </fieldset>
</form>