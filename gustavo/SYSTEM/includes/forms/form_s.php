<?php
require_once 'includes/logic/logic_e.php';
function obtenerResultadosSeguros($bd, $sql, $tipos = '', $params = [])
{
    $resultado = $bd->obtenerRegistro($sql, $tipos, $params);
    return is_array($resultado) ? $resultado : [];
}
$obras = $bd->obtenerRegistro("SELECT 
    o.cod_obra, 
    o.titulo_obra, 
    o.estado_obra, 
    GROUP_CONCAT(CONCAT(a.nombres_autor, ' ', a.apellidos_autor) SEPARATOR ', ') AS autores
    FROM obra o
    LEFT JOIN autor_obra ao ON o.cod_obra = ao.obra_cod
    LEFT JOIN autor a ON ao.autor_ci = a.ci_autor
    WHERE o.status_obra = 1 
    GROUP BY o.cod_obra", '', []);
$restauradores = $bd->obtenerRegistro("SELECT ci_restaurador, CONCAT(nombres_restaurador, ' ', apellidos_restaurador) AS nombre_completo 
    FROM restaurador 
    WHERE status_restaurador = 1", '', []);

$restauradores_map = [];
foreach ($restauradores as $r) {
    $restauradores_map[$r['ci_restaurador']] = $r['nombre_completo'];
}

$archivo_guardado = NULL;

if (!empty($_POST) && ((!empty($_POST['accion']) && $_POST['accion'] == '_registrar'))) {

    $obras_sel_str = trim(filter_var($_POST['obra_formulario_oculto'] ?? '', FILTER_SANITIZE_STRING));
    $obras_array = array_filter(explode(',', $obras_sel_str));
    $resu_man = trim(ucwords(filter_var($_POST['resu_formulario'] ?? '', FILTER_SANITIZE_STRING)));
    $desc_man = trim(filter_var($_POST['te_formulario'] ?? '', FILTER_SANITIZE_STRING));
    $rest_ci = trim(filter_var($_POST['re_formulario'] ?? '', FILTER_SANITIZE_STRING));
    $img_man = NULL;

    if (empty($resu_man) || empty($desc_man) || empty($rest_ci)) {
        $_SESSION['error_code'] = 1;
        header("Location: {$url}");
        exit();
    }
    if (empty($obras_array)) {
        $_SESSION['error_code'] = 20;
        header("Location: {$url}");
        exit();
    }

    if (strlen($resu_man) > 85 || strlen($resu_man) < 14) {
        $_SESSION['error_code'] = 7;
        header("Location: {$url}");
        exit();
    }
    if (strlen($desc_man) > 2550 || strlen($desc_man) < 28) {
        $_SESSION['error_code'] = 11;
        header("Location: {$url}");
        exit();
    }
    if (!$bd->existeEnTabla('restaurador', 'ci_restaurador', $rest_ci)) {
        $_SESSION['error_code'] = 16;
        header("Location: {$url}");
        exit();
    }

    foreach ($obras_array as $cod_obra) {
        if (!$bd->existeEnTabla('obra', 'cod_obra', $cod_obra)) {
            $_SESSION['error_code'] = 16;
            header("Location: {$url}");
            exit();
        }
    }

    if (!empty($_FILES['foto_formulario']) && $_FILES['foto_formulario']['error'] !== UPLOAD_ERR_NO_FILE) {
        try {
            $manejador = new logica_files($ruta);
            $archivo_guardado = $manejador->procesarYGuardarImagen($_FILES['foto_formulario']);
            if (!empty($archivo_guardado)) {
                $img_man = $archivo_guardado;
            }
        } catch (Exception $e) {
            $_SESSION['error_code'] = 99;
            header("Location: {$url}");
            exit();
        }
    }

    $bd->iniciarTransaccion();
    $estado_registro = true;
    $estado_inicial = 'Pendiente';
    $id_man_generado = 0;

    $sql_man = 'INSERT INTO mantenimiento (resumen_mantenimiento, descripcion_mantenimiento, estado_mantenimiento, img_mantenimiento, status_mantenimiento, usuario_ci, restaurador_ci) 
        VALUES (?, ?, ?, ?, ?, ?, ?)';
    $tipos_man = 'ssssiss';
    $parametros_man = [
        $resu_man,
        $desc_man,
        $estado_inicial,
        $img_man,
        $status_comun,
        $ci_usuario_sesion,
        $rest_ci
    ];
    $resultado_man = $bd->accionRegistro($sql_man, $tipos_man, $parametros_man);

    if ($resultado_man['success']) {
        $id_man_generado = $resultado_man['insert_id'];
    } else {
        $estado_registro = false;
    }

    $sql_relacion = 'INSERT INTO mantenimiento_obra (mantenimiento_id, obra_cod) VALUES (?, ?)';
    $tipos_relacion = 'is';

    if ($estado_registro && !empty($id_man_generado)) {
        foreach ($obras_array as $cod_obra) {
            $cod_obra = trim($cod_obra);
            if (!empty($cod_obra) && $bd->existeEnTabla('obra', 'cod_obra', $cod_obra)) {
                $parametros_relacion = [$id_man_generado, $cod_obra];
                if (!$bd->accionRegistro($sql_relacion, $tipos_relacion, $parametros_relacion)['success']) {
                    $estado_registro = false;
                    break;
                }
            }
        }
    } else {
        $estado_registro = false;
    }

    if ($estado_registro) {
        $bd->confirmarTransaccion();
        $_SESSION['success_code'] = 2; 
        header("Location: {$url}");
        exit();
    } else {
        $bd->revertirTransaccion();
        $_SESSION['error_code'] = 2; 
        header("Location: {$url}");
        exit();
    }
}
?>
<section id="cuerpo_formulario">
    <fieldset id="f_contenedor_uno" class="formulario_parte">
        <legend class="label_artificial">
            1ra Parte - Obras Asociadas (*):
        </legend>
        <div id="contenedor_de_tabla">
            <table>
                <thead>
                    <tr>
                        <th class="columna_corta">
                            ...
                        </th>
                        <th>
                            Código
                        </th>
                        <th>
                            Título
                        </th>
                        <th>
                            Autor(es)
                        </th>
                        <th>
                            Estado
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($obras)): ?>
                        <?php foreach ($obras as $obra): ?>
                            <tr>
                                <td>
                                    <input type="checkbox" name="obra_seleccionada[]" class="obra_selector"
                                        value='<?php echo htmlspecialchars($obra['cod_obra']); ?>'>
                                </td>
                                <td><?php echo htmlspecialchars($obra['cod_obra']); ?></td>
                                <td><?php echo htmlspecialchars($obra['titulo_obra']); ?> </td>
                                <td><?php echo htmlspecialchars($obra['autores'] ?? 'Sin autor'); ?></td>
                                <td><?php echo htmlspecialchars($obra['estado_obra']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">No se encontraron obras activas para asociar.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <input type="hidden" autocomplete="off" name="obra_formulario_oculto" id="obra_formulario_oculto" value="">
    </fieldset>
    <fieldset id="f_contenedor_dos" class="formulario_parte">
        <legend>
            2da Parte - Sustentación
        </legend>
        <div>
            <label for="resu_formulario">
                Título Resumen (*):
            </label>
            <input type="text" name="resu_formulario" id="resu_formulario" minlength="14" maxlength="85"
                placeholder="Título que resuma los motivos de la Solicitud" autocomplete="off">
        </div>
        <div>
            <label for="te_formulario">
                Descripción de los Motivos (*):
            </label>
            <textarea name="te_formulario" id="te_formulario" placeholder="Descripción de los Motivos" minlength="28"
                maxlength="2550" autocomplete="off"></textarea>
        </div>
        <div>
            <label for="re_formulario">
                Restaurador Asociado (*):
            </label>
            <select name="re_formulario" id="re_formulario" required <?php echo empty($restauradores) ? 'disabled' : ''; ?>>
                <option value="">
                    <?php echo empty($restauradores) ? 'No hay Restauradores activos' : 'Seleccione un Restaurador'; ?>
                </option>
                <?php if (!empty($restauradores)): ?>
                    <?php foreach ($restauradores as $restaurador): ?>
                        <option value="<?php echo htmlspecialchars($restaurador['ci_restaurador']); ?>">
                            <?php echo htmlspecialchars($restaurador['nombre_completo']); ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
    </fieldset>
    <fieldset id="f_contenedor_tres" class="formulario_parte">
        <legend class="label_artificial">
            3ra Parte - Sustento Fotográfico
        </legend>
        <div class="imagen_contenedor_uno">
            <img id="imagen_previa" src="" alt="Previsualización de la imagen">
        </div>
        <div id="foto_input_contenedor">
            <label for="foto_formulario" class="label_de_imagen_o_archivo">
                <img src="img/svg/foto_blanco.svg" class="icono">
                Seleccionar Foto
            </label>
            <input type="file" name="foto_formulario" id="foto_formulario" accept="image/*"
                class="label_de_imagen_o_archivo">
        </div>
    </fieldset>
</section>
<section id="formulario_navegacion">
    <button id="boton_atras">Atrás</button>
    <button id="boton_siguiente">Siguiente</button>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const selectors = document.querySelectorAll('.obra_selector');
        const hiddenInput = document.getElementById('obra_formulario_oculto');

        function updateHiddenInput() {
            const checkedValues = Array.from(selectors)
                .filter(checkbox => checkbox.checked)
                .map(checkbox => checkbox.value);
            hiddenInput.value = checkedValues.join(',');
        }
        selectors.forEach(selector => {
            selector.addEventListener('change', updateHiddenInput);
        });
        updateHiddenInput();
    });
</script>